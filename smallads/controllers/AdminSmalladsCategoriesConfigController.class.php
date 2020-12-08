<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 08
 * @since       PHPBoost 5.1 - 2018 03 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
*/

class AdminSmalladsCategoriesConfigController extends AdminModuleController
{
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonSubmit
	 */
	private $submit_button;

	private $lang;
	private $admin_common_lang;

	/**
	 * @var SmalladsConfig
	 */
	private $config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();

		$view = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$view->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('characters_number_to_cut')->set_hidden($this->config->get_display_type() === SmalladsConfig::DISPLAY_TABLE_VIEW);
			$this->form->get_field_by_id('displayed_cols_number_per_line')->set_hidden($this->config->get_display_type() !== SmalladsConfig::DISPLAY_GRID_VIEW);
			$view->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 4));
		}

		$view->put('FORM', $this->form->display());

		return new AdminSmalladsDisplayResponse($view, $this->lang['config.categories.title']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'smallads');
		$this->admin_common_lang = LangLoader::get('admin-common');
		$this->config = SmalladsConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('smallads_configuration', $this->lang['config.categories.title']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('display_sort_filters', $this->lang['config.sort.filter.display'], $this->config->are_sort_filters_enabled(),
			array('class'=> 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('display_icon_cats', $this->lang['config.cats.icon.display'], $this->config->are_cat_icons_enabled(),
			array('class'=> 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldNumberEditor('items_number_per_page', $this->admin_common_lang['config.items_number_per_page'], $this->config->get_items_number_per_page(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldCheckbox('display_summaries_to_guests', $this->lang['config.display.summaries.to.guests'], $this->config->are_summaries_displayed_to_guests(),
			array('class'=> 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('display_type', $this->admin_common_lang['config.display.type'], $this->config->get_display_type(),
			array(
				new FormFieldSelectChoiceOption($this->admin_common_lang['config.display.type.grid'], SmalladsConfig::DISPLAY_GRID_VIEW, array('data_option_icon' => 'fa fa-th-large')),
				new FormFieldSelectChoiceOption($this->admin_common_lang['config.display.type.list'], SmalladsConfig::DISPLAY_LIST_VIEW, array('data_option_icon' => 'fa fa-list')),
				new FormFieldSelectChoiceOption($this->admin_common_lang['config.display.type.table'], SmalladsConfig::DISPLAY_TABLE_VIEW, array('data_option_icon' => 'fa fa-table'))
			),
			array(
				'select_to_list' => true,
				'events' => array('change' => '
					if (HTMLForms.getField("display_type").getValue() === \'' . SmalladsConfig::DISPLAY_GRID_VIEW . '\') {
						HTMLForms.getField("characters_number_to_cut").enable();
						HTMLForms.getField("displayed_cols_number_per_line").enable();
					} else if (HTMLForms.getField("display_type").getValue() === \'' . SmalladsConfig::DISPLAY_LIST_VIEW . '\') {
						HTMLForms.getField("characters_number_to_cut").enable();
						HTMLForms.getField("displayed_cols_number_per_line").disable();
					} else {
						HTMLForms.getField("characters_number_to_cut").disable();
						HTMLForms.getField("displayed_cols_number_per_line").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldNumberEditor('characters_number_to_cut', $this->lang['config.characters.number.to.cut'], $this->config->get_characters_number_to_cut(),
			array(
				'min' => 20, 'max' => 1000,
				'hidden' => $this->config->get_display_type() === SmalladsConfig::DISPLAY_TABLE_VIEW
			),
			array(new FormFieldConstraintIntegerRange(20, 1000))
		));

		$fieldset->add_field(new FormFieldNumberEditor('displayed_cols_number_per_line', $this->admin_common_lang['config.columns_number_per_line'], $this->config->get_displayed_cols_number_per_line(),
			array(
				'min' => 1, 'max' => 4,
				'hidden' => $this->config->get_display_type() !== SmalladsConfig::DISPLAY_GRID_VIEW
			),
			array(new FormFieldConstraintIntegerRange(1, 4))
		));

		$fieldset->add_field(new FormFieldRichTextEditor('root_category_description', $this->admin_common_lang['config.root_category_description'], $this->config->get_root_category_description(),
			array('rows' => 8, 'cols' => 47)
		));

		$fieldset_authorizations = new FormFieldsetHTML('authorizations', LangLoader::get_message('authorizations', 'common'),
			array('description' => $this->admin_common_lang['config.authorizations.explain'])
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

		$this->config->set_items_number_per_page($this->form->get_value('items_number_per_page'));
		if ($this->form->get_value('display_icon_cats'))
			$this->config->enable_cats_icon();
		else
			$this->config->disable_cats_icon();

		$this->config->set_display_type($this->form->get_value('display_type')->get_raw_value());
		if($this->config->get_display_type() == SmalladsConfig::DISPLAY_GRID_VIEW) {
			$this->config->set_characters_number_to_cut($this->form->get_value('characters_number_to_cut'));
			$this->config->set_displayed_cols_number_per_line($this->form->get_value('displayed_cols_number_per_line'));
		} else if ($this->config->get_display_type() == SmalladsConfig::DISPLAY_LIST_VIEW) {
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
	}
}
?>
