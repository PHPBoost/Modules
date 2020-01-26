<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 26
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

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE CURL_WARNING_MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}

		$server_configuration = new ServerConfiguration();
		if ($server_configuration->has_curl_library())
		{
			$tpl->put('CURL_WARNING_MSG', MessageHelper::display($this->lang['admin.config.curl_extension_disabled'], MessageHelper::WARNING));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminServerStatusDisplayResponse($tpl, LangLoader::get_message('configuration', 'admin'));
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'ServerStatus');
		$this->config = ServerStatusConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('configuration', LangLoader::get_message('configuration', 'admin'));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('refresh_delay', $this->lang['admin.config.refresh_delay'], $this->config->get_refresh_delay(),
			array(
				'maxlength' => 4, 'size' => 4, 'required' => true,
				'description' => $this->lang['admin.config.refresh_delay.explain']
			),
			array(new FormFieldConstraintRegex('`^[0-9]+$`iu'))
		));

		$fieldset->add_field(new FormFieldTextEditor('timeout', $this->lang['admin.config.timeout'], $this->config->get_timeout(),
			array(
				'maxlength' => 5, 'size' => 5, 'required' => true,
				'description' => $this->lang['admin.config.timeout.explain']
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('address_displayed', $this->lang['admin.config.address_displayed'], $this->config->is_address_displayed(),
			array(
				'class' => 'custom-checkbox',
				'description' => $this->lang['admin.config.address_displayed.explain']
			)
		));

		$fieldset_authorizations = new FormFieldsetHTML('authorizations', $this->lang['admin.authorizations']);
		$form->add_fieldset($fieldset_authorizations);

		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['admin.authorizations.read'], ServerStatusAuthorizationsService::READ_AUTHORIZATIONS),
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
