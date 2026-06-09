<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      LamPDL
 * @version     PHPBoost 6.0 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2026 03 06
 *
 * Handles ImportBDD actions:
 *   action=list   → list modules that have SQL files in /backup/importBDD/
 *   action=import → drop existing tables then import SQL file(s) for a module
 */

class DevToolsAjaxImportBddController extends AbstractController
{
    /** Base directory where SQL files are stored: /cache/backup/importBDD/{module_id}/*.sql */
    const IMPORT_DIR = '/cache/backup/importBDD/';

    public function execute(HTTPRequestCustom $request)
    {
        if (!DevToolsAuthorizationsService::check_authorizations()->moderation())
            return new JSONResponse(['success' => false, 'error' => 'Unauthorized'], 403);

        $action = $request->get_string('action', 'list');

        switch ($action)
        {
            case 'list':
                return $this->action_list();

            case 'import':
                return $this->action_import($request);

            default:
                return new JSONResponse(['success' => false, 'error' => 'Unknown action']);
        }
    }

    // -----------------------------------------------------------------------
    // action=list
    // Scans /backup/importBDD/ for module sub-directories that contain at
    // least one .sql file AND have at least one matching table in the DB.
    // -----------------------------------------------------------------------
    private function action_list()
    {
        $base = PATH_TO_ROOT . self::IMPORT_DIR;

        if (!is_dir($base))
            return new JSONResponse(['success' => true, 'modules' => []]);

        $result = [];

        $dirs = scandir($base);
        foreach ($dirs as $module_id)
        {
            if ($module_id === '.' || $module_id === '..')
                continue;

            $module_dir = $base . $module_id;
            if (!is_dir($module_dir))
                continue;

            // Collect SQL files for this module
            $sql_files = glob($module_dir . '/*.sql');
            if (empty($sql_files))
                continue;

            // Check that at least one table exists in the DB for this module
            $db_tables = $this->get_module_db_tables($module_id);
            if (empty($db_tables))
                continue;

            $files_info = [];
            foreach ($sql_files as $filepath)
            {
                $files_info[] = [
                    'filename' => basename($filepath),
                    'size'     => self::format_size(filesize($filepath)),
                    'date'     => date('d/m/Y H:i', filemtime($filepath)),
                ];
            }

            $result[] = [
                'module_id' => $module_id,
                'files'     => $files_info,
                'db_tables' => $db_tables,
            ];
        }

        return new JSONResponse(['success' => true, 'modules' => $result]);
    }

    // -----------------------------------------------------------------------
    // action=import
    // Imports all SQL files for the given module_id.
    // Each SQL file is executed with DROP TABLE IF EXISTS before each CREATE.
    // -----------------------------------------------------------------------
    private function action_import(HTTPRequestCustom $request)
    {
        $module_id = preg_replace('/[^a-zA-Z0-9_\-]/', '', $request->get_string('module_id', ''));
        $filename  = preg_replace('/[^a-zA-Z0-9_\-\.]/', '', $request->get_string('filename', ''));

        if (empty($module_id))
            return new JSONResponse(['success' => false, 'error' => 'Missing module identifier']);

        $module_dir = PATH_TO_ROOT . self::IMPORT_DIR . $module_id;

        if (!is_dir($module_dir))
            return new JSONResponse(['success' => false, 'error' => 'Directory not found: ' . self::IMPORT_DIR . $module_id]);

        // If a specific filename is given, import only that file
        if (!empty($filename))
        {
            $filepath = $module_dir . '/' . $filename;
            if (!file_exists($filepath))
                return new JSONResponse(['success' => false, 'error' => 'File not found : ' . $filename]);

            $error = $this->execute_sql_file($filepath);
            if ($error)
                return new JSONResponse(['success' => false, 'error' => $filename . ' : ' . $error]);

            return new JSONResponse([
                'success' => true,
                'message' => 'File successfully imported: ' . $filename,
            ]);
        }

        // Otherwise import all SQL files for the module
        $sql_files = glob($module_dir . '/*.sql');
        if (empty($sql_files))
            return new JSONResponse(['success' => false, 'error' => 'No SQL file found for this module']);

        $imported = [];
        $errors   = [];

        foreach ($sql_files as $filepath)
        {
            $error = $this->execute_sql_file($filepath);
            if ($error)
                $errors[] = basename($filepath) . ' : ' . $error;
            else
                $imported[] = basename($filepath);
        }

        if (!empty($errors))
        {
            $msg = 'Erreurs lors de l\'import : ' . implode(' | ', $errors);
            if (!empty($imported))
                $msg .= ' (files successfully imported: ' . implode(', ', $imported) . ')';
            return new JSONResponse(['success' => false, 'error' => $msg]);
        }

        return new JSONResponse([
            'success'  => true,
            'imported' => $imported,
            'message'  => count($imported) . ' file(s) successfully imported : ' . implode(', ', $imported),
        ]);
    }

    // -----------------------------------------------------------------------
    // Executes a single SQL file.
    // - DROP TABLE IF EXISTS statements are executed as-is (passed through).
    // - CREATE TABLE statements are executed after a DROP TABLE IF EXISTS
    //   to guarantee a clean slate even if the dump does not include one.
    // - INSERT, UPDATE, DELETE etc. are executed normally.
    // Returns null on success, error string on failure.
    // -----------------------------------------------------------------------
    private function execute_sql_file($filepath)
    {
        $sql = file_get_contents($filepath);
        if ($sql === false)
            return 'Could not read the file';

        $statements = $this->split_sql_statements($sql);

        try
        {
            $querier = PersistenceContext::get_querier();

            foreach ($statements as $statement)
            {
                $statement = trim($statement);
                if (empty($statement))
                    continue;

                $upper = strtoupper(ltrim($statement));

                // Skip SET statements (charset / FK checks in dumps)
                if (strpos($upper, 'SET ') === 0)
                    continue;

                // Before CREATE TABLE, force a DROP TABLE IF EXISTS so the
                // import is always clean even when the dump has none.
                if (strpos($upper, 'CREATE TABLE') === 0)
                {
                    if (preg_match('/CREATE\s+TABLE\s+(?:IF\s+NOT\s+EXISTS\s+)?`?([^\s`(]+)`?/i', $statement, $m))
                    {
                        $querier->inject('DROP TABLE IF EXISTS `' . $m[1] . '`');
                    }
                }

                $querier->inject($statement);
            }
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }

        return null;
    }

    // -----------------------------------------------------------------------
    // Splits a SQL dump into individual statements.
    // Delegates to the shared implementation in DevToolsBackupService.
    // -----------------------------------------------------------------------
    private function split_sql_statements($sql)
    {
        return DevToolsBackupService::split_sql_statements($sql);
    }

    // -----------------------------------------------------------------------
    // Returns existing DB table names that match the module_id prefix.
    // -----------------------------------------------------------------------
    private function get_module_db_tables($module_id)
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
            // DB error – return empty
        }

        return $tables;
    }

    private static function format_size($bytes)
    {
        if ($bytes < 1024)      return $bytes . ' o';
        if ($bytes < 1048576)   return round($bytes / 1024, 1) . ' Ko';
        return round($bytes / 1048576, 1) . ' Mo';
    }
}
?>
