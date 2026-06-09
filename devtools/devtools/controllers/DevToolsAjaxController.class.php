<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      LamPDL
 * @version     PHPBoost 6.0 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2026 03 01
 *
 * Single Ajax controller dispatched by action parameter.
 * All responses are JSON.
 */

class DevToolsAjaxController extends AbstractController
{
    public function execute(HTTPRequestCustom $request)
    {
        $action = $request->get_string('pbtm_action', '');
        // All ajax endpoints require at least moderator level
        if (!DevToolsAuthorizationsService::check_authorizations()->read())
        {
            return $this->json_response(['success' => false, 'error' => 'Unauthorized'], 403);
        }

        switch ($action)
        {
            case 'branches':   return $this->action_branches($request);
            case 'folders':    return $this->action_folders($request);
            case 'install':    return $this->action_install($request);
            case 'activate':   return $this->action_activate($request);
            case 'deactivate': return $this->action_deactivate($request);
            case 'uninstall':  return $this->action_uninstall($request);
            default:
                return $this->json_response(['success' => false, 'error' => 'Unknown action']);
        }
    }

    // -------------------------------------------------------------------------
    // Actions
    // -------------------------------------------------------------------------

    /** Returns the list of branches for a given repo (owner + repo from POST). */
    private function action_branches(HTTPRequestCustom $request)
    {
        $owner = $request->get_string('owner', '');
        $repo  = $request->get_string('repo', '');

        if (!$owner || !$repo)
            return $this->json_response(['success' => false, 'error' => 'Missing owner or repo']);

        $branches = DevToolsGitHubService::get_branches($owner, $repo);

        if (!is_array($branches))
            return $this->json_response(['success' => false, 'error' => 'GitHub API error']);

        $names = array_map(function($b) { return $b['name']; }, $branches);
        return $this->json_response(['success' => true, 'branches' => $names]);
    }

    /** Returns module folders for a given repo + branch + path. */
    private function action_folders(HTTPRequestCustom $request)
    {
        $owner  = $request->get_string('owner', '');
        $repo   = $request->get_string('repo', '');
        $branch = $request->get_string('branch', '');
        $path   = $request->get_string('path', '');

        if (!$owner || !$repo || !$branch)
            return $this->json_response(['success' => false, 'error' => 'Missing parameters']);

        $folders = DevToolsGitHubService::get_module_folders($owner, $repo, $branch, $path);

        if (!is_array($folders))
            return $this->json_response(['success' => false, 'error' => 'GitHub API error']);

        // Get local state to mark already-installed modules
        $local = DevToolsLocalService::get_local_modules();

        $result = [];
        foreach ($folders as $folder)
        {
            $name = $folder['name'];
            // Try to fetch remote version from config.ini
            $remote_version = DevToolsGitHubService::get_remote_module_version(
                $owner, $repo, $branch,
                ($path ? trim($path, '/') . '/' : '') . $name
            );
            $local_version  = isset($local[$name]) ? $local[$name]['version'] : null;
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

        return $this->json_response(['success' => true, 'folders' => $result]);
    }

    /** Installs one or several modules by downloading the branch zip and extracting. */
    private function action_install(HTTPRequestCustom $request)
    {
        $this->verify_csrf($request);

        $owner   = $request->get_string('owner', '');
        $repo    = $request->get_string('repo', '');
        $branch  = $request->get_string('branch', '');
        $path    = $request->get_string('path', '');
        $modules = $request->get_value('modules', []); // array of module names

        if (!$owner || !$repo || !$branch || empty($modules))
            return $this->json_response(['success' => false, 'error' => 'Missing parameters']);

        $errors = [];
        foreach ((array)$modules as $module_name)
        {
            $module_name = preg_replace('/[^a-zA-Z0-9_\-]/', '', $module_name);
            if (!$module_name)
                continue;

            $result = DevToolsLocalService::install_module_from_github($owner, $repo, $branch, $path, $module_name);
            if ($result !== true)
                $errors[$module_name] = $result;
        }

        if (empty($errors))
            return $this->json_response(['success' => true]);

        return $this->json_response(['success' => false, 'errors' => $errors]);
    }

    /** Activates a locally installed module using ModulesManager. */
    private function action_activate(HTTPRequestCustom $request)
    {
        $this->verify_csrf($request);
        $module_id = $this->safe_module_id($request);

        if (!ModulesManager::is_module_installed($module_id))
            return $this->json_response(['success' => false, 'error' => 'Module not installed']);

        ModulesManager::activate_module($module_id, false);
        return $this->json_response(['success' => true]);
    }

    /** Deactivates an active module. */
    private function action_deactivate(HTTPRequestCustom $request)
    {
        $this->verify_csrf($request);
        $module_id = $this->safe_module_id($request);

        if (!ModulesManager::is_module_installed($module_id))
            return $this->json_response(['success' => false, 'error' => 'Module not installed']);

        ModulesManager::deactivate_module($module_id);
        return $this->json_response(['success' => true]);
    }

    /** Uninstalls a module (runs its uninstall routine). */
    private function action_uninstall(HTTPRequestCustom $request)
    {
        $this->verify_csrf($request);
        $module_id = $this->safe_module_id($request);

        // Safety: never uninstall devtools itself
        if ($module_id === 'devtools')
            return $this->json_response(['success' => false, 'error' => 'You cannot uninstall this module from within itself.']);

        if (!ModulesManager::is_module_installed($module_id))
            return $this->json_response(['success' => false, 'error' => 'Module not installed']);

        ModulesManager::uninstall_module($module_id);
        return $this->json_response(['success' => true]);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function safe_module_id(HTTPRequestCustom $request)
    {
        return preg_replace('/[^a-zA-Z0-9_\-]/', '', $request->get_string('id', ''));
    }

    private function verify_csrf(HTTPRequestCustom $request)
    {
        $token = $request->get_string('token', '');
        if (!AppContext::get_session()->csrf_token_valid($token))
        {
            return $this->json_response(['success' => false, 'error' => 'Invalid CSRF token'], 403);
            exit;
        }
    }

    private function json_response(array $data, $status = 200)
    {
        return new JSONResponse($data, $status);
    }
}
?>
