<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 02 03
 * @since       PHPBoost 4.0 - 2013 08 27
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class BirthdayModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__NOT_ENABLED;
	}

 	public function get_menu_id()
 	{
 		return 'birthday-mini-module';
 	}

 	public function get_menu_title()
 	{
 		return LangLoader::get_message('birthday.happy.birthday', 'common', 'birthday');
 	}

	public function get_formated_title()
	{
		return LangLoader::get_message('birthday.module.title', 'common', 'birthday');
	}

 	public function is_displayed()
 	{
 		return BirthdayAuthorizationsService::check_authorizations()->read() && !empty($user_born_field) && $user_born_field['display'] || AppContext::get_current_user()->is_admin();
 	}

	public function get_menu_content()
	{
		$user_born_field = ExtendedFieldsCache::load()->get_extended_field_by_field_name('user_born');
		$lang = LangLoader::get_all_langs('birthday');

		$view = new FileTemplate('birthday/BirthdayModuleMiniMenu.tpl');
		$view->add_lang($lang);
		MenuService::assign_positions_conditions($view, $this->get_block());
		Menu::assign_common_template_variables($view);
		$config = BirthdayConfig::load();

		$cache = BirthdayCache::load();
		$users_birthday = $cache->get_users_birthday();
		$upcoming_birthdays = $cache->get_upcoming_birthdays();

		if (count($users_birthday) > 0)
		{
			foreach ($users_birthday as $user)
			{
				$user_group_color = User::get_group_color($user['user_groups'], $user['level'], false);

				$view->assign_block_vars('birthday', array(
					'C_USER_GROUP_COLOR' => !empty($user_group_color),
					'LOGIN' => $user['display_name'],
					'USER_LEVEL_CLASS' => UserService::get_level_class($user['level']),
					'USER_GROUP_COLOR' => $user_group_color,
					'AGE' => $user['age'],
					'U_USER_PROFILE' => UserUrlBuilder::profile($user['user_id'])->absolute()
				));
			}
		}

		if (count($upcoming_birthdays) > 0)
		{
			foreach ($upcoming_birthdays as $user)
			{
				$user_group_color = User::get_group_color($user['user_groups'], $user['level'], false);

				$view->assign_block_vars('upcoming_birthdays', array(
					'C_USER_GROUP_COLOR' => !empty($user_group_color),
					'LOGIN' => $user['display_name'],
					'USER_LEVEL_CLASS' => UserService::get_level_class($user['level']),
					'USER_GROUP_COLOR' => $user_group_color,
					// 'DELAY' => $user['delay'],
					'BIRTHDATE' => $user['birthdate'],
					'U_USER_PROFILE' => UserUrlBuilder::profile($user['user_id'])->absolute()
				));
			}
		}

		$view->put_all(array(
			'C_BIRTHDAY_ENABLED' => BirthdayAuthorizationsService::check_authorizations()->read() && !empty($user_born_field) && $user_born_field['display'],
			'C_HAS_BIRTHDAY' => count($users_birthday),
			'C_COMING_NEXT' => $config->get_coming_next() > 1,
			'C_UPCOMING_BIRTHDAYS' => count($upcoming_birthdays),
			'C_DISPLAY_MEMBERS_AGE' => BirthdayConfig::load()->is_members_age_displayed(),

			'L_COMING_NEXT' => $config->get_coming_next() > 1 ? StringVars::replace_vars($lang['birthday.next.days'], array('coming_next' => $config->get_coming_next())) : $lang['date.tomorrow']
		));

		return $view->render();
	}

	public function display()
	{
		if ($this->is_displayed())
		{
			if ($this->get_block() == Menu::BLOCK_POSITION__LEFT || $this->get_block() == Menu::BLOCK_POSITION__RIGHT)
			{
				$template = $this->get_template_to_use();
				MenuService::assign_positions_conditions($template, $this->get_block());
				$this->assign_common_template_variables($template);

				$template->put_all(array(
					'ID' => $this->get_menu_id(),
					'TITLE' => $this->get_menu_title(),
					'CONTENTS' => $this->get_menu_content()
				));

				return $template->render();
			}
			else
			{
				return $this->get_menu_content();
			}
		}
		return '';
	}
}
?>
