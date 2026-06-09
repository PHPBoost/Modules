<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      LamPDL
 * @version     PHPBoost 6.0 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2026 03 01
 *
 * Adds a new GitHub repo to the saved configuration.
 */
class DevToolsAjaxSaveRepoController extends AbstractController
{
    public function execute(HTTPRequestCustom $request)
    {
        if (!DevToolsAuthorizationsService::check_authorizations()->admin())
            return new JSONResponse(['success' => false, 'error' => 'Unauthorized'], 403);

        $org   = preg_replace('/[^a-zA-Z0-9_\-]/', '', $request->get_string('org', ''));
        $repo  = preg_replace('/[^a-zA-Z0-9_\-\.]/', '', $request->get_string('repo', ''));
        $path  = trim($request->get_string('path', ''));
        $label = trim($request->get_string('label', ''));

        if (empty($org) || empty($repo))
            return new JSONResponse(['success' => false, 'error' => 'Missing parameters']);

        $config = DevToolsConfig::load();
        $repos  = $config->get_repos() ?: DevToolsConfig::DEFAULT_REPOS;

        $repos[] = [
            'label' => $label ?: $org . '/' . $repo,
            'owner' => $org,
            'repo'  => $repo,
            'path'  => $path,
        ];

        $config->set_repos($repos);
        ConfigManager::save('devtools', $config, 'config');

        return new JSONResponse(['success' => true, 'repos' => $repos]);
    }
}
?>
