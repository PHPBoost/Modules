<?php
/*##################################################
 *                   AdminSmalladsCategoriesConfigController.class.php
 *                            -------------------
 *   begin                : March 15, 2018
 *   copyright            : (C) 2018 Sebastien LARTIGUE
 *   email                : babsolune@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
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

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('characters_number_to_cut')->set_hidden($this->config->get_display_type() === SmalladsConfig::TABLE_DISPLAY);
			$this->form->get_field_by_id('displayed_cols_number_per_line')->set_hidden($this->config->get_display_type() !== SmalladsConfig::MOSAIC_DISPLAY);
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 4));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminSmalladsDisplayResponse($tpl, $this->lang['config.categories.title']);
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

		$fieldset = new FormFieldsetHTML('smallads_configuration', $this->lang['config.categories.title']);
		$form->add_fieldset($fieldset);

		// $fieldset->add_field(new FormFieldSimpleSelectChoice('items_default_sort', $this->lang['config.items.default.sort'], $this->config->get_items_default_sort_field() . '-' . $this->config->get_items_default_sort_mode(), $this->get_sort_options()));

		$fieldset->add_field(new FormFieldCheckbox('display_sort_filters', $this->lang['config.sort.filter.display'], $this->config->are_sort_filters_enabled()));

		$fieldset->add_field(new FormFieldCheckbox('display_icon_cats', $this->lang['config.cats.icon.display'], $this->config->are_cat_icons_enabled()));

		$fieldset->add_field(new FormFieldNumberEditor('items_number_per_page', $this->admin_common_lang['config.items_number_per_page'], $this->config->get_items_number_per_page(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('display_type', $this->lang['config.display.type'], $this->config->get_display_type(),
			array(
				new FormFieldSelectChoiceOption($this->lang['config.mosaic.type.display'], SmalladsConfig::MOSAIC_DISPLAY),
				new FormFieldSelectChoiceOption($this->lang['config.list.type.display'], SmalladsConfig::LIST_DISPLAY),
				new FormFieldSelectChoiceOption($this->lang['config.table.type.display'], SmalladsConfig::TABLE_DISPLAY)
			),
			array('events' => array('change' => '
				if (HTMLForms.getField("display_type").getValue() === \'' . SmalladsConfig::MOSAIC_DISPLAY . '\') {
					HTMLForms.getField("characters_number_to_cut").enable();
					HTMLForms.getField("displayed_cols_number_per_line").enable();
				} else if (HTMLForms.getField("display_type").getValue() === \'' . SmalladsConfig::LIST_DISPLAY . '\') {
					HTMLForms.getField("characters_number_to_cut").enable();
					HTMLForms.getField("displayed_cols_number_per_line").disable();
				} else {
					HTMLForms.getField("characters_number_to_cut").disable();
					HTMLForms.getField("displayed_cols_number_per_line").disable();
				}'))
		));

		$fieldset->add_field(new FormFieldNumberEditor('characters_number_to_cut', $this->lang['config.characters.number.to.cut'], $this->config->get_characters_number_to_cut(),
			array('min' => 20, 'max' => 1000, 'hidden' => $this->config->get_display_type() === SmalladsConfig::TABLE_DISPLAY),
			array(new FormFieldConstraintIntegerRange(20, 1000))
		));

		$fieldset->add_field(new FormFieldNumberEditor('displayed_cols_number_per_line', $this->admin_common_lang['config.columns_number_per_line'], $this->config->get_displayed_cols_number_per_line(),
			array('min' => 1, 'max' => 6, 'hidden' => $this->config->get_display_type() !== SmalladsConfig::MOSAIC_DISPLAY),
			array(new FormFieldConstraintIntegerRange(1, 6))
		));

		$fieldset->add_field(new FormFieldCheckbox('display_descriptions_to_guests', $this->lang['config.display.descriptions.to.guests'], $this->config->are_descriptions_displayed_to_guests()));

		$fieldset->add_field(new FormFieldRichTextEditor('root_category_description', $this->admin_common_lang['config.root_category_description'], $this->config->get_root_category_description(),
			array('rows' => 8, 'cols' => 47)
		));

		$common_lang = LangLoader::get('common');
		$fieldset_authorizations = new FormFieldsetHTML('authorizations', $common_lang['authorizations'],
			array('description' => $this->admin_common_lang['config.authorizations.explain'])
		);

		$form->add_fieldset($fieldset_authorizations);

		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($common_lang['authorizations.read'], Category::READ_AUTHORIZATIONS),
			new ActionAuthorization($common_lang['authorizations.write'], Category::WRITE_AUTHORIZATIONS),
			new ActionAuthorization($common_lang['authorizations.contribution'], Category::CONTRIBUTION_AUTHORIZATIONS),
			new ActionAuthorization($common_lang['authorizations.moderation'], Category::MODERATION_AUTHORIZATIONS),
			new ActionAuthorization($common_lang['authorizations.categories_management'], Category::CATEGORIES_MANAGEMENT_AUTHORIZATIONS)
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
		// $items_default_sort = $this->form->get_value('items_default_sort')->get_raw_value();
		// $items_default_sort = explode('-', $items_default_sort);
		// $this->config->set_items_default_sort_field($items_default_sort[0]);
		// $this->config->set_items_default_sort_mode(TextHelper::strtolower($items_default_sort[1]));

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
		if($this->config->get_display_type() == SmalladsConfig::MOSAIC_DISPLAY) {
			$this->config->set_characters_number_to_cut($this->form->get_value('characters_number_to_cut'));
			$this->config->set_displayed_cols_number_per_line($this->form->get_value('displayed_cols_number_per_line'));
		} else if ($this->config->get_display_type() == SmalladsConfig::LIST_DISPLAY) {
			$this->config->set_characters_number_to_cut($this->form->get_value('characters_number_to_cut'));
		}

		if ($this->form->get_value('display_descriptions_to_guests'))
			$this->config->display_descriptions_to_guests();
		else
			$this->config->hide_descriptions_to_guests();


		$this->config->set_root_category_description($this->form->get_value('root_category_description'));
		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());

		SmalladsConfig::save();
		SmalladsService::get_categories_manager()->regenerate_cache();
		SmalladsCache::invalidate();
	}
}
?>
