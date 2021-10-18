<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 09 16
 * @since       PHPBoost 6.0 - 2021 08 22
*/

class AdminSpotsConfigController extends AdminModuleController
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
	private $form_lang;

	/**
	 * @var SpotsConfig
	 */
	private $config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();

		$view = new StringTemplate('# INCLUDE MESSAGE_HELPER # # INCLUDE FORM #');
		$view->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('items_per_row')->set_hidden($this->config->get_display_type() !== SpotsConfig::GRID_VIEW);
			$view->put('MESSAGE_HELPER', MessageHelper::display(LangLoader::get_message('warning.success.config', 'warning-lang'), MessageHelper::SUCCESS, 5));
		}

		$view->put('FORM', $this->form->display());

		return new DefaultAdminDisplayResponse($view);
	}

	private function init()
	{
		$this->config = SpotsConfig::load();
		$this->lang = LangLoader::get('common', 'spots');
		$this->form_lang = LangLoader::get('form-lang');
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('config', $this->form_lang['form.configuration']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('module_name', $this->lang['spots.module.name'], $this->config->get_module_name()));

		$fieldset->add_field(new FormFieldColorPicker('default_color', $this->lang['spots.default.color'], $this->config->get_default_color(),
			array('description' => $this->lang['spots.default.color.clue'])
		));

		$fieldset->add_field(new FormFieldTextEditor('default_inner_icon', $this->lang['spots.default.inner.icon'], $this->config->get_default_inner_icon(),
			array(
				'required' => true,
				'description' => $this->lang['spots.default.inner.icon.clue']
			)
		));

		$fieldset->add_field(new FormFieldSpacer('default_config', ''));

        $fieldset->add_field(new FormFieldNumberEditor('items_per_page', $this->form_lang['form.items.per.page'], $this->config->get_items_per_page(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldCheckbox('new_window', $this->form_lang['form.new.window'], $this->config->get_new_window(),
			array(
	            'description' => $this->form_lang['form.new.window.clue'],
				'class' => 'custom-checkbox'
        	)
		));

        $fieldset->add_field(new FormFieldSimpleSelectChoice('display_type', $this->form_lang['form.display.type'], $this->config->get_display_type(),
			array(
				new FormFieldSelectChoiceOption($this->form_lang['form.display.type.grid'], SpotsConfig::GRID_VIEW, array('data_option_icon' => 'fa fa-th-large')),
				new FormFieldSelectChoiceOption($this->form_lang['form.display.type.table'], SpotsConfig::TABLE_VIEW, array('data_option_icon' => 'fa fa-table'))
			),
			array(
				'select_to_list' => true,
				'events' => array('change' => '
					if (HTMLForms.getField("display_type").getValue() == \'' . SpotsConfig::GRID_VIEW . '\') {
						HTMLForms.getField("items_per_row").enable();
					} else {
						HTMLForms.getField("items_per_row").disable();
					}'
				)
			)
		));

        $fieldset->add_field(new FormFieldNumberEditor('items_per_row', $this->form_lang['form.items.per.row'], $this->config->get_items_per_row(),
			array(
				'min' => 1, 'max' => 4, 'required' => true,
				'hidden' => $this->config->get_display_type() !== SpotsConfig::GRID_VIEW
			),
			array(new FormFieldConstraintIntegerRange(1, 4))
		));

		$fieldset->add_field(new FormFieldRichTextEditor('default_content', $this->form_lang['form.item.default.content'], $this->config->get_default_content(),
			array('rows' => 8, 'cols' => 47)
		));

		$fieldset->add_field(new FormFieldNumberEditor('categories_per_page', $this->form_lang['form.categories.per.page'], $this->config->get_categories_per_page(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldNumberEditor('categories_per_row', $this->form_lang['form.categories.per.row'], $this->config->get_categories_per_row(),
			array('min' => 1, 'max' => 4, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 4))
		));

		$fieldset->add_field(new FormFieldRichTextEditor('root_category_description', $this->form_lang['form.root.category.description'], $this->config->get_root_category_description(),
			array('rows' => 8, 'cols' => 47)
		));

		$fieldset_authorizations = new FormFieldsetHTML('authorizations_fieldset', $this->form_lang['form.authorizations'],
			array('description' => $this->form_lang['form.authorizations.clue'])
		);
		$form->add_fieldset($fieldset_authorizations);

		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->form_lang['form.authorizations.read'], Category::READ_AUTHORIZATIONS),
			new ActionAuthorization($this->form_lang['form.authorizations.write'], Category::WRITE_AUTHORIZATIONS),
			new ActionAuthorization($this->form_lang['form.authorizations.contribution'], Category::CONTRIBUTION_AUTHORIZATIONS),
			new ActionAuthorization($this->form_lang['form.authorizations.moderation'], Category::MODERATION_AUTHORIZATIONS),
			new ActionAuthorization($this->form_lang['form.authorizations.categories'], Category::CATEGORIES_MANAGEMENT_AUTHORIZATIONS)
		));
		$auth_setter = new FormFieldAuthorizationsSetter('authorizations', $auth_settings);
		$auth_settings->build_from_auth_array($this->config->get_authorizations());
		$fieldset_authorizations->add_field($auth_setter);

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function save()
	{
		$this->config->set_module_name($this->form->get_value('module_name'));
		$this->config->set_new_window($this->form->get_value('new_window'));

		$this->config->set_display_type($this->form->get_value('display_type')->get_raw_value());
		$this->config->set_items_per_page($this->form->get_value('items_per_page'));
		if ($this->form->get_value('display_type')->get_raw_value() == SpotsConfig::GRID_VIEW)
			$this->config->set_items_per_row($this->form->get_value('items_per_row'));
		$this->config->set_default_color($this->form->get_value('default_color'));
		$this->config->set_default_inner_icon($this->form->get_value('default_inner_icon'));
		$this->config->set_default_content($this->form->get_value('default_content'));

		$this->config->set_categories_per_page($this->form->get_value('categories_per_page'));
		$this->config->set_categories_per_row($this->form->get_value('categories_per_row'));
		$this->config->set_root_category_description($this->form->get_value('root_category_description'));

		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());

		SpotsConfig::save();
		CategoriesService::get_categories_manager()->regenerate_cache();
	}
}
?>
