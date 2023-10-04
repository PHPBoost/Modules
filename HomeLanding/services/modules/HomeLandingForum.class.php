<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 10 04
 * @since       PHPBoost 5.2 - 2020 03 06
*/

class HomeLandingForum
{
	public static function get_forum_view()
	{
		$user_accounts_config = UserAccountsConfig::load();

		$module_config = ForumConfig::load();
		$home_config   = HomeLandingConfig::load();
		$modules       = HomeLandingModulesList::load();
		$module_name   = HomeLandingConfig::MODULE_FORUM;

        $theme_id = AppContext::get_current_user()->get_theme();
		if (file_exists(PATH_TO_ROOT . '/templates/' . $theme_id . '/modules/HomeLanding/pagecontent/' . $module_name . '.tpl'))
			$view = new FileTemplate('/templates/' . $theme_id . '/modules/HomeLanding/pagecontent/' . $module_name . '.tpl');
        elseif (file_exists(PATH_TO_ROOT . '/HomeLanding/templates/pagecontent/' . $module_name . '.tpl'))
			$view = new FileTemplate('/HomeLanding/templates/pagecontent/' . $module_name . '.tpl');
		else
            $view = new FileTemplate('HomeLanding/pagecontent/messages.tpl');

		$home_lang = LangLoader::get_module_langs('HomeLanding');
		$module_lang = LangLoader::get_module_langs($module_name);
        $view->add_lang(array_merge(LangLoader::get_all_langs(), $home_lang, $module_lang));

		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, true, $module_name, 'id_category');

		$result = PersistenceContext::get_querier()->select('SELECT
			t.id, t.id_category, t.title, t.last_timestamp, t.last_user_id, t.last_msg_id, t.display_msg, t.nbr_msg AS t_nbr_msg, t.user_id AS glogin,
			member.display_name AS last_login, member.user_groups, member.level,
			msg.id mid, msg.content, ext_field.user_avatar
		FROM ' . PREFIX . 'forum_topics t
		LEFT JOIN ' . PREFIX . 'forum_cats cat ON cat.id = t.id_category
		LEFT JOIN ' . PREFIX . 'forum_msg msg ON msg.id = t.last_msg_id
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = t.last_user_id
		LEFT JOIN ' . DB_TABLE_MEMBER_EXTENDED_FIELDS . ' ext_field ON ext_field.user_id = member.user_id
		WHERE t.display_msg = 0 AND id_category IN :authorized_categories
		ORDER BY t.last_timestamp DESC
		LIMIT :forum_limit', array(
			'authorized_categories' => $authorized_categories,
			'forum_limit' => $modules[$module_name]->get_elements_number_displayed()
		));

		$view->put_all(array(
			'C_NO_ITEM'     => $result->get_rows_count() == 0,
			'C_PARENT'      => true,
			'C_TOPIC'       => true,
			'C_AVATAR_IMG'  => $user_accounts_config->is_default_avatar_enabled(),
			'C_MODULE_LINK' => true,

			'MODULE_NAME'     => $module_name,
			'MODULE_POSITION' => $home_config->get_module_position_by_id($module_name),
			'L_MODULE_TITLE'  => ModulesManager::get_module($module_name)->get_configuration()->get_name(),
		));

		while ($row = $result->fetch())
		{
			$user_avatar = !empty($row['user_avatar']) ? Url::to_rel($row['user_avatar']) : $user_accounts_config->get_default_avatar();

			$last_page = ceil($row['t_nbr_msg'] / $module_config->get_number_messages_per_page());
			$last_page_rewrite = ($last_page > 1) ? '-' . $last_page : '';
			$last_page = ($last_page > 1) ? 'pt=' . $last_page . '&amp;' : '';
			$link = new Url('/forum/topic' . url('.php?' . $last_page .  'id=' . $row['id'], '-' . $row['id'] . $last_page_rewrite . '-' . Url::encode_rewrite($row['title'])  . '.php') . '#m' .  $row['last_msg_id']);
			$link_message = new Url('/forum/topic' . url('.php?' . $last_page .  'id=' . $row['id'], '-' . $row['id'] . $last_page_rewrite . '-' . Url::encode_rewrite($row['title'])  . '.php'));
			$user_group_color = User::get_group_color($row['user_groups'], $row['level']);

			$characters_number_to_cut = $modules[$module_name]->get_characters_number_displayed();

			$view->assign_block_vars('items', array(
				'C_AUTHOR_GROUP_COLOR' => !empty($user_group_color),
				'C_AUTHOR_EXISTS'      => $row['last_user_id'] !== User::VISITOR_LEVEL,

				'AUTHOR_DISPLAY_NAME' => $row['last_login'],
				'AUTHOR_LEVEL_CLASS'  => UserService::get_level_class($row['level']),
				'AUTHOR_GROUP_COLOR'  => $user_group_color,
				'DATE'                => Date::to_format($row['last_timestamp'], Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE),
				'SORT_DATE'           => $row['last_timestamp'],
				'TOPIC'               => stripslashes($row['title']),
				'CONTENT'             => TextHelper::cut_string(@strip_tags(FormatingHelper::second_parse(stripslashes($row['content'])), '<br><br/>'), (int)$characters_number_to_cut),

				'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($row['last_user_id'])->rel(),
				'U_AVATAR_IMG'     => $user_avatar,
				'U_TOPIC'          => $link_message->rel(),
				'U_ITEM'           => $link->rel(),
			));
		}
		$result->dispose();

		return $view;
	}
}
?>
