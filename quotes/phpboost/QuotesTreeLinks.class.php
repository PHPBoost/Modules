<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2019 11 03
 * @since   	PHPBoost 5.0 - 2016 02 18
 * @contributor mipel <mipel@phpboost.com>
*/

class QuotesTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$module_id = 'quotes';

		$lang = LangLoader::get('common', $module_id);
		$tree = new ModuleTreeLinks();

		$manage_categories_link = new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), CategoriesUrlBuilder::manage_categories($module_id), CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->manage_categories());
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), CategoriesUrlBuilder::manage_categories($module_id), CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->manage_categories()));
		$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('category.add', 'categories-common'), CategoriesUrlBuilder::add_category(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY), $module_id), CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->manage_categories()));
		$tree->add_link($manage_categories_link);

		$manage_link = new ModuleLink($lang['quotes.manage'], QuotesUrlBuilder::manage(), CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->moderation());
		$manage_link->add_sub_link(new ModuleLink($lang['quotes.manage'], QuotesUrlBuilder::manage(), CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->moderation()));
		$manage_link->add_sub_link(new ModuleLink($lang['quotes.add'], QuotesUrlBuilder::add(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY), AppContext::get_request()->get_getvalue('author', '')), CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->moderation()));
		$tree->add_link($manage_link);

		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin'), QuotesUrlBuilder::configuration()));

		if (!CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->moderation())
		{
			$tree->add_link(new ModuleLink($lang['quotes.add'], QuotesUrlBuilder::add(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY)), CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->write() || CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->contribution()));
		}

		$tree->add_link(new ModuleLink($lang['quotes.pending'], QuotesUrlBuilder::display_pending(), CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->write() || CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->moderation()));

		return $tree;
	}
}
?>
