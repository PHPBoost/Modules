<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      LamPDL
 * @version     PHPBoost 6.0 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2026 03 01
 */

require_once (is_dir(PATH_TO_ROOT . '/modules') ? PATH_TO_ROOT . '/modules' : PATH_TO_ROOT) . '/devtools/services/DevToolsBackupService.class.php';

class DevToolsAjaxUninstallController extends AbstractController
{
    public function execute(HTTPRequestCustom $request)
    {
        if (!DevToolsAuthorizationsService::check_authorizations()->moderation())
            return new JSONResponse(['success' => false, 'error' => 'Unauthorized'], 403);

        $module_id = preg_replace('/[^a-zA-Z0-9_\-]/', '', $request->get_string('id', ''));

        if ($module_id === 'devtools')
            return new JSONResponse(['success' => false, 'error' => 'You cannot uninstall this module from within itself.']);

        if (!ModulesManager::is_module_installed($module_id))
            return new JSONResponse(['success' => false, 'error' => 'Module not installed']);

        // Backup tables FIRST before any deactivation or uninstall
        $backup_result = DevToolsBackupService::backup_module($module_id);

        if (ModulesManager::is_module_activated($module_id))
            ModulesManager::update_module($module_id, 0);

        $drop_files = $request->get_string('drop_files', '0') === '1';
        ModulesManager::uninstall_module($module_id, $drop_files);

        if (isset($backup_result['error']))
            return new JSONResponse(['success' => true, 'warning' => 'Module uninstalled but SQL backup failed: [' . $backup_result['error'] . '] ' . $backup_result['detail']]);

        return new JSONResponse(['success' => true, 'backup' => $backup_result['filepath']]);
    }
}
?>
