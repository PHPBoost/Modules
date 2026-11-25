<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 25
 * @since       PHPBoost 4.0 - 2013 03 01
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ContactSetup extends DefaultModuleSetup
{
	public function uninstall(): void
	{
		$this->delete_configuration();
	}

	private function delete_configuration(): void
	{
		ConfigManager::delete('contact', 'config');
	}
}
?>
