<?php
/**
 * @copyright   &copy; 2005-2024 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2024 01 22
 * @since       PHPBoost 6.0 - 2020 07 29
*/

class HomeLandingConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('HomeLanding', false, 'homelanding-config');
	}

	protected function build_new_config()
	{
		$old_config = $this->get_old_config();

		if (ModulesManager::is_module_installed('HomeLanding') && ModulesManager::get_module('HomeLanding')->get_configuration()->get_compatibility() == UpdateServices::NEW_KERNEL_VERSION && ClassLoader::is_class_registered_and_valid('HomeLandingConfig') && !empty($old_config))
		{
			$config = HomeLandingConfig::load();
			$modules = $config->get_modules();
			$new_modules_list = array();

			if (!isset($modules[HomeLandingConfig::MODULE_ANCHORS_MENU]))
			{
				$module = new HomeLandingModule();
				$module->set_module_id(HomeLandingConfig::MODULE_ANCHORS_MENU);
				$new_modules_list[1] = $module->get_properties();
			}

			$onepage_menu = '';

			try {
				$onepage_menu = $old_config->get_property('onepage_menu');
			} catch (PropertyNotFoundException $e) {}
			if ($onepage_menu)
				$config->set_anchors_menu((bool)$old_config->get_property('onepage_menu'));

			foreach($modules as &$module)
			{
				if ($module['module_id'] == 'onepage_menu')
					$module['module_id'] = 'anchors_menu';
			}

			$config->set_modules($modules);

			if (!isset($modules[HomeLandingConfig::MODULE_FLUX]))
			{
				$module = new HomeLandingModule();
				$module->set_module_id(HomeLandingConfig::MODULE_FLUX);
				$module->set_phpboost_module_id(HomeLandingConfig::MODULE_FLUX);
				$module->hide();
				$new_modules_list[20] = $module->get_properties();
			}

			if (!isset($modules[HomeLandingConfig::MODULE_PINNED_NEWS]))
			{
				$module = new HomeLandingModule();
				$module->set_module_id(HomeLandingConfig::MODULE_PINNED_NEWS);
				$module->set_phpboost_module_id(HomeLandingConfig::MODULE_NEWS);
				$module->hide();
				$new_modules_list[21] = $module->get_properties();
			}

			if (!isset($modules[HomeLandingConfig::MODULE_RECIPE]))
			{
				$module = new HomeLandingModule();
				$module->set_module_id(HomeLandingConfig::MODULE_RECIPE);
				$module->set_phpboost_module_id(HomeLandingConfig::MODULE_RECIPE);
				$module->hide();
				$new_modules_list[22] = $module->get_properties();
			}

			if (!isset($modules[HomeLandingConfig::MODULE_RECIPE_CATEGORY]))
			{
				$module = new HomeLandingModuleCategory();
				$module->set_module_id(HomeLandingConfig::MODULE_RECIPE_CATEGORY);
				$module->set_phpboost_module_id(HomeLandingConfig::MODULE_RECIPE);
				$module->hide();
				$new_modules_list[23] = $module->get_properties();
			}

			if (!isset($modules[HomeLandingConfig::MODULE_SMALLADS]))
			{
				$module = new HomeLandingModule();
				$module->set_module_id(HomeLandingConfig::MODULE_SMALLADS);
				$module->set_phpboost_module_id(HomeLandingConfig::MODULE_SMALLADS);
				$module->hide();
				$new_modules_list[24] = $module->get_properties();
			}

			if (!isset($modules[HomeLandingConfig::MODULE_SMALLADS_CATEGORY]))
			{
				$module = new HomeLandingModuleCategory();
				$module->set_module_id(HomeLandingConfig::MODULE_SMALLADS_CATEGORY);
				$module->set_phpboost_module_id(HomeLandingConfig::MODULE_SMALLADS);
				$module->hide();
				$new_modules_list[25] = $module->get_properties();
			}

			if (!isset($modules[HomeLandingConfig::MODULE_VIDEO]))
			{
				$module = new HomeLandingModule();
				$module->set_module_id(HomeLandingConfig::MODULE_VIDEO);
				$module->set_phpboost_module_id(HomeLandingConfig::MODULE_VIDEO);
				$module->hide();
				$new_modules_list[26] = $module->get_properties();
			}

			if (!isset($modules[HomeLandingConfig::MODULE_VIDEO_CATEGORY]))
			{
				$module = new HomeLandingModuleCategory();
				$module->set_module_id(HomeLandingConfig::MODULE_VIDEO_CATEGORY);
				$module->set_phpboost_module_id(HomeLandingConfig::MODULE_VIDEO);
				$module->hide();
				$new_modules_list[27] = $module->get_properties();
			}

			if (!isset($modules[HomeLandingConfig::MODULE_WEB_CATEGORY]))
			{
				$module = new HomeLandingModuleCategory();
				$module->set_module_id(HomeLandingConfig::MODULE_WEB_CATEGORY);
				$module->set_phpboost_module_id(HomeLandingConfig::MODULE_WEB);
				$module->hide();
				$new_modules_list[28] = $module->get_properties();
			}

			// if (!isset($modules[HomeLandingConfig::MODULE_PODCAST]))
			// {
			// 	$module = new HomeLandingModule();
			// 	$module->set_module_id(HomeLandingConfig::MODULE_PODCAST);
			// 	$module->set_phpboost_module_id(HomeLandingConfig::MODULE_PODCAST);
			// 	$module->hide();
			// 	$new_modules_list[25] = $module->get_properties();
			// }

			// if (!isset($modules[HomeLandingConfig::MODULE_PODCAST_CATEGORY]))
			// {
			// 	$module = new HomeLandingModuleCategory();
			// 	$module->set_module_id(HomeLandingConfig::MODULE_PODCAST_CATEGORY);
			// 	$module->set_phpboost_module_id(HomeLandingConfig::MODULE_PODCAST);
			// 	$module->hide();
			// 	$new_modules_list[26] = $module->get_properties();
			// }

			foreach ($modules as $module)
			{
				$new_modules_list[] = $module;
			}

			HomeLandingModulesList::save($new_modules_list);
			HomeLandingConfig::save();

			return true;
		}
		return false;
	}
}
?>
