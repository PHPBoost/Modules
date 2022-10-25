<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 10 17
 * @since       PHPBoost 6.0 - 2022 08 26
 */

class RecipeTagController extends DefaultModuleController
{
	private $keyword;
	private $comments_config;
	private $content_management_config;

	protected function get_template_to_use()
	{
		return new FileTemplate('recipe/RecipeSeveralItemsController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->build_view($request);

		return $this->generate_response($request);
	}

	public function init()
	{
		$this->comments_config = CommentsConfig::load();
		$this->content_management_config = ContentManagementConfig::load();
	}

	public function build_view(HTTPRequestCustom $request)
	{
		$now = new Date();

		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, $this->config->is_summary_displayed_to_guests());
		$mode = $request->get_getstring('sort', $this->config->get_items_default_sort_mode());
		$field = $request->get_getstring('field', RecipeItem::SORT_FIELDS_URL_VALUES[$this->config->get_items_default_sort_field()]);

		$condition = 'WHERE relation.id_keyword = :id_keyword
		AND id_category IN :authorized_categories
		AND (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))';
		$parameters = array(
			'id_keyword' => $this->get_keyword()->get_id(),
			'authorized_categories' => $authorized_categories,
			'timestamp_now' => $now->get_timestamp()
		);

		$page = $request->get_getint('page', 1);
		$pagination = $this->get_pagination($condition, $parameters, $field, TextHelper::strtolower($mode), $page);

		$sort_mode = TextHelper::strtoupper($mode);
		$sort_mode = (in_array($sort_mode, array(RecipeItem::ASC, RecipeItem::DESC)) ? $sort_mode : $this->config->get_items_default_sort_mode());

		if (in_array($field, RecipeItem::SORT_FIELDS_URL_VALUES))
			$sort_field = array_search($field, RecipeItem::SORT_FIELDS_URL_VALUES);
		else
			$sort_field = $this->config->get_items_default_sort_field();

		$result = PersistenceContext::get_querier()->select('SELECT recipe.*, member.*, com.comments_number, notes.average_notes, notes.notes_number, note.note
		FROM ' . RecipeSetup::$recipe_table . ' recipe
		LEFT JOIN ' . DB_TABLE_KEYWORDS_RELATIONS . ' relation ON relation.module_id = \'recipe\' AND relation.id_in_module = recipe.id
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = recipe.author_user_id
		LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = recipe.id AND com.module_id = \'recipe\'
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = recipe.id AND notes.module_name = \'recipe\'
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = recipe.id AND note.module_name = \'recipe\' AND note.user_id = :user_id
		' . $condition . '
		ORDER BY ' . $sort_field . ' ' . $sort_mode . '
		LIMIT :number_items_per_page OFFSET :display_from', array_merge($parameters, array(
			'user_id' => AppContext::get_current_user()->get_id(),
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		)));

		$this->view->put_all(array(
			'C_TAG_ITEMS'         => true,
			'C_ITEMS'             => $result->get_rows_count() > 0,
			'C_SEVERAL_ITEMS'     => $result->get_rows_count() > 1,
			'C_GRID_VIEW'         => $this->config->get_display_type() == RecipeConfig::GRID_VIEW,
			'C_TABLE_VIEW'        => $this->config->get_display_type() == RecipeConfig::TABLE_VIEW,
			'ITEMS_PER_ROW'       => $this->config->get_items_per_row(),
			'C_ENABLED_COMMENTS'  => $this->comments_config->module_comments_is_enabled('recipe'),
			'C_ENABLED_NOTATION'  => $this->content_management_config->module_notation_is_enabled('recipe'),
			'C_AUTHOR_DISPLAYED'  => $this->config->is_author_displayed(),
			'C_PAGINATION'        => $pagination->has_several_pages(),

			'C_CATEGORY_DESCRIPTION' => !empty($category_description),
			'CATEGORIES_PER_ROW'     => $this->config->get_categories_per_row(),
			'PAGINATION'             => $pagination->display(),
			'TABLE_COLSPAN'          => 4 + (int)$this->comments_config->module_comments_is_enabled('recipe') + (int)$this->content_management_config->module_notation_is_enabled('recipe'),
			'CATEGORY_NAME'          => $this->get_keyword()->get_name()
		));

		while ($row = $result->fetch())
		{
			$item = new RecipeItem();
			$item->set_properties($row);

			$keywords = $item->get_keywords();
			$has_keywords = count($keywords) > 0;

			$this->view->assign_block_vars('items', array_merge($item->get_template_vars(), array(
				'C_KEYWORDS' => $has_keywords
			)));

			if ($has_keywords)
				$this->build_keywords_view($keywords);
		}
		$result->dispose();
		$this->build_sorting_form($field, TextHelper::strtolower($sort_mode));
	}

	private function build_sorting_form($field, $mode)
	{
		$form = new HTMLForm(__CLASS__, '', false);
		$form->set_css_class('options');

		$fieldset = new FormFieldsetHorizontal('filters', array('description' => $this->lang['common.sort.by']));
		$form->add_fieldset($fieldset);

		$sort_options = array(
			new FormFieldSelectChoiceOption($this->lang['common.sort.by.update'], RecipeItem::SORT_FIELDS_URL_VALUES[RecipeItem::SORT_UPDATE_DATE], array('data_option_icon' => 'far fa-calendar-plus')),
			new FormFieldSelectChoiceOption($this->lang['common.sort.by.date'], RecipeItem::SORT_FIELDS_URL_VALUES[RecipeItem::SORT_DATE], array('data_option_icon' => 'far fa-calendar-alt')),
			new FormFieldSelectChoiceOption($this->lang['common.sort.by.alphabetic'], RecipeItem::SORT_FIELDS_URL_VALUES[RecipeItem::SORT_ALPHABETIC], array('data_option_icon' => 'fa fa-sort-alpha-up')),
			new FormFieldSelectChoiceOption($this->lang['common.sort.by.author'], RecipeItem::SORT_FIELDS_URL_VALUES[RecipeItem::SORT_AUTHOR], array('data_option_icon' => 'fa fa-user')),
			new FormFieldSelectChoiceOption($this->lang['common.sort.by.views.number'], RecipeItem::SORT_FIELDS_URL_VALUES[RecipeItem::SORT_VIEWS_NUMBER], array('data_option_icon' => 'far fa-eye'))
		);

		if ($this->comments_config->module_comments_is_enabled('recipe'))
			$sort_options[] = new FormFieldSelectChoiceOption($this->lang['common.sort.by.comments.number'], RecipeItem::SORT_FIELDS_URL_VALUES[RecipeItem::SORT_COMMENTS_NUMBER], array('data_option_icon' => 'far fa-comments'));

		if ($this->content_management_config->module_notation_is_enabled('recipe'))
			$sort_options[] = new FormFieldSelectChoiceOption($this->lang['common.sort.by.best.note'], RecipeItem::SORT_FIELDS_URL_VALUES[RecipeItem::SORT_NOTATION], array('data_option_icon' => 'far fa-star'));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_fields', '', $field, $sort_options,
			array('select_to_list' => true, 'events' => array('change' => 'document.location = "'. RecipeUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name())->rel() . '" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_mode', '', $mode,
			array(
				new FormFieldSelectChoiceOption($this->lang['common.sort.asc'], 'asc'),
				new FormFieldSelectChoiceOption($this->lang['common.sort.desc'], 'desc')
			),
			array('select_to_list' => true, 'events' => array('change' => 'document.location = "' . RecipeUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name())->rel() . '" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));

		$this->view->put('SORT_FORM', $form->display());
	}

	private function get_keyword()
	{
		if ($this->keyword === null)
		{
			$rewrited_name = AppContext::get_request()->get_getstring('tag', '');
			if (!empty($rewrited_name))
			{
				try {
					$this->keyword = KeywordsService::get_keywords_manager()->get_keyword('WHERE rewrited_name=:rewrited_name', array('rewrited_name' => $rewrited_name));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}
		return $this->keyword;
	}

	private function get_pagination($condition, $parameters, $field, $mode, $page)
	{
		$result = PersistenceContext::get_querier()->select_single_row_query('SELECT COUNT(*) AS items_number
		FROM '. RecipeSetup::$recipe_table .' recipe
		LEFT JOIN '. DB_TABLE_KEYWORDS_RELATIONS .' relation ON relation.module_id = \'recipe\' AND relation.id_in_module = recipe.id
		' . $condition, $parameters);

		$pagination = new ModulePagination($page, $result['items_number'], (int)RecipeConfig::load()->get_items_per_page());
		$pagination->set_url(RecipeUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name(), $field, $mode, '%d'));

		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
	}

	private function build_keywords_view($keywords)
	{
		$nbr_keywords = count($keywords);

		$i = 1;
		foreach ($keywords as $keyword)
		{
			$this->view->assign_block_vars('items.keywords', array(
				'C_SEPARATOR' => $i < $nbr_keywords,
				'NAME' => $keyword->get_name(),
				'URL' => RecipeUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
	}

	private function check_authorizations()
	{
		if (!CategoriesAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response(HTTPRequestCustom $request)
	{
		$sort_field = $request->get_getstring('field', RecipeItem::SORT_FIELDS_URL_VALUES[$this->config->get_items_default_sort_field()]);
		$sort_mode = $request->get_getstring('sort', $this->config->get_items_default_sort_mode());
		$page = $request->get_getint('page', 1);
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->get_keyword()->get_name(), $this->lang['recipe.module.title'], $page);
		$graphical_environment->get_seo_meta_data()->set_description(StringVars::replace_vars($this->lang['recipe.seo.description.tag'], array('subject' => $this->get_keyword()->get_name())), $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(RecipeUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name(), $sort_field, $sort_mode, $page));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['recipe.module.title'], RecipeUrlBuilder::home());
		$breadcrumb->add($this->get_keyword()->get_name(), RecipeUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name(), $sort_field, $sort_mode, $page));

		return $response;
	}
}
?>
