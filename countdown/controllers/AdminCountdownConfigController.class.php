<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 16
 * @since       PHPBoost 4.1 - 2014 12 12
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class AdminCountdownConfigController extends AdminModuleController
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
	/**
	 * @var GoogleAnalyticsConfig
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
			$this->form->get_field_by_id('no_event')->set_hidden(!$this->config->get_timer_disabled());
			$this->form->get_field_by_id('stopped_event')->set_hidden(!$this->config->get_stop_counter());
			$this->form->get_field_by_id('hidden_counter')->set_hidden(!$this->config->get_stop_counter());
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 4));
		}

		$tpl->put('FORM', $this->form->display());

		return new DefaultAdminDisplayResponse($tpl);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'countdown');
		$this->config = CountdownConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm('countdown');

		$fieldset = new FormFieldsetHTMLHeading('configuration', StringVars::replace_vars(LangLoader::get_message('configuration.module.title', 'admin-common'), array('module_name' => self::get_module()->get_configuration()->get_name())));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldDateTime('event_date', $this->lang['config.event.date'], $this->config->get_event_date(),
			array('class' => 'half-field')
		));

		$fieldset->add_field(new FormFieldTextEditor('no_javas', $this->lang['config.no.script'], $this->config->get_no_javas(),
			array('class' => 'half-field', 'description' => $this->lang['config.no.script.desc'])));

		$fieldset->add_field(new FormFieldTextEditor('next_event', $this->lang['config.next.event'], $this->config->get_next_event(),
			array('class' => 'half-field', 'description' => $this->lang['config.next.event.desc'])));

		$fieldset->add_field(new FormFieldTextEditor('last_event', $this->lang['config.last.event'], $this->config->get_last_event(),
			array('class' => 'half-field', 'description' => $this->lang['config.last.event.desc'])));

		$fieldset->add_field(new FormFieldCheckbox('stop_counter', $this->lang['config.stop.counter'], $this->config->get_stop_counter(),
			array(
				'class' => 'custom-checkbox',
				'events' => array('click' => '
					if (HTMLForms.getField("stop_counter").getValue()) {
						HTMLForms.getField("hidden_counter").enable();
						HTMLForms.getField("stopped_event").enable();
					} else {
						HTMLForms.getField("hidden_counter").disable();
						HTMLForms.getField("stopped_event").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('hidden_counter', $this->lang['config.hidden.counter'], $this->config->get_hidden_counter(),
			array('class' => 'custom-checkbox', 'hidden' => !$this->config->get_stop_counter())
		));

		$fieldset->add_field(new FormFieldTextEditor('stopped_event', $this->lang['config.stopped.event'], $this->config->get_stopped_event(),
			array('class' => 'half-field', 'hidden' => !$this->config->get_stop_counter(), 'description' => $this->lang['config.stopped.event.desc'])
		));

		$fieldset->add_field(new FormFieldFree('1_separator', '', ''));

		$fieldset->add_field(new FormFieldCheckbox('timer_disabled', $this->lang['config.timer.disabled'], $this->config->get_timer_disabled(),
			array(
				'class' => 'custom-checkbox',
				'events' => array('click' => '
					if (HTMLForms.getField("timer_disabled").getValue()) {
						HTMLForms.getField("no_event").enable();
					} else {
						HTMLForms.getField("no_event").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldTextEditor('no_event', $this->lang['config.no.event'], $this->config->get_no_event(),
			array('class' => 'half-field', 'hidden' => !$this->config->get_timer_disabled(), 'description' => $this->lang['config.no.event.desc'])
		));

		$common_lang = LangLoader::get('common');
		$fieldset_authorizations = new FormFieldsetHTML('authorizations', $common_lang['authorizations']);
		$form->add_fieldset($fieldset_authorizations);

		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['config.authorizations.read'], CountdownAuthorizationsService::READ_AUTHORIZATIONS)
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
		$this->config->set_event_date($this->form->get_value('event_date'));
		$this->config->set_no_javas($this->form->get_value('no_javas'));
		$this->config->set_next_event($this->form->get_value('next_event'));
		$this->config->set_last_event($this->form->get_value('last_event'));

		$this->config->set_timer_disabled($this->form->get_value('timer_disabled'));

		if ($this->form->get_value('timer_disabled'))
			$this->config->set_no_event($this->form->get_value('no_event'));

		$this->config->set_stop_counter($this->form->get_value('stop_counter'));

		if ($this->form->get_value('stop_counter')) {
			$this->config->set_hidden_counter($this->form->get_value('hidden_counter'));
			$this->config->set_stopped_event($this->form->get_value('stopped_event'));
		}


		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());
		CountdownConfig::save();
	}
}
?>
