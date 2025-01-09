<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 14
 * @since       PHPBoost 6.0 - 2021 09 06
*/

class AdminHomeLandingAddModulesController extends DefaultAdminModuleController
{
	private $features;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();

		$this->view->put('CONTENT', $this->form->display());

		return new AdminHomeLandingDisplayResponse($this->view, $this->lang['homelanding.add.modules']);
	}

	private function init()
	{
		$this->features = ModulesManager::get_activated_feature_modules('homelanding');
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('has_new_module', $this->lang['homelanding.add.modules']);
		$form->add_fieldset($fieldset);

		$home_modules = $modules_from_list = array();
		foreach ($this->features as $module)
		{
			$home_modules[] = $module->get_id();
		}

		foreach($this->config->get_modules() as $id => $module)
		{
			$modules_from_list[] = $module['module_id'];
		}

		$new_modules = array_diff($home_modules, $modules_from_list);

		if($new_modules) {
			foreach($new_modules as $module)
			{
				$new_module_name[] = ModulesManager::get_module($module)->get_configuration()->get_name() . ' | ';
			}
			$modules_list = substr(json_encode($new_module_name), 2, -4);

			$fieldset->add_field(new FormFieldFree('new_modules', $this->lang['homelanding.new.modules'], StringVars::replace_vars($this->lang['homelanding.add.modules.warning'], array('modules_list' => $modules_list)),
				array('class' => 'full-field')
			));

			$this->submit_button = new FormButtonDefaultSubmit();
			$form->add_button($this->submit_button);

			if ($this->submit_button->has_been_submited())
			{
				$this->refresh_modules_list();
			}
		}
		else {
			$fieldset = new FormFieldsetHTML('has_new_module', '');
				$form->add_fieldset($fieldset);

				$fieldset->add_field(new FormFieldHTML('new_modules', $this->lang['homelanding.no.new.module'],
					array('class' => 'full-field success bgc message-helper')
				));

				$fieldset->add_field(new FormFieldHTML('configuration', $this->lang['homelanding.back.to.configuration'],
					array('class' => 'full-field')
				));

				$this->submit_button = '';
		}

		$this->form = $form;
	}

	private function refresh_modules_list()
	{
		$modules_list = $this->config->get_modules();
		$modules = array();

		$add_directory = new Folder(PATH_TO_ROOT . '/HomeLanding/additional/add/');
		$add_files = $add_directory->get_files();
		foreach ($add_files as $add_file)
		{
			require_once($add_file->get_path());
		}

		foreach ($modules_list as $module)
		{
			$modules[] = $module;
		}

		HomeLandingModulesList::save($modules);
		HomeLandingConfig::save();
		AppContext::get_response()->redirect(HomeLandingUrlBuilder::add_modules());
	}
}
?>
