<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 05 13
 * @since       PHPBoost 5.2 - 2020 03 06
*/

class HomeLandingGuestbook
{
	public static function get_guestbook_view()
	{
		$view = new FileTemplate('HomeLanding/pagecontent/guestbook.tpl');
		$user_accounts_config = UserAccountsConfig::load();
		$home_config = HomeLandingConfig::load();
		$modules = HomeLandingModulesList::load();
		$module_name   = HomeLandingConfig::MODULE_GUESTBOOK;

        $theme_id = AppContext::get_current_user()->get_theme();
        if (file_exists(PATH_TO_ROOT . '/HomeLanding/templates/pagecontent/' . $module_name . '.tpl'))
			$view = new FileTemplate('/HomeLanding/templates/pagecontent/' . $module_name . '.tpl');
        elseif (file_exists(PATH_TO_ROOT . '/templates/' . $theme_id . '/modules/HomeLanding/pagecontent/' . $module_name . '.tpl'))
			$view = new FileTemplate('/templates/' . $theme_id . '/modules/HomeLanding/pagecontent/' . $module_name . '.tpl');
		else
            $view = new FileTemplate('HomeLanding/pagecontent/messages.tpl');

		$home_lang = LangLoader::get('common', 'HomeLanding');
		$module_lang = LangLoader::get('common', $module_name);
		$view->add_lang($home_lang);
		$view->add_lang($module_lang);


		$result = PersistenceContext::get_querier()->select('SELECT member.*, guestbook.*, guestbook.login as glogin, ext_field.user_avatar
		FROM ' . GuestbookSetup::$guestbook_table . ' guestbook
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = guestbook.user_id
		LEFT JOIN ' . DB_TABLE_MEMBER_EXTENDED_FIELDS . ' ext_field ON ext_field.user_id = member.user_id
		ORDER BY guestbook.timestamp DESC
		LIMIT :guestbook_limit', array(
			'guestbook_limit' => $modules[$module_name]->get_elements_number_displayed()
		));

		$view->put_all(array(
			'C_NO_ITEM' => $result->get_rows_count() == 0,
			'C_MODULE_LINK' => true,
			'MODULE_NAME' => $module_name,
			'MODULE_POSITION' => $home_config->get_module_position_by_id($module_name),
			'L_MODULE_TITLE'  => LangLoader::get_message('last.'.$module_name, 'common', 'HomeLanding'),
			'L_SEE_ALL_ITEMS' => LangLoader::get_message('link.to.'.$module_name, 'common', 'HomeLanding'),
			'C_AVATAR_IMG' => $user_accounts_config->is_default_avatar_enabled(),
		));

		while ($row = $result->fetch())
		{
			$message = new GuestbookMessage();
			$message->set_properties($row);

			$contents = @strip_tags(FormatingHelper::second_parse($message->get_contents()));
			$nb_char = $modules[$module_name]->get_characters_number_displayed();
			$user_avatar = !empty($row['user_avatar']) ? Url::to_rel($row['user_avatar']) : $user_accounts_config->get_default_avatar();
			$cut_contents = trim(TextHelper::substr($contents, 0, $nb_char));

			$view->assign_block_vars('item', array_merge($message->get_array_tpl_vars(), array(
				'C_READ_MORE' => $cut_contents != $contents,
				'U_AVATAR_IMG' => $user_avatar,
				'CONTENTS' => $cut_contents,
			)));
		}
		$result->dispose();
		return $view;
	}
}
?>
