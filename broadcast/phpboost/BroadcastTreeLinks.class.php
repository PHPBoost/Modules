<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 10 25
 * @since       PHPBoost 6.0 - 2022 10 25
 */

class BroadcastTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get_all_langs('broadcast');
		$tree = new ModuleTreeLinks();

		$tree->add_link(new ModuleLink($lang['category.categories.manage'], BroadcastUrlBuilder::manage_categories(), CategoriesAuthorizationsService::check_authorizations()->manage()));
		$tree->add_link(new ModuleLink($lang['category.add'], BroadcastUrlBuilder::add_category(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY)), CategoriesAuthorizationsService::check_authorizations()->manage()));

		$tree->add_link(new ModuleLink($lang['broadcast.items.manage'], BroadcastUrlBuilder::manage(), CategoriesAuthorizationsService::check_authorizations()->moderation()));
		$tree->add_link(new ModuleLink($lang['broadcast.item.add'], BroadcastUrlBuilder::add(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY)), CategoriesAuthorizationsService::check_authorizations()->moderation()));

		$tree->add_link(new AdminModuleLink($lang['form.configuration'], BroadcastUrlBuilder::configuration()));

		if (ModuleConfigurationManager::get('broadcast')->get_documentation())
			$tree->add_link(new ModuleLink($lang['form.documentation'], ModulesManager::get_module('broadcast')->get_configuration()->get_documentation(), CategoriesAuthorizationsService::check_authorizations()->write() || CategoriesAuthorizationsService::check_authorizations()->contribution() || CategoriesAuthorizationsService::check_authorizations()->moderation()));

		return $tree;
	}
}
?>
