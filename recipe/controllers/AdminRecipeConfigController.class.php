<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2023 09 17
 * @since       PHPBoost 6.0 - 2022 08 26
 */

class AdminRecipeConfigController extends DefaultAdminModuleController
{
	private $comments_config;
	private $content_management_config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('display_summary_to_guests')->set_hidden($this->config->get_display_type() == RecipeConfig::TABLE_VIEW);
			$this->form->get_field_by_id('auto_cut_characters_number')->set_hidden($this->config->get_display_type() == RecipeConfig::TABLE_VIEW);
			$this->form->get_field_by_id('items_per_row')->set_hidden($this->config->get_display_type() !== RecipeConfig::GRID_VIEW);
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.success.config'], MessageHelper::SUCCESS, 5));
		}

		$this->view->put('CONTENT', $this->form->display());

		return new DefaultAdminDisplayResponse($this->view);
	}

	private function init()
	{
		$this->comments_config = CommentsConfig::load();
		$this->content_management_config = ContentManagementConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('configuration', StringVars::replace_vars($this->lang['form.module.title'], array('module_name' => self::get_module()->get_configuration()->get_name())));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldNumberEditor('categories_per_page', $this->lang['form.categories.per.page'], $this->config->get_categories_per_page(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldNumberEditor('categories_per_row', $this->lang['form.categories.per.row'], $this->config->get_categories_per_row(),
			array('min' => 1, 'max' => 4, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 4))
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('items_default_sort', $this->lang['form.items.default.sort'], $this->config->get_items_default_sort_field() . '-' . TextHelper::strtoupper($this->config->get_items_default_sort_mode()), $this->get_sort_options()));

		$fieldset->add_field(new FormFieldNumberEditor('items_per_page', $this->lang['form.items.per.page'], $this->config->get_items_per_page(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldSpacer('display', ''));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('display_type', $this->lang['form.display.type'], $this->config->get_display_type(),
			array(
				new FormFieldSelectChoiceOption($this->lang['form.display.type.grid'], RecipeConfig::GRID_VIEW, array('data_option_icon' => 'fa fa-th-large')),
				new FormFieldSelectChoiceOption($this->lang['form.display.type.table'], RecipeConfig::TABLE_VIEW, array('data_option_icon' => 'fa fa-table'))
			),
			array(
				'select_to_list' => true,
				'events' => array('change' => '
				if (HTMLForms.getField("display_type").getValue() == \'' . RecipeConfig::GRID_VIEW . '\') {
					HTMLForms.getField("items_per_row").enable();
					HTMLForms.getField("display_summary_to_guests").enable();
						HTMLForms.getField("auto_cut_characters_number").enable();
					HTMLForms.getField("full_item_display").disable();
				} else {
					HTMLForms.getField("items_per_row").disable();
					HTMLForms.getField("display_summary_to_guests").disable();
					HTMLForms.getField("full_item_display").disable();
					HTMLForms.getField("auto_cut_characters_number").disable();
				}'
			))
		));

		$fieldset->add_field(new FormFieldNumberEditor('items_per_row', $this->lang['form.items.per.row'], $this->config->get_items_per_row(),
			array(
				'hidden' => $this->config->get_display_type() !== RecipeConfig::GRID_VIEW,
				'min' => 1, 'max' => 4, 'required' => true),
				array(new FormFieldConstraintIntegerRange(1, 4))
		));

		$fieldset->add_field(new FormFieldNumberEditor('auto_cut_characters_number', $this->lang['form.characters.number.to.cut'], $this->config->get_auto_cut_characters_number(),
			array(
				'min' => 20, 'max' => 1000, 'required' => true,
				'hidden' => $this->config->get_display_type() == RecipeConfig::TABLE_VIEW
			),
			array(new FormFieldConstraintIntegerRange(20, 1000)
		)));

		$fieldset->add_field(new FormFieldCheckbox('display_summary_to_guests', $this->lang['form.display.summary.to.guests'], $this->config->is_summary_displayed_to_guests(),
			array(
				'class' => 'custom-checkbox',
				'hidden' => $this->config->get_display_type() == RecipeConfig::TABLE_VIEW
			)
		));$fieldset->add_field(new FormFieldRichTextEditor('root_category_description', $this->lang['form.root.category.description'], $this->config->get_root_category_description(),
			array('rows' => 8, 'cols' => 47)
		));

		$fieldset->add_field(new FormFieldCheckbox('author_displayed', $this->lang['form.display.author'], $this->config->is_author_displayed(),
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('views_nb_enabled', $this->lang['form.display.views.number'], $this->config->get_enabled_views_number(),
			array('class' => 'custom-checkbox')
		));

        $fieldset->add_field(new FormFieldRichTextEditor('default_content', $this->lang['form.item.default.content'], $this->config->get_default_content(),
			array('rows' => 8, 'cols' => 47)
		));

		$fieldset_authorizations = new FormFieldsetHTML('authorizations_fieldset', $this->lang['form.authorizations'],
			array('description' => $this->lang['form.authorizations.clue'])
		);
		$form->add_fieldset($fieldset_authorizations);

		$auth_settings = new AuthorizationsSettings(RootCategory::get_authorizations_settings());
		$auth_settings->build_from_auth_array($this->config->get_authorizations());
		$fieldset_authorizations->add_field(new FormFieldAuthorizationsSetter('authorizations', $auth_settings));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function get_sort_options()
	{
		$sort_options = array(
			new FormFieldSelectChoiceOption($this->lang['common.sort.by.update'] . ' - ' . $this->lang['common.sort.asc'], RecipeItem::SORT_UPDATE_DATE . '-' . RecipeItem::ASC),
			new FormFieldSelectChoiceOption($this->lang['common.sort.by.update'] . ' - ' . $this->lang['common.sort.desc'], RecipeItem::SORT_UPDATE_DATE . '-' . RecipeItem::DESC),
			new FormFieldSelectChoiceOption($this->lang['common.sort.by.date'] . ' - ' . $this->lang['common.sort.asc'], RecipeItem::SORT_DATE . '-' . RecipeItem::ASC),
			new FormFieldSelectChoiceOption($this->lang['common.sort.by.date'] . ' - ' . $this->lang['common.sort.desc'], RecipeItem::SORT_DATE . '-' . RecipeItem::DESC),
			new FormFieldSelectChoiceOption($this->lang['common.sort.by.alphabetic'] . ' - ' . $this->lang['common.sort.asc'], RecipeItem::SORT_ALPHABETIC . '-' . RecipeItem::ASC),
			new FormFieldSelectChoiceOption($this->lang['common.sort.by.alphabetic'] . ' - ' . $this->lang['common.sort.desc'], RecipeItem::SORT_ALPHABETIC . '-' . RecipeItem::DESC),
			new FormFieldSelectChoiceOption($this->lang['common.sort.by.author'] . ' - ' . $this->lang['common.sort.asc'], RecipeItem::SORT_AUTHOR . '-' . RecipeItem::ASC),
			new FormFieldSelectChoiceOption($this->lang['common.sort.by.author'] . ' - ' . $this->lang['common.sort.desc'], RecipeItem::SORT_AUTHOR . '-' . RecipeItem::DESC),
			new FormFieldSelectChoiceOption($this->lang['common.sort.by.views.number'] . ' - ' . $this->lang['common.sort.asc'], RecipeItem::SORT_VIEWS_NUMBER . '-' . RecipeItem::ASC),
			new FormFieldSelectChoiceOption($this->lang['common.sort.by.views.number'] . ' - ' . $this->lang['common.sort.desc'], RecipeItem::SORT_VIEWS_NUMBER . '-' . RecipeItem::DESC)
		);

		if ($this->comments_config->module_comments_is_enabled('recipe'))
		{
			$sort_options[] = new FormFieldSelectChoiceOption($this->lang['common.sort.by.comments.number'] . ' - ' . $this->lang['common.sort.asc'], RecipeItem::SORT_COMMENTS_NUMBER . '-' . RecipeItem::ASC);
			$sort_options[] = new FormFieldSelectChoiceOption($this->lang['common.sort.by.comments.number'] . ' - ' . $this->lang['common.sort.desc'], RecipeItem::SORT_COMMENTS_NUMBER . '-' . RecipeItem::DESC);
		}

		if ($this->content_management_config->module_notation_is_enabled('recipe'))
		{
			$sort_options[] = new FormFieldSelectChoiceOption($this->lang['common.sort.by.best.note'] . ' - ' . $this->lang['common.sort.asc'], RecipeItem::SORT_NOTATION . '-' . RecipeItem::ASC);
			$sort_options[] = new FormFieldSelectChoiceOption($this->lang['common.sort.by.best.note'] . ' - ' . $this->lang['common.sort.desc'], RecipeItem::SORT_NOTATION . '-' . RecipeItem::DESC);
		}

		return $sort_options;
	}

	private function save()
	{
		$this->config->set_items_per_page($this->form->get_value('items_per_page'));

		if($this->form->get_value('display_type')->get_raw_value() == RecipeConfig::GRID_VIEW)
			$this->config->set_items_per_row($this->form->get_value('items_per_row'));

		$this->config->set_categories_per_page($this->form->get_value('categories_per_page'));
		$this->config->set_categories_per_row($this->form->get_value('categories_per_row'));
		$this->config->set_display_type($this->form->get_value('display_type')->get_raw_value());

		$items_default_sort = $this->form->get_value('items_default_sort')->get_raw_value();
		$items_default_sort = explode('-', $items_default_sort);
		$this->config->set_items_default_sort_field($items_default_sort[0]);
		$this->config->set_items_default_sort_mode(TextHelper::strtolower($items_default_sort[1]));

		if ($this->config->get_display_type() != RecipeConfig::TABLE_VIEW)
		{
			if ($this->form->get_value('display_summary_to_guests'))
				$this->config->display_summary_to_guests();
			else
				$this->config->hide_summary_to_guests();
		}

		if ($this->form->get_value('author_displayed'))
			$this->config->display_author();
		else
			$this->config->hide_author();

		$this->config->set_auto_cut_characters_number($this->form->get_value('auto_cut_characters_number', $this->config->get_auto_cut_characters_number()));
		$this->config->set_enabled_views_number($this->form->get_value('views_nb_enabled'));
		$this->config->set_root_category_description($this->form->get_value('root_category_description'));
		$this->config->set_default_content($this->form->get_value('default_content'));
        $this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());

		RecipeConfig::save();
		CategoriesService::get_categories_manager()->regenerate_cache();
		
		HooksService::execute_hook_action('edit_config', self::$module_id, array('title' => StringVars::replace_vars($this->lang['form.module.title'], array('module_name' => self::get_module_configuration()->get_name())), 'url' => ModulesUrlBuilder::configuration()->rel()));
	}
}
?>
