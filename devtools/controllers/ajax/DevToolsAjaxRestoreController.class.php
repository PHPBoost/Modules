<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      LamPDL
 * @version     PHPBoost 6.0 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2026 03 01
 */

require_once (is_dir(PATH_TO_ROOT . '/modules') ? PATH_TO_ROOT . '/modules' : PATH_TO_ROOT) . '/devtools/services/DevToolsBackupService.class.php';

class DevToolsAjaxRestoreController extends AbstractController
{
    public function execute(HTTPRequestCustom $request)
    {
        if (!DevToolsAuthorizationsService::check_authorizations()->moderation())
            return new JSONResponse(['success' => false, 'error' => 'Unauthorized'], 403);

        $action = $request->get_string('action', 'list');

        switch ($action)
        {
            case 'list':
                return $this->action_list();

            case 'download':
                return $this->action_download($request);

            case 'reinstall':
                return $this->action_reinstall($request);

            default:
                return new JSONResponse(['success' => false, 'error' => 'Unknown action']);
        }
    }

    private function action_list()
    {
        $backups = DevToolsBackupService::list_backups();

        $result = [];
        foreach ($backups as $b)
        {
            $result[] = [
                'module_id' => $b['module_id'],
                'filename'  => $b['filename'],
                'date'      => date('d/m/Y H:i:s', $b['timestamp']),
                'size'      => self::format_size($b['size']),
                'installed' => ModulesManager::is_module_installed($b['module_id']),
                'has_folder'=> is_dir(PATH_TO_ROOT . '/modules/' . $b['module_id']),
            ];
        }

        return new JSONResponse(['success' => true, 'backups' => $result]);
    }

    private function action_download(HTTPRequestCustom $request)
    {
        $module_id = preg_replace('/[^a-zA-Z0-9_\-]/', '', $request->get_string('module_id', ''));
        $filename  = preg_replace('/[^a-zA-Z0-9_\-\.]/', '', $request->get_string('filename', ''));

        $filepath = PATH_TO_ROOT . '/cache/backup/' . $module_id . '/' . $filename;

        if (!file_exists($filepath))
            return new JSONResponse(['success' => false, 'error' => 'File not found']);

        // Serve file as download
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit;
    }

    private function action_reinstall(HTTPRequestCustom $request)
    {
        $module_id = preg_replace('/[^a-zA-Z0-9_\-]/', '', $request->get_string('module_id', ''));
        $filename  = preg_replace('/[^a-zA-Z0-9_\-\.]/', '', $request->get_string('filename', ''));

        if (empty($module_id))
            return new JSONResponse(['success' => false, 'error' => 'Missing module identifier']);

        if (!is_dir(PATH_TO_ROOT . '/modules/' . $module_id))
            return new JSONResponse(['success' => false, 'error' => 'Module folder not found — download it first from the Remote Repositories tab']);

        if (ModulesManager::is_module_installed($module_id))
            return new JSONResponse(['success' => false, 'error' => 'Module already installed']);

        $result = ModulesManager::install_module($module_id);

        switch ($result)
        {
            case ModulesManager::MODULE_INSTALLED:
                $module = ModulesManager::get_module($module_id);
                HooksService::execute_hook_typed_action('install', 'module', $module_id, array_merge(
                    ['title' => $module->get_configuration()->get_name(), 'url' => ''],
                    $module->get_configuration()->get_properties()
                ));

                // Restore SQL data if a backup file was specified
                if (!empty($filename))
                {
                    $filepath = PATH_TO_ROOT . '/cache/backup/' . $module_id . '/' . $filename;
                    if (file_exists($filepath))
                    {
                        $sql_error = $this->execute_sql_file($filepath);
                        if ($sql_error)
                            return new JSONResponse(['success' => true, 'warning' => 'Module installed but SQL restoration failed: ' . $sql_error]);
                    }
                }

                return new JSONResponse(['success' => true]);

            case ModulesManager::MODULE_ALREADY_INSTALLED:
                return new JSONResponse(['success' => false, 'error' => 'Module already installed']);

            case ModulesManager::CONFIG_CONFLICT:
                return new JSONResponse(['success' => false, 'error' => 'Configuration conflict']);

            case ModulesManager::PHP_VERSION_CONFLICT:
                return new JSONResponse(['success' => false, 'error' => 'Incompatible PHP version']);

            case ModulesManager::PHPBOOST_VERSION_CONFLICT:
                return new JSONResponse(['success' => false, 'error' => 'Incompatible PHPBoost version']);

            default:
                return new JSONResponse(['success' => false, 'error' => 'Erreur d\'installation']);
        }
    }

    /**
     * Executes a SQL dump file.
     * Skips CREATE TABLE statements (tables already created by install_module).
     * Truncates tables before INSERT to avoid duplicates.
     * Returns null on success, error message on failure.
     */
    private function execute_sql_file($filepath)
    {
        $sql = file_get_contents($filepath);
        if ($sql === false)
            return 'Could not read the SQL file';

        $statements = DevToolsBackupService::split_sql_statements($sql);

        try
        {
            $querier = PersistenceContext::get_querier();
            $truncated = [];

            foreach ($statements as $statement)
            {
                $statement = trim($statement);
                if (empty($statement)) continue;

                $upper = strtoupper(ltrim($statement));

                // Skip DROP TABLE et CREATE TABLE
                if (strpos($upper, 'DROP TABLE') === 0 || strpos($upper, 'CREATE TABLE') === 0)
                    continue;

                // Skip SET statements
                if (strpos($upper, 'SET ') === 0)
                    continue;

                // Truncate before first INSERT
                if (strpos($upper, 'INSERT INTO') === 0)
                {
                    preg_match('/INSERT\s+INTO\s+`?([^\s`(]+)`?/i', $statement, $m);
                    if (!empty($m[1]) && !isset($truncated[$m[1]]))
                    {
                        try { $querier->inject('TRUNCATE TABLE `' . $m[1] . '`'); }
                        catch (Exception $te) { /* ignore truncate errors */ }
                        $truncated[$m[1]] = true;
                    }
                }

                $querier->inject($statement);
            }
        }
        catch (Exception $e)
        {
            return $e->getMessage() . ' | Statement: ' . substr($statement, 0, 200);
        }

        return null;
    }

    private static function format_size($bytes)
    {
        if ($bytes < 1024) return $bytes . ' o';
        if ($bytes < 1048576) return round($bytes / 1024, 1) . ' Ko';
        return round($bytes / 1048576, 1) . ' Mo';
    }
}
?>
