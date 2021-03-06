<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 12
 * @since       PHPBoost 4.0 - 2013 08 27
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class BirthdayModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__NOT_ENABLED;
	}

	public function get_formated_title()
	{
		return LangLoader::get_message('birthday.module.title', 'common', 'birthday');
	}

	public function display($tpl = false)
	{
		$user_born_field = ExtendedFieldsCache::load()->get_extended_field_by_field_name('user_born');

		if (BirthdayAuthorizationsService::check_authorizations()->read() && !empty($user_born_field) && $user_born_field['display'])
		{
			$lang = LangLoader::get('common', 'birthday');

			$tpl = new FileTemplate('birthday/BirthdayModuleMiniMenu.tpl');
			$tpl->add_lang($lang);

			MenuService::assign_positions_conditions($tpl, $this->get_block());

			$users_birthday = BirthdayCache::load()->get_users_birthday();

			foreach ($users_birthday as $user)
			{
				$user_group_color = User::get_group_color($user['user_groups'], $user['level'], false);

				$tpl->assign_block_vars('birthday', array(
					'C_USER_GROUP_COLOR' => !empty($user_group_color),
					'LOGIN' => $user['display_name'],
					'USER_LEVEL_CLASS' => UserService::get_level_class($user['level']),
					'USER_GROUP_COLOR' => $user_group_color,
					'AGE' => $user['age'],
					'U_USER_PROFILE' => UserUrlBuilder::profile($user['user_id'])->absolute()
				));
			}

			$tpl->put_all(array(
				'C_BIRTHDAY' => count($users_birthday),
				'C_DISPLAY_MEMBERS_AGE' => BirthdayConfig::load()->is_members_age_displayed()
			));

			return $tpl->render();
		}
		else if (AppContext::get_current_user()->check_level(User::ADMINISTRATOR_LEVEL))
		{
			return MessageHelper::display(LangLoader::get_message('birthday.user.born.field.disabled', 'common', 'birthday'), MessageHelper::WARNING)->render();
		}
		return '';
	}
}
?>
