<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2015 04 13
 * @since       PHPBoost 4.1 - 2014 09 24
*/

class TeamspeakSetup extends DefaultModuleSetup
{
	public function upgrade($installed_version)
	{
		return '6.0.0';
	}

	public function uninstall()
	{
		$this->delete_configuration();
	}

	private function delete_configuration()
	{
		ConfigManager::delete('Teamspeak', 'config');
	}
}
?>
