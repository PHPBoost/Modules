<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      LamPDL
 * @version     PHPBoost 6.0 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2026 03 01
 */
class DevToolsAjaxBranchesController extends AbstractController
{
    public function execute(HTTPRequestCustom $request)
    {
        if (!DevToolsAuthorizationsService::check_authorizations()->read())
            return new JSONResponse(['success' => false, 'error' => 'Unauthorized'], 403);

        $owner = $request->get_string('owner', '');
        $repo  = $request->get_string('repo', '');

        if (!$owner || !$repo)
            return new JSONResponse(['success' => false, 'error' => 'Missing owner or repo']);

        $branches = DevToolsGitHubService::get_branches($owner, $repo);

        if (!is_array($branches))
            return new JSONResponse(['success' => false, 'error' => 'GitHub API error']);

        $names = array_map(function($b) { return $b['name']; }, $branches);
        return new JSONResponse(['success' => true, 'branches' => $names]);
    }
}
?>
