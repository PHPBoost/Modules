<?php
/*##################################################
 *                               QuotesDisplayCategoryController.class.php
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

class QuotesDisplayCategoryController extends ModuleController
{
	private $lang;
	private $tpl;
	private $config;
	
	private $category;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();
		
		$this->init();
		
		$this->build_view($request);
		
		return $this->generate_response();
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'quotes');
		$this->tpl = new FileTemplate('quotes/QuotesDisplaySeveralQuotesController.tpl');
		$this->tpl->add_lang($this->lang);
		$this->config = QuotesConfig::load();
	}
	
	private function build_view(HTTPRequestCustom $request)
	{
		$main_lang = LangLoader::get('main');
		$authorized_categories = $this->get_authorized_categories();
		$page = $request->get_getint('page', 1);
		$subcategories_page = $request->get_getint('subcategories_page', 1);
		
		//Children categories
		$subcategories = QuotesService::get_categories_manager()->get_categories_cache()->get_children($this->get_category()->get_id(), QuotesService::get_authorized_categories($this->get_category()->get_id()));
		$subcategories_pagination = $this->get_subcategories_pagination(count($subcategories), $this->config->get_categories_number_per_page(), $page, $subcategories_page);
		
		$nbr_cat_displayed = 0;
		foreach ($subcategories as $id => $category)
		{
			$nbr_cat_displayed++;
			
			if ($nbr_cat_displayed > $subcategories_pagination->get_display_from() && $nbr_cat_displayed <= ($subcategories_pagination->get_display_from() + $subcategories_pagination->get_number_items_per_page()))
			{
				$category_image = $category->get_image()->rel();
				
				$this->tpl->assign_block_vars('sub_categories_list', array(
					'C_CATEGORY_IMAGE' => !empty($category_image),
					'C_MORE_THAN_ONE_ELEMENT' => $category->get_elements_number() > 1,
					'CATEGORY_ID' => $category->get_id(),
					'CATEGORY_NAME' => $category->get_name(),
					'CATEGORY_IMAGE' => $category_image,
					'ELEMENTS_NUMBER' => $category->get_elements_number(),
					'U_CATEGORY' => QuotesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel()
				));
			}
		}
		
		$nbr_column_cats = ($nbr_cat_displayed > $this->config->get_columns_number_per_line()) ? $this->config->get_columns_number_per_line() : $nbr_cat_displayed;
		$nbr_column_cats = !empty($nbr_column_cats) ? $nbr_column_cats : 1;
		$cats_columns_width = floor(100 / $nbr_column_cats);
		
		$condition = 'WHERE id_category = :id_category AND approved = 1';
		$parameters = array(
			'id_category' => $this->get_category()->get_id()
		);
		
		//Category content
		$pagination = $this->get_pagination($condition, $parameters, $page, $subcategories_page);
		
		$result = PersistenceContext::get_querier()->select('SELECT quotes.*, member.*
		FROM '. QuotesSetup::$quotes_table .' quotes
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = quotes.author_user_id
		' . $condition . '
		ORDER BY quotes.creation_date DESC
		LIMIT :number_items_per_page OFFSET :display_from', array_merge($parameters, array(
			'number_items_per_page' => (int)$pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		)));
		
		$category_description = FormatingHelper::second_parse($this->get_category()->get_description());
		
		$this->tpl->put_all(array(
			'C_RESULTS' => $result->get_rows_count() > 0,
			'C_MORE_THAN_ONE_RESULT' => $result->get_rows_count() > 1,
			'C_CATEGORY_DESCRIPTION' => !empty($category_description),
			'C_CATEGORY' => true,
			'C_ROOT_CATEGORY' => $this->get_category()->get_id() == Category::ROOT_CATEGORY,
			'C_HIDE_NO_ITEM_MESSAGE' => $this->get_category()->get_id() == Category::ROOT_CATEGORY && ($nbr_cat_displayed != 0 || !empty($category_description)),
			'C_SUB_CATEGORIES' => $nbr_cat_displayed > 0,
			'C_PAGINATION' => $pagination->has_several_pages(),
			'C_SUBCATEGORIES_PAGINATION' => $subcategories_pagination->has_several_pages(),
			'SUBCATEGORIES_PAGINATION' => $subcategories_pagination->display(),
			'PAGINATION' => $pagination->display(),
			'CATS_COLUMNS_WIDTH' => $cats_columns_width,
			'ID_CAT' => $this->get_category()->get_id(),
			'CATEGORY_NAME' => $this->get_category()->get_name(),
			'CATEGORY_IMAGE' => $this->get_category()->get_image()->rel(),
			'CATEGORY_DESCRIPTION' => $category_description,
			'U_EDIT_CATEGORY' => $this->get_category()->get_id() == Category::ROOT_CATEGORY ? QuotesUrlBuilder::configuration()->rel() : QuotesUrlBuilder::edit_category($this->get_category()->get_id())->rel()
		));
		
		while ($row = $result->fetch())
		{
			$quote = new Quote();
			$quote->set_properties($row);
			
			$this->tpl->assign_block_vars('quotes', $quote->get_array_tpl_vars());
		}
		$result->dispose();
	}
	
	private function get_authorized_categories()
	{
		$category = $this->get_category();
		$authorized_categories = array();
		if ($category->get_id() !== Category::ROOT_CATEGORY)
		{
			$authorized_categories[] = $category->get_id();
		}
		else
		{
			$search_category_children_options = new SearchCategoryChildrensOptions();
			$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);
			$categories = QuotesService::get_categories_manager()->get_children($category->get_id(), $search_category_children_options);
			$authorized_categories = array_keys($categories);
		}
		return $authorized_categories;
	}
	
	private function get_pagination($condition, $parameters, $page, $subcategories_page)
	{
		$quotes_number = QuotesService::count($condition, $parameters);
		
		$pagination = new ModulePagination($page, $quotes_number, (int)QuotesConfig::load()->get_items_number_per_page());
		$pagination->set_url(QuotesUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name(), '%d', $subcategories_page));
		
		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		return $pagination;
	}
	
	private function get_subcategories_pagination($subcategories_number, $categories_number_per_page, $page, $subcategories_page)
	{
		$pagination = new ModulePagination($subcategories_page, $subcategories_number, (int)$categories_number_per_page);
		$pagination->set_url(QuotesUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name(), $page, '%d'));
		
		if ($pagination->current_page_is_empty() && $subcategories_page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		return $pagination;
	}
	
	private function get_category()
	{
		if ($this->category === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->category = QuotesService::get_categories_manager()->get_categories_cache()->get_category($id);
				} catch (CategoryNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
   					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->category = QuotesService::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
			}
		}
		return $this->category;
	}
	
	private function check_authorizations()
	{
		if (!QuotesAuthorizationsService::check_authorizations($this->get_category()->get_id())->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->tpl);
		
		$graphical_environment = $response->get_graphical_environment();
		
		if ($this->get_category()->get_id() != Category::ROOT_CATEGORY)
			$graphical_environment->set_page_title($this->get_category()->get_name(), $this->lang['module_title']);
		else
			$graphical_environment->set_page_title($this->lang['module_title']);
		
		$graphical_environment->get_seo_meta_data()->set_description($this->get_category()->get_description());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(QuotesUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name(), AppContext::get_request()->get_getint('page', 1)));
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], QuotesUrlBuilder::home());
		
		$categories = array_reverse(QuotesService::get_categories_manager()->get_parents($this->get_category()->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), QuotesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}
		
		return $response;
	}
	
	public static function get_view()
	{
		$object = new self();
		$object->init();
		$object->check_authorizations();
		$object->build_view(AppContext::get_request());
		return $object->tpl;
	}
}
?>