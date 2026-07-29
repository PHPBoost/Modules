<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 07 29
 * @since       PHPBoost 1.2 - 2005 10 30
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

define('PATH_TO_ROOT', '../..');

require_once(PATH_TO_ROOT . '/admin/admin_begin.php');

$lang = LangLoader::get_all_langs('forum');
define('TITLE', $lang['forum.ranks.management']);
require_once(PATH_TO_ROOT . '/admin/admin_header.php');

$request = AppContext::get_request();
$session = AppContext::get_session();

$get_id = $request->get_getint('id', 0);
$del    = $request->get_getint('del', 0);
$token  = $request->get_getstring('token', '');

$valid = $request->get_postbool('valid', false);

$view = new FileTemplate('forum/admin_ranks.tpl');
$view->add_lang($lang);

$csrf_token = $session->get_token();

if ($valid)
{
    // Validate CSRF token for POST action
    if ($request->get_poststring('token', '') === $csrf_token)
    {
        $result = PersistenceContext::get_querier()->select("SELECT id, special FROM " . PREFIX . "forum_ranks");
        while ($row = $result->fetch())
        {
            $name       = $request->get_poststring($row['id'] . 'name', '');
            $msg_number = $request->get_postint($row['id'] . 'msg', 0);
            $icon       = $request->get_poststring($row['id'] . 'icon', '');

            if (!empty($name) && $row['special'] != 1) {
                PersistenceContext::get_querier()->update(PREFIX . "forum_ranks", ['name' => $name, 'msg' => $msg_number, 'icon' => $icon], ' WHERE id = :id', ['id' => $row['id']]);
            } else {
                PersistenceContext::get_querier()->update(PREFIX . "forum_ranks", ['name' => $name, 'icon' => $icon], ' WHERE id = :id', ['id' => $row['id']]);
            }
        }
        $result->dispose();

        ForumRanksCache::invalidate();

        HooksService::execute_hook_action('edit_config', 'forum', ['title' => $lang['forum.ranks.management'], 'url' => ForumUrlBuilder::manage_ranks()->rel()]);

        $view->put('MESSAGE_HELPER', MessageHelper::display(LangLoader::get_message('warning.process.success', 'warning-lang'), MessageHelper::SUCCESS, 4));
    }
    else
    {
        $view->put('MESSAGE_HELPER', MessageHelper::display(LangLoader::get_message('warning.invalid.csrf.token', 'warning-lang'), MessageHelper::ERROR, 4));
    }
}
// 2. Rank deletion (GET) — Requires CSRF token verification
elseif (!empty($del) && !empty($get_id))
{
    if (!empty($token) && hash_equals($csrf_token, $token))
    {
        // Delete rank from DB
        PersistenceContext::get_querier()->delete(PREFIX . 'forum_ranks', 'WHERE id = :id', ['id' => $get_id]);

        // Regenerate ranks cache
        ForumRanksCache::invalidate();

        HooksService::execute_hook_action('edit_config', 'forum', ['title' => $lang['forum.ranks.management'], 'url' => ForumUrlBuilder::manage_ranks()->rel()]);

        $view->put('MESSAGE_HELPER', MessageHelper::display(LangLoader::get_message('warning.process.success', 'warning-lang'), MessageHelper::SUCCESS, 4));
    }
    else
    {
        $view->put('MESSAGE_HELPER', MessageHelper::display(LangLoader::get_message('warning.invalid.csrf.token', 'warning-lang'), MessageHelper::ERROR, 4));
    }
}

// Retrieve rank icons
$rank_options_array = [];
$rank_folder = PATH_TO_ROOT . '/templates/' . ThemesManager::get_default_theme() . '/modules/forum/images/ranks';

if (!is_dir($rank_folder)) {
    $rank_folder = (PATH_TO_ROOT . '/modules/forum/templates/images/ranks');
}

$image_folder_path = new Folder($rank_folder);

foreach ($image_folder_path->get_files('`\.(png|jpg|bmp|gif)$`i') as $image)
{
    $rank_options_array[] = $image->get_name();
}

$ranks_cache = ForumRanksCache::load()->get_ranks();

foreach ($ranks_cache as $msg => $row)
{
    $rank_options = '<option value="">--</option>';
    foreach ($rank_options_array as $icon)
    {
        $selected = ($icon == $row['icon']) ? ' selected="selected"' : '';
        $safe_icon = htmlspecialchars($icon, ENT_QUOTES, 'UTF-8');
        $rank_options .= '<option value="' . $safe_icon . '"' . $selected . '>' . $safe_icon . '</option>';
    }

    $view->assign_block_vars('rank', [
        'C_CUSTOM_RANK' => $row['special'] == 0,

        'ID'             => (int)$row['id'],
        'RANK'           => $row['name'],
        'MESSAGE'        => (int)$msg,
        'RANK_OPTIONS'   => $rank_options,
        'RANK_THUMBNAIL' => $row['icon'],

        'U_RANK_THUMBNAIL' => $rank_folder . '/' . $row['icon'],
        'JS_PATH_RANKS'    => $rank_folder . '/',
        'U_DELETE'         => 'admin_ranks.php?del=1&amp;id=' . (int)$row['id'] . '&amp;token=' . $csrf_token,
    ]);
}

$view->display();

require_once(PATH_TO_ROOT . '/admin/admin_footer.php');
?>