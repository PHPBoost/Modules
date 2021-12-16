<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 4.1 - 2014 09 24
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminTeamspeakConfigController extends DefaultAdminModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.success.config'], MessageHelper::SUCCESS, 5));
		}

		$this->view->put('CONTENT', $this->form->display());

		return new DefaultAdminDisplayResponse($this->view);
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('configuration', StringVars::replace_vars($this->lang['form.module.title'], array('module_name' => self::get_module()->get_configuration()->get_name())));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('ts_ip', $this->lang['ts.ip'], $this->config->get_ip(),
			array(
				'maxlength' => 255, 'required' => true,
				'description' => $this->lang['ts.ip.clue']
			),
			array(new FormFieldConstraintRegex('`^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$|^(([a-zA-Z]|[a-zA-Z][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z]|[A-Za-z][A-Za-z0-9\-]*[A-Za-z0-9])$|^((([0-9A-Fa-f]{1,4}:){7}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}:[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){5}:([0-9A-Fa-f]{1,4}:)?[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){4}:([0-9A-Fa-f]{1,4}:){0,2}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){3}:([0-9A-Fa-f]{1,4}:){0,3}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){2}:([0-9A-Fa-f]{1,4}:){0,4}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}((b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b).){3}(b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b))|(([0-9A-Fa-f]{1,4}:){0,5}:((b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b).){3}(b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b))|(::([0-9A-Fa-f]{1,4}:){0,5}((b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b).){3}(b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b))|([0-9A-Fa-f]{1,4}::([0-9A-Fa-f]{1,4}:){0,5}[0-9A-Fa-f]{1,4})|(::([0-9A-Fa-f]{1,4}:){0,6}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){1,7}:))$`iu'))
		));

		$fieldset->add_field(new FormFieldTextEditor('ts_voice', $this->lang['ts.voice'], $this->config->get_voice(),
			array(
				'maxlength' => 5, 'size' => 5, 'required' => true,
				'description' => $this->lang['ts.voice.clue']
			),
			array(new FormFieldConstraintIntegerRange(1, 65535))
		));

		$fieldset->add_field(new FormFieldTextEditor('ts_query', $this->lang['ts.query'], $this->config->get_query(),
			array(
				'maxlength' => 5, 'size' => 5, 'required' => true,
				'description' => $this->lang['ts.query.clue']
			),
			array(new FormFieldConstraintIntegerRange(1, 65535))
		));

		$fieldset->add_field(new FormFieldTextEditor('ts_user', $this->lang['ts_user'], $this->config->get_user(),
			array(
				'required' => true,
				'description' => $this->lang['ts.user.clue']
			)
		));

		$fieldset->add_field($password = new FormFieldPasswordEditor('ts_pass', $this->lang['ts_pass'], $this->config->get_pass(),
			array(
				'required' => true,
				'description' => $this->lang['ts.pass.clue']
			)
		));

		$fieldset->add_field(new FormFieldTextEditor('ts_refresh_delay', $this->lang['ts_refresh_delay'], $this->config->get_refresh_delay(),
			array(
				'maxlength' => 2, 'size' => 2, 'required' => true,
				'description' => $this->lang['ts.refresh.delay.clue']
			),
			array(new FormFieldConstraintIntegerRange(0, 60))
		));

		$fieldset->add_field(new FormFieldCheckbox('clients_number_displayed', $this->lang['ts.display.clients.number'], $this->config->is_clients_number_displayed(),
			array('class' => 'custom-checkbox')
		));

		$fieldset_authorizations = new FormFieldsetHTML('authorizations_fieldset', $this->lang['form.authorizations']);
		$form->add_fieldset($fieldset_authorizations);

		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['form.authorizations.read'], TeamspeakAuthorizationsService::READ_AUTHORIZATIONS),
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
		$this->config->set_ip($this->form->get_value('ts_ip'));
		$this->config->set_voice($this->form->get_value('ts_voice'));
		$this->config->set_query($this->form->get_value('ts_query'));
		$this->config->set_user($this->form->get_value('ts_user'));
		$this->config->set_pass($this->form->get_value('ts_pass'));
		$this->config->set_refresh_delay($this->form->get_value('ts_refresh_delay'));

		if ($this->form->get_value('clients_number_displayed'))
			$this->config->display_clients_number();
		else
			$this->config->hide_clients_number();

		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());

		TeamspeakConfig::save();
		TeamspeakCache::invalidate();
		HooksService::execute_hook_action('edit_config', self::$module_id, array('title' => StringVars::replace_vars($this->lang['form.module.title'], array('module_name' => self::get_module_configuration()->get_name())), 'url' => ModulesUrlBuilder::configuration()->rel()));
	}
}
?>
