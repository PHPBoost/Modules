<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 12 14
 * @since       PHPBoost 5.1 - 2018 03 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class SmalladsCategoryController extends DefaultModuleController
{
	private $category;
	private $comments_config;
	private $content_management_config;

	protected function get_template_to_use()
	{
		return new FileTemplate('smallads/SmalladsSeveralItemsController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->check_authorizations();
		$this->build_view($request);
		return $this->generate_response($request);
	}

	private function init()
	{
		$this->comments_config = CommentsConfig::load();
		$this->content_management_config = ContentManagementConfig::load();
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$now = new Date();

		$this->build_items_listing_view($now);
		$this->build_sorting_smallad_type();
		$this->build_category_list();
	}

	private function build_category_list()
	{
		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, $this->config->are_summaries_displayed_to_guests(), 'smallads');

		$result_cat = PersistenceContext::get_querier()->select('SELECT smallads_cat.*
		FROM '. SmalladsSetup::$smallads_cats_table .' smallads_cat
		WHERE smallads_cat.id IN :authorized_categories
		ORDER BY id', array(
			'authorized_categories' => $authorized_categories
		));

		while ($row_cat = $result_cat->fetch())
		{
			$this->view->assign_block_vars('categories', array(
				'ID'         => $row_cat['id'],
				'ID_PARENT'  => $row_cat['id_parent'],
				'SUB_ORDER'  => $row_cat['c_order'],
				'NAME'       => $row_cat['name'],
				'U_CATEGORY' => SmalladsUrlBuilder::display_category($row_cat['id'], $row_cat['rewrited_name'])->rel(),
				'C_ITEMS'    => $result_cat->get_rows_count() > 0,
			));
		}
		$result_cat->dispose();
	}

	private function build_items_listing_view(Date $now)
	{
		$authorized_categories = CategoriesService::get_authorized_categories($this->get_category()->get_id(), $this->config->are_summaries_displayed_to_guests(), 'smallads');

		$condition = 'WHERE id_category IN :authorized_categories
		AND (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))
		AND archived = 0';
		$parameters = array(
			'authorized_categories' => $authorized_categories,
			'timestamp_now' => $now->get_timestamp()
		);

		$result = PersistenceContext::get_querier()->select('SELECT smallads.*, member.*, com.comments_number
		FROM ' . SmalladsSetup::$smallads_table . ' smallads
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = smallads.author_user_id
		LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = smallads.id AND com.module_id = \'smallads\'

		' . $condition . '
		ORDER BY smallads.creation_date DESC
		', array_merge($parameters, array(
			'user_id' => AppContext::get_current_user()->get_id()
		)));

		$category_description = FormatingHelper::second_parse($this->get_category()->get_description());
		$category_thumbnail = $this->get_category()->get_thumbnail()->rel();

		$this->view->put_all(array(
			'C_CATEGORY'             => true,
			'C_CATEGORY_THUMBNAIL'   => !$this->get_category()->get_id() == Category::ROOT_CATEGORY && !empty($this->get_category()->get_thumbnail()->rel()),
			'C_ROOT_CATEGORY'        => $this->get_category()->get_id() == Category::ROOT_CATEGORY,
			'C_CATEGORY_THUMBNAIL'   => !empty($category_thumbnail),
			'C_CATEGORY_DESCRIPTION' => !empty($category_description),
			'CATEGORY_NAME'          => $this->get_category()->get_name(),
			'CATEGORY_PARENT_ID'   	 => $this->get_category()->get_id_parent(),
			'CATEGORY_SUB_ORDER'   	 => $this->get_category()->get_order(),
			'CATEGORY_DESCRIPTION'   => $category_description,
			'U_CATEGORY_THUMBNAIL'   => $category_thumbnail,

			'C_ITEMS'                => $result->get_rows_count() > 0,
			'C_SEVERAL_ITEMS'        => $result->get_rows_count() > 1,
			'C_ENABLED_FILTERS'		 => $this->config->are_sort_filters_enabled(),
			'C_GRID_VIEW'            => $this->config->get_display_type() == SmalladsConfig::GRID_VIEW,
			'C_LIST_VIEW'            => $this->config->get_display_type() == SmalladsConfig::LIST_VIEW,
			'C_TABLE_VIEW'           => $this->config->get_display_type() == SmalladsConfig::TABLE_VIEW,
			'C_LOCATION'			 => $this->config->is_location_displayed(),
			'C_COMMENTS_ENABLED'     => $this->comments_config->are_comments_enabled(),
			'C_DISPLAY_CAT_ICONS'    => $this->config->are_cat_icons_enabled(),
			'C_MODERATION'           => CategoriesAuthorizationsService::check_authorizations($this->get_category()->get_id())->moderation(),
			'C_USAGE_TERMS'	         => $this->config->are_usage_terms_displayed(),
			'C_PAGINATION'           => $result->get_rows_count() > $this->config->get_items_per_page(),

			'ITEMS_PER_ROW'          => $this->config->get_items_per_row(),
			'ITEMS_PER_PAGE'         => $this->config->get_items_per_page(),
			'ID_CATEGORY'            => $this->get_category()->get_id(),
			'U_EDIT_CATEGORY'        => $this->get_category()->get_id() == Category::ROOT_CATEGORY ? SmalladsUrlBuilder::categories_configuration()->rel() : CategoriesUrlBuilder::edit($this->get_category()->get_id(), 'smallads')->rel(),
			'U_USAGE_TERMS' 		 => SmalladsUrlBuilder::usage_terms()->rel(),
			'U_CATEGORY_THUMBNAIL' => $this->get_category()->get_thumbnail()->rel()
		));

		while($row = $result->fetch())
		{
			$item = new SmalladsItem();
			$item->set_properties($row);

			$this->build_keywords_view($item);

			$this->view->assign_block_vars('items', $item->get_template_vars());
			$this->build_sources_view($item);
		}
		$result->dispose();
	}

	private function build_sorting_smallad_type()
	{
		$smallad_types = $this->config->get_smallad_types();
		$type_nbr = count($smallad_types);
		if ($type_nbr)
		{
			$this->view->put('C_TYPES_FILTERS', $type_nbr > 0);

			$i = 1;
			foreach ($smallad_types as $name)
			{
				$this->view->assign_block_vars('types', array(
					'C_SEPARATOR'      => $i < $type_nbr,
					'TYPE_NAME'        => $name,
					'TYPE_NAME_FILTER' => Url::encode_rewrite(TextHelper::strtolower($name)),
				));
				$i++;
			}
		}
	}

	private function build_sources_view(SmalladsItem $item)
	{
		$sources = $item->get_sources();
		$nbr_sources = count($sources);
		if ($nbr_sources)
		{
			$this->view->put('items.C_SOURCES', $nbr_sources > 0);

			$i = 1;
			foreach ($sources as $name => $url)
			{
				$this->view->assign_block_vars('items.sources', array(
					'C_SEPARATOR' => $i < $nbr_sources,
					'NAME'        => $name,
					'URL'         => $url,
				));
				$i++;
			}
		}
	}

	private function get_category()
	{
		if ($this->category === null)
		{
			$id = AppContext::get_request()->get_getstring('id_category', 0);
			if (!empty($id))
			{
				try {
					$this->category = CategoriesService::get_categories_manager('smallads')->get_categories_cache()->get_category($id);
				} catch (CategoryNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->category = CategoriesService::get_categories_manager('smallads')->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
			}
		}
		return $this->category;
	}

	private function build_keywords_view(SmalladsItem $item)
	{
		$keywords = $item->get_keywords();
		$nbr_keywords = count($keywords);
		$this->view->put('C_KEYWORDS', $nbr_keywords > 0);

		$i = 1;
		foreach ($keywords as $keyword)
		{
			$this->view->assign_block_vars('keywords', array(
				'C_SEPARATOR' => $i < $nbr_keywords,
				'NAME'        => $keyword->get_name(),
				'URL'         => SmalladsUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
	}

	private function check_authorizations()
	{
		if (AppContext::get_current_user()->is_guest())
		{
			if (($this->config->are_summaries_displayed_to_guests() && !Authorizations::check_auth(RANK_TYPE, User::MEMBER_LEVEL, $this->get_category()->get_authorizations(), Category::READ_AUTHORIZATIONS)) || (!$this->config->are_summaries_displayed_to_guests() && !CategoriesAuthorizationsService::check_authorizations($this->get_category()->get_id())->read()))
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

	private function generate_response(HTTPRequestCustom $request)
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();

		if ($this->category->get_id() != Category::ROOT_CATEGORY)
			$graphical_environment->set_page_title($this->category->get_name(), $this->lang['smallads.module.title']);
		else
			$graphical_environment->set_page_title($this->lang['smallads.module.title'], '');

		$description = $this->category->get_description();
		if (empty($description))
			$description = StringVars::replace_vars($this->lang['smallads.seo.description.root'], array('site' => GeneralConfig::load()->get_site_name())) . ($this->category->get_id() != Category::ROOT_CATEGORY ? ' ' . LangLoader::get_message('category.category', 'category-lang') . ' ' . $this->category->get_name() : '');
		$graphical_environment->get_seo_meta_data()->set_description($description);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(SmalladsUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name()));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['smallads.module.title'], SmalladsUrlBuilder::home());

		$categories = array_reverse(CategoriesService::get_categories_manager('smallads')->get_parents($this->category->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), SmalladsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name(), $category->get_id()));
		}

		return $response;
	}

	public static function get_view()
	{
		$object = new self('smallads');
		$object->check_authorizations();
		$object->build_view(AppContext::get_request());
		return $object->view;
	}
}
?>
