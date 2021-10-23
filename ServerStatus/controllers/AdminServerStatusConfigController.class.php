<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 23
 * @since       PHPBoost 4.0 - 2013 08 04
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminServerStatusConfigController extends AdminController
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

		$view = new StringTemplate('# INCLUDE MESSAGE_HELPER # # INCLUDE CURL_WARNING_MSG # # INCLUDE FORM #');

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$view->put('MESSAGE_HELPER', MessageHelper::display(LangLoader::get_message('warning.success.config', 'warning-lang'), MessageHelper::SUCCESS, 5));
		}

		$server_configuration = new ServerConfiguration();
		if ($server_configuration->has_curl_library())
		{
			$view->put('CURL_WARNING_MSG', MessageHelper::display($this->lang['server.warning.curl.extension'], MessageHelper::WARNING));
		}

		$view->put('FORM', $this->form->display());

		return new AdminServerStatusDisplayResponse($view, LangLoader::get_message('form.configuration', 'form-lang'));
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'ServerStatus');
		$this->config = ServerStatusConfig::load();
	}

	private function build_form()
	{
		$form_lang = LangLoader::get('form-lang');
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('configuration', StringVars::replace_vars($form_lang['form.module.title'], array('module_name' => $this->lang['server.module.title'])));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldNumberEditor('refresh_delay', $this->lang['server.refresh.delay'], $this->config->get_refresh_delay(),
			array(
				'required' => true,
				'description' => $this->lang['server.refresh.delay.clue']
			),
			array(new FormFieldConstraintRegex('`^[0-9]+$`iu'))
		));

		$fieldset->add_field(new FormFieldNumberEditor('timeout', $this->lang['server.timeout'], $this->config->get_timeout(),
			array(
				'required' => true,
				'description' => $this->lang['server.timeout.clue']
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('address_displayed', $this->lang['server.display.address'], $this->config->is_address_displayed(),
			array(
				'class' => 'custom-checkbox',
				'description' => $this->lang['server.display.address.clue']
			)
		));

		$fieldset_authorizations = new FormFieldsetHTML('authorizations', $form_lang['form.authorizations']);
		$form->add_fieldset($fieldset_authorizations);

		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($form_lang['form.authorizations.read'], ServerStatusAuthorizationsService::READ_AUTHORIZATIONS),
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
		$this->config->set_refresh_delay($this->form->get_value('refresh_delay'));
		$this->config->set_timeout($this->form->get_value('timeout'));

		if ($this->form->get_value('address_displayed'))
		{
			$this->config->display_address();
		}
		else
			$this->config->hide_address();

		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());

		ServerStatusConfig::save();
	}
}
?>
