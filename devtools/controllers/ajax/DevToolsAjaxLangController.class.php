<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      LamPDL
 * @version     PHPBoost 6.0 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2026 03 07
 *
 * Ajax endpoint for the Lang Review tab.
 * action=analyze&module=xxx  → returns unused keys + duplicates
 * action=modules             → returns list of installed modules
 */

require_once (is_dir(PATH_TO_ROOT . '/modules') ? PATH_TO_ROOT . '/modules' : PATH_TO_ROOT) . '/devtools/services/DevToolsAuthorizationsService.class.php';

class DevToolsAjaxLangController extends AbstractController
{
    public function execute(HTTPRequestCustom $request)
    {
        if (!DevToolsAuthorizationsService::check_authorizations()->moderation())
            return new JSONResponse(['success' => false, 'error' => 'Unauthorized'], 403);

        $action = $request->get_string('action', '');

        switch ($action)
        {
            case 'modules':
            case 'lang_modules':
                return $this->action_modules();

            case 'analyze':
            case 'lang_analyze':
                return $this->action_analyze($request);

            default:
                return new JSONResponse(['success' => false, 'error' => 'Unknown action']);
        }
    }

    // -------------------------------------------------------------------------
    // action=modules — list installed modules with available languages
    // -------------------------------------------------------------------------
    private function action_modules()
    {
        $modules = [];
        $modules_path = is_dir(PATH_TO_ROOT . '/modules') ? PATH_TO_ROOT . '/modules' : PATH_TO_ROOT;

        if (!is_dir($modules_path))
            return new JSONResponse(['success' => true, 'modules' => [], 'debug' => 'modules dir not found: ' . $modules_path]);

        $entries = @scandir($modules_path);
        if (!$entries)
            return new JSONResponse(['success' => true, 'modules' => [], 'debug' => 'scandir failed']);

        foreach ($entries as $entry)
        {
            if ($entry === '.' || $entry === '..') continue;
            $lang_path = $modules_path . '/' . $entry . '/lang';
            if (!is_dir($lang_path)) continue;

            $langs = $this->detect_languages($lang_path);
            if (!empty($langs))
                $modules[] = ['name' => $entry, 'languages' => $langs];
        }

        usort($modules, function($a, $b) { return strcmp($a['name'], $b['name']); });
        return new JSONResponse(['success' => true, 'modules' => $modules]);
    }

    // -------------------------------------------------------------------------
    // Detect available languages (subdirs of /lang/ that contain common.php)
    // -------------------------------------------------------------------------
    private function detect_languages($lang_path)
    {
        $langs = [];
        $entries = @scandir($lang_path);
        if (!$entries) return $langs;
        foreach ($entries as $entry)
        {
            if ($entry === '.' || $entry === '..') continue;
            if (is_file($lang_path . '/' . $entry . '/common.php'))
                $langs[] = $entry;
        }
        usort($langs, function($a, $b) {
            if ($a === 'french') return -1;
            if ($b === 'french') return 1;
            return strcmp($a, $b);
        });
        return $langs;
    }

    // -------------------------------------------------------------------------
    // action=analyze&module=xxx&lang=french
    // -------------------------------------------------------------------------
    private function action_analyze(HTTPRequestCustom $request)
    {
        $module = $request->get_string('module', '');
        if (!$module || !preg_match('`^[a-z0-9_-]+$`i', $module))
            return new JSONResponse(['success' => false, 'error' => 'Invalid module']);

        $lang = $request->get_string('lang', 'french');
        if (!preg_match('`^[a-z0-9_-]+$`i', $lang))
            return new JSONResponse(['success' => false, 'error' => 'Invalid lang']);

        $modules_root = is_dir(PATH_TO_ROOT . '/modules') ? PATH_TO_ROOT . '/modules' : PATH_TO_ROOT;
        $module_path = $modules_root . '/' . $module;
        if (!is_dir($module_path))
            return new JSONResponse(['success' => false, 'error' => 'Module not found']);

        // 1. Extract keys for the requested language
        $keys_lang = $this->extract_lang_keys($module_path . '/lang/' . $lang . '/common.php');

        if (empty($keys_lang))
            return new JSONResponse(['success' => false, 'error' => 'No lang keys found for ' . $lang]);

        // 2. Scan all .php + .tpl files in the module for key usage
        $source_files = $this->scan_source_files($module_path);
        $source_content = implode("\n", $source_files);

        // 3. Find unused keys
        $unused = [];
        foreach ($keys_lang as $key => $value)
        {
            if (!$this->is_key_used($key, $source_content))
                $unused[] = ['key' => $key, 'value' => $value];
        }

        // 4. Find internal duplicates (same value, different key) for this lang
        $duplicates_internal = $this->find_internal_duplicates_for_lang($keys_lang, $lang);

        // 5. External duplicates only for french (reference lang)
        $duplicates_external = ($lang === 'french')
            ? $this->find_external_duplicates($keys_lang, $module)
            : [];

        return new JSONResponse([
            'success'             => true,
            'module'              => $module,
            'lang'                => $lang,
            'total_keys'          => count($keys_lang),
            'unused'              => $unused,
            'duplicates_internal' => $duplicates_internal,
            'duplicates_external' => $duplicates_external,
        ]);
    }

    // -------------------------------------------------------------------------
    // Extract $lang['key'] = 'value' from a PHP lang file
    // -------------------------------------------------------------------------
    private function extract_lang_keys($file_path)
    {
        $keys = [];
        if (!is_file($file_path)) return $keys;

        $content = file_get_contents($file_path);
        // Match $lang['key'] = 'value'; or $lang['key'] = "value";
        preg_match_all('`\$lang\[\'([^\']+)\'\]\s*=\s*(?:\'((?:[^\'\\\\]|\\\\.)*)\'|"((?:[^"\\\\]|\\\\.)*)")\s*;`', $content, $matches, PREG_SET_ORDER);

        foreach ($matches as $m)
        {
            $key   = $m[1];
            $value = isset($m[3]) && $m[3] !== '' ? $m[3] : $m[2];
            $keys[$key] = $value;
        }

        return $keys;
    }

    // -------------------------------------------------------------------------
    // Scan all .php and .tpl files in a module directory, return array of contents
    // -------------------------------------------------------------------------
    private function scan_source_files($module_path)
    {
        $contents = [];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($module_path, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file)
        {
            $ext = TextHelper::strtolower(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
            if (!in_array($ext, ['php', 'tpl'])) continue;
            // Skip lang files themselves
            if (strpos($file->getPathname(), '/lang/') !== false) continue;

            $c = @file_get_contents($file->getPathname());
            if ($c !== false) $contents[] = $c;
        }

        return $contents;
    }

    // -------------------------------------------------------------------------
    // Check if a lang key is referenced anywhere in source content
    // PHPBoost patterns:
    //   {@key} in .tpl
    //   $this->lang['key'] in .php
    //   LangLoader::get_message('key', ...) in .php
    // -------------------------------------------------------------------------
    private function is_key_used($key, $source_content)
    {
        // {@key} — tpl pattern
        if (strpos($source_content, '{@' . $key . '}') !== false) return true;
        // $lang['key'] or $this->lang['key']
        if (strpos($source_content, "lang['" . $key . "']") !== false) return true;
        // LangLoader::get_message('key'
        if (strpos($source_content, "get_message('" . $key . "'") !== false) return true;

        return false;
    }

    // -------------------------------------------------------------------------
    // Find keys with identical values within a single lang file
    // -------------------------------------------------------------------------
    private function find_internal_duplicates_for_lang($keys, $lang)
    {
        $duplicates = [];
        $by_value = [];
        foreach ($keys as $key => $value)
        {
            $norm = trim(mb_strtolower($value, 'UTF-8'));
            if ($norm === '') continue;
            $by_value[$norm][] = $key;
        }
        foreach ($by_value as $value => $dup_keys)
            if (count($dup_keys) > 1)
                $duplicates[] = ['lang' => $lang, 'value' => $value, 'keys' => $dup_keys];

        return $duplicates;
    }

    // -------------------------------------------------------------------------
    // Find keys whose fr value matches a key in another module's fr lang file
    // -------------------------------------------------------------------------
    private function find_external_duplicates($keys_fr, $current_module)
    {
        if (empty($keys_fr)) return [];

        $duplicates = [];
        $modules_path = is_dir(PATH_TO_ROOT . '/modules') ? PATH_TO_ROOT . '/modules' : PATH_TO_ROOT;

        // Build a map: value → array of [module, key] from other modules
        $other_values = [];
        foreach (scandir($modules_path) as $mod)
        {
            if ($mod === '.' || $mod === '..' || $mod === $current_module) continue;
            $lang_file = $modules_path . '/' . $mod . '/lang/french/common.php';
            if (!is_file($lang_file)) continue;

            $other_keys = $this->extract_lang_keys($lang_file);
            foreach ($other_keys as $k => $v)
            {
                $norm = trim(strtolower($v));
                if ($norm === '') continue;
                $other_values[$norm][] = ['module' => $mod, 'key' => $k];
            }
        }

        // Compare current module keys against other modules
        foreach ($keys_fr as $key => $value)
        {
            $norm = trim(strtolower($value));
            if ($norm === '' || !isset($other_values[$norm])) continue;
            $duplicates[] = [
                'key'     => $key,
                'value'   => $value,
                'matches' => $other_values[$norm],
            ];
        }

        return $duplicates;
    }
}
?>
