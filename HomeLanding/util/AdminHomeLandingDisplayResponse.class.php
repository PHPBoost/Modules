<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 09 07
 * @since       PHPBoost 5.0 - 2016 01 02
*/

class AdminHomeLandingDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $page_title)
	{
		parent::__construct($view);
		$config = HomeLandingConfig::load();
		$features = ModulesManager::get_activated_feature_modules('homelanding');

		$home_modules = $modules_from_list = array();
		foreach ($features as $module)
		{
			$home_modules[] = $module->get_id();
		}

		foreach($config->get_modules() as $id => $module)
		{
			$modules_from_list[] = $module['module_id'];
		}
		$new_modules = array_diff($home_modules, $modules_from_list);

		$sticky_title = HomeLandingConfig::load()->get_sticky_title();

		$this->add_link(LangLoader::get_message('form.configuration', 'form-lang'), $this->module->get_configuration()->get_admin_main_page());
		$this->add_link(LangLoader::get_message('homelanding.modules.position', 'common', 'HomeLanding'), HomeLandingUrlBuilder::positions());
		if($new_modules)
			$this->add_link(LangLoader::get_message('homelanding.add.modules', 'common', 'HomeLanding'), HomeLandingUrlBuilder::add_modules());
		$this->add_link(LangLoader::get_message('homelanding.sticky.manage', 'sticky', 'HomeLanding') . ': '. $sticky_title, HomeLandingUrlBuilder::sticky_manage());
		$this->add_link($sticky_title, HomeLandingUrlBuilder::sticky());
		$this->add_link(LangLoader::get_message('form.documentation', 'form-lang'), $this->module->get_configuration()->get_documentation());

		$this->get_graphical_environment()->set_page_title($page_title);
	}
}
?>
