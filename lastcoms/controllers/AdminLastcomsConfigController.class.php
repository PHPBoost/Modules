<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Geoffrey ROGUELON <liaght@gmail.com>
 * @version   	PHPBoost 5.2 - last update: 2018 12 22
 * @since   	PHPBoost 3.0 - 2009 07 26
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminLastcomsConfigController extends AdminController
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
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminLastcomsDisplayResponse($tpl, $this->lang['module.config.title']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'lastcoms');
		$this->config = LastcomsConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm('lastcoms');

		$fieldset = new FormFieldsetHTMLHeading('configuration', LangLoader::get_message('configuration', 'admin'));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldNumberEditor('lastcoms_number', $this->lang['lastcoms.number'], $this->config->get_lastcoms_number(),
			array('description' => $this->lang['lastcoms.number.explain'])));

		$fieldset->add_field(new FormFieldNumberEditor('lastcoms_char', $this->lang['lastcoms.char'], $this->config->get_lastcoms_char(),
			array('description' => $this->lang['lastcoms.char.explain'])));

		$common_lang = LangLoader::get('common');
		$fieldset_authorizations = new FormFieldsetHTML('authorizations', $common_lang['authorizations']);
		$form->add_fieldset($fieldset_authorizations);

		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['admin.authorizations.read'], LastcomsAuthorizationsService::READ_AUTHORIZATIONS)
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
		$this->config->set_lastcoms_number($this->form->get_value('lastcoms_number'));
		$this->config->set_lastcoms_char($this->form->get_value('lastcoms_char'));
		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());
		LastcomsConfig::save();
	}
}
?>
