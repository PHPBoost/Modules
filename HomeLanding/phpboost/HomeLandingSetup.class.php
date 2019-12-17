<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 12 29
 * @since       PHPBoost 5.0 - 2016 01 02
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class HomeLandingSetup extends DefaultModuleSetup
{
	public function uninstall()
	{
		$this->delete_configuration();
	}

	public function upgrade($installed_version)
	{
		$config = HomeLandingConfig::load();

		$modules = $config->get_modules();

		if (!isset($modules[HomeLandingConfig::MODULE_ONEPAGE_MENU]))
		{
			$new_modules_list = array();

			$module = new HomeLandingModule();
			$module->set_module_id(HomeLandingConfig::MODULE_ONEPAGE_MENU);
			$module->hide();

			$new_modules_list[1] = $module->get_properties();

			foreach ($modules as $module)
			{
				$new_modules_list[] = $module;
			}

			HomeLandingModulesList::save($new_modules_list);
			HomeLandingConfig::save();
		}

		return '5.2.0';
	}

	private function delete_configuration()
	{
		ConfigManager::delete('homelanding', 'config');
	}
}
?>
