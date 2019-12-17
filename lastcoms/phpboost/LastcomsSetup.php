<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 26
 * @since       PHPBoost 5.1 - 2017 05 30
 * @contributor mipel <mipel@phpboost.com>
*/

class LastcomsSetup extends DefaultModuleSetup
{
	public function upgrade($installed_version)
	{
		return '5.2.0';
	}

	public function uninstall()
	{
		ConfigManager::delete('lastcoms', 'config');
	}
}
?>
