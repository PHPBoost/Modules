<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      LamPDL
 * @version     PHPBoost 6.0 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2026 03 01
 */
class DevToolsAjaxInstallController extends AbstractController
{
    public function execute(HTTPRequestCustom $request)
    {
        // Suppress warnings to avoid corrupting the JSON response
        $prev_error_reporting = error_reporting(E_ERROR);

        if (!DevToolsAuthorizationsService::check_authorizations()->moderation())
        {
            error_reporting($prev_error_reporting);
            return new JSONResponse(['success' => false, 'error' => 'Unauthorized'], 403);
        }

        $owner   = $request->get_string('owner', '');
        $repo    = $request->get_string('repo', '');
        $branch  = $request->get_string('branch', '');
        $path    = $request->get_string('path', '');
        $modules = $request->get_value('modules', []);

        if (!$owner || !$repo || !$branch || empty($modules))
        {
            error_reporting($prev_error_reporting);
            return new JSONResponse(['success' => false, 'error' => 'Missing parameters']);
        }

        $errors = [];
        foreach ((array)$modules as $module_name)
        {
            $module_name = preg_replace('/[^a-zA-Z0-9_\-]/', '', $module_name);
            if (!$module_name) continue;

            $result = DevToolsLocalService::install_module_from_github($owner, $repo, $branch, $path, $module_name);
            if ($result !== true)
                $errors[$module_name] = $result;
        }

        error_reporting($prev_error_reporting);

        if (empty($errors))
            return new JSONResponse(['success' => true]);

        return new JSONResponse(['success' => false, 'errors' => $errors]);
    }
}
?>
