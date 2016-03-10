<?php
/*##################################################
 *                               QuotesTreeLinks.class.php
 *                            -------------------
 *   begin                : February 18, 2016
 *   copyright            : (C) 2016 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class QuotesTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get('common', 'quotes');
		$tree = new ModuleTreeLinks();
		
		$manage_categories_link = new AdminModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), QuotesUrlBuilder::manage_categories());
		$manage_categories_link->add_sub_link(new AdminModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), QuotesUrlBuilder::manage_categories()));
		$manage_categories_link->add_sub_link(new AdminModuleLink(LangLoader::get_message('category.add', 'categories-common'), QuotesUrlBuilder::add_category(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY))));
		$tree->add_link($manage_categories_link);
		
		$manage_link = new AdminModuleLink($lang['quotes.manage'], QuotesUrlBuilder::manage());
		$manage_link->add_sub_link(new AdminModuleLink($lang['quotes.manage'], QuotesUrlBuilder::manage()));
		$manage_link->add_sub_link(new AdminModuleLink($lang['quotes.add'], QuotesUrlBuilder::add(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY), AppContext::get_request()->get_getvalue('author', ''))));
		$tree->add_link($manage_link);
		
		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin'), QuotesUrlBuilder::configuration()));
		
		if (!AppContext::get_current_user()->check_level(User::ADMIN_LEVEL))
		{
			$tree->add_link(new ModuleLink($lang['quotes.add'], QuotesUrlBuilder::add(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY)), QuotesAuthorizationsService::check_authorizations()->write() || QuotesAuthorizationsService::check_authorizations()->contribution()));
		}
		
		$tree->add_link(new ModuleLink($lang['quotes.pending'], QuotesUrlBuilder::display_pending(), QuotesAuthorizationsService::check_authorizations()->write() || QuotesAuthorizationsService::check_authorizations()->moderation()));
		
		return $tree;
	}
}
?>
