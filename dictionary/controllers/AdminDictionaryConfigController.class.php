<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 11 25
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

	/**
	 * @var ForumConfig
	 */
	private $config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();

		$view = new StringTemplate('# INCLUDE MESSAGE_HELPER # # INCLUDE FORM #');

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('forbidden_tags')->set_selected_options($this->config->get_forbidden_tags());
			$view->put('MESSAGE_HELPER', MessageHelper::display(LangLoader::get_message('warning.success.config', 'warning-lang'), MessageHelper::SUCCESS, 5));
		}

		$view->put('FORM', $this->form->display());

		return new AdminDictionaryDisplayResponse($view, $this->lang['dictionary.config.module.title']);
	}

	private function init()
	{
		$this->config = DictionaryConfig::load();
		$this->lang = array_merge(
			LangLoader::get('form-lang'),
			LangLoader::get('common', 'dictionary')
		);
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('config', $this->lang['dictionary.config.module.title']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldNumberEditor('items_per_page', $this->lang['form.items.per.page'], $this->config->get_items_per_page(),
			array('class' => 'top-field', 'min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldMultipleSelectChoice('forbidden_tags', $this->lang['form.forbidden.tags'], $this->config->get_forbidden_tags(), $this->generate_forbidden_tags_option(),
			array('size' => 10)
		));

		$fieldset_authorizations = new FormFieldsetHTML('authorizations_fieldset', $this->lang['form.authorizations']);
		$form->add_fieldset($fieldset_authorizations);

		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['form.authorizations.read'], DictionaryAuthorizationsService::READ_AUTHORIZATIONS),
			new VisitorDisabledActionAuthorization($this->lang['form.authorizations.write'], DictionaryAuthorizationsService::WRITE_AUTHORIZATIONS),
			new VisitorDisabledActionAuthorization($this->lang['form.authorizations.contribution'], DictionaryAuthorizationsService::CONTRIBUTION_AUTHORIZATIONS),
			new MemberDisabledActionAuthorization($this->lang['form.authorizations.moderation'], DictionaryAuthorizationsService::MODERATION_AUTHORIZATIONS)
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
		$this->config->set_items_per_page($this->form->get_value('items_per_page'));

		$forbidden_tags = array();
		foreach ($this->form->get_value('forbidden_tags') as $field => $option)
		{
			$forbidden_tags[] = $option->get_raw_value();
		}

		$this->config->set_forbidden_tags($forbidden_tags);

		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());

		DictionaryConfig::save();

		HooksService::execute_hook_action('edit_config', self::$module_id, array('title' => StringVars::replace_vars($this->lang['form.module.title'], array('module_name' => self::get_module_configuration()->get_name())), 'url' => ModulesUrlBuilder::configuration()->rel()));
	}
}
?>
