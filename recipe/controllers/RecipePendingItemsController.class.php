<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 10 17
 * @since       PHPBoost 6.0 - 2022 08 26
 */

class RecipePendingItemsController extends DefaultModuleController
{
	protected function get_template_to_use()
	{
		return new FileTemplate('recipe/RecipeSeveralItemsController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->build_view($request);

		return $this->generate_response($request);
	}

	public function build_view(HTTPRequestCustom $request)
	{
		$now = new Date();
		$comments_config = CommentsConfig::load();
		$content_management_config = ContentManagementConfig::load();
		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, $this->config->is_summary_displayed_to_guests());
		$mode = $request->get_getstring('sort', $this->config->get_items_default_sort_mode());
		$field = $request->get_getstring('field', RecipeItem::SORT_FIELDS_URL_VALUES[$this->config->get_items_default_sort_field()]);

		$condition = 'WHERE id_category IN :authorized_categories
		' . (!CategoriesAuthorizationsService::check_authorizations()->moderation() ? ' AND author_user_id = :user_id' : '') . '
		AND (published = 0 OR (published = 2 AND (publishing_start_date > :timestamp_now OR (publishing_end_date != 0 AND publishing_end_date < :timestamp_now))))';
		$parameters = array(
			'user_id' => AppContext::get_current_user()->get_id(),
			'authorized_categories' => $authorized_categories,
			'timestamp_now' => $now->get_timestamp()
		);

		$page = $request->get_getint('page', 1);
		$pagination = $this->get_pagination($condition, $parameters, $field, TextHelper::strtolower($mode), $page);

		$sort_mode = TextHelper::strtoupper($mode);
		$sort_mode = (in_array($sort_mode, array(RecipeItem::ASC, RecipeItem::DESC)) ? $sort_mode : $this->config->get_items_default_sort_mode());

		if (in_array($field, array(RecipeItem::SORT_FIELDS_URL_VALUES[RecipeItem::SORT_ALPHABETIC], RecipeItem::SORT_FIELDS_URL_VALUES[RecipeItem::SORT_AUTHOR], RecipeItem::SORT_FIELDS_URL_VALUES[RecipeItem::SORT_DATE])))
			$sort_field = array_search($field, RecipeItem::SORT_FIELDS_URL_VALUES);
		else
			$sort_field = RecipeItem::SORT_DATE;

		$result = PersistenceContext::get_querier()->select('SELECT recipe.*, member.*, com.comments_number, notes.average_notes, notes.notes_number, note.note
		FROM '. RecipeSetup::$recipe_table .' recipe
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = recipe.author_user_id
		LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = recipe.id AND com.module_id = \'recipe\'
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = recipe.id AND notes.module_name = \'recipe\'
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = recipe.id AND note.module_name = \'recipe\' AND note.user_id = :user_id
		' . $condition . '
		ORDER BY ' . $sort_field . ' ' . $sort_mode . '
		LIMIT :number_items_per_page OFFSET :display_from', array_merge($parameters, array(
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		)));

		$this->view->put_all(array(
			'C_PENDING' => true,
			'C_ITEMS' => $result->get_rows_count() > 0,
			'C_SEVERAL_ITEMS' => $result->get_rows_count() > 1,
			'C_GRID_VIEW' => $this->config->get_display_type() == RecipeConfig::GRID_VIEW,
			'C_TABLE_VIEW' => $this->config->get_display_type() == RecipeConfig::TABLE_VIEW,
			'C_CATEGORY_DESCRIPTION' => !empty($category_description),
			'CATEGORIES_PER_ROW' => $this->config->get_categories_per_row(),
			'ITEMS_PER_ROW' => $this->config->get_items_per_row(),
			'C_ENABLED_COMMENTS' => $comments_config->module_comments_is_enabled('recipe'),
			'C_ENABLED_NOTATION' => $content_management_config->module_notation_is_enabled('recipe'),
			'C_AUTHOR_DISPLAYED' => $this->config->is_author_displayed(),
			'C_PAGINATION' => $pagination->has_several_pages(),
			'PAGINATION' => $pagination->display(),
			'TABLE_COLSPAN' => 4 + (int)$comments_config->module_comments_is_enabled('recipe') + (int)$content_management_config->module_notation_is_enabled('recipe')
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

		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_fields', '', $field,
			array(
				new FormFieldSelectChoiceOption($this->lang['common.sort.by.date'], RecipeItem::SORT_FIELDS_URL_VALUES[RecipeItem::SORT_DATE]),
				new FormFieldSelectChoiceOption($this->lang['common.sort.by.alphabetic'], RecipeItem::SORT_FIELDS_URL_VALUES[RecipeItem::SORT_ALPHABETIC]),
				new FormFieldSelectChoiceOption($this->lang['common.sort.by.author'], RecipeItem::SORT_FIELDS_URL_VALUES[RecipeItem::SORT_AUTHOR])
			),
			array('events' => array('change' => 'document.location = "'. RecipeUrlBuilder::display_pending()->rel() . '" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_mode', '', $mode,
			array(
				new FormFieldSelectChoiceOption($this->lang['common.sort.asc'], 'asc'),
				new FormFieldSelectChoiceOption($this->lang['common.sort.desc'], 'desc')
			),
			array('events' => array('change' => 'document.location = "' . RecipeUrlBuilder::display_pending()->rel() . '" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));

		$this->view->put('SORT_FORM', $form->display());
	}

	private function get_pagination($condition, $parameters, $field, $mode, $page)
	{
		$items_number = RecipeService::count($condition, $parameters);

		$pagination = new ModulePagination($page, $items_number, (int)RecipeConfig::load()->get_items_per_page());
		$pagination->set_url(RecipeUrlBuilder::display_pending($field, $mode, '%d'));

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
		if (!(CategoriesAuthorizationsService::check_authorizations()->write() || CategoriesAuthorizationsService::check_authorizations()->contribution() || CategoriesAuthorizationsService::check_authorizations()->moderation()))
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
		$graphical_environment->set_page_title($this->lang['recipe.pending.items'], $this->lang['recipe.module.title'], $page);
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['recipe.seo.description.pending'], $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(RecipeUrlBuilder::display_pending($sort_field, $sort_mode, $page));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['recipe.module.title'], RecipeUrlBuilder::home());
		$breadcrumb->add($this->lang['recipe.pending.items'], RecipeUrlBuilder::display_pending($sort_field, $sort_mode, $page));

		return $response;
	}
}
?>
