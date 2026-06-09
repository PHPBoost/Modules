<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      LamPDL
 * @version     PHPBoost 6.0 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2026 03 01
 *
 * Handles all calls to the GitHub REST API.
 */

class DevToolsGitHubService
{
    const GITHUB_API_BASE = 'https://api.github.com';

    /**
     * Returns the list of public repositories for a given GitHub organization.
     *
     * @param  string $org
     * @return array|false  [ ['name' => 'Modules', 'full_name' => 'PHPBoost/Modules', ...], … ]
     */
    public static function get_org_repos($org)
    {
        $url = self::GITHUB_API_BASE . '/orgs/' . urlencode($org) . '/repos?per_page=100&sort=name';
        $data = self::github_get($url);
        if (!is_array($data))
            return false;
        return $data;
    }

    /**
     * Returns the list of branches for a given repo.
     *
     * @param  string $owner
     * @param  string $repo
     * @return array  [ ['name' => 'main', ...], … ]
     */
    public static function get_branches($owner, $repo)
    {
        $url = self::GITHUB_API_BASE . '/repos/' . urlencode($owner) . '/' . urlencode($repo) . '/branches';
        return self::github_get($url);
    }

    /**
     * Returns all module folders AND their versions in a single API call using the git tree endpoint.
     *
     * @param  string $owner
     * @param  string $repo
     * @param  string $branch
     * @param  string $path    Sub-directory inside the repo where modules live. Empty = root.
     * @return array|false  [ ['name' => 'news', 'version' => '1.0'], … ] or false on error
     */
    public static function get_modules_with_versions($owner, $repo, $branch, $path = '')
    {
        // 1. Get the full recursive tree in one call
        $url = self::GITHUB_API_BASE . '/repos/'
            . urlencode($owner) . '/' . urlencode($repo)
            . '/git/trees/' . urlencode($branch) . '?recursive=1';

        $data = self::github_get($url);

        if (!isset($data['tree']) || !is_array($data['tree']))
            return false;

        $tree = $data['tree'];
        $path = trim($path, '/');

        // 2. Find immediate subdirectories of $path (= module folders)
        $modules = [];
        foreach ($tree as $item)
        {
            if ($item['type'] !== 'tree')
                continue;

            $item_path = $item['path'];

            if ($path)
            {
                if (strpos($item_path, $path . '/') !== 0)
                    continue;
                $relative = substr($item_path, strlen($path) + 1);
            }
            else
            {
                $relative = $item_path;
            }

            if (strpos($relative, '/') === false && $relative !== '')
                $modules[$relative] = ['name' => $relative, 'version' => null];
        }

        // 3. Find config.ini blobs and extract versions via raw URL (faster than blob API)
        foreach ($tree as $item)
        {
            if ($item['type'] !== 'blob' || basename($item['path']) !== 'config.ini')
                continue;

            $item_path = $item['path'];

            if ($path)
            {
                if (strpos($item_path, $path . '/') !== 0)
                    continue;
                $relative = substr($item_path, strlen($path) + 1);
            }
            else
            {
                $relative = $item_path;
            }

            $parts = explode('/', $relative);
            if (count($parts) !== 2)
                continue;

            $module_name = $parts[0];
            if (!isset($modules[$module_name]))
                continue;

            $raw_url = 'https://raw.githubusercontent.com/'
                . urlencode($owner) . '/' . urlencode($repo)
                . '/' . urlencode($branch) . '/' . $item_path;

            $content = self::http_get($raw_url);
            if ($content === false)
                continue;

            if (preg_match('/^version\s*=\s*"?([^"\r\n]+)"?/m', $content, $matches))
                $modules[$module_name]['version'] = trim($matches[1]);
        }

        return array_values($modules);
    }

    /**
     * Returns the list of directories (= module folders) inside $path on a given branch.
     *
     * @param  string $owner
     * @param  string $repo
     * @param  string $branch
     * @param  string $path    Sub-directory in the repo (e.g. "modules"). Empty = root.
     * @return array  [ ['name' => 'news', 'type' => 'dir', ...], … ]  (only dirs)
     */
    public static function get_module_folders($owner, $repo, $branch, $path = '')
    {
        $modules = self::get_modules_with_versions($owner, $repo, $branch, $path);
        if (!is_array($modules))
            return [];
        return array_map(function($m) { return ['name' => $m['name'], 'type' => 'dir']; }, $modules);
    }

    /**
     * Attempts to read a config.ini inside a module folder to retrieve its version.
     *
     * @param  string $owner
     * @param  string $repo
     * @param  string $branch
     * @param  string $module_path  Full path to the module folder in the repo (e.g. "modules/news")
     * @return string|null  Version string or null if not found
     */
    public static function get_remote_module_version($owner, $repo, $branch, $module_path)
    {
        $url = self::GITHUB_API_BASE . '/repos/'
            . urlencode($owner) . '/' . urlencode($repo)
            . '/contents/' . trim($module_path, '/') . '/config.ini'
            . '?ref=' . urlencode($branch);

        $data = self::github_get($url);

        if (!isset($data['content']))
            return null;

        $content = base64_decode(str_replace("\n", '', $data['content']));
        if (preg_match('/^version\s*=\s*"?([^"\r\n]+)"?/m', $content, $matches))
            return trim($matches[1]);

        return null;
    }

    /**
     * Downloads the zip archive of a specific branch.
     *
     * @param  string $owner
     * @param  string $repo
     * @param  string $branch
     * @return string  Raw zip binary content, or false on error
     */
    public static function download_branch_zip($owner, $repo, $branch)
    {
        $url = 'https://github.com/' . urlencode($owner) . '/' . urlencode($repo)
            . '/archive/refs/heads/' . urlencode($branch) . '.zip';

        return self::http_get($url, true);
    }

    // -------------------------------------------------------------------------
    // Internal HTTP helpers
    // -------------------------------------------------------------------------

    private static function github_get($url)
    {
        $response = self::http_get($url);
        if ($response === false)
            return false;

        $decoded = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE)
            return false;

        // GitHub returns {"message": "..."} on API errors (rate limit, repo not found...)
        if (isset($decoded['message']))
            return false;

        return $decoded;
    }

    private static function http_get($url, $binary = false)
    {
        $config  = DevToolsConfig::load();
        $token   = $config->get_github_token();

        $headers = [
            'User-Agent: PHPBoost-DevTools/1.0',
            'Accept: application/vnd.github+json',
            'X-GitHub-Api-Version: 2022-11-28',
        ];

        if (!empty($token))
            $headers[] = 'Authorization: Bearer ' . $token;

        // --- cURL (prioritaire) ---
        if (function_exists('curl_init'))
        {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_TIMEOUT        => 120,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_HTTPHEADER     => $headers,
            ]);

            $result   = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curl_err  = curl_error($ch);
            curl_close($ch);

            if ($result === false)
                return false; // cURL network error

            if ($http_code >= 400)
                return false; // HTTP error (401, 403, 404...)

            return $result;
        }

        // --- Fallback : file_get_contents ---
        if (!ini_get('allow_url_fopen'))
            return false; // ni cURL ni allow_url_fopen : impossible

        $ctx = stream_context_create([
            'http' => [
                'method'          => 'GET',
                'header'          => implode("\r\n", $headers),
                'timeout'         => 15,
                'follow_location' => 1,
                'ignore_errors'   => true,
            ],
            'ssl' => [
                'verify_peer'      => true,
                'verify_peer_name' => true,
            ],
        ]);

        $result = @file_get_contents($url, false, $ctx);

        // Check returned HTTP status code
        if (isset($http_response_header))
        {
            preg_match('#HTTP/\S+\s+(\d+)#', $http_response_header[0], $m);
            if (isset($m[1]) && (int)$m[1] >= 400)
                return false;
        }

        return $result;
    }
}
?>
