<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 08 25
 * @since       PHPBoost 6.0 - 2021 08 22
*/

class SpotsCategoryController extends DefaultModuleController
{
	private $category;

	protected function get_template_to_use()
	{
		return new FileTemplate('spots/SpotsSeveralItemsController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->build_view($request);

		return $this->generate_response();
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$now = new Date();

		$authorized_categories = CategoriesService::get_authorized_categories($this->get_category()->get_id(), '', 'spots');

		$page = AppContext::get_request()->get_getint('page', 1);
		$subcategories_page = AppContext::get_request()->get_getint('subcategories_page', 1);

		$subcategories = CategoriesService::get_categories_manager('spots')->get_categories_cache()->get_children($this->get_category()->get_id(), CategoriesService::get_authorized_categories($this->get_category()->get_id(),'', 'spots'));
		$subcategories_pagination = $this->get_subcategories_pagination(count($subcategories), $this->config->get_categories_per_page(), $page, $subcategories_page);


		$nbr_cat_displayed = 0;
		foreach ($subcategories as $id => $category)
		{
			$nbr_cat_displayed++;

			if ($nbr_cat_displayed > $subcategories_pagination->get_display_from() && $nbr_cat_displayed <= ($subcategories_pagination->get_display_from() + $subcategories_pagination->get_number_items_per_page()))
			{
				$this->view->assign_block_vars('sub_categories_list', array(
					'C_SEVERAL_ITEMS' => $category->get_elements_number() > 1,

					'CATEGORY_ID'            => $category->get_id(),
					'CATEGORY_NAME'          => $category->get_name(),
					'ITEMS_NUMBER'           => $category->get_elements_number(),
					'CATEGORY_COLOR' 	 	 => $category->get_color(),
					'CATEGORY_INNER_ICON' 	 => !empty($category->get_inner_icon()) ? $category->get_inner_icon() : $this->config->get_default_inner_icon(),

					'U_CATEGORY' => SpotsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel()
				));
			}
		}

		$condition = 'WHERE id_category IN :authorised_categories AND published = 1';

		$parameters = array(
			'authorised_categories' => $authorized_categories,
			'id_category' => $this->get_category()->get_id(),
			'timestamp_now' => $now->get_timestamp()
		);

		$pagination = $this->get_pagination($condition, $parameters, $page, $subcategories_page);

		$result = PersistenceContext::get_querier()->select('SELECT spots.*, member.*
		FROM '. SpotsSetup::$spots_table .' spots
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = spots.author_user_id
		' . $condition . '
		ORDER BY spots.title ASC
		LIMIT :number_items_per_page OFFSET :display_from', array_merge($parameters, array(
			'user_id' => AppContext::get_current_user()->get_id(),
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		)));

		$root_category = $this->get_category()->get_id() == Category::ROOT_CATEGORY;

		if(!$root_category){
			$category_address_values = TextHelper::deserialize($this->get_category()->get_category_address());
			$this->view->put_all(array(
				'CATEGORY_LATITUDE' => (!empty($this->get_category()->get_category_address()) || TextHelper::is_serialized($this->get_category()->get_category_address())) ? $category_address_values['latitude'] : GoogleMapsConfig::load()->get_default_marker_latitude(),
				'CATEGORY_LONGITUDE' => (!empty($this->get_category()->get_category_address()) || TextHelper::is_serialized($this->get_category()->get_category_address())) ? $category_address_values['longitude'] : GoogleMapsConfig::load()->get_default_marker_longitude(),
			));
		}

		$this->view->put_all(array(
			'C_CATEGORY'                 => true,
			'C_ITEMS'                    => $result->get_rows_count() > 0,
            'C_GMAP_ENABLED'             => SpotsService::is_gmap_enabled(),
            'C_SEVERAL_ITEMS'            => $result->get_rows_count() > 1,
			'C_GRID_VIEW'                => $this->config->get_display_type() == SpotsConfig::GRID_VIEW,
			'C_TABLE_VIEW'               => $this->config->get_display_type() == SpotsConfig::TABLE_VIEW,
			'C_CATEGORY_DESCRIPTION'     => !empty($category_description),
			'C_CONTROLS'                 => CategoriesAuthorizationsService::check_authorizations($this->get_category()->get_id())->moderation(),
			'C_PAGINATION'               => $pagination->has_several_pages(),
			'C_ROOT_CATEGORY'            => $root_category,
			'C_HIDE_NO_ITEM_MESSAGE'     => $root_category && ($nbr_cat_displayed != 0 || !empty($category_description)),
			'C_SUB_CATEGORIES'           => $nbr_cat_displayed > 0,
			'C_SUBCATEGORIES_PAGINATION' => $subcategories_pagination->has_several_pages(),

			'MODULE_NAME'              => $this->config->get_module_name(),
			'ROOT_CATEGORY_DESC'       => $this->config->get_root_category_description(),
			'CATEGORY_NAME'            => $this->get_category()->get_name(),
			'GMAP_API_KEY'             => GoogleMapsConfig::load()->get_api_key(),
			'DEFAULT_LAT'              => GoogleMapsConfig::load()->get_default_marker_latitude(),
			'DEFAULT_LNG'              => GoogleMapsConfig::load()->get_default_marker_longitude(),
			'SUBCATEGORIES_PAGINATION' => $subcategories_pagination->display(),
			'PAGINATION'               => $pagination->display(),
			'CATEGORIES_PER_ROW'       => $this->config->get_categories_per_row(),
			'ITEMS_PER_ROW'            => $this->config->get_items_per_row(),
			'ID_CAT'                   => $this->get_category()->get_id(),

			'U_EDIT_CATEGORY' => $root_category ? SpotsUrlBuilder::configuration()->rel() : CategoriesUrlBuilder::edit($this->get_category()->get_id(), 'spots')->rel()
		));

		while ($row = $result->fetch())
		{
			$item = new SpotsItem();
			$item->set_properties($row);
			$this->view->assign_block_vars('items', array_merge($item->get_template_vars()));
		}
		$result->dispose();

		$this->build_category_view($request);
	}

	private function build_category_view(HTTPRequestCustom $request)
	{
		$now = new Date();
		$page = AppContext::get_request()->get_getint('page', 1);
		$condition = 'WHERE id_category = :id_category AND published = 1';

		$parameters = array(
			'id_category' => $this->get_category()->get_id(),
			'timestamp_now' => $now->get_timestamp()
		);

		$pagination = $this->get_pagination($condition, $parameters, $page, '');

		$result = PersistenceContext::get_querier()->select('SELECT spots.*, member.*
		FROM '. SpotsSetup::$spots_table .' spots
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = spots.author_user_id
		' . $condition . '
		ORDER BY spots.title ASC
		LIMIT :number_items_per_page OFFSET :display_from', array_merge($parameters, array(
			'user_id' => AppContext::get_current_user()->get_id(),
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		)));

		while ($row = $result->fetch())
		{
			$item = new SpotsItem();
			$item->set_properties($row);
			$this->view->assign_block_vars('self_items', array_merge($item->get_template_vars()));
		}
		$result->dispose();

	}

	private function get_pagination($condition, $parameters, $page, $subcategories_page)
	{
		$items_number = SpotsService::count($condition, $parameters);

		$pagination = new ModulePagination($page, $items_number, (int)SpotsConfig::load()->get_items_per_page());
		$pagination->set_url(SpotsUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name(), '%d', $subcategories_page));

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
		$pagination->set_url(SpotsUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name(), $page, '%d'));

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
			$id = AppContext::get_request()->get_getint('id_category', 0);
			if (!empty($id))
			{
				try {
					$this->category = CategoriesService::get_categories_manager('spots')->get_categories_cache()->get_category($id);
				} catch (CategoryNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
   					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->category = CategoriesService::get_categories_manager('spots')->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
			}
		}
		return $this->category;
	}

	private function check_authorizations()
	{
		if (AppContext::get_current_user()->is_guest())
		{
			if (!Authorizations::check_auth(RANK_TYPE, User::MEMBER_LEVEL, $this->get_category()->get_authorizations(), Category::READ_AUTHORIZATIONS) || !CategoriesAuthorizationsService::check_authorizations($this->get_category()->get_id())->read())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!CategoriesAuthorizationsService::check_authorizations($this->get_category()->get_id())->read())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();

		if ($this->get_category()->get_id() != Category::ROOT_CATEGORY)
			$graphical_environment->set_page_title($this->get_category()->get_name(), $this->config->get_module_name());
		else
			$graphical_environment->set_page_title($this->config->get_module_name());

		// $graphical_environment->get_seo_meta_data()->set_description($this->get_category()->get_description());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(SpotsUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name(), AppContext::get_request()->get_getint('page', 1)));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->config->get_module_name(), SpotsUrlBuilder::home());

		$categories = array_reverse(CategoriesService::get_categories_manager('spots')->get_parents($this->get_category()->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), SpotsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}

		return $response;
	}

	public static function get_view()
	{
		$object = new self();
		$object->check_authorizations();
		$object->build_view(AppContext::get_request());
		return $object->view;
	}
}
?>
