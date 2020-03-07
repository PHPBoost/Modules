<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 03 06
 * @since       PHPBoost 5.2 - 2020 03 06
*/

class HomeLandingForum
{
    public static function get_forum_view()
	{
        $tpl = new FileTemplate('HomeLanding/pagecontent/forum.tpl');
        $user_accounts_config = UserAccountsConfig::load();
		$config = HomeLandingConfig::load();
        $modules = HomeLandingModulesList::load();

        $authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, true, HomeLandingConfig::MODULE_FORUM, 'id_category');

        $result = PersistenceContext::get_querier()->select('SELECT
            t.id, t.id_category, t.title, t.last_timestamp, t.last_user_id, t.last_msg_id, t.display_msg, t.nbr_msg AS t_nbr_msg, t.user_id AS glogin,
            member.display_name AS last_login,
            msg.id mid, msg.contents, ext_field.user_avatar
        FROM ' . PREFIX . 'forum_topics t
        LEFT JOIN ' . PREFIX . 'forum_cats cat ON cat.id = t.id_category
        LEFT JOIN ' . PREFIX . 'forum_msg msg ON msg.id = t.last_msg_id
        LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = t.last_user_id
        LEFT JOIN ' . DB_TABLE_MEMBER_EXTENDED_FIELDS . ' ext_field ON ext_field.user_id = member.user_id
        WHERE t.display_msg = 0 AND id_category IN :authorized_categories
        ORDER BY t.last_timestamp DESC
        LIMIT :forum_limit', array(
            'authorized_categories' => $authorized_categories,
            'forum_limit' => $modules[HomeLandingConfig::MODULE_FORUM]->get_elements_number_displayed()
        ));

        $tpl->put('FORUM_POSITION', $config->get_module_position_by_id(HomeLandingConfig::MODULE_FORUM));

        while ($row = $result->fetch())
        {
            $contents = FormatingHelper::second_parse($row['contents']);
            $user_avatar = !empty($row['user_avatar']) ? Url::to_rel($row['user_avatar']) : ($user_accounts_config->is_default_avatar_enabled() ? Url::to_rel('/templates/' . AppContext::get_current_user()->get_theme() . '/images/' .  $user_accounts_config->get_default_avatar_name()) : '');

            $config = ForumConfig::load();
            $last_page = ceil($row['t_nbr_msg'] / $config->get_number_messages_per_page());
            $last_page_rewrite = ($last_page > 1) ? '-' . $last_page : '';
            $last_page = ($last_page > 1) ? 'pt=' . $last_page . '&amp;' : '';
            $link = new Url('/forum/topic' . url('.php?' . $last_page .  'id=' . $row['id'], '-' . $row['id'] . $last_page_rewrite . '+' . Url::encode_rewrite($row['title'])  . '.php') . '#m' .  $row['last_msg_id']);
            $link_message = new Url('/forum/topic' . url('.php?' . $last_page .  'id=' . $row['id'], '-' . $row['id'] . $last_page_rewrite . '+' . Url::encode_rewrite($row['title'])  . '.php'));

            $nb_char = $modules[HomeLandingConfig::MODULE_FORUM]->get_characters_number_displayed();

            $tpl->assign_block_vars('item', array(
                'U_AVATAR' => $user_avatar,
                'CONTENTS' => TextHelper::cut_string(@strip_tags(stripslashes($contents), 0), (int)$nb_char),
                'PSEUDO' => $row['last_login'],
                'DATE' => strftime('%d/%m/%Y - %Hh%M', $row['last_timestamp']),
                'MESSAGE' => stripslashes($row['title']),
                'U_LINK' => $link->rel(),
                'U_MESSAGE' => $link_message->rel()
            ));
        }
        $result->dispose();

        return $tpl;
	}
}
?>
