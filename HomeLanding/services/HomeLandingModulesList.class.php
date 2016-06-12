<?php
/*##################################################
 *                       HomeLandingModulesList.class.php
 *                            -------------------
 *   begin                : May 1, 2016
 *   copyright            : (C) 2016 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

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
