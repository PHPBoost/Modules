<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 12 14
 * @since       PHPBoost 5.0 - 2016 02 18
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class QuotesCategoryController extends DefaultModuleController
{
	private $category;

	protected function get_template_to_use()
	{
		return new FileTemplate('quotes/QuotesSeveralItemsController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->build_view($request);

		return $this->generate_response($request);
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$authorized_categories = $this->get_authorized_categories();
		$page = $request->get_getint('page', 1);
		$subcategories_page = $request->get_getint('subcategories_page', 1);

		//Children categories
		$subcategories = CategoriesService::get_categories_manager('quotes')->get_categories_cache()->get_children($this->get_category()->get_id(), CategoriesService::get_authorized_categories($this->get_category()->get_id(), true, 'quotes'));
		$subcategories_pagination = $this->get_subcategories_pagination(count($subcategories), $this->config->get_categories_per_page(), $page, $subcategories_page);

		$nbr_cat_displayed = 0;
		foreach ($subcategories as $id => $category)
		{
			$nbr_cat_displayed++;

			if ($nbr_cat_displayed > $subcategories_pagination->get_display_from() && $nbr_cat_displayed <= ($subcategories_pagination->get_display_from() + $subcategories_pagination->get_number_items_per_page()))
			{
				$category_thumbnail = $category->get_thumbnail()->rel();

				$this->view->assign_block_vars('sub_categories_list', array(
					'C_CATEGORY_THUMBNAIL' 	  => !empty($category_thumbnail),
					'C_MORE_THAN_ONE_ELEMENT' => $category->get_elements_number() > 1,

					'CATEGORY_ID' 	  => $category->get_id(),
					'CATEGORY_NAME'   => $category->get_name(),
					'CATEGORY_PARENT' => $category->get_id_parent(),
					'CATEGORY_ORDER'  => $category->get_order(),
					'ELEMENTS_NUMBER' => $category->get_elements_number(),

					'U_CATEGORY_THUMBNAIL' => $category_thumbnail,
					'U_CATEGORY' 		   => QuotesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel()
				));
			}
		}

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

		$this->view->put_all(array(
			'C_ITEMS'                    => $result->get_rows_count() > 0,
			'C_CATEGORY_DESCRIPTION'     => !empty($category_description),
			'C_CATEGORY'                 => true,
			'C_CATEGORY_THUMBNAIL'       => !$this->get_category()->get_id() == Category::ROOT_CATEGORY && !empty($this->get_category()->get_thumbnail()->rel()),
			'C_ROOT_CATEGORY'            => $this->get_category()->get_id() == Category::ROOT_CATEGORY,
			'C_HIDE_NO_ITEM_MESSAGE'     => $this->get_category()->get_id() == Category::ROOT_CATEGORY && ($nbr_cat_displayed != 0 || !empty($category_description)),
			'C_SUB_CATEGORIES'           => $nbr_cat_displayed > 0,
			'C_PAGINATION'               => $pagination->has_several_pages(),
			'C_SUBCATEGORIES_PAGINATION' => $subcategories_pagination->has_several_pages(),

			'SUBCATEGORIES_PAGINATION' => $subcategories_pagination->display(),
			'PAGINATION'               => $pagination->display(),
			'CATEGORIES_PER_ROW'       => $this->config->get_categories_per_row(),
			'CATEGORY_ID'              => $this->get_category()->get_id(),
			'CATEGORY_NAME'            => $this->get_category()->get_name(),
			'CATEGORY_PARENT_ID'   	   => $this->get_category()->get_id_parent(),
			'CATEGORY_SUB_ORDER'   	   => $this->get_category()->get_order(),
			'CATEGORY_DESCRIPTION'     => $category_description,

			'U_CATEGORY_THUMBNAIL' => $this->get_category()->get_thumbnail()->rel(),
			'U_EDIT_CATEGORY' 	   => $this->get_category()->get_id() == Category::ROOT_CATEGORY ? QuotesUrlBuilder::configuration()->rel() : CategoriesUrlBuilder::edit($this->get_category()->get_id(), 'quotes')->rel()
		));

		while ($row = $result->fetch())
		{
			$item = new QuotesItem();
			$item->set_properties($row);

			$this->view->assign_block_vars('items', $item->get_template_vars());
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
			$categories = CategoriesService::get_categories_manager('quotes')->get_children($category->get_id(), $search_category_children_options);
			$authorized_categories = array_keys($categories);
		}
		return $authorized_categories;
	}

	private function get_pagination($condition, $parameters, $page, $subcategories_page)
	{
		$items_number = QuotesService::count($condition, $parameters);

		$pagination = new ModulePagination($page, $items_number, (int)QuotesConfig::load()->get_items_per_page());
		$pagination->set_url(QuotesUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name(), '%d', $subcategories_page));

		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
	}

	private function get_subcategories_pagination($subcategories_number, $categories_per_page, $page, $subcategories_page)
	{
		$pagination = new ModulePagination($subcategories_page, $subcategories_number, (int)$categories_per_page);
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
					$this->category = CategoriesService::get_categories_manager('quotes')->get_categories_cache()->get_category($id);
				} catch (CategoryNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
   					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->category = CategoriesService::get_categories_manager('quotes')->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
			}
		}
		return $this->category;
	}

	private function check_authorizations()
	{
		if (!CategoriesAuthorizationsService::check_authorizations($this->get_category()->get_id())->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response(HTTPRequestCustom $request)
	{
		$page = $request->get_getint('page', 1);
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();

		if ($this->get_category()->get_id() != Category::ROOT_CATEGORY)
			$graphical_environment->set_page_title($this->get_category()->get_name(), $this->lang['quotes.module.title'], $page);
		else
			$graphical_environment->set_page_title($this->lang['quotes.module.title'], '', $page);

		$description = $this->get_category()->get_description();
		if (empty($description))
			$description = StringVars::replace_vars($this->lang['quotes.seo.description.root'], array('site' => GeneralConfig::load()->get_site_name())) . ($this->get_category()->get_id() != Category::ROOT_CATEGORY ? ' ' . LangLoader::get_message('category.category', 'category-lang') . ' ' . $this->get_category()->get_name() : '');
		$graphical_environment->get_seo_meta_data()->set_description($description, $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(QuotesUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name(), $page));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['quotes.module.title'], QuotesUrlBuilder::home());

		$categories = array_reverse(CategoriesService::get_categories_manager('quotes')->get_parents($this->get_category()->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), QuotesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name(), ($category->get_id() == $this->get_category()->get_id() ? $page : 1)));
		}

		return $response;
	}

	public static function get_view()
	{
		$object = new self('quotes');
		$object->check_authorizations();
		$object->build_view(AppContext::get_request());
		return $object->view;
	}
}
?>
