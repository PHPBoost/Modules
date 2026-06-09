<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      LamPDL
 * @version     PHPBoost 6.0 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2026 03 01
 */
class DevToolsAjaxFoldersController extends AbstractController
{
    public function execute(HTTPRequestCustom $request)
    {
        if (!DevToolsAuthorizationsService::check_authorizations()->read())
            return new JSONResponse(['success' => false, 'error' => 'Unauthorized'], 403);

        $owner  = $request->get_string('owner', '');
        $repo   = $request->get_string('repo', '');
        $branch = $request->get_string('branch', '');
        $path   = $request->get_string('path', '');

        if (!$owner || !$repo || !$branch)
            return new JSONResponse(['success' => false, 'error' => 'Missing parameters']);

        $folders = DevToolsGitHubService::get_modules_with_versions($owner, $repo, $branch, $path);

        if (!is_array($folders))
            return new JSONResponse(['success' => false, 'error' => 'GitHub API error']);

        $local  = DevToolsLocalService::get_local_modules();
        $result = [];

        foreach ($folders as $folder)
        {
            $name           = $folder['name'];
            $remote_version = $folder['version'];
            $local_version  = isset($local[$name]) ? $local[$name]['version']   : null;
            $installed      = isset($local[$name]) ? $local[$name]['installed'] : false;
            $activated      = isset($local[$name]) ? $local[$name]['activated'] : false;

            $result[] = [
                'name'           => $name,
                'remote_version' => $remote_version,
                'local_version'  => $local_version,
                'installed'      => $installed,
                'activated'      => $activated,
            ];
        }

        return new JSONResponse(['success' => true, 'folders' => $result]);
    }
}
?>
