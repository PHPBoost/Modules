<?php
/*##################################################
 *                   AdminSmalladsItemsConfigController.class.php
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

class AdminSmalladsItemsConfigController extends AdminModuleController
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
	private $comments_config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('max_weeks_number')->set_hidden(!$this->config->is_max_weeks_number_displayed());
			$this->form->get_field_by_id('suggested_items_nb')->set_hidden(!$this->config->get_enabled_items_suggestions());
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 4));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminSmalladsDisplayResponse($tpl, $this->lang['config.items.title']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'smallads');
		$this->admin_common_lang = LangLoader::get('admin-common');
		$this->config = SmalladsConfig::load();
		$this->comments_config = CommentsConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('smallads_options_config', $this->lang['config.items.title']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldSelectCurrencies('currency', $this->lang['config.currency'], $this->config->get_currency()));

		$fieldset->add_field(new FormFieldNumberEditor('display_delay_before_delete', $this->lang['config.display.delay.before.delete'], $this->config->get_display_delay_before_delete(),
			array('min' => 1, 'max' => 7, 'required' => true, 'description' => $this->lang['config.display.delay.before.delete.desc']),
			array(new FormFieldConstraintIntegerRange(1, 7))
		));

		$fieldset->add_field(new FormFieldCheckbox('max_weeks_number_displayed', $this->lang['config.max.weeks.number.displayed'], $this->config->is_max_weeks_number_displayed(), array(
			'events' => array('click' => '
				if (HTMLForms.getField("max_weeks_number_displayed").getValue()) {
					HTMLForms.getField("max_weeks_number").enable();
				} else {
					HTMLForms.getField("max_weeks_number").disable();
				}'
			)
		)));

		$fieldset->add_field(new FormFieldNumberEditor('max_weeks_number', $this->lang['config.max.weeks.number'], $this->config->get_max_weeks_number(),
			array('min' => 1, 'max' => 52, 'required' => true, 'hidden' => !$this->config->is_max_weeks_number_displayed()),
			array(new FormFieldConstraintIntegerRange(1, 52))
		));

		$fieldset->add_field(new FormFieldFree('1_separator', '', ''));

		$fieldset->add_field(new FormFieldCheckbox('contact_level', $this->lang['config.display.contact.to.visitors'], $this->config->is_user_allowed(),
			array('description' => $this->lang['config.display.contact.to.visitors.desc'])
		));

		$fieldset->add_field(new FormFieldCheckbox('display_email_enabled', $this->lang['config.display.email.enabled'], $this->config->is_email_displayed()));

		$fieldset->add_field(new FormFieldCheckbox('display_pm_enabled', $this->lang['config.display.pm.enabled'], $this->config->is_pm_displayed()));

		$fieldset->add_field(new FormFieldCheckbox('display_phone_enabled', $this->lang['config.display.phone.enabled'], $this->config->is_phone_displayed()));

		$fieldset->add_field(new FormFieldCheckbox('enabled_navigation_links', $this->lang['config.related.links.display'], $this->config->get_enabled_navigation_links(),
			array('description' => $this->lang['config.related.links.display.desc'])
		));

		$fieldset->add_field(new FormFieldCheckbox('display_location_enabled', $this->lang['config.location'], $this->config->is_location_displayed()));

		$fieldset->add_field(new FormFieldCheckbox('enabled_items_suggestions', $this->lang['config.suggestions.display'], $this->config->get_enabled_items_suggestions(),
			array('events' => array('click' => '
				if (HTMLForms.getField("enabled_items_suggestions").getValue()) {
					HTMLForms.getField("suggested_items_nb").enable();
				} else {
					HTMLForms.getField("suggested_items_nb").disable();
				}
			'))
		));

		$fieldset->add_field(new FormFieldNumberEditor('suggested_items_nb', $this->lang['config.suggestions.nb'], $this->config->get_suggested_items_nb(),
			array('min' => 1, 'max' => 10, 'hidden' => !$this->config->get_enabled_items_suggestions()),
			array(new FormFieldConstraintIntegerRange(1, 10))
		));

		$fieldset->add_field(new FormFieldFree('2_separator', '', ''));

		$fieldset->add_field(new SmalladsFormFieldSmalladType('smallad_type', $this->lang['smallads.type.add'], $this->config->get_smallad_types()));

		// $fieldset->add_field(new SmalladsFormFieldBrand('smallad_brand', $this->lang['smallads.brand.add'], $this->config->get_brands()));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function get_sort_options()
	{
		$common_lang = LangLoader::get('common');
		$lang = LangLoader::get('common', 'smallads');

		$sort_options = array(
			new FormFieldSelectChoiceOption($common_lang['form.date.creation'] . ' - ' . $common_lang['sort.desc'], Smallad::SORT_DATE . '-' . Smallad::DESC),
			new FormFieldSelectChoiceOption($common_lang['form.date.creation'] . ' - ' . $common_lang['sort.asc'], Smallad::SORT_DATE . '-' . Smallad::ASC),
			new FormFieldSelectChoiceOption($common_lang['sort_by.alphabetic'] . ' - ' . $common_lang['sort.desc'], Smallad::SORT_ALPHABETIC . '-' . Smallad::DESC),
			new FormFieldSelectChoiceOption($common_lang['sort_by.alphabetic'] . ' - ' . $common_lang['sort.asc'], Smallad::SORT_ALPHABETIC . '-' . Smallad::ASC),
			new FormFieldSelectChoiceOption($lang['smallads.sort.field.views'] . ' - ' . $common_lang['sort.desc'], Smallad::SORT_NUMBER_VIEWS . '-' . Smallad::DESC),
			new FormFieldSelectChoiceOption($lang['smallads.sort.field.views'] . ' - ' . $common_lang['sort.asc'], Smallad::SORT_NUMBER_VIEWS . '-' . Smallad::ASC),
			new FormFieldSelectChoiceOption($common_lang['author'] . ' - ' . $common_lang['sort.desc'], Smallad::SORT_AUTHOR . '-' . Smallad::DESC),
			new FormFieldSelectChoiceOption($common_lang['author'] . ' - ' . $common_lang['sort.asc'], Smallad::SORT_AUTHOR . '-' . Smallad::ASC),
		);

		if ($this->comments_config->are_comments_enabled('smallads'))
		{
			$sort_options[] = new FormFieldSelectChoiceOption($common_lang['sort_by.number_comments'] . ' - ' . $common_lang['sort.asc'], Smallad::SORT_NUMBER_COMMENTS . '-' . Smallad::ASC);
			$sort_options[] = new FormFieldSelectChoiceOption($common_lang['sort_by.number_comments'] . ' - ' . $common_lang['sort.desc'], Smallad::SORT_NUMBER_COMMENTS . '-' . Smallad::DESC);
		}

		return $sort_options;
	}

	private function save()
	{
		$this->config->set_currency($this->form->get_value('currency')->get_raw_value());

		$this->config->set_smallad_types($this->form->get_value('smallad_type'));
		// $this->config->set_brands($this->form->get_value('smallad_brand'));

		if ($this->form->get_value('max_weeks_number_displayed'))
		{
			$this->config->display_max_weeks_number();
			$this->config->set_max_weeks_number($this->form->get_value('max_weeks_number'));
		}
		else
			$this->config->hide_max_weeks_number();

		$this->config->set_display_delay_before_delete($this->form->get_value('display_delay_before_delete'));

		if ($this->form->get_value('contact_level'))
			$this->config->visitor_allowed_to_contact();
		else
			$this->config->visitor_not_allowed_to_contact();

		if ($this->form->get_value('display_email_enabled'))
			$this->config->display_email();
		else
			$this->config->hide_email();

		if ($this->form->get_value('display_pm_enabled'))
			$this->config->display_pm();
		else
			$this->config->hide_pm();

		if ($this->form->get_value('display_phone_enabled'))
			$this->config->display_phone();
		else
			$this->config->hide_phone();

		$this->config->set_enabled_items_suggestions($this->form->get_value('enabled_items_suggestions'));
		if($this->form->get_value('enabled_items_suggestions'))
			$this->config->set_suggested_items_nb($this->form->get_value('suggested_items_nb'));

		$this->config->set_enabled_navigation_links($this->form->get_value('enabled_navigation_links'));

		if ($this->form->get_value('display_location_enabled'))
			$this->config->display_location();
		else
			$this->config->hide_location();

		SmalladsConfig::save();
		SmalladsService::get_categories_manager()->regenerate_cache();
	}
}
?>
