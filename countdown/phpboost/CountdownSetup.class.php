<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 09 13
 * @since       PHPBoost 4.1 - 2014 12 12
 * @contributor mipel <mipel@phpboost.com>
*/

class CountdownSetup extends DefaultModuleSetup
{
	public function upgrade($installed_version)
	{
		return '6.0.0';
	}

	public function uninstall()
	{
		ConfigManager::delete('countdown', 'config');
	}
}
?>
