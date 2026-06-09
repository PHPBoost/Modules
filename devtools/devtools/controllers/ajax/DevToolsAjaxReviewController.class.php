<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      LamPDL
 * @version     PHPBoost 6.0 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2026 03 06
 *
 * Handles all Ajax requests for the File Review tab.
 *
 * Supported actions (POST parameter: action):
 *   refresh  — Repopulates the review cache table and returns counters.
 *   section  — Returns the HTML rows for a given file list section.
 */

require_once (is_dir(PATH_TO_ROOT . '/modules') ? PATH_TO_ROOT . '/modules' : PATH_TO_ROOT) . '/devtools/services/ReviewService.class.php';

class DevToolsAjaxReviewController extends AbstractController
{
    public function execute(HTTPRequestCustom $request)
    {
        // Review tab requires at least moderator level
        if (!DevToolsAuthorizationsService::check_authorizations()->moderation())
            return new JSONResponse(['success' => false, 'error' => 'Unauthorized'], 403);

        $action = $request->get_string('action', '');

        switch ($action)
        {
            case 'refresh':
                return $this->action_refresh();

            case 'clear':
                return $this->action_clear();

            case 'section':
                return $this->action_section($request);

            case 'lang_modules':
                return $this->action_lang_modules();

            case 'lang_analyze':
                return $this->action_lang_analyze($request);

            default:
                return new JSONResponse(['success' => false, 'error' => 'Unknown action']);
        }
    }

    // -------------------------------------------------------------------------
    // action=clear
    // Truncates the review cache table without repopulating it.
    // -------------------------------------------------------------------------
    private function action_clear()
    {
        try
        {
            $this->ensure_table_exists();
            ReviewService::delete_files_in_content_table();
        }
        catch (Exception $e)
        {
            return new JSONResponse(['success' => false, 'error' => $e->getMessage()]);
        }

        return new JSONResponse(['success' => true]);
    }

    // -------------------------------------------------------------------------
    // action=refresh
    // Repopulates the devtools_review cache table, then returns all
    // counters so the UI can update its badges without a full page reload.
    // -------------------------------------------------------------------------
    private function action_refresh()
    {
        try
        {
            $this->ensure_table_exists();
            ReviewService::delete_files_in_content_table();

            // populate returns content-derived counters computed during the scan
            $this->populate_files_in_content_table();

            // Remaining counters require a single disk scan + upload table read
            $files_on_server = ReviewService::get_files_on_server('/upload');
            $files_in_upload = ReviewService::get_files_in_table('upload');
            $files_in_content = array_unique(array_column(
                ReviewService::get_files_in_content(), 'file_path'
            ));

            $all_unused         = array_values(array_diff($files_in_upload, $files_in_content));
            $missing_paths      = array_values(array_diff($files_in_content, $files_on_server));
            $used_not_on_server = ReviewService::get_data_used_files_not_on_server($missing_paths);

            // unused_with_users and orphan require upload join — use ReviewService
            $unused_with_users = ReviewService::get_unused_files_with_users();
            $orphan            = ReviewService::get_orphan_files();

            $counters = [
                'files_on_server'           => count($files_on_server),
                'files_in_upload'           => count($files_in_upload),
                'files_in_content'          => count($files_in_content),
                'all_unused'                => count($all_unused),
                'used_not_on_server'        => count($used_not_on_server),
                'unused_with_users'         => count($unused_with_users),
                'orphan'                    => count($orphan),
                'files_in_gallery_folder'   => count(ReviewService::get_files_on_server('/gallery/pics')),
                'files_in_gallery_table'    => count(ReviewService::get_files_in_table('gallery')),
                'not_in_gallery_folder'     => count(ReviewService::get_count_files_not_in_gallery_folder()),
                'not_in_gallery_table'      => count(ReviewService::get_count_files_not_in_gallery_table()),
            ];
        }
        catch (Exception $e)
        {
            return new JSONResponse(['success' => false, 'error' => $e->getMessage()]);
        }

        return new JSONResponse([
            'success'  => true,
            'counters' => $counters,
        ]);
    }

    // -------------------------------------------------------------------------
    // action=section
    // Returns an array of data rows for the requested section so the JS can
    // render the table without a page reload.
    // Supported section values mirror those of the original ReviewDisplayController.
    // -------------------------------------------------------------------------
    private function action_section(HTTPRequestCustom $request)
    {
        $section = $request->get_string('section', '');
        $rows    = [];

        switch ($section)
        {
            case 'onserver':
                foreach (ReviewService::get_files_on_server('/upload') as $file)
                    $rows[] = [
                        'file'       => $file,
                        'is_picture' => ReviewService::is_picture_file($file, '/upload'),
                        'is_pdf'     => ReviewService::is_pdf_file($file, '/upload'),
                        'folder'     => 'upload',
                    ];
                break;

            case 'inupload':
                foreach (ReviewService::get_files_in_table('upload') as $file)
                    $rows[] = [
                        'file'       => $file,
                        'is_picture' => ReviewService::is_picture_file($file, '/upload'),
                        'is_pdf'     => ReviewService::is_pdf_file($file, '/upload'),
                        'folder'     => 'upload',
                    ];
                break;

            case 'incontent':
                foreach (ReviewService::get_files_in_content() as $file)
                {
                    $link  = ReviewService::get_file_link($file);
                    $rows[] = [
                        'file'          => $file['file_path'],
                        'is_picture'    => ReviewService::is_picture_file($file['file_path'], '/upload'),
                        'is_pdf'        => ReviewService::is_pdf_file($file['file_path'], '/upload'),
                        'module_source' => $file['module_source'],
                        'item_title'    => $file['item_title'],
                        'item_link'     => $link ?: '',
                        'folder'        => 'upload',
                    ];
                }
                break;

            case 'allunused':
                foreach (ReviewService::get_all_unused_files() as $file)
                    $rows[] = [
                        'file'       => $file,
                        'is_picture' => ReviewService::is_picture_file($file, '/upload'),
                        'is_pdf'     => ReviewService::is_pdf_file($file, '/upload'),
                        'folder'     => 'upload',
                    ];
                break;

            case 'usednoserver':
                foreach (ReviewService::get_data_used_files_not_on_server() as $file)
                {
                    $rows[] = [
                        'file'          => $file['file_path'],
                        'module_source' => $file['module_source'],
                        'item_title'    => $file['item_title'],
                        'item_link'     => $file['file_link'] ?: '',
                        'edit_link'     => $file['edit_link'] ?: '',
                        'file_context'  => $file['file_context'] ?: '',
                    ];
                }
                break;

            case 'unuseduser':
                foreach (ReviewService::get_unused_files_with_users() as $file)
                {
                    $upload_date = Date::to_format($file['timestamp'], Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE);
                    $file_size   = $file['file_size'] > 1024
                        ? NumberHelper::round($file['file_size'] / 1024, 2) . ' ' . LangLoader::get_message('common.unit.megabytes', 'common-lang')
                        : NumberHelper::round($file['file_size'], 0)        . ' ' . LangLoader::get_message('common.unit.kilobytes', 'common-lang');

                    $rows[] = [
                        'file'        => $file['file_path'],
                        'is_picture'  => ReviewService::is_picture_file($file['file_path'], '/upload'),
                        'is_pdf'      => ReviewService::is_pdf_file($file['file_path'], '/upload'),
                        'user'        => $file['display_name'],
                        'upload_date' => $upload_date,
                        'file_size'   => $file_size,
                        'folder'      => 'upload',
                    ];
                }
                break;

            case 'orphan':
                foreach (ReviewService::get_orphan_files() as $file)
                    $rows[] = [
                        'file'       => $file,
                        'is_picture' => ReviewService::is_picture_file($file, '/upload'),
                        'is_pdf'     => ReviewService::is_pdf_file($file, '/upload'),
                        'folder'     => 'upload',
                    ];
                break;

            case 'ingalleryfolder':
                foreach (ReviewService::get_files_on_server('/gallery/pics') as $file)
                    $rows[] = [
                        'file'       => $file,
                        'is_picture' => ReviewService::is_picture_file($file, '/gallery/pics'),
                        'is_pdf'     => ReviewService::is_pdf_file($file, '/gallery/pics'),
                        'folder'     => 'gallery/pics',
                    ];
                break;

            case 'ingallerytable':
                foreach (ReviewService::get_files_in_table('gallery') as $file)
                    $rows[] = [
                        'file'       => $file,
                        'is_picture' => ReviewService::is_picture_file($file, '/gallery/pics'),
                        'is_pdf'     => ReviewService::is_pdf_file($file, '/gallery/pics'),
                        'folder'     => 'gallery/pics',
                    ];
                break;

            case 'nogalleryfolder':
                foreach (ReviewService::get_files_not_in_gallery_folder() as $file)
                    $rows[] = ['file' => $file];
                break;

            case 'nogallerytable':
                foreach (ReviewService::get_files_not_in_gallery_table() as $file)
                    $rows[] = ['file' => $file];
                break;

            default:
                return new JSONResponse(['success' => false, 'error' => 'Unknown section: ' . $section]);
        }

        return new JSONResponse(['success' => true, 'rows' => $rows]);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Creates the review cache table if it does not already exist.
     * This covers the case where the module was installed before the Review tab
     * was added, or where the table was dropped manually.
     */
    private function ensure_table_exists()
    {
        $dbms  = PersistenceContext::get_dbms_utils();
        $table = ReviewService::get_table_name();

        if (!in_array($table, $dbms->list_tables()))
        {
            $fields = [
                'id'                 => ['type' => 'integer', 'length' => 11,       'autoincrement' => true, 'notnull' => 1],
                'file_path'          => ['type' => 'text',    'length' => 16777215],
                'file_link'          => ['type' => 'text',    'length' => 16777215],
                'edit_link'          => ['type' => 'text',    'length' => 16777215],
                'module_source'      => ['type' => 'string',  'length' => 255,      'notnull' => 1],
                'id_module_category' => ['type' => 'integer', 'length' => 11,       'notnull' => 1],
                'category_name'      => ['type' => 'string',  'length' => 255],
                'item_id'            => ['type' => 'integer', 'length' => 11,       'notnull' => 1],
                'item_title'         => ['type' => 'string',  'length' => 255,      'notnull' => 1],
                'id_in_module'       => ['type' => 'integer', 'length' => 11,       'notnull' => 1],
            ];
            $dbms->create_table($table, $fields, ['primary' => ['id']]);
            return;
        }

        // Table exists — add edit_link column if missing (migration)
        $existing_cols = [];
        try
        {
            $res = PersistenceContext::get_querier()->select('SHOW COLUMNS FROM `' . $table . '`');
            while ($row = $res->fetch())
                $existing_cols[] = $row['Field'];
            $res->dispose();
        }
        catch (Exception $e) {}

        if (!in_array('edit_link', $existing_cols))
        {
            PersistenceContext::get_querier()->inject(
                'ALTER TABLE `' . $table . '` ADD COLUMN `edit_link` MEDIUMTEXT NULL AFTER `file_link`'
            );
        }

        if (!in_array('file_context', $existing_cols))
        {
            PersistenceContext::get_querier()->inject(
                'ALTER TABLE `' . $table . '` ADD COLUMN `file_context` MEDIUMTEXT NULL'
            );
        }
    }

    /**
     * Repopulates the devtools_review table by scanning all PHPBoost
     * module tables for /upload references.
     * Mirrors ReviewSetup::insert_files_in_content_table() but lives here so
     * it can be triggered on demand without reinstalling the module.
     */
    private function populate_files_in_content_table()
    {
        $result = ReviewService::get_tables_with_content_field();

        foreach ($result as $values)
        {
            // Extract module from table name by stripping the PREFIX.
            // e.g. "pbtnext_wiki_contents" → "wiki_contents" → module = "wiki"
            // e.g. "pbtnext_news_items"    → "news_items"    → module = "news"
            $table_without_prefix = substr($values['table'], strlen(PREFIX));
            $module = strstr($table_without_prefix, '_', true); // part before first underscore
            if ($module === false)
                $module = $table_without_prefix;

            if (!ReviewService::check_module_compatibility($module))
                continue;

            // Build WHERE clause: at least one column contains /upload
            $where_parts = [];
            foreach ($values['columns'] as $col)
                $where_parts[] = $col . ' LIKE "%/upload%"';

            // wiki_contents stores one row per version — only scan the latest version per article.
            // We avoid a self-referencing subquery (blocked on some MySQL versions) by fetching
            // the max id per id_article first, then filtering in PHP.
            $wiki_latest_ids = null;
            if ($values['table'] === PREFIX . 'wiki_contents')
            {
                $wiki_latest_ids = [];
                try
                {
                    $r = PersistenceContext::get_querier()->select(
                        'SELECT MAX(content_id) AS max_id FROM ' . $values['table'] . ' GROUP BY item_id'
                    );
                    while ($row = $r->fetch())
                        $wiki_latest_ids[] = (int)$row['max_id'];
                    $r->dispose();
                }
                catch (Exception $e) { $wiki_latest_ids = null; }
            }

            try
            {
                $req = PersistenceContext::get_querier()->select(
                    'SELECT * FROM ' . $values['table'] . '
                     WHERE (' . implode(' OR ', $where_parts) . ')'
                );
            }
            catch (Exception $e) { continue; }

            while ($data = $req->fetch())
            {
                // Skip older wiki versions — keep only the latest per article
                if ($wiki_latest_ids !== null && !in_array((int)$data['content_id'], $wiki_latest_ids))
                    continue;
                // Special title resolution for modules that do not use a standard `title` column
                if ($module === 'newsletter' && isset($data['stream_id']))
                    $data['title'] = Url::encode_rewrite(ReviewService::get_newsletter_title($data['stream_id']));

                if ($module === 'wiki')
                {
                    // wiki_contents uses item_id as FK to wiki_articles
                    if (!isset($data['item_id']))
                        $data['item_id'] = 0;

                    $data['title'] = $data['item_id']
                        ? Url::encode_rewrite(ReviewService::get_wiki_title($data['item_id']))
                        : '';
                }

                if ($module === 'forum')
                    $data['title'] = isset($data['name'])
                        ? Url::encode_rewrite($data['name'])
                        : (isset($data['idtopic']) ? ReviewService::get_topic_title($data['idtopic']) : '');

                $cat_name  = ReviewService::get_category_name($module, $data);
                $file_link = ReviewService::create_file_link($module, $data);
                $edit_link = ReviewService::create_edit_link($module, $data);

                // Concatenate all text columns — catches /upload refs in any column, one pass per row
                $combined = '';
                foreach ($values['columns'] as $col)
                    if (isset($data[$col])) $combined .= ' ' . $data[$col];

                $content = TextHelper::htmlspecialchars($combined);
                preg_match_all('`\/upload\/([^\s\"\'\]\)\[<]+\.[a-zA-Z0-9]{2,5})`us', $content, $files);
                $unique_paths = array_unique($files[1]);

                $id_list = array_values($data);

                foreach ($unique_paths as $file_path)
                {
                    // Extract ~60 chars around the filename for context display
                    $needle  = '/upload/' . $file_path;
                    $pos     = strpos($combined, $needle);
                    $context = '';
                    if ($pos !== false)
                    {
                        $start   = max(0, $pos - 40);
                        $excerpt = substr($combined, $start, 40 + strlen($needle) + 40);
                        // Mark the filename with «» delimiters, strip Bbcode tags around it
                        $excerpt = preg_replace('`\[/?[a-z]+[^\]]*\]`i', '', $excerpt);
                        $before  = substr($excerpt, 0, $pos - $start);
                        $after   = substr($excerpt, $pos - $start + strlen($needle));
                        $context = trim($before) . ' «' . $file_path . '» ' . trim($after);
                        // Trim to ~100 chars total
                        $context = mb_substr(trim($context), 0, 120, 'UTF-8');
                    }

                    PersistenceContext::get_querier()->insert(
                        ReviewService::get_table_name(),
                        [
                            'file_path'          => $file_path,
                            'file_link'          => $file_link ?: '',
                            'edit_link'          => $edit_link ?: '',
                            'module_source'      => $module,
                            'id_module_category' => isset($data['id_category']) ? $data['id_category'] : 0,
                            'category_name'      => $cat_name ?: '---',
                            'item_id'            => isset($data['item_id']) ? $data['item_id'] : 0,
                            'item_title'         => isset($data['title']) ? $data['title'] : (isset($data['name']) ? $data['name'] : ''),
                            'id_in_module'       => $id_list[0],
                            'file_context'       => $context,
                        ]
                    );
                }
            }
        }
    }

    // =========================================================================
    // Lang Review actions
    // =========================================================================

    private function action_lang_modules()
    {
        $modules = [];
        $modules_path = PATH_TO_ROOT . '/modules';

        if (!is_dir($modules_path))
            return new JSONResponse(['success' => true, 'modules' => []]);

        $entries = @scandir($modules_path);
        if (!$entries)
            return new JSONResponse(['success' => true, 'modules' => []]);

        foreach ($entries as $entry)
        {
            if ($entry === '.' || $entry === '..') continue;
            if (is_file($modules_path . '/' . $entry . '/lang/french/common.php'))
                $modules[] = $entry;
        }

        sort($modules);
        return new JSONResponse(['success' => true, 'modules' => $modules]);
    }

    private function action_lang_analyze(HTTPRequestCustom $request)
    {
        $module = $request->get_string('module', '');
        if (!$module || !preg_match('`^[a-z0-9_-]+$`i', $module))
            return new JSONResponse(['success' => false, 'error' => 'Invalid module']);

        $module_path = PATH_TO_ROOT . '/modules/' . $module;
        if (!is_dir($module_path))
            return new JSONResponse(['success' => false, 'error' => 'Module not found']);

        $keys_fr = $this->lang_extract_keys($module_path . '/lang/french/common.php');
        $keys_en = $this->lang_extract_keys($module_path . '/lang/english/common.php');

        $all_keys = [];
        foreach ($keys_fr as $k => $d) $all_keys[$k] = ['fr' => $d['value'], 'en' => isset($keys_en[$k]) ? $keys_en[$k]['value'] : null];
        foreach ($keys_en as $k => $d)
            if (!isset($all_keys[$k])) $all_keys[$k] = ['fr' => null, 'en' => $d['value']];

        if (empty($all_keys))
            return new JSONResponse(['success' => false, 'error' => 'No lang keys found']);

        $source = $this->lang_scan_sources($module_path);

        $unused = [];
        foreach ($all_keys as $key => $vals)
            if (!$this->lang_is_used($key, $source))
                $unused[] = [
                    'key'  => $key,
                    'fr'   => $vals['fr'],
                    'en'   => $vals['en'],
                    'file' => isset($keys_fr[$key]) ? $keys_fr[$key]['file'] : (isset($keys_en[$key]) ? $keys_en[$key]['file'] : ''),
                    'line' => isset($keys_fr[$key]) ? $keys_fr[$key]['line'] : (isset($keys_en[$key]) ? $keys_en[$key]['line'] : 0),
                ];

        return new JSONResponse([
            'success'             => true,
            'module'              => $module,
            'total_keys'          => count($all_keys),
            'unused'              => $unused,
            'duplicates_internal' => $this->lang_dup_internal($keys_fr, $keys_en),
            'duplicates_external' => $this->lang_dup_external($keys_fr, $module),
        ]);
    }

    private function lang_extract_keys($file)
    {
        $keys = [];
        if (!is_file($file)) return $keys;
        $content = file_get_contents($file);
        $lines   = explode("\n", $content);
        preg_match_all('`\$lang\[\'([^\']+)\'\]\s*=\s*(?:\'((?:[^\'\\\\]|\\\\.)*)\')?\s*;`', $content, $m, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);
        foreach ($m as $r)
        {
            $key    = $r[1][0];
            $value  = isset($r[2][0]) ? $r[2][0] : '';
            $offset = $r[0][1];
            $line   = substr_count(substr($content, 0, $offset), "\n") + 1;
            $keys[$key] = ['value' => $value, 'line' => $line, 'file' => $file];
        }
        return $keys;
    }

    private function lang_scan_sources($module_path)
    {
        $out = '';
        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($module_path, RecursiveDirectoryIterator::SKIP_DOTS));
        foreach ($it as $f)
        {
            $ext = TextHelper::strtolower(pathinfo($f->getFilename(), PATHINFO_EXTENSION));
            if (!in_array($ext, ['php', 'tpl'])) continue;
            if (strpos($f->getPathname(), DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR) !== false) continue;
            $c = @file_get_contents($f->getPathname());
            if ($c) $out .= $c;
        }
        return $out;
    }

    private function lang_is_used($key, $source)
    {
        if (strpos($source, '{@' . $key . '}') !== false) return true;
        if (strpos($source, "lang['" . $key . "']") !== false) return true;
        if (strpos($source, "get_message('" . $key . "'") !== false) return true;
        return false;
    }

    private function lang_dup_internal($keys_fr, $keys_en)
    {
        $dups = [];
        foreach (['fr' => $keys_fr, 'en' => $keys_en] as $lang => $keys)
        {
            $by_val = [];
            foreach ($keys as $k => $d)
            {
                $n = trim(mb_strtolower($d['value'], 'UTF-8'));
                if ($n) $by_val[$n][] = ['key' => $k, 'line' => $d['line'], 'file' => $d['file']];
            }
            foreach ($by_val as $v => $entries)
                if (count($entries) > 1) $dups[] = ['lang' => $lang, 'value' => $v, 'keys' => $entries];
        }
        return $dups;
    }

    private function lang_dup_external($keys_fr, $current)
    {
        if (empty($keys_fr)) return [];
        $other = [];
        foreach (@scandir(PATH_TO_ROOT . '/modules') ?: [] as $mod)
        {
            if ($mod === '.' || $mod === '..' || $mod === $current) continue;
            $f = PATH_TO_ROOT . '/modules/' . $mod . '/lang/french/common.php';
            if (!is_file($f)) continue;
            foreach ($this->lang_extract_keys($f) as $k => $d)
            {
                $n = trim(mb_strtolower($d['value'], 'UTF-8'));
            }
        }
        $dups = [];
        foreach ($keys_fr as $key => $d)
        {
            $n = trim(mb_strtolower($d['value'], 'UTF-8'));
            if ($n && isset($other[$n]))
                $dups[] = ['key' => $key, 'value' => $d['value'], 'line' => $d['line'], 'file' => $d['file'], 'matches' => $other[$n]];
        }
        return $dups;
    }

}
?>
