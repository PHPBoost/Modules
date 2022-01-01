<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 08
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

		if (!isset($modules[HomeLandingConfig::MODULE_ANCHORS_MENU]))
		{
			$new_modules_list = array();

			$module = new HomeLandingModule();
			$module->set_module_id(HomeLandingConfig::MODULE_ANCHORS_MENU);
			$module->hide();

			$new_modules_list[1] = $module->get_properties();

			foreach ($modules as $module)
			{
				$new_modules_list[] = $module;
			}

			HomeLandingModulesList::save($new_modules_list);
			HomeLandingConfig::save();
		}

		if (!isset($modules[HomeLandingConfig::MODULE_SMALLADS]))
		{
			$new_modules_list = array();

			$module = new HomeLandingModule();
			$module->set_module_id(HomeLandingConfig::MODULE_SMALLADS);
			$module->set_phpboost_module_id(HomeLandingConfig::MODULE_SMALLADS);
			$module->hide();

			$new_modules_list[] = $module->get_properties();

			foreach ($modules as $module)
			{
				$new_modules_list[] = $module;
			}

			HomeLandingModulesList::save($new_modules_list);
			HomeLandingConfig::save();
		}

		if (!isset($modules[HomeLandingConfig::MODULE_SMALLADS_CATEGORY]))
		{
			$new_modules_list = array();

			$module = new HomeLandingModuleCategory();
			$module->set_module_id(HomeLandingConfig::MODULE_SMALLADS_CATEGORY);
			$module->set_phpboost_module_id(HomeLandingConfig::MODULE_SMALLADS);
			$module->hide();

			$new_modules_list[] = $module->get_properties();

			foreach ($modules as $module)
			{
				$new_modules_list[] = $module;
			}

			HomeLandingModulesList::save($new_modules_list);
			HomeLandingConfig::save();
		}

		return '6.0.0';
	}

	private function delete_configuration()
	{
		ConfigManager::delete('homelanding', 'config');
	}
}
?>
