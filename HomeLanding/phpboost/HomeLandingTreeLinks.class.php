<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 18
 * @since       PHPBoost 5.0 - 2016 01 02
*/

class HomeLandingTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$module_id = 'HomeLanding';
		$tree = new ModuleTreeLinks();
		$config = HomeLandingConfig::load();
		$sticky_title = $config->get_sticky_title();

		$tree->add_link(new AdminModuleLink(LangLoader::get_message('form.configuration', 'form-lang'), HomeLandingUrlBuilder::configuration()));
		$tree->add_link(new AdminModuleLink(LangLoader::get_message('homelanding.modules.position', 'common', $module_id), HomeLandingUrlBuilder::positions()));

		if(AppContext::get_current_user()->check_level(User::MODERATOR_LEVEL)){
			$tree->add_link(new AdminModuleLink(LangLoader::get_message('homelanding.sticky.manage', 'sticky', $module_id).': '.$sticky_title, HomeLandingUrlBuilder::sticky_manage()));
			$tree->add_link(new ModuleLink($sticky_title, HomeLandingUrlBuilder::sticky()));
		}

		if (ModulesManager::get_module($module_id)->get_configuration()->get_documentation())
			$tree->add_link(new AdminModuleLink(LangLoader::get_message('form.documentation', 'form-lang'), ModulesManager::get_module($module_id)->get_configuration()->get_documentation()));

		return $tree;
	}
}
?>
