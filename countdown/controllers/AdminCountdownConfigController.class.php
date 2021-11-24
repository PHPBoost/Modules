<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 11 23
 * @since       PHPBoost 4.1 - 2014 12 12
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
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

		$view = new StringTemplate('# INCLUDE MESSAGE_HELPER # # INCLUDE FORM #');
		$view->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('no_event')->set_hidden(!$this->config->get_timer_disabled());
			$this->form->get_field_by_id('stopped_event')->set_hidden(!$this->config->get_stop_counter());
			$this->form->get_field_by_id('hidden_counter')->set_hidden(!$this->config->get_stop_counter());
			$view->put('MESSAGE_HELPER', MessageHelper::display(LangLoader::get_message('warning.success.config', 'warning-lang'), MessageHelper::SUCCESS, 4));
		}

		$view->put('FORM', $this->form->display());

		return new DefaultAdminDisplayResponse($view);
	}

	private function init()
	{
		$this->lang = array_merge(
			LangLoader::get('common', 'countdown'),
			LangLoader::get('form-lang')
		);
		$this->config = CountdownConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm('countdown');

		$fieldset = new FormFieldsetHTML('configuration', StringVars::replace_vars($this->lang['form.module.title'], array('module_name' => self::get_module()->get_configuration()->get_name())));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldDateTime('event_date', $this->lang['countdown.config.event.date'], $this->config->get_event_date(),
			array('class' => 'half-field')
		));

		$fieldset->add_field(new FormFieldTextEditor('no_js', $this->lang['countdown.config.no.script'], $this->config->get_no_js(),
			array(
				'class' => 'half-field',
				'description' => $this->lang['countdown.config.no.script.clue']
			)
		));

		$fieldset->add_field(new FormFieldTextEditor('next_event', $this->lang['countdown.config.next.event'], $this->config->get_next_event(),
			array(
				'class' => 'half-field',
				'description' => $this->lang['countdown.config.next.event.clue']
			)
		));

		$fieldset->add_field(new FormFieldTextEditor('last_event', $this->lang['countdown.config.last.event'], $this->config->get_last_event(),
			array(
				'class' => 'half-field',
				'description' => $this->lang['countdown.config.last.event.clue']
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('stop_counter', $this->lang['countdown.config.stop.counter'], $this->config->get_stop_counter(),
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

		$fieldset->add_field(new FormFieldCheckbox('hidden_counter', $this->lang['countdown.config.hide.counter'], $this->config->get_hidden_counter(),
			array(
				'class' => 'custom-checkbox',
				'hidden' => !$this->config->get_stop_counter()
			)
		));

		$fieldset->add_field(new FormFieldTextEditor('stopped_event', $this->lang['countdown.config.event.over'], $this->config->get_stopped_event(),
			array(
				'class' => 'half-field',
				'hidden' => !$this->config->get_stop_counter(),
				'description' => $this->lang['countdown.config.event.over.clue']
			)
		));

		$fieldset->add_field(new FormFieldSpacer('1_separator', ''));

		$fieldset->add_field(new FormFieldCheckbox('timer_disabled', $this->lang['countdown.config.disable.countdown'], $this->config->get_timer_disabled(),
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

		$fieldset->add_field(new FormFieldTextEditor('no_event', $this->lang['countdown.config.no.event'], $this->config->get_no_event(),
			array(
				'class' => 'half-field',
				'hidden' => !$this->config->get_timer_disabled(),
				'description' => $this->lang['countdown.config.no.event.clue']
			)
		));

		$fieldset_authorizations = new FormFieldsetHTML('authorizations', $this->lang['form.authorizations']);
		$form->add_fieldset($fieldset_authorizations);

		$auth_settings = new AuthorizationsSettings(array(new ActionAuthorization($this->lang['form.authorizations.read'], CountdownAuthorizationsService::READ_AUTHORIZATIONS)));
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
		$this->config->set_no_js($this->form->get_value('no_js'));
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
		HooksService::execute_hook_action('edit_config', self::$module_id, array('title' => StringVars::replace_vars($this->lang['form.module.title'], array('module_name' => self::get_module_configuration()->get_name())), 'url' => ModulesUrlBuilder::configuration()->rel()));
	}
}
?>
