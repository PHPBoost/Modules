<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 08 09
 * @since   	PHPBoost 5.1 - 2018 03 15
*/

class SmalladsCategoriesManagerController extends AbstractCategoriesManageController
{
	protected function get_categories_manager()
	{
		return SmalladsService::get_categories_manager();
	}

	protected function get_display_category_url(Category $category)
	{
		return SmalladsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name());
	}

	protected function get_edit_category_url(Category $category)
	{
		return SmalladsUrlBuilder::edit_category($category->get_id());
	}

	protected function get_delete_category_url(Category $category)
	{
		return SmalladsUrlBuilder::delete_category($category->get_id());
	}

	protected function get_categories_management_url()
	{
		return SmalladsUrlBuilder::manage_categories();
	}

	protected function get_module_home_page_url()
	{
		return SmalladsUrlBuilder::home();
	}

	protected function get_module_home_page_title()
	{
		return LangLoader::get_message('smallads.module.title', 'common', 'smallads');
	}

	protected function check_authorizations()
	{
		if (!SmalladsAuthorizationsService::check_authorizations()->manage_categories())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
