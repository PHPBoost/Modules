<?php
/*##################################################
 *                         BirthdayScheduledJobs.class.php
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

class BirthdayScheduledJobs extends AbstractScheduledJobExtensionPoint
{
	/**
	 * {@inheritDoc}
	 */
	public function on_changeday(Date $yesterday, Date $today)
	{
		BirthdayCache::invalidate();
		
		$config = BirthdayConfig::load();
		
		if ($config->is_pm_for_members_birthday_enabled())
		{
			$users = BirthdayCache::load()->get_users_birthday();
			
			foreach ($users as $user)
			{
				//Send the PM
				PrivateMsg::start_conversation(
					$user['user_id'],
					StringVars::replace_vars($config->get_pm_for_members_birthday_title(), array('user_login' => $user['login'], 'user_age' => $user['age'])),
					StringVars::replace_vars($config->get_pm_for_members_birthday_content(), array('user_login' => $user['login'], 'user_age' => $user['age'])),
					'-1',
					PrivateMsg::SYSTEM_PM
				);
			}
		}
	}
}
?>
