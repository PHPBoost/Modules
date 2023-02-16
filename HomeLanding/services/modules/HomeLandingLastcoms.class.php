<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 02 16
 * @since       PHPBoost 5.2 - 2020 03 06
*/

class HomeLandingLastcoms
{
	public static function get_lastcoms_view()
	{
		$user_accounts_config = UserAccountsConfig::load();
		$modules_config = ModulesConfig::load();
		$home_config    = HomeLandingConfig::load();
		$modules        = HomeLandingModulesList::load();
		$module_name    = HomeLandingConfig::MODULE_LASTCOMS;

        $theme_id = AppContext::get_current_user()->get_theme();
		if (file_exists(PATH_TO_ROOT . '/templates/' . $theme_id . '/modules/HomeLanding/pagecontent/' . $module_name . '.tpl'))
			$view = new FileTemplate('/templates/' . $theme_id . '/modules/HomeLanding/pagecontent/' . $module_name . '.tpl');
        elseif (file_exists(PATH_TO_ROOT . '/HomeLanding/templates/pagecontent/' . $module_name . '.tpl'))
			$view = new FileTemplate('/HomeLanding/templates/pagecontent/' . $module_name . '.tpl');
		else
            $view = new FileTemplate('HomeLanding/pagecontent/messages.tpl');

        $view->add_lang(LangLoader::get_all_langs('HomeLanding'));

		$result = PersistenceContext::get_querier()->select('SELECT c.id, c.user_id, c.pseudo, c.message, c.timestamp, ct.module_id, ct.is_locked, ct.path, m.*, ext_field.user_avatar
		FROM ' . DB_TABLE_COMMENTS . ' AS c
		LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' AS ct ON ct.id_topic = c.id_topic
		LEFT JOIN ' . DB_TABLE_MEMBER . ' AS m ON c.user_id = m.user_id
		LEFT JOIN ' . DB_TABLE_MEMBER_EXTENDED_FIELDS . ' ext_field ON ext_field.user_id = m.user_id
		WHERE ct.is_locked = 0
		AND ct.module_id != :forbidden_module
		ORDER BY c.timestamp DESC
		LIMIT :last_coms_limit', array(
			'last_coms_limit' => $modules[$module_name]->get_elements_number_displayed(),
			'forbidden_module' => 'user'
		));

		$view->put_all(array(
			'C_NO_ITEM' => $result->get_rows_count() == 0,
			'C_PARENT' => true,
			'C_AVATAR_IMG' => $user_accounts_config->is_default_avatar_enabled(),
			'MODULE_NAME' => $module_name,
			'MODULE_POSITION' => $home_config->get_module_position_by_id($module_name),
			'L_MODULE_TITLE'  => LangLoader::get_message('homelanding.module.' . $module_name, 'common', 'HomeLanding'),
		));

		while ($row = $result->fetch())
		{
			$contents = @strip_tags(FormatingHelper::second_parse($row['message']), '<br><br/>');
			$characters_number_to_cut = $modules[$module_name]->get_characters_number_displayed();
			$cut_contents = TextHelper::cut_string($contents, (int)$characters_number_to_cut);

			$user_avatar = !empty($row['user_avatar']) ? Url::to_rel($row['user_avatar']) : $user_accounts_config->get_default_avatar();

			$author = new User();
			if (!empty($row['user_id']))
				$author->set_properties($row);
			else
				$author->init_visitor_user();
			$user_group_color = User::get_group_color($author->get_groups(), $author->get_level(), true);

			$view->assign_block_vars('items', array(
				'C_AUTHOR_GROUP_COLOR' => !empty($user_group_color),
				'C_AUTHOR_EXISTS'      => $author->get_id() !== User::VISITOR_LEVEL,
				'C_READ_MORE'          => strlen($contents) > $characters_number_to_cut,

				'AUTHOR_DISPLAY_NAME' => $author->get_display_name(),
				'AUTHOR_LEVEL_CLASS'  => UserService::get_level_class($author->get_level()),
				'AUTHOR_GROUP_COLOR'  => $user_group_color,
				'DATE'                => Date::to_format($row['timestamp'], Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE),
				'SORT_DATE'           => $row['timestamp'],
				'TOPIC'               => $modules_config->get_module($row['module_id']) ? $modules_config->get_module($row['module_id'])->get_configuration()->get_name() : '',
				'CONTENT'             => $cut_contents,

				'U_AVATAR_IMG'     => $user_avatar,
				'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($author->get_id())->rel(),
				'U_TOPIC'          => Url::to_rel($row['path']),
				'U_ITEM'           => Url::to_rel($row['path'] . '#com' . $row['id'])
			));
		}
		$result->dispose();


		return $view;
	}
}
?>
