<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 31
 * @since       PHPBoost 5.0 - 2017 03 26
*/

class GoogleMapsSetup extends DefaultModuleSetup
{
	public function uninstall(): mixed
	{
		ConfigManager::delete('google-maps', 'config');
		return null;
	}
}
?>
