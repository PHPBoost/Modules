<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      LamPDL
 * @version     PHPBoost 6.0 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2026 03 01
 */

require_once (is_dir(PATH_TO_ROOT . '/modules') ? PATH_TO_ROOT . '/modules' : PATH_TO_ROOT) . '/devtools/services/DevToolsBackupService.class.php';

class DevToolsAjaxBackupController extends AbstractController
{
    public function execute(HTTPRequestCustom $request)
    {
        if (!DevToolsAuthorizationsService::check_authorizations()->moderation())
            return new JSONResponse(['success' => false, 'error' => 'Unauthorized'], 403);

        $module_id = preg_replace('/[^a-zA-Z0-9_\-]/', '', $request->get_string('id', ''));

        if (empty($module_id))
            return new JSONResponse(['success' => false, 'error' => 'Missing module identifier']);

        $result = DevToolsBackupService::backup_module($module_id);

        if (isset($result['error']))
            return new JSONResponse(['success' => false, 'error' => '[' . $result['error'] . '] ' . $result['detail']]);

        return new JSONResponse(['success' => true, 'filepath' => $result['filepath'], 'tables' => $result['tables']]);
    }
}
?>
