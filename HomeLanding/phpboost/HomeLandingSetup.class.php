<?php
/*##################################################
 *		             HomeLandingSetup.class.php
 *                            -------------------
 *   begin                : January 2, 2016
 *   copyright            : (C) 2016 Sebastien Lartigue
 *   email                : babsolune@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Comments Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Comments Public License for more details.
 *
 * You should have received a copy of the GNU Comments Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

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
