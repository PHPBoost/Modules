<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 25
 * @since       PHPBoost 4.0 - 2013 08 27
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminBirthdayConfigController extends AdminModuleController
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

		$view = new StringTemplate('# INCLUDE MESSAGE_HELPER # # INCLUDE USER_BORN_DISABLED_MSG # # INCLUDE FORM #');
		$view->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('pm_for_members_birthday_title')->set_hidden(!$this->config->is_pm_for_members_birthday_enabled());
			$this->form->get_field_by_id('pm_for_members_birthday_content')->set_hidden(!$this->config->is_pm_for_members_birthday_enabled());
			$view->put('MESSAGE_HELPER', MessageHelper::display(LangLoader::get_message('warning.success.config', 'warning-lang'), MessageHelper::SUCCESS, 5));
		}

		$user_born_field = ExtendedFieldsCache::load()->get_extended_field_by_field_name('user_born');

		if (!empty($user_born_field) && !$user_born_field['display'])
		{
			$view->put('USER_BORN_DISABLED_MSG', MessageHelper::display($this->lang['birthday.user.born.field.disabled'], MessageHelper::WARNING));
		}

		$view->put('FORM', $this->form->display());

		return new DefaultAdminDisplayResponse($view);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'birthday');
		$this->config = BirthdayConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('configuration', StringVars::replace_vars(LangLoader::get_message('form.module.title', 'form-lang'), array('module_name' => self::get_module()->get_configuration()->get_name())));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('members_age_displayed', $this->lang['birthday.members.age.displayed'], $this->config->is_members_age_displayed(),
			array('class' => 'third-field top-field custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('pm_for_members_birthday_enabled', $this->lang['birthday.send.pm.for.members.birthday'], $this->config->is_pm_for_members_birthday_enabled(),
			array(
				'class' => 'third-field top-field custom-checkbox',
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

		$fieldset->add_field(new FormFieldTextEditor('pm_for_members_birthday_title', $this->lang['birthday.pm.for.members.birthday.title'], $this->config->get_pm_for_members_birthday_title(),
			array(
				'class' => 'third-field', 'size' => 40,
				'description' => $this->lang['birthday.pm.for.members.birthday.title.clue'],
				'hidden' => !$this->config->is_pm_for_members_birthday_enabled()
			)
		));

		$fieldset->add_field(new FormFieldShortMultiLineTextEditor('pm_for_members_birthday_content', $this->lang['birthday.pm.for.members.birthday.content'], $this->config->get_pm_for_members_birthday_content(),
			array(
				'description' => $this->lang['birthday.pm.for.members.birthday.content.clue'],
				'hidden' => !$this->config->is_pm_for_members_birthday_enabled()
			)
		));

		$fieldset_authorizations = new FormFieldsetHTML('authorizations', LangLoader::get_message('form.authorizations', 'form-lang'));
		$form->add_fieldset($fieldset_authorizations);

		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization(LangLoader::get_message('form.authorizations.menu', 'form-lang'), BirthdayAuthorizationsService::READ_AUTHORIZATIONS),
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
