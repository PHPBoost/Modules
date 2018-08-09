<?php
/*##################################################
 *		    SmalladsTreeLinks.class.php
 *                            -------------------
 *   begin                : March 15, 2018
 *   copyright            : (C) 2018 Sebastien LARTIGUE
 *   email                : babsolune@phpboost.com
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

/**
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
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
