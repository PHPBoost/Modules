<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Geoffrey ROGUELON <liaght@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 16
 * @since       PHPBoost 3.0 - 2009 07 26
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminLastcomsConfigController extends AdminModuleController
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

		$view = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$view->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$view->put('MSG', MessageHelper::display(LangLoader::get_message('warning.success.config', 'warning-lang'), MessageHelper::SUCCESS, 5));
		}

		$view->put('FORM', $this->form->display());

		return new DefaultAdminDisplayResponse($view);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'lastcoms');
		$this->config = LastcomsConfig::load();
	}

	private function build_form()
	{
		$form_lang = LangLoader::get('form-lang');
		$form = new HTMLForm('lastcoms');

		$fieldset = new FormFieldsetHTML('configuration', StringVars::replace_vars($form_lang['form.module.title'], array('module_name' => self::get_module()->get_configuration()->get_name())));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldNumberEditor('lastcoms_number', $this->lang['lastcoms.number'], $this->config->get_lastcoms_number(),
			array('description' => $this->lang['lastcoms.number.clue'])
		));

		$fieldset->add_field(new FormFieldNumberEditor('lastcoms_char', $this->lang['lastcoms.char'], $this->config->get_lastcoms_char(),
			array('description' => $this->lang['lastcoms.char.clue'])
		));

		$fieldset_authorizations = new FormFieldsetHTML('authorizations', $form_lang['form.authorizations']);
		$form->add_fieldset($fieldset_authorizations);

		$auth_settings = new AuthorizationsSettings(array(new ActionAuthorization($form_lang['form.authorizations.read'], LastcomsAuthorizationsService::READ_AUTHORIZATIONS)));
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
