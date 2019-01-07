<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 12 10
 * @since   	PHPBoost 4.0 - 2014 02 03
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SmalladsTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get('common', 'smallads');
		$tree = new ModuleTreeLinks();

		$config_link = new AdminModuleLink(LangLoader::get_message('configuration', 'admin-common'), SmalladsUrlBuilder::categories_configuration());
		$config_link->add_sub_link(new AdminModuleLink($lang['config.categories.title'], SmalladsUrlBuilder::categories_configuration()));
		$config_link->add_sub_link(new AdminModuleLink($lang['config.items.title'], SmalladsUrlBuilder::items_configuration()));
		$config_link->add_sub_link(new AdminModuleLink($lang['config.mini.title'], SmalladsUrlBuilder::mini_configuration()));
		$config_link->add_sub_link(new AdminModuleLink($lang['config.usage.terms'], SmalladsUrlBuilder::usage_terms_configuration()));
		$tree->add_link($config_link);

		$manage_categories_link = new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), SmalladsUrlBuilder::manage_categories(), SmalladsAuthorizationsService::check_authorizations()->manage_categories());
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), SmalladsUrlBuilder::manage_categories(), SmalladsAuthorizationsService::check_authorizations()->manage_categories()));
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('category.add', 'categories-common'), SmalladsUrlBuilder::add_category(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY)), SmalladsAuthorizationsService::check_authorizations()->manage_categories()));
		$tree->add_link($manage_categories_link);

		$manage_smallads_link = new ModuleLink($lang['smallads.management'], SmalladsUrlBuilder::manage_items(), SmalladsAuthorizationsService::check_authorizations()->moderation());
		$manage_smallads_link->add_sub_link(new ModuleLink($lang['smallads.management'], SmalladsUrlBuilder::manage_items(), SmalladsAuthorizationsService::check_authorizations()->moderation()));
		$manage_smallads_link->add_sub_link(new ModuleLink($lang['smallads.add'], SmalladsUrlBuilder::add_item(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY)), SmalladsAuthorizationsService::check_authorizations()->moderation()));
		$tree->add_link($manage_smallads_link);

		if (!SmalladsAuthorizationsService::check_authorizations()->moderation())
		{
			$tree->add_link(new ModuleLink($lang['smallads.add'], SmalladsUrlBuilder::add_item(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY)), SmalladsAuthorizationsService::check_authorizations()->write() || SmalladsAuthorizationsService::check_authorizations()->contribution()));
		}

		$tree->add_link(new ModuleLink($lang['smallads.pending.items'], SmalladsUrlBuilder::display_pending_items(), SmalladsAuthorizationsService::check_authorizations()->write() || SmalladsAuthorizationsService::check_authorizations()->contribution() || SmalladsAuthorizationsService::check_authorizations()->moderation()));

		$tree->add_link(new ModuleLink($lang['smallads.member.items'], SmalladsUrlBuilder::display_member_items(), SmalladsAuthorizationsService::check_authorizations()->write() || SmalladsAuthorizationsService::check_authorizations()->contribution() || SmalladsAuthorizationsService::check_authorizations()->moderation()));

		$tree->add_link(new ModuleLink(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module('smallads')->get_configuration()->get_documentation(), SmalladsAuthorizationsService::check_authorizations()->write() || SmalladsAuthorizationsService::check_authorizations()->contribution() || SmalladsAuthorizationsService::check_authorizations()->moderation()));

		return $tree;
	}
}
?>
