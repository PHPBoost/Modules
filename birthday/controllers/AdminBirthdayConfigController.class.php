<?php
/*##################################################
 *                       AdminBirthdayConfigController.class.php
 *                            -------------------
 *   begin                : August 27, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

class AdminBirthdayConfigController extends AdminController
{
	private $lang;
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonDefaultSubmit
	 */
	private $submit_button;

	private $config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE USER_BORN_DISABLED_MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('pm_for_members_birthday_title')->set_hidden(!$this->config->is_pm_for_members_birthday_enabled());
			$this->form->get_field_by_id('pm_for_members_birthday_content')->set_hidden(!$this->config->is_pm_for_members_birthday_enabled());
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}

		$user_born_field = ExtendedFieldsCache::load()->get_extended_field_by_field_name('user_born');

		if (!empty($user_born_field) && !$user_born_field['display'])
		{
			$tpl->put('USER_BORN_DISABLED_MSG', MessageHelper::display($this->lang['user_born_field_disabled'], MessageHelper::WARNING));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminBirthdayDisplayResponse($tpl, LangLoader::get_message('configuration', 'admin'));
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'birthday');
		$this->config = BirthdayConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('configuration', LangLoader::get_message('configuration', 'admin'));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('members_age_displayed', $this->lang['admin.config.members_age_displayed'], $this->config->is_members_age_displayed(),
			array('class' => 'third-field')
		));

		$fieldset->add_field(new FormFieldCheckbox('pm_for_members_birthday_enabled', $this->lang['admin.config.send_pm_for_members_birthday'], $this->config->is_pm_for_members_birthday_enabled(),
			array(
				'class' => 'third-field',
				'events' => array('click' => '
					if (HTMLForms.getField("pm_for_members_birthday_enabled").getValue()) {
						HTMLForms.getField("pm_for_members_birthday_title").enable();
						HTMLForms.getField("pm_for_members_birthday_content").enable();
					} else {
						HTMLForms.getField("pm_for_members_birthday_title").disable();
						HTMLForms.getField("pm_for_members_birthday_content").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldTextEditor('pm_for_members_birthday_title', $this->lang['admin.config.pm_for_members_birthday_title'], $this->config->get_pm_for_members_birthday_title(),
			array('class' => 'third-field', 'size' => 40, 'description' => $this->lang['admin.config.pm_for_members_birthday_title.explain'], 'hidden' => !$this->config->is_pm_for_members_birthday_enabled())
		));

		$fieldset->add_field(new FormFieldShortMultiLineTextEditor('pm_for_members_birthday_content', $this->lang['admin.config.pm_for_members_birthday_content'], $this->config->get_pm_for_members_birthday_content(),
			array('class' => 'full-field', 'description' => $this->lang['admin.config.pm_for_members_birthday_content.explain'], 'hidden' => !$this->config->is_pm_for_members_birthday_enabled())
		));

		$fieldset_authorizations = new FormFieldsetHTML('authorizations', $this->lang['admin.authorizations']);
		$form->add_fieldset($fieldset_authorizations);

		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['admin.authorizations.read'], BirthdayAuthorizationsService::READ_AUTHORIZATIONS),
		));

		$auth_settings->build_from_auth_array($this->config->get_authorizations());
		$fieldset_authorizations->add_field(new FormFieldAuthorizationsSetter('authorizations', $auth_settings));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function save()
	{
		if ($this->form->get_value('members_age_displayed'))
		{
			$this->config->display_members_age();
		}
		else
			$this->config->hide_members_age();

		if ($this->form->get_value('pm_for_members_birthday_enabled'))
		{
			$this->config->enable_pm_for_members_birthday();
			$this->config->set_pm_for_members_birthday_title($this->form->get_value('pm_for_members_birthday_title'));
			$this->config->set_pm_for_members_birthday_content($this->form->get_value('pm_for_members_birthday_content'));
		}
		else
			$this->config->disable_pm_for_members_birthday();

		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());

		BirthdayConfig::save();
	}
}
?>
