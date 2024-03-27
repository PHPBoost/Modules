<?php
/**
 * @copyright   &copy; 2005-2024 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 05 22
 * @since       PHPBoost 4.0 - 2013 08 27
*/

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
					StringVars::replace_vars($config->get_pm_for_members_birthday_title(), array('user_display_name' => $user['display_name'], 'user_age' => $user['age'])),
					StringVars::replace_vars($config->get_pm_for_members_birthday_content(), array('user_display_name' => $user['display_name'], 'user_age' => $user['age'])),
					'-1',
					PrivateMsg::SYSTEM_PM
				);
			}
		}
	}
}
?>
