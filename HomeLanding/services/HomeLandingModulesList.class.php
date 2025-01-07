<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 06 12
 * @since       PHPBoost 5.0 - 2016 05 01
*/

class HomeLandingModulesList
{
	public static function load()
	{
		$modules = array();

		foreach (HomeLandingConfig::load()->get_modules() as $position => $properties)
		{
			$module = isset($properties['id_category']) ? new HomeLandingModuleCategory() : new HomeLandingModule();
			$module->set_properties($properties);

			$modules[$module->get_module_id()] = $module;
		}

		return $modules;
	}

	public static function save($modules_list)
	{
		$modules = array();
		$i = 1;
		foreach ($modules_list as $id => $module)
		{
			$modules[$i] = is_array($module) ? $module : $module->get_properties();
			$i++;
		}

		if (!empty($modules))
			HomeLandingConfig::load()->set_modules($modules);
	}
}
?>
