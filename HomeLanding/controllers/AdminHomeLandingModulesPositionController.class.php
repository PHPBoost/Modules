<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 14
 * @since       PHPBoost 5.0 - 2016 05 01
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminHomeLandingModulesPositionController extends DefaultAdminModuleController
{
    protected function get_template_to_use()
    {
	    return new FileTemplate('HomeLanding/AdminHomeLandingModulesPositionController.tpl');
    }

	public function execute(HTTPRequestCustom $request)
	{
		$this->update_modules($request);
		
		$modules_number = 0;
		foreach ($this->config->get_modules() as $id => $properties)
		{
			$module = new HomeLandingModule();
			$module->set_properties($properties);

			$this->view->assign_block_vars('modules_list', array(
				'C_ACTIVE'  => in_array($module->get_module_id(), array('anchors_menu', 'carousel', 'edito', 'lastcoms')) ? true : $module->is_active(),
				'C_DISPLAY' => $module->is_displayed(),
				'ID'        => $id,
				'NAME'      => in_array($module->get_module_id(), array('anchors_menu', 'carousel', 'edito', 'lastcoms')) ? $module->get_name() : ($module->is_active() ? $module->get_name() : ''),
				'U_EDIT'    => HomeLandingUrlBuilder::configuration($module->get_config_module_id())->rel()
			));
			$modules_number++;
		}

		$this->view->put_all(array(
			'C_MODULES'         => $modules_number,
			'C_SEVERAL_MODULES' => $modules_number > 1
		));

		return new AdminHomeLandingDisplayResponse($this->view, $this->lang['homelanding.modules.position']);
	}

	private function update_modules(HTTPRequestCustom $request)
	{
		if ($request->get_value('submit', false))
		{
			$this->update_position($request);
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.success.position.update'], MessageHelper::SUCCESS, 5));
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
