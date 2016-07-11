<?php
/*##################################################
 *                       AdminHomeLandingModulesPositionController.class.php
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

class AdminHomeLandingModulesPositionController extends AdminModuleController
{
	private $lang;
	private $view;
	private $config;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->update_modules($request);
		
		$modules_number = 0;
		foreach ($this->config->get_modules() as $id => $properties)
		{
			$module = new HomeLandingModule();
			$module->set_properties($properties);
			
			$this->view->assign_block_vars('modules_list', array(
				'C_DISPLAY' => $module->is_displayed(),
				'ID' => $id,
				'NAME' => $module->get_name(),
				'U_EDIT' => HomeLandingUrlBuilder::configuration($module->get_config_module_id())->rel()
			));
			$modules_number++;
		}
		
		$this->view->put_all(array(
			'C_MODULES' => $modules_number,
			'C_MORE_THAN_ONE_MODULE' => $modules_number > 1
		));
		
		return new AdminHomeLandingDisplayResponse($this->view, LangLoader::get_message('admin.elements_position', 'common', 'HomeLanding'));
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('admin-user-common');
		$this->view = new FileTemplate('HomeLanding/AdminHomeLandingModulesPositionController.tpl');
		$this->view->add_lang($this->lang);
		$this->config = HomeLandingConfig::load();
	}
	
	private function update_modules(HTTPRequestCustom $request)
	{
		if ($request->get_value('submit', false))
		{
			$this->update_position($request);
			$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.position.update', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}
	}
	
	private function update_position(HTTPRequestCustom $request)
	{
		$modules = $this->config->get_modules();
		$sorted_modules = array();
		
		$modules_list = json_decode(TextHelper::html_entity_decode($request->get_value('tree')));
		foreach($modules_list as $position => $tree)
		{
			$sorted_modules[$position + 1] = $modules[$tree->id];
			unset($modules[$tree->id]);
		}
		foreach($modules as $position => $module)
		{
			$sorted_modules[] = $module;
		}
		
		$this->config->set_modules($sorted_modules);
		
		HomeLandingConfig::save();
	}
}
?>
