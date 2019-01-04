<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 12 24
 * @since   	PHPBoost 4.1 - 2014 12 12
 * @contributor mipel <mipel@phpboost.com>
*/

class CountdownSetup extends DefaultModuleSetup
{
	public function upgrade($installed_version)
	{
		return '5.2.0';
	}

	public function uninstall()
	{
		ConfigManager::delete('countdown', 'config');
	}
}
?>
