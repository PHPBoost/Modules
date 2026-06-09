<?php
/**
 * @copyright   &copy; 2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Mipel <mipel@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2022 01 10
 *
 * Provides all file-audit logic for the DevTools Review tab.
 * Compares files physically present on the server against those
 * referenced in the database (upload table and module content fields).
 */
class ReviewService
{
    private static $db_querier;
    public static $files_in_content_table;

    /** List of modules whose content is indexed by this service. */
    private static $supported_modules = [
        'news', 'articles', 'calendar', 'download', 'media',
        'wiki', 'gallery', 'faq', 'forum', 'web',
        'newsletter', 'pages', 'smallads',
    ];

    public static function __static()
    {
        self::$db_querier             = PersistenceContext::get_querier();
        self::$files_in_content_table = PREFIX . 'devtools_review';
    }

    // Always returns the correct table name, even if __static() was not yet invoked.
    private static function table()
    {
        return PREFIX . 'devtools_review';
    }

    // Public alias for external callers (e.g. DevToolsAjaxReviewController).
    public static function get_table_name()
    {
        return PREFIX . 'devtools_review';
    }

    // -------------------------------------------------------------------------
    // Module compatibility
    // -------------------------------------------------------------------------

    /**
     * Returns true if the given module is supported by this service.
     * Fixed: original returned null (not false) for unsupported modules.
     *
     * @param  string $module
     * @return bool
     */
    public static function check_module_compatibility($module)
    {
        return in_array($module, self::$supported_modules);
    }

    // -------------------------------------------------------------------------
    // Cache table management
    // -------------------------------------------------------------------------

    /** Truncates the review cache table so it can be repopulated. */
    public static function delete_files_in_content_table()
    {
        PersistenceContext::get_dbms_utils()->truncate([self::table()]);
    }

    /**
     * Scans text/varchar columns in the database, restricted to PHPBoost tables
     * (filtered by PREFIX) to avoid false positives on shared servers.
     * Fixed: original scanned ALL tables in information_schema without any prefix filter.
     *
     * @return array  [ ['table' => string, 'column' => string], ... ]
     */
    public static function get_tables_with_content_field()
    {
        $db_name = PersistenceContext::get_dbms_utils()->get_database_name();

        $req = PersistenceContext::get_querier()->select(
            'SELECT TABLE_NAME, COLUMN_NAME
             FROM information_schema.columns c
             WHERE c.table_schema = "' . $db_name . '"
               AND c.TABLE_NAME LIKE "' . PREFIX . '%"
               AND (c.column_type LIKE "%text"
                OR c.column_type LIKE "%varchar%")'
        );

        // Suffixes that indicate secondary/auxiliary tables — never contain user-uploaded files.
        // Only the primary content table of each module should be scanned.
        $excluded_suffixes = [
            '_cats', '_cat', '_categories', '_config', '_configs',
            '_topics', '_articles', '_archives', '_streams',
            '_logs', '_rights', '_auth',
        ];

        $by_table = [];
        while ($row = $req->fetch())
        {
            $table = $row['TABLE_NAME'];

            // Skip auxiliary tables
            $skip = false;
            foreach ($excluded_suffixes as $suffix)
            {
                if (substr($table, -strlen($suffix)) === $suffix)
                {
                    $skip = true;
                    break;
                }
            }
            if ($skip) continue;

            $by_table[$table][] = $row['COLUMN_NAME'];
        }

        $req->dispose();

        $results = [];
        foreach ($by_table as $table => $columns)
            $results[] = ['table' => $table, 'columns' => $columns];

        return $results;
    }

    // -------------------------------------------------------------------------
    // File listings
    // -------------------------------------------------------------------------

    /**
     * Returns filenames physically present in $folder on the server.
     * Only files that have an extension are returned (directories and dotfiles skipped).
     * Fixed: original condition `!strpos(...) != 1` was always true due to bool/int coercion.
     *
     * @param  string $folder  Relative path from PATH_TO_ROOT, e.g. '/upload'
     * @return array           Plain list of filenames (basename only)
     */
    public static function get_files_on_server($folder)
    {
        $data    = new Folder(PATH_TO_ROOT . $folder);
        $results = [];

        if ($data->exists())
        {
            foreach ($data->get_files() as $file)
            {
                $name = $file->get_name();
                $dot  = strrpos($name, '.');
                // Skip dotfiles (.htaccess, .gitignore…) and files without extension
                if ($dot === false || $dot === 0)
                    continue;
                $ext = substr($name, $dot + 1);
                // Skip extensions that are purely dots or empty (e.g. ".xxx" artefacts)
                if ($ext === '' || $ext === '.')
                    continue;
                $results[] = $name;
            }
        }

        return $results;
    }

    /**
     * Returns all rows from the review cache table.
     *
     * @return array
     */
    public static function get_files_in_content()
    {
        $files_in_content = [];

        try
        {
            $req = PersistenceContext::get_querier()->select(
                'SELECT * FROM ' . self::table()
            );
            while ($row = $req->fetch())
            {
                if (!empty($row))
                    $files_in_content[] = $row;
            }
            $req->dispose();
        }
        catch (Exception $e)
        {
            // Table may not exist yet — return empty array
        }

        return $files_in_content;
    }

    /**
     * Returns all file paths stored in a module's table (expects a `path` column).
     *
     * @param  string $table  Table name without prefix (e.g. 'gallery', 'upload')
     * @return array          List of path strings
     */
    public static function get_files_in_table($table)
    {
        $results = [];

        try
        {
            $req = PersistenceContext::get_querier()->select(
                'SELECT path FROM ' . PREFIX . $table . ' ORDER BY path DESC'
            );
            while ($row = $req->fetch())
                $results[] = $row['path'];

            $req->dispose();
        }
        catch (Exception $e)
        {
            // Table may not exist (module not installed) — return empty
        }

        return $results;
    }

    // -------------------------------------------------------------------------
    // Comparison helpers
    // -------------------------------------------------------------------------

    /**
     * Returns files present on the server but not referenced in any content.
     *
     * @return array  List of filenames
     */
    public static function get_all_unused_files()
    {
        $files_on_server  = self::get_files_on_server('/upload');
        $files_in_content = [];

        foreach (self::get_files_in_content() as $file)
            $files_in_content[] = $file['file_path'];

        return array_values(array_diff($files_on_server, $files_in_content));
    }

    /**
     * Returns file paths referenced in content but no longer present on the server.
     *
     * @param  string $folder  e.g. '/upload'
     * @return array           List of file_path strings
     */
    public static function get_count_used_files_not_on_server($folder)
    {
        $files_on_server  = self::get_files_on_server($folder);
        $files_in_content = [];

        foreach (self::get_files_in_content() as $file)
            $files_in_content[] = $file['file_path'];

        return array_values(array_diff($files_in_content, $files_on_server));
    }

    /**
     * Returns detailed rows for files used in content but missing on the server.
     * Fixed: $data_used_files was potentially undefined if the loop body never executed.
     *
     * @return array  [ ['file_path' => ..., 'module_source' => ..., 'item_title' => ...], ... ]
     */
    public static function get_data_used_files_not_on_server($missing = null)
    {
        if ($missing === null)
            $missing = self::get_count_used_files_not_on_server('/upload');

        if (empty($missing))
            return [];

        // Single query instead of one per file to avoid N×1 timeout
        $placeholders = implode(', ', array_map(function($f) {
            return '"' . addslashes($f) . '"';
        }, $missing));

        $data_used_files = [];
        try
        {
            $result = PersistenceContext::get_querier()->select(
                'SELECT id, file_path, file_link, edit_link, module_source, item_title, id_module_category, category_name, item_id, id_in_module, file_context
                 FROM ' . self::table() . '
                 WHERE file_path IN (' . $placeholders . ')'
            );
            while ($row = $result->fetch())
            {
                $data_used_files[] = [
                    'id'                 => $row['id'],
                    'file_path'          => $row['file_path'],
                    'file_link'          => $row['file_link'],
                    'edit_link'          => $row['edit_link'],
                    'module_source'      => $row['module_source'],
                    'item_title'         => $row['item_title'],
                    'id_module_category' => $row['id_module_category'],
                    'category_name'      => $row['category_name'],
                    'item_id'            => $row['item_id'],
                    'id_in_module'       => $row['id_in_module'],
                    'file_context'       => $row['file_context'],
                ];
            }
            $result->dispose();
        }
        catch (Exception $e) {}

        return $data_used_files;
    }

    // -------------------------------------------------------------------------
    // Gallery-specific comparisons
    // -------------------------------------------------------------------------

    /** Returns files in the gallery table but absent from /gallery/pics on disk. */
    public static function get_count_files_not_in_gallery_folder()
    {
        return array_values(array_diff(
            self::get_files_in_table('gallery'),
            self::get_files_on_server('/gallery/pics')
        ));
    }

    public static function get_files_not_in_gallery_folder()
    {
        return self::get_count_files_not_in_gallery_folder();
    }

    /** Returns files physically present in /gallery/pics but absent from the gallery table. */
    public static function get_count_files_not_in_gallery_table()
    {
        return array_values(array_diff(
            self::get_files_on_server('/gallery/pics'),
            self::get_files_in_table('gallery')
        ));
    }

    public static function get_files_not_in_gallery_table()
    {
        return self::get_count_files_not_in_gallery_table();
    }

    // -------------------------------------------------------------------------
    // User / orphan file analysis
    // -------------------------------------------------------------------------

    /**
     * Returns unused files that have a record in the upload table (linked to a user).
     *
     * @return array  [ ['file_path' => ..., 'display_name' => ..., 'timestamp' => ..., 'file_size' => ...], ... ]
     */
    public static function get_unused_files_with_users()
    {
        $upload_data = [];

        foreach (self::get_all_unused_files() as $file)
        {
            try
            {
                $result = PersistenceContext::get_querier()->select(
                    'SELECT id, upload.path, member.display_name, timestamp, size
                     FROM ' . DB_TABLE_UPLOAD . ' upload
                     LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON upload.user_id = member.user_id
                     WHERE upload.path = "' . $file . '"'
                );
                while ($row = $result->fetch())
                {
                    $upload_data[] = [
                        'file_path'    => $row['path'],
                        'display_name' => $row['display_name'],
                        'timestamp'    => $row['timestamp'],
                        'file_size'    => $row['size'],
                    ];
                }
                $result->dispose();
            }
            catch (Exception $e) {}
        }

        return $upload_data;
    }

    /**
     * Returns unused files that have NO record in the upload table (truly orphaned).
     *
     * @return array  List of filenames
     */
    public static function get_orphan_files()
    {
        $files_with_users = [];
        foreach (self::get_unused_files_with_users() as $file)
            $files_with_users[] = $file['file_path'];

        return array_values(array_diff(self::get_all_unused_files(), $files_with_users));
    }

    // -------------------------------------------------------------------------
    // File type helpers
    // -------------------------------------------------------------------------

    /**
     * Returns true if $file has an image extension (jpg, jpeg, png, svg, gif).
     * Fixed: original unnecessarily checked server existence — extension check alone is sufficient.
     */
    public static function is_picture_file($file, $folder)
    {
        $ext = TextHelper::strtolower(substr(strrchr(is_array($file) ? $file['file_path'] : $file, '.'), 1));
        return in_array($ext, ['jpg', 'jpeg', 'png', 'svg', 'gif']);
    }

    /**
     * Returns true if $file has a .pdf extension.
     */
    public static function is_pdf_file($file, $folder)
    {
        $ext = TextHelper::strtolower(substr(strrchr(is_array($file) ? $file['file_path'] : $file, '.'), 1));
        return $ext === 'pdf';
    }

    // -------------------------------------------------------------------------
    // URL builders (used during cache population in ReviewSetup)
    // -------------------------------------------------------------------------

    /**
     * Builds the front-end URL pointing to a content item that references a file.
     *
     * @param  string $module
     * @param  array  $data    Row data from the module's table
     * @return string          Absolute or relative URL
     */
    public static function create_file_link($module, $data)
    {
        $db_name = PersistenceContext::get_dbms_utils()->get_database_name();

        switch ($module)
        {
            case 'wiki':
                $article_id = isset($data['item_id']) ? (int)$data['item_id'] : (isset($data['id']) ? (int)$data['id'] : 0);
                $article    = self::get_wiki_article($article_id);
                if (!$article) return '';
                $cat_name = self::get_category_name('wiki', ['id_category' => $article['id_category']]);
                return '/' . $db_name . '/wiki/' . $article['id_category'] . '-' . $cat_name . '/' . $article['id'] . '-' . $article['rewrited_title'] . '/';

            case 'media':
                if (isset($data['id_category']) && isset($data['id']))
                    return '/' . $db_name . '/media/' . url('media.php?id=' . $data['id'], 'media-' . $data['id_category'] . '-' . $data['id'] . '+' . Url::encode_rewrite(isset($data['title']) ? $data['title'] : '') . '.php');
                return isset($data['id']) ? '/' . $db_name . '/media/' . url('media.php?cat=' . $data['id']) : '';

            case 'gallery':
                if (!isset($data['id'], $data['rewrited_name'])) return '';
                return '/' . $db_name . '/gallery/gallery-' . $data['id'] . '+' . $data['rewrited_name'] . '.php';

            case 'faq':
                if (!isset($data['id'])) return '';
                if (isset($data['id_category']))
                {
                    $cat_name = self::get_category_name($module, $data);
                    return '/' . $db_name . '/faq/' . $data['id'] . '-' . $cat_name . '/#question' . $data['id'];
                }
                return '/' . $db_name . '/faq/' . $data['id'] . '-' . (isset($data['rewrited_name']) ? $data['rewrited_name'] : '');

            case 'forum':
                if (isset($data['idtopic'], $data['user_id'], $data['id']))
                    return '/' . $db_name . '/forum/' . url('topic.php?id=' . $data['idtopic'] . '&pt=' . $data['user_id'] . '#m' . $data['id']);
                if (isset($data['id']))
                    return '/' . $db_name . '/forum/' . url('forum.php?id=' . $data['id'], 'forum-' . $data['id'] . '+' . Url::encode_rewrite(isset($data['title']) ? $data['title'] : '') . '.php');
                return '';

            case 'newsletter':
                if (isset($data['stream_id']))
                    return '/' . $db_name . '/newsletter/archives/' . $data['stream_id'] . '-' . self::get_newsletter_title($data['stream_id']);
                return '/' . $db_name . '/newsletter/streams/';

            default:
                if (isset($data['id_category'], $data['id'], $data['rewrited_title']))
                {
                    $cat_name = self::get_category_name($module, $data);
                    $link     = ItemsUrlBuilder::display($data['id_category'], $data['id_category'] != Category::ROOT_CATEGORY ? $cat_name : 'root', $data['id'], $data['rewrited_title'], $module);
                    return $link->absolute();
                }
                if (isset($data['id'], $data['rewrited_name']))
                {
                    $link = ItemsUrlBuilder::display_category($data['id'], $data['rewrited_name'], $module);
                    return $link->absolute();
                }
                return '';
        }
    }

    /**
     * Builds the admin edit URL for the item that contains the file.
     * PHPBoost edit URLs follow the pattern:
     *   /$db_name/$module/categories/$id_category/edit/  (items with category)
     *   /$db_name/$module/$id/edit/                      (items without category)
     *
     * @param  string $module
     * @param  array  $data    Row from the module's content table
     * @return string          Absolute URL, or empty string if not determinable
     */
    public static function create_edit_link($module, $data)
    {
        $db_name     = PersistenceContext::get_dbms_utils()->get_database_name();
        $id          = isset($data['id']) ? (int)$data['id'] : 0;
        $id_category = isset($data['id_category']) ? (int)$data['id_category'] : 0;

        switch ($module)
        {
            // Modules without standard category/item edit URL
            case 'wiki':
                $article_id = isset($data['item_id']) ? (int)$data['item_id'] : (isset($data['id']) ? (int)$data['id'] : 0);
                if (!$article_id) return '';
                return '/' . $db_name . '/wiki/' . $article_id . '/edit/';

            case 'forum':
                $topic_id = isset($data['idtopic']) ? (int)$data['idtopic'] : $id;
                return '/' . $db_name . '/forum/' . $topic_id . '/edit/';

            case 'newsletter':
                $stream_id = isset($data['stream_id']) ? (int)$data['stream_id'] : $id;
                return '/' . $db_name . '/newsletter/' . $stream_id . '/edit/';

            // Standard modules: categories/$id_category/edit/ or $id/edit/
            default:
                if ($id_category > 0)
                    return '/' . $db_name . '/' . $module . '/categories/' . $id_category . '/edit/';
                return '/' . $db_name . '/' . $module . '/' . $id . '/edit/';
        }
    }

    /**
     * Returns the stored file_link for a given row from the review cache table.
     *
     * @param  array  $file  Row from devtools_review
     * @return string|null
     */
    public static function get_file_link($file)
    {
        $condition = $file['id_module_category'] != 0
            ? 'AND rew.id_module_category != 0'
            : 'AND rew.id_module_category = 0';

        try
        {
            $req = PersistenceContext::get_querier()->select(
                'SELECT rew.file_link
                 FROM ' . self::table() . ' AS rew
                 WHERE rew.file_path = "' . $file['file_path'] . '"
                   ' . $condition . '
                   AND rew.id = ' . (int)$file['id']
            );
            $row = $req->fetch();
            $req->dispose();
            return $row ? $row['file_link'] : null;
        }
        catch (Exception $e) { return null; }
    }

    // -------------------------------------------------------------------------
    // Title resolution helpers
    // -------------------------------------------------------------------------

    public static function get_wiki_article($id)
    {
        try
        {
            $result = PersistenceContext::get_querier()->select(
                'SELECT id, id_category, title, rewrited_title FROM ' . PREFIX . 'wiki_articles WHERE id = ' . (int)$id
            );
            $row = $result->fetch();
            $result->dispose();
            return $row ?: [];
        }
        catch (Exception $e) { return []; }
    }

    public static function get_wiki_title($id)
    {
        $article = self::get_wiki_article($id);
        return $article ? $article['title'] : '';
    }

    public static function get_topic_title($idtopic)
    {
        try
        {
            $result = PersistenceContext::get_querier()->select(
                'SELECT ft.title FROM ' . PREFIX . 'forum_topics ft WHERE ft.id = ' . (int)$idtopic
            );
            $row = $result->fetch();
            $result->dispose();
            return $row ? $row['title'] : '';
        }
        catch (Exception $e) { return ''; }
    }

    public static function get_newsletter_title($stream_id)
    {
        try
        {
            $result = PersistenceContext::get_querier()->select(
                'SELECT ns.rewrited_name FROM ' . PREFIX . 'newsletter_streams ns WHERE ns.id = ' . (int)$stream_id
            );
            $row = $result->fetch();
            $result->dispose();
            return $row ? $row['rewrited_name'] : '';
        }
        catch (Exception $e) { return ''; }
    }

    public static function get_category_name($module, $data)
    {
        if (isset($data['id_category']) && $data['id_category'] != 0)
        {
            try
            {
                $result = PersistenceContext::get_querier()->select(
                    'SELECT cats.rewrited_name FROM ' . PREFIX . $module . '_cats cats
                     WHERE cats.id = ' . (int)$data['id_category']
                );
                $row = $result->fetch();
                $result->dispose();
                return $row ? $row['rewrited_name'] : '';
            }
            catch (Exception $e) { return ''; }
        }
        elseif (isset($data['id_category']) && $data['id_category'] == 0)
            return 'root';

        return isset($data['name']) ? $data['name'] : '';
    }
}
?>
