<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 5.1 - 2018 03 15
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Mipel <mipel@phpboost.com>
*/

class AdminSmalladsCategoriesConfigController extends DefaultAdminModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('characters_number_to_cut')->set_hidden($this->config->get_display_type() === SmalladsConfig::TABLE_VIEW);
			$this->form->get_field_by_id('items_per_row')->set_hidden($this->config->get_display_type() !== SmalladsConfig::GRID_VIEW);
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.success.config'], MessageHelper::SUCCESS, 4));
		}

		$this->view->put('CONTENT', $this->form->display());

		return new AdminSmalladsDisplayResponse($this->view, $this->lang['smallads.categories.config']);
	}

	private function build_form()
	{
		$form = new HTMLForm(self::class);

		$fieldset = new FormFieldsetHTML('smallads_configuration', $this->lang['smallads.categories.config']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('display_sort_filters', $this->lang['form.display.sort.form'], $this->config->are_sort_filters_enabled(),
			['class'=> 'custom-checkbox']
		));

		$fieldset->add_field(new FormFieldCheckbox('display_icon_cats', $this->lang['smallads.cats.icon.display'], $this->config->are_cat_icons_enabled(),
			['class'=> 'custom-checkbox']
		));

		$fieldset->add_field(new FormFieldNumberEditor('items_per_page', $this->lang['form.items.per.page'], $this->config->get_items_per_page(),
			['min' => 1, 'max' => 50, 'required' => true],
			[new FormFieldConstraintIntegerRange(1, 50)]
		));

		$fieldset->add_field(new FormFieldCheckbox('display_summaries_to_guests', $this->lang['form.display.summary.to.guests'], $this->config->are_summaries_displayed_to_guests(),
			['class'=> 'custom-checkbox']
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('display_type', $this->lang['form.display.type'], $this->config->get_display_type(),
			[
				new FormFieldSelectChoiceOption($this->lang['form.display.type.grid'], SmalladsConfig::GRID_VIEW, ['data_option_icon' => 'fa fa-th-large']),
				new FormFieldSelectChoiceOption($this->lang['form.display.type.list'], SmalladsConfig::LIST_VIEW, ['data_option_icon' => 'fa fa-list']),
				new FormFieldSelectChoiceOption($this->lang['form.display.type.table'], SmalladsConfig::TABLE_VIEW, ['data_option_icon' => 'fa fa-table'])
			],
			[
				'select_to_list' => true,
				'events' => ['change' => '
					if (HTMLForms.getField("display_type").getValue() === \'' . SmalladsConfig::GRID_VIEW . '\') {
						HTMLForms.getField("characters_number_to_cut").enable();
						HTMLForms.getField("items_per_row").enable();
					} else if (HTMLForms.getField("display_type").getValue() === \'' . SmalladsConfig::LIST_VIEW . '\') {
						HTMLForms.getField("characters_number_to_cut").enable();
						HTMLForms.getField("items_per_row").disable();
					} else {
						HTMLForms.getField("characters_number_to_cut").disable();
						HTMLForms.getField("items_per_row").disable();
					}'
				]
			]
		));

		$fieldset->add_field(new FormFieldNumberEditor('characters_number_to_cut', $this->lang['form.characters.number.to.cut'], $this->config->get_characters_number_to_cut(),
			[
				'min' => 20, 'max' => 1000,
				'hidden' => $this->config->get_display_type() === SmalladsConfig::TABLE_VIEW
			],
			[new FormFieldConstraintIntegerRange(20, 1000)]
		));

		$fieldset->add_field(new FormFieldNumberEditor('items_per_row', $this->lang['form.items.per.row'], $this->config->get_items_per_row(),
			[
				'min' => 1, 'max' => 4,
				'hidden' => $this->config->get_display_type() !== SmalladsConfig::GRID_VIEW
			],
			[new FormFieldConstraintIntegerRange(1, 4)]
		));

		$fieldset->add_field(new FormFieldRichTextEditor('root_category_description', $this->lang['form.root.category.description'], $this->config->get_root_category_description(),
			['rows' => 8, 'cols' => 47]
		));

		$fieldset_authorizations = new FormFieldsetHTML('authorizations', $this->lang['form.authorizations'],
			['description' => $this->lang['form.authorizations.clue']]
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


	private function save()
	{

		if ($this->form->get_value('display_sort_filters'))
			$this->config->enable_sort_filters();
		else
			$this->config->disable_sort_filters();

		$this->config->set_items_per_page($this->form->get_value('items_per_page'));
		if ($this->form->get_value('display_icon_cats'))
			$this->config->enable_cats_icon();
		else
			$this->config->disable_cats_icon();

		$this->config->set_display_type($this->form->get_value('display_type')->get_raw_value());
		if($this->config->get_display_type() == SmalladsConfig::GRID_VIEW) {
			$this->config->set_characters_number_to_cut($this->form->get_value('characters_number_to_cut'));
			$this->config->set_items_per_row($this->form->get_value('items_per_row'));
		} else if ($this->config->get_display_type() == SmalladsConfig::LIST_VIEW) {
			$this->config->set_characters_number_to_cut($this->form->get_value('characters_number_to_cut'));
		}

		if ($this->form->get_value('display_summaries_to_guests'))
			$this->config->display_summaries_to_guests();
		else
			$this->config->hide_summaries_to_guests();


		$this->config->set_root_category_description($this->form->get_value('root_category_description'));
		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());

		SmalladsConfig::save();
		CategoriesService::get_categories_manager()->regenerate_cache();
		SmalladsCache::invalidate();
		HooksService::execute_hook_action('edit_config', self::$module_id, ['title' => StringVars::replace_vars($this->lang['form.module.title'], ['module_name' => self::get_module_configuration()->get_name()]) . ' - ' . $this->lang['smallads.categories.config'], 'url' => ModulesUrlBuilder::configuration()->rel()]);
	}
}
?>
