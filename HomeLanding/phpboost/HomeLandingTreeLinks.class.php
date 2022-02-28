<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 02 28
 * @since       PHPBoost 5.0 - 2016 01 02
*/

class HomeLandingTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$module_id = 'HomeLanding';
		$lang = LangLoader::get_all_langs($module_id);
		$tree = new ModuleTreeLinks();
		$config = HomeLandingConfig::load();
		$sticky_title = $config->get_sticky_title();

		if(AppContext::get_current_user()->check_level(User::MODERATOR_LEVEL)){
			$tree->add_link(new ModuleLink($sticky_title, HomeLandingUrlBuilder::sticky()));
			$tree->add_link(new AdminModuleLink($lang['homelanding.sticky.manage'] . ': ' . $sticky_title, HomeLandingUrlBuilder::sticky_manage()));
		}

		$tree->add_link(new AdminModuleLink($lang['homelanding.modules.position'], HomeLandingUrlBuilder::positions()));

		$tree->add_link(new AdminModuleLink($lang['form.configuration'], HomeLandingUrlBuilder::configuration()));
		if (ModulesManager::get_module($module_id)->get_configuration()->get_documentation())
			$tree->add_link(new AdminModuleLink($lang['form.documentation'], ModulesManager::get_module($module_id)->get_configuration()->get_documentation()));

		return $tree;
	}
}
?>
