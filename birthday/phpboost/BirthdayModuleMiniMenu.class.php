<?php
/*##################################################
 *                        BirthdayModuleMiniMenu.class.php
 *                            -------------------
 *   begin                : August 27, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

class BirthdayModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__NOT_ENABLED;
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
				$user_group_color = User::get_group_color($user['groups'], $user['level'], false);
				
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
		else if (AppContext::get_current_user()->check_level(User::ADMIN_LEVEL))
		{
			return MessageHelper::display(LangLoader::get_message('user_born_field_disabled', 'common', 'birthday'), MessageHelper::WARNING)->render();
		}
		return '';
	}
}
?>
