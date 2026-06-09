<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 4.0 - 2014 08 24
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DownloadCategoryController extends DefaultModuleController
{
	private $comments_config;
	private $content_management_config;

	private $category;

	protected function get_template_to_use()
	{
		return new FileTemplate('download/DownloadSeveralItemsController.tpl');
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
		$mode = $request->get_getstring('sort', $this->config->get_items_default_sort_mode());
		$field = $request->get_getstring('field', DownloadItem::SORT_FIELDS_URL_VALUES[$this->config->get_items_default_sort_field()]);
		$page = $request->get_getint('page', 1);
		$subcategories_page = $request->get_getint('subcategories_page', 1);

		$subcategories = CategoriesService::get_categories_manager('download')->get_categories_cache()->get_children($this->get_category()->get_id(), CategoriesService::get_authorized_categories($this->get_category()->get_id(), $this->config->is_summary_displayed_to_guests(), 'download'));
		$subcategories_pagination = $this->get_subcategories_pagination(count($subcategories), $this->config->get_categories_per_page(), $field, $mode, $page, $subcategories_page);

		$categories_number_displayed = 0;
		foreach ($subcategories as $id => $category)
		{
			$categories_number_displayed++;

			if ($categories_number_displayed > $subcategories_pagination->get_display_from() && $categories_number_displayed <= ($subcategories_pagination->get_display_from() + $subcategories_pagination->get_number_items_per_page()))
			{
				$category_thumbnail = $category->get_thumbnail()->rel();

				$this->view->assign_block_vars('sub_categories_list', [
					'C_CATEGORY_THUMBNAIL' => !empty($category_thumbnail),
					'C_SEVERAL_ITEMS'      => $category->get_elements_number() > 1,

					'CATEGORY_ID'          => $category->get_id(),
					'CATEGORY_NAME'        => $category->get_name(),
					'CATEGORY_PARENT_ID'   => $category->get_id_parent(),
					'CATEGORY_SUB_ORDER'   => $category->get_order(),
					'U_CATEGORY_THUMBNAIL' => $category_thumbnail,
					'ITEMS_NUMBER'         => $category->get_elements_number(),
					'U_CATEGORY'           => DownloadUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel()
				]);
			}
		}

		$condition = 'WHERE id_category = :id_category
		AND (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))';
		$parameters = [
			'id_category' => $this->get_category()->get_id(),
			'timestamp_now' => $now->get_timestamp()
		];

		$pagination = $this->get_pagination($condition, $parameters, $field, TextHelper::strtolower($mode), $page, $subcategories_page);

		$sort_mode = TextHelper::strtoupper($mode);
		$sort_mode = (in_array($sort_mode, [DownloadItem::ASC, DownloadItem::DESC]) ? $sort_mode : $this->config->get_items_default_sort_mode());

		if (in_array($field, DownloadItem::SORT_FIELDS_URL_VALUES))
			$sort_field = array_search($field, DownloadItem::SORT_FIELDS_URL_VALUES);
		else
			$sort_field = $this->config->get_items_default_sort_field();

		$result = PersistenceContext::get_querier()->select('SELECT download.*, member.*, com.comments_number, notes.average_notes, notes.notes_number, note.note
		FROM ' . DownloadSetup::$download_table . ' download
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = download.author_user_id
		LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = download.id AND com.module_id = \'download\'
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = download.id AND notes.module_name = \'download\'
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = download.id AND note.module_name = \'download\' AND note.user_id = :user_id
		' . $condition . '
		ORDER BY ' . $sort_field . ' ' . $sort_mode . '
		LIMIT :number_items_per_page OFFSET :display_from', array_merge($parameters, [
			'user_id' => AppContext::get_current_user()->get_id(),
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		]));

		$category_description = FormatingHelper::second_parse($this->get_category()->get_description());

		$this->view->put_all([
			'C_ITEMS'                    => $result->get_rows_count() > 0,
			'C_SEVERAL_ITEMS'            => $result->get_rows_count() > 1,
			'C_GRID_VIEW'                => $this->config->get_display_type() == DownloadConfig::GRID_VIEW,
			'C_LIST_VIEW'                => $this->config->get_display_type() == DownloadConfig::LIST_VIEW,
			'C_TABLE_VIEW'               => $this->config->get_display_type() == DownloadConfig::TABLE_VIEW,
			'C_FULL_ITEM_DISPLAY'        => $this->config->is_full_item_displayed(),
			'C_CATEGORY_DESCRIPTION'     => !empty($category_description),
			'C_AUTHOR_DISPLAYED'         => $this->config->is_author_displayed(),
			'C_ENABLED_COMMENTS'         => $this->comments_config->module_comments_is_enabled('download'),
			'C_ENABLED_NOTATION'         => $this->content_management_config->module_notation_is_enabled('download'),
			'C_ENABLED_VIEWS_NUMBER'     => $this->config->get_enabled_views_number(),
			'C_CONTROLS'                 => DownloadAuthorizationsService::check_authorizations($this->get_category()->get_id())->moderation(),
			'C_PAGINATION'               => $pagination->has_several_pages(),
			'C_CATEGORY'                 => true,
			'C_CATEGORY_THUMBNAIL' 		 => !$this->get_category()->get_id() == Category::ROOT_CATEGORY && !empty($this->get_category()->get_thumbnail()->rel()),
			'C_ROOT_CATEGORY'            => $this->get_category()->get_id() == Category::ROOT_CATEGORY,
			'C_HIDE_NO_ITEM_MESSAGE'     => $this->get_category()->get_id() == Category::ROOT_CATEGORY && ($categories_number_displayed != 0 || !empty($category_description)),
			'C_SUB_CATEGORIES'           => $categories_number_displayed > 0,
			'C_SUBCATEGORIES_PAGINATION' => $subcategories_pagination->has_several_pages(),

			'CATEGORIES_PER_ROW'       => $this->config->get_categories_per_row(),
			'ITEMS_PER_ROW'            => $this->config->get_items_per_row(),
			'SUBCATEGORIES_PAGINATION' => $subcategories_pagination->display(),
			'PAGINATION'               => $pagination->display(),
			'TABLE_COLSPAN'            => 4 + (int)$this->comments_config->module_comments_is_enabled('download') + (int)$this->content_management_config->module_notation_is_enabled('download'),
			'CATEGORY_ID'              => $this->get_category()->get_id(),
			'CATEGORY_NAME'            => $this->get_category()->get_name(),
			'CATEGORY_PARENT_ID' 	   => $this->get_category()->get_id_parent(),
			'CATEGORY_SUB_ORDER' 	   => $this->get_category()->get_order(),
			'CATEGORY_DESCRIPTION'     => $category_description,

			'U_CATEGORY_THUMBNAIL' => $this->get_category()->get_thumbnail()->rel(),
			'U_EDIT_CATEGORY'  	   => $this->get_category()->get_id() == Category::ROOT_CATEGORY ? DownloadUrlBuilder::configuration()->rel() : CategoriesUrlBuilder::edit($this->get_category()->get_id(), 'download')->rel()
		]);

		while ($row = $result->fetch())
		{
			$item = new DownloadItem();
			$item->set_properties($row);

			$keywords = $item->get_keywords();
			$has_keywords = count($keywords) > 0;

			$this->view->assign_block_vars('items', array_merge($item->get_template_vars(), [
				'C_KEYWORDS' => $has_keywords
			]));

			if ($has_keywords)
				$this->build_keywords_view($keywords);

			foreach ($item->get_sources() as $name => $url)
			{
				$this->view->assign_block_vars('items.sources', $item->get_array_tpl_source_vars($name));
			}
		}
		$result->dispose();

		$this->build_sorting_form($field, TextHelper::strtolower($sort_mode));
	}

	private function build_sorting_form($field, $mode)
	{
		$form = new HTMLForm(self::class, '', false);
		$form->set_css_class('options');

		$fieldset = new FormFieldsetHorizontal('filters', ['description' => $this->lang ['common.sort.by']]);
		$form->add_fieldset($fieldset);

		$sort_options = [
			new FormFieldSelectChoiceOption($this->lang['common.sort.by.update'], DownloadItem::SORT_FIELDS_URL_VALUES[DownloadItem::SORT_UPDATE_DATE], ['data_option_icon' => 'far fa-calendar-plus']),
			new FormFieldSelectChoiceOption($this->lang['common.sort.by.date'], DownloadItem::SORT_FIELDS_URL_VALUES[DownloadItem::SORT_DATE], ['data_option_icon' => 'far fa-calendar-alt']),
			new FormFieldSelectChoiceOption($this->lang['common.sort.by.alphabetic'], DownloadItem::SORT_FIELDS_URL_VALUES[DownloadItem::SORT_ALPHABETIC], ['data_option_icon' => 'fa fa-sort-alpha-up']),
			new FormFieldSelectChoiceOption($this->lang['common.sort.by.author'], DownloadItem::SORT_FIELDS_URL_VALUES[DownloadItem::SORT_AUTHOR], ['data_option_icon' => 'fa fa-user']),
			new FormFieldSelectChoiceOption($this->lang['download.downloads.number'], DownloadItem::SORT_FIELDS_URL_VALUES[DownloadItem::SORT_DOWNLOADS_NUMBER], ['data_option_icon' => 'fa fa-download']),
			new FormFieldSelectChoiceOption($this->lang['common.sort.by.views.number'], DownloadItem::SORT_FIELDS_URL_VALUES[DownloadItem::SORT_VIEWS_NUMBERS], ['data_option_icon' => 'far fa-eye'])
		];

		if ($this->comments_config->module_comments_is_enabled('download'))
			$sort_options[] = new FormFieldSelectChoiceOption($this->lang['common.sort.by.comments.number'], DownloadItem::SORT_FIELDS_URL_VALUES[DownloadItem::SORT_COMMENTS_NUMBER], ['data_option_icon' => 'far fa-comments']);

		if ($this->content_management_config->module_notation_is_enabled('download'))
			$sort_options[] = new FormFieldSelectChoiceOption($this->lang['common.sort.by.best.note'], DownloadItem::SORT_FIELDS_URL_VALUES[DownloadItem::SORT_NOTATION], ['data_option_icon' => 'far fa-star']);

		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_fields', '', $field, $sort_options,
			['select_to_list' => true, 'events' => ['change' => 'document.location = "'. DownloadUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name())->rel() .'" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();']]
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_mode', '', $mode,
			[
				new FormFieldSelectChoiceOption($this->lang['common.sort.asc'], 'asc', ['data_option_icon' => 'fa fa-arrow-up']),
				new FormFieldSelectChoiceOption($this->lang['common.sort.desc'], 'desc', ['data_option_icon' => 'fa fa-arrow-down'])
			],
			['select_to_list' => true, 'events' => ['change' => 'document.location = "' . DownloadUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name())->rel() . '" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();']]
		));

		$this->view->put('SORT_FORM', $form->display());
	}

	private function get_pagination($condition, $parameters, $field, $mode, $page, $subcategories_page)
	{
		$items_number = DownloadService::count($condition, $parameters);

		$pagination = new ModulePagination($page, $items_number, (int)DownloadConfig::load()->get_items_per_page());
		$pagination->set_url(DownloadUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name(), $field, $mode, '%d', $subcategories_page));

		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
	}

	private function get_subcategories_pagination($subcategories_number, $categories_per_page, $field, $mode, $page, $subcategories_page)
	{
		$pagination = new ModulePagination($subcategories_page, $subcategories_number, (int)$categories_per_page);
		$pagination->set_url(DownloadUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name(), $field, $mode, $page, '%d'));

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
					$this->category = CategoriesService::get_categories_manager('download')->get_categories_cache()->get_category($id);
				} catch (CategoryNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->category = CategoriesService::get_categories_manager('download')->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
			}
		}
		return $this->category;
	}

	private function build_keywords_view($keywords)
	{
		$nbr_keywords = count($keywords);

		$i = 1;
		foreach ($keywords as $keyword)
		{
			$this->view->assign_block_vars('items.keywords', [
				'C_SEPARATOR' => $i < $nbr_keywords,
				'NAME' => $keyword->get_name(),
				'URL'  => DownloadUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			]);
			$i++;
		}
	}

	private function check_authorizations()
	{
		if (AppContext::get_current_user()->is_guest())
		{
			if (($this->config->is_summary_displayed_to_guests() && (!Authorizations::check_auth(RANK_TYPE, User::MEMBER_LEVEL, $this->get_category()->get_authorizations(), Category::READ_AUTHORIZATIONS) || $this->config->get_display_type() == DownloadConfig::LIST_VIEW)) || (!$this->config->is_summary_displayed_to_guests() && !DownloadAuthorizationsService::check_authorizations($this->get_category()->get_id())->read()))
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!DownloadAuthorizationsService::check_authorizations($this->get_category()->get_id())->read())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
	}

	private function generate_response(HTTPRequestCustom $request)
	{
		$sort_field = $request->get_getstring('field', DownloadItem::SORT_FIELDS_URL_VALUES[$this->config->get_items_default_sort_field()]);
		$sort_mode = $request->get_getstring('sort', $this->config->get_items_default_sort_mode());
		$page = $request->get_getint('page', 1);
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();

		if ($this->get_category()->get_id() != Category::ROOT_CATEGORY)
			$graphical_environment->set_page_title($this->get_category()->get_name(), $this->lang['download.module.title'], $page);
		else
			$graphical_environment->set_page_title($this->lang['download.module.title'], '', $page);

		$description = $this->get_category()->get_description();
		if (empty($description))
			$description = StringVars::replace_vars($this->lang['download.seo.description.root'], ['site' => GeneralConfig::load()->get_site_name()]) . ($this->get_category()->get_id() != Category::ROOT_CATEGORY ? ' ' . $this->lang['category.category'] . ' ' . $this->get_category()->get_name() : '');
		$graphical_environment->get_seo_meta_data()->set_description($description, $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(DownloadUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name(), $sort_field, $sort_mode, $page));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['download.module.title'], DownloadUrlBuilder::home());

		$categories = array_reverse(CategoriesService::get_categories_manager('download')->get_parents($this->get_category()->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), DownloadUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name(), $sort_field, $sort_mode, ($category->get_id() == $this->get_category()->get_id() ? $page : 1)));
		}

		return $response;
	}

	public static function get_view()
	{
		$object = new self('download');
		$object->init();
		$object->check_authorizations();
		$object->build_view(AppContext::get_request());
		return $object->view;
	}
}
?>
