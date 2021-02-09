<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 02 09
 * @since       PHPBoost 4.1 - 2016 02 15
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminDictionaryConfigController extends AdminModuleController
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
	 * @var ForumConfig
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
			$this->form->get_field_by_id('forbidden_tags')->set_selected_options($this->config->get_forbidden_tags());
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminDictionaryDisplayResponse($tpl, $this->lang['module_config_title']);
	}

	private function init()
	{
		$this->config = DictionaryConfig::load();
		$this->lang = LangLoader::get('common', 'dictionary');
		$this->admin_common_lang = LangLoader::get('admin-common');
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('config', $this->admin_common_lang['configuration']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldNumberEditor('items_number_per_page', $this->admin_common_lang['config.items_number_per_page'], $this->config->get_items_number_per_page(),
			array('class' => 'top-field', 'min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldMultipleSelectChoice('forbidden_tags', $this->admin_common_lang['config.forbidden-tags'], $this->config->get_forbidden_tags(), $this->generate_forbidden_tags_option(),
			array('size' => 10)
		));

		$common_lang = LangLoader::get('common');
		$fieldset_authorizations = new FormFieldsetHTML('authorizations_fieldset', $common_lang['authorizations']);
		$form->add_fieldset($fieldset_authorizations);

		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($common_lang['authorizations.read'], DictionaryAuthorizationsService::READ_AUTHORIZATIONS),
			new VisitorDisabledActionAuthorization($common_lang['authorizations.write'], DictionaryAuthorizationsService::WRITE_AUTHORIZATIONS),
			new VisitorDisabledActionAuthorization($common_lang['authorizations.contribution'], DictionaryAuthorizationsService::CONTRIBUTION_AUTHORIZATIONS),
			new MemberDisabledActionAuthorization($common_lang['authorizations.moderation'], DictionaryAuthorizationsService::MODERATION_AUTHORIZATIONS)
		));
		$auth_settings->build_from_auth_array($this->config->get_authorizations());
		$fieldset_authorizations->add_field(new FormFieldAuthorizationsSetter('authorizations', $auth_settings));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function generate_forbidden_tags_option()
	{
		$options = array();
		foreach (AppContext::get_content_formatting_service()->get_available_tags() as $identifier => $name)
		{
			$options[] = new FormFieldSelectChoiceOption($name, $identifier);
		}
		return $options;
	}

	private function save()
	{
		$this->config->set_items_number_per_page($this->form->get_value('items_number_per_page'));

		$forbidden_tags = array();
		foreach ($this->form->get_value('forbidden_tags') as $field => $option)
		{
			$forbidden_tags[] = $option->get_raw_value();
		}

		$this->config->set_forbidden_tags($forbidden_tags);

		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());

		DictionaryConfig::save();
	}
}
?>
