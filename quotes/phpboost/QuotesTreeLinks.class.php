<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 12 24
 * @since   	PHPBoost 5.0 - 2016 02 18
 * @contributor mipel <mipel@phpboost.com>
*/

class QuotesTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get('common', 'quotes');
		$tree = new ModuleTreeLinks();

		$manage_categories_link = new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), QuotesUrlBuilder::manage_categories(), QuotesAuthorizationsService::check_authorizations()->manage_categories());
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), QuotesUrlBuilder::manage_categories(), QuotesAuthorizationsService::check_authorizations()->manage_categories()));
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('category.add', 'categories-common'), QuotesUrlBuilder::add_category(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY)), QuotesAuthorizationsService::check_authorizations()->manage_categories()));
		$tree->add_link($manage_categories_link);

		$manage_link = new ModuleLink($lang['quotes.manage'], QuotesUrlBuilder::manage(), QuotesAuthorizationsService::check_authorizations()->moderation());
		$manage_link->add_sub_link(new ModuleLink($lang['quotes.manage'], QuotesUrlBuilder::manage(), QuotesAuthorizationsService::check_authorizations()->moderation()));
		$manage_link->add_sub_link(new ModuleLink($lang['quotes.add'], QuotesUrlBuilder::add(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY), AppContext::get_request()->get_getvalue('author', '')), QuotesAuthorizationsService::check_authorizations()->moderation()));
		$tree->add_link($manage_link);

		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin'), QuotesUrlBuilder::configuration()));

		if (!QuotesAuthorizationsService::check_authorizations()->moderation())
		{
			$tree->add_link(new ModuleLink($lang['quotes.add'], QuotesUrlBuilder::add(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY)), QuotesAuthorizationsService::check_authorizations()->write() || QuotesAuthorizationsService::check_authorizations()->contribution()));
		}

		$tree->add_link(new ModuleLink($lang['quotes.pending'], QuotesUrlBuilder::display_pending(), QuotesAuthorizationsService::check_authorizations()->write() || QuotesAuthorizationsService::check_authorizations()->moderation()));

		return $tree;
	}
}
?>
