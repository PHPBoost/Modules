<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 12 24
 * @since   	PHPBoost 5.0 - 2016 02 18
 * @contributor mipel <mipel@phpboost.com>
*/

class QuotesDeleteCategoryController extends AbstractDeleteCategoryController
{
	protected function get_id_category()
	{
		return AppContext::get_request()->get_getint('id', 0);
	}

	protected function get_categories_manager()
	{
		return QuotesService::get_categories_manager();
	}

	protected function get_categories_management_url()
	{
		return QuotesUrlBuilder::manage_categories();
	}

	protected function get_delete_category_url(Category $category)
	{
		return QuotesUrlBuilder::delete_category($category->get_id());
	}

	protected function get_module_home_page_url()
	{
		return QuotesUrlBuilder::home();
	}

	protected function get_module_home_page_title()
	{
		return LangLoader::get_message('module_title', 'common', 'quotes');
	}

	protected function check_authorizations()
	{
		if (!QuotesAuthorizationsService::check_authorizations()->manage_categories())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
