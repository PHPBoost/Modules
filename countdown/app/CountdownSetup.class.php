<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 26
 * @author      mipel <mipel@phpboost.com>
*/

class CountdownSetup extends DefaultModuleSetup
{
	public function upgrade($installed_version): string
	{
		return '6.1.0';
	}

	public function uninstall(): void
	{
		ConfigManager::delete('countdown', 'config');
	}
}
?>
