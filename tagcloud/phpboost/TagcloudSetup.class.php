<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 01 17
 * @since       PHPBoost 6.0 - 2023 01 17
*/

class TagcloudSetup extends DefaultModuleSetup
{
	public function upgrade($installed_version)
	{
		return '6.0.0';
	}

	public function uninstall()
	{
		ConfigManager::delete('tagcloud', 'config');
	}
}
?>
