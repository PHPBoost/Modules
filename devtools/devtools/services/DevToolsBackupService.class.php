<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      LamPDL
 * @version     PHPBoost 6.0 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2026 03 01
 *
 * Detects and exports SQL tables of a module before uninstallation.
 */

class DevToolsBackupService
{
    /**
     * Detects tables for a module, exports them to SQL, saves in
     * /modules/{module_id}/SQL_restaure/{datetime}.sql
     *
     * @param  string $module_id
     * @return string|false  Path to the .sql file, or false if no tables found
     */
    public static function backup_module($module_id)
    {
        $tables = self::detect_tables($module_id);

        if (empty($tables))
            return ['error' => 'no_tables', 'detail' => 'No tables detected for module ' . $module_id];

        $sql = self::export_tables($tables);

        if (empty($sql))
            return ['error' => 'empty_sql', 'detail' => 'SQL export is empty'];

        $dir = PATH_TO_ROOT . '/cache/backup/' . $module_id;
        if (!is_dir($dir))
        {
            if (!@mkdir($dir, 0755, true))
                return ['error' => 'mkdir_failed', 'detail' => 'Could not create directory ' . $dir];
        }

        // Protect directory with .htaccess if not already done
        $htaccess = PATH_TO_ROOT . '/cache/backup/.htaccess';
        if (!file_exists($htaccess))
            @file_put_contents($htaccess, "Order deny,allow\nDeny from all\n");

        $filename = $module_id . '_' . date('Y-m-d_H-i-s') . '.sql';
        $filepath = $dir . '/' . $filename;

        if (file_put_contents($filepath, $sql) === false)
            return ['error' => 'write_failed', 'detail' => 'Could not write to ' . $filepath];

        return ['success' => true, 'filepath' => $filepath, 'tables' => $tables];
    }

    /**
     * Detects table names for a module.
     * Strategy: parse Setup.class.php first, then fallback to DB prefix search.
     *
     * @param  string $module_id
     * @return array  List of table names (without PREFIX, with actual prefix applied)
     */
    public static function detect_tables($module_id)
    {
        $setup_tables   = self::detect_from_setup($module_id);
        $prefix_tables  = [];

        if (empty($setup_tables))
            $prefix_tables = self::detect_from_db_prefix($module_id);

        $tables = !empty($setup_tables) ? $setup_tables : $prefix_tables;

        return $tables;
    }

    /**
     * Parses Setup.class.php to find static table properties.
     * Looks for: PREFIX . 'table_name'  or  PREFIX . "table_name"
     */
    private static function detect_from_setup($module_id)
    {
        $setup_path = PATH_TO_ROOT . '/modules/' . $module_id . '/phpboost/' . ucfirst($module_id) . 'Setup.class.php';

        // Try common casing variants
        if (!file_exists($setup_path))
        {
            $files = glob(PATH_TO_ROOT . '/modules/' . $module_id . '/phpboost/*Setup.class.php');
            if (empty($files))
                return [];
            $setup_path = $files[0];
        }

        $content = file_get_contents($setup_path);
        if ($content === false)
            return [];

        // Match: PREFIX . 'table_name'  or  PREFIX . "table_name"
        preg_match_all('/PREFIX\s*\.\s*[\'"]([a-zA-Z0-9_]+)[\'"]/', $content, $matches);

        if (empty($matches[1]))
            return [];

        $tables = [];
        foreach (array_unique($matches[1]) as $suffix)
        {
            $tables[] = PREFIX . $suffix;
        }

        return $tables;
    }

    /**
     * Fallback: lists all tables in DB that start with PREFIX + module_id
     */
    private static function detect_from_db_prefix($module_id)
    {
        $prefix = PREFIX . $module_id;
        $tables = [];

        try
        {
            $db_tables = PersistenceContext::get_dbms_utils()->list_tables();
            foreach ($db_tables as $table)
            {
                if (strpos($table, $prefix) === 0)
                    $tables[] = $table;
            }
        }
        catch (Exception $e)
        {
            // DB error - return empty
        }

        return $tables;
    }

    /**
     * Exports an array of tables to a SQL dump string.
     */
    private static function export_tables(array $tables)
    {
        $sql = '-- PBT Manager SQL backup' . "\n";
        $sql .= '-- Date: ' . date('Y-m-d H:i:s') . "\n";
        $sql .= '-- Tables: ' . implode(', ', $tables) . "\n\n";
        $sql .= 'SET FOREIGN_KEY_CHECKS=0;' . "\n\n";

        foreach ($tables as $table)
        {
            $sql .= self::export_table($table);
        }

        $sql .= 'SET FOREIGN_KEY_CHECKS=1;' . "\n";

        return $sql;
    }

    // Export a single table: DROP + CREATE + INSERT statements.
    private static function export_table($table)
    {
        $sql = '';

        try
        {
            $querier = PersistenceContext::get_querier();

            // DROP + CREATE via SHOW CREATE TABLE (full name with prefix)
            $result = $querier->select('SHOW CREATE TABLE `' . $table . '`');
            $row = $result->fetch();
            $result->dispose();

            if (!$row)
                return '';

            $create = isset($row['Create Table']) ? $row['Create Table'] : (isset($row[1]) ? $row[1] : '');
            if (empty($create))
                return '';

            $sql .= '-- Table: ' . $table . "\n";
            $sql .= 'DROP TABLE IF EXISTS `' . $table . '`;' . "\n";
            $sql .= $create . ';' . "\n\n";

            // Get column types
            $col_types = [];
            $result = $querier->select('SHOW COLUMNS FROM `' . $table . '`');
            while ($col = $result->fetch())
            {
                $is_numeric = preg_match('/^(int|tinyint|smallint|mediumint|bigint|float|double|decimal|numeric|bit)/i', $col['Type']);
                $col_types[$col['Field']] = $is_numeric ? 'numeric' : 'string';
            }
            $result->dispose();

            // INSERT rows - use full table name with backticks, querier will not re-prefix it
            $result = $querier->select('SELECT * FROM `' . $table . '`');
            $rows = [];
            while ($row = $result->fetch())
            {
                $values = [];
                foreach ($row as $col_name => $val)
                {
                    if ($val === null)
                    {
                        $values[] = 'NULL';
                    }
                    elseif (isset($col_types[$col_name]) && $col_types[$col_name] === 'numeric')
                    {
                        $values[] = (string)$val;
                    }
                    else
                    {
                        $escaped = str_replace(
                            ['\\',    "'",     "\n",    "\r",    "\x00",  "\x1a"],
                            ['\\\\',  "\\'",   '\\n',   '\\r',   '\\0',   '\\Z'],
                            (string)$val
                        );
                        $values[] = "'" . $escaped . "'";
                    }
                }
                $rows[] = '(' . implode(', ', $values) . ')';
            }
            $result->dispose();

            if (!empty($rows))
            {
                $sql .= 'INSERT INTO `' . $table . '` VALUES' . "\n";
                $sql .= implode(",\n", $rows) . ';' . "\n";
            }

            $sql .= "\n";
        }
        catch (Exception $e)
        {
            $sql .= '-- ERROR exporting table ' . $table . ': ' . $e->getMessage() . "\n\n";
        }

        return $sql;
    }

    // -------------------------------------------------------------------------
    // Shared SQL execution helpers (used by Restore and ImportBDD controllers)
    // -------------------------------------------------------------------------

    /**
     * Splits a SQL dump string into individual statements.
     * Correctly handles quoted strings (single, double, backtick) and
     * both line comments (--) and block comments (/* ... *\/).
     *
     * @param  string $sql  Raw SQL dump content
     * @return array        List of trimmed SQL statements (without trailing semicolon)
     */
    public static function split_sql_statements($sql)
    {
        $statements = [];
        $current    = '';
        $in_string  = false;
        $quote_char = '';
        $len        = strlen($sql);

        for ($i = 0; $i < $len; $i++)
        {
            $c = $sql[$i];

            if ($in_string)
            {
                $current .= $c;
                if ($c === '\\')
                {
                    // Escaped character inside a string — consume the next char as-is
                    if ($i + 1 < $len)
                        $current .= $sql[++$i];
                }
                elseif ($c === $quote_char)
                {
                    $in_string = false;
                }
            }
            else
            {
                if ($c === "'" || $c === '"' || $c === '`')
                {
                    $in_string  = true;
                    $quote_char = $c;
                    $current   .= $c;
                }
                elseif ($c === ';')
                {
                    $trimmed = trim($current);
                    if (!empty($trimmed))
                        $statements[] = $trimmed;
                    $current = '';
                }
                elseif ($c === '-' && isset($sql[$i + 1]) && $sql[$i + 1] === '-')
                {
                    // Line comment — skip to end of line
                    while ($i < $len && $sql[$i] !== "\n")
                        $i++;
                }
                elseif ($c === '/' && isset($sql[$i + 1]) && $sql[$i + 1] === '*')
                {
                    // Block comment — skip to closing */
                    $i += 2;
                    while ($i < $len - 1 && !($sql[$i] === '*' && $sql[$i + 1] === '/'))
                        $i++;
                    $i++; // skip the final '/'
                }
                else
                {
                    $current .= $c;
                }
            }
        }

        $trimmed = trim($current);
        if (!empty($trimmed))
            $statements[] = $trimmed;

        return $statements;
    }

    // List all backup entries in /cache/backup/.
    // Returns: [ ['module_id' => ..., 'filename' => ..., 'timestamp' => ..., 'size' => ...], ... ]
    public static function list_backups()
    {
        $backups = [];
        $save_dir = PATH_TO_ROOT . '/cache/backup/';

        if (!is_dir($save_dir))
            return $backups;

        $dirs = scandir($save_dir);
        foreach ($dirs as $module_id)
        {
            if ($module_id === '.' || $module_id === '..')
                continue;

            $backup_dir = $save_dir . $module_id;
            if (!is_dir($backup_dir))
                continue;

            $files = glob($backup_dir . '/*.sql');
            if (empty($files))
                continue;

            foreach ($files as $filepath)
            {
                $filename  = basename($filepath);
                // Format: module_id_YYYY-MM-DD_HH-MM-SS.sql
                // Extract date part after module_id_
                $date_part = substr($filename, strlen($module_id) + 1); // "YYYY-MM-DD_HH-MM-SS.sql"
                $timestamp = @strtotime(substr($date_part, 0, 10) . ' ' . str_replace('-', ':', substr($date_part, 11, 8)));

                $backups[] = [
                    'module_id' => $module_id,
                    'filename'  => $filename,
                    'filepath'  => $filepath,
                    'timestamp' => $timestamp ?: filemtime($filepath),
                    'size'      => filesize($filepath),
                ];
            }
        }

        usort($backups, function($a, $b) { return $b['timestamp'] - $a['timestamp']; });

        return $backups;
    }
}
?>
