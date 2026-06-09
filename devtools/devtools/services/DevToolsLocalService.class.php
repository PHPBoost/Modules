<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      LamPDL
 * @version     PHPBoost 6.0 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2026 03 01
 *
 * Reads the local state of PHPBoost modules using native PHPBoost APIs.
 */

class DevToolsLocalService
{
    /**
     * Returns all modules present on disk inside /modules, enriched with PHPBoost state.
     *
     * @return array  Keyed by module id:
     *   [
     *     'id'        => string,
     *     'name'      => string,
     *     'version'   => string|null,
     *     'installed' => bool,
     *     'activated' => bool,
     *   ]
     */
    public static function get_local_modules()
    {
        $modules_dir = (is_dir(PATH_TO_ROOT . '/modules') ? PATH_TO_ROOT . '/modules/' : PATH_TO_ROOT . '/');
        $result      = [];

        if (!is_dir($modules_dir))
            return $result;

        $dirs = scandir($modules_dir);
        foreach ($dirs as $dir)
        {
            if ($dir === '.' || $dir === '..' || !is_dir($modules_dir . $dir))
                continue;

            $module_id = $dir;
            $version   = self::read_local_version($module_id);
            $installed = ModulesManager::is_module_installed($module_id);
            $activated = $installed && ModulesManager::is_module_activated($module_id);
            $name      = $module_id;

            // Try to get display name from module configuration
            if ($installed)
            {
                try {
                    $cfg = ModulesManager::get_module($module_id)->get_configuration();
                    $name = $cfg->get_name();
                } catch (Exception $e) {
                    // keep $module_id as name
                }
            }
            else
            {
                // Folder exists but not registered: try config.ini
                $ini_path = $modules_dir . $module_id . '/config.ini';
                if (file_exists($ini_path))
                {
                    $ini = parse_ini_file($ini_path);
                    // name comes from lang desc.ini, use module_id as fallback
                }
            }

            $result[$module_id] = [
                'id'        => $module_id,
                'name'      => $name,
                'version'   => $version,
                'installed' => $installed,
                'activated' => $activated,
            ];
        }

        // Sort alphabetically by id
        ksort($result);
        return $result;
    }

    /**
     * Reads the version string from /modules/{id}/config.ini.
     */
    public static function read_local_version($module_id)
    {
        $ini_path = (is_dir(PATH_TO_ROOT . '/modules') ? PATH_TO_ROOT . '/modules/' : PATH_TO_ROOT . '/') . $module_id . '/config.ini';
        if (!file_exists($ini_path))
            return null;

        $ini = @parse_ini_file($ini_path);
        return isset($ini['version']) ? $ini['version'] : null;
    }

    // -------------------------------------------------------------------------
    // Installation helpers
    // -------------------------------------------------------------------------

    /**
     * Downloads the zip for the given branch, extracts only the $module_name sub-folder
     * into /modules/$module_name.
     *
     * @param  string $owner
     * @param  string $repo
     * @param  string $branch
     * @param  string $repo_path  Sub-directory inside the repo where modules live (e.g. "modules")
     * @param  string $module_name  Folder name of the module
     * @return true|string  true on success, error message on failure
     */
    public static function install_module_from_github($owner, $repo, $branch, $repo_path, $module_name)
    {
        // 1. Download zip
        $zip_content = DevToolsGitHubService::download_branch_zip($owner, $repo, $branch);
        if ($zip_content === false)
            return 'Could not download the repository from GitHub.';

        // 2. Save to a temp file
        $tmp_zip = tempnam(sys_get_temp_dir(), 'pbtm_') . '.zip';
        if (file_put_contents($tmp_zip, $zip_content) === false)
            return 'Could not write the temporary file.';

        // 3. Open zip and extract the relevant subfolder
        $zip = new ZipArchive();
        if ($zip->open($tmp_zip) !== true)
        {
            @unlink($tmp_zip);
            return 'Impossible d\'ouvrir l\'archive zip.';
        }

        // GitHub zips have a root folder like "RepoName-branch/"
        $zip_root = $repo . '-' . $branch . '/';
        $module_in_zip = $zip_root . (trim($repo_path, '/') ? trim($repo_path, '/') . '/' : '') . $module_name . '/';

        $target_dir = (is_dir(PATH_TO_ROOT . '/modules') ? PATH_TO_ROOT . '/modules/' : PATH_TO_ROOT . '/') . $module_name . '/';
        if (!is_dir($target_dir))
            @mkdir($target_dir, 0755, true);

        $extracted = 0;
        for ($i = 0; $i < $zip->numFiles; $i++)
        {
            $entry = $zip->getNameIndex($i);
            if (strpos($entry, $module_in_zip) !== 0)
                continue;

            // Relative path inside the module
            $relative = substr($entry, strlen($module_in_zip));
            if ($relative === '' || $relative === false)
                continue;

            $dest = $target_dir . $relative;

            if (substr($entry, -1) === '/')
            {
                if (!is_dir($dest))
                    @mkdir($dest, 0755, true);
            }
            else
            {
                $dir = dirname($dest);
                if (!is_dir($dir))
                    @mkdir($dir, 0755, true);

                file_put_contents($dest, $zip->getFromIndex($i));
                $extracted++;
            }
        }

        $zip->close();
        @unlink($tmp_zip);

        if ($extracted === 0)
            return 'No files extracted. Check the module name and repository path.';

        return true;
    }
}
?>
