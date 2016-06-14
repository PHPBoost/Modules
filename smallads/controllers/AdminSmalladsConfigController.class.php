<?php
/*##################################################
 *                               AdminSmalladsConfigController.class.php
 *                            -------------------
 *   begin                : February 2, 2016
 *   copyright            : (C) 2016 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class AdminSmalladsConfigController extends AdminModuleController
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
			$this->form->get_field_by_id('max_weeks_number')->set_hidden(!$this->config->is_max_weeks_number_displayed());
			$this->form->get_field_by_id('usage_terms')->set_hidden(!$this->config->are_usage_terms_displayed());
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}
		
		$tpl->put('FORM', $this->form->display());
		
		return new AdminSmalladsDisplayResponse($tpl, $this->lang['module_config_title']);
	}
	
	private function init()
	{
		$this->config = SmalladsConfig::load();
		$this->lang = LangLoader::get('common', 'smallads');
		$this->admin_common_lang = LangLoader::get('admin-common');
	}
	
	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('config', $this->admin_common_lang['configuration']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldNumberEditor('items_number_per_page', $this->admin_common_lang['config.items_number_per_page'], $this->config->get_items_number_per_page(), 
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));
		
		$fieldset->add_field(new FormFieldNumberEditor('list_size', $this->lang['config.list_size'], $this->config->get_list_size(), 
			array('min' => 1, 'max' => 10, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 10))
		));
		
		$fieldset->add_field(new FormFieldNumberEditor('max_contents_length', $this->lang['config.max_contents_length'], $this->config->get_max_contents_length(), 
			array('min' => 255, 'max' => 65535, 'required' => true),
			array(new FormFieldConstraintIntegerRange(255, 65535))
		));
		
		$fieldset->add_field(new FormFieldCheckbox('max_weeks_number_displayed', $this->lang['config.max_weeks_number_displayed'], $this->config->is_max_weeks_number_displayed(), array(
			'events' => array('click' => '
				if (HTMLForms.getField("max_weeks_number_displayed").getValue()) {
					HTMLForms.getField("max_weeks_number").enable();
				} else {
					HTMLForms.getField("max_weeks_number").disable();
				}'
			)
		)));
		
		$fieldset->add_field(new FormFieldNumberEditor('max_weeks_number', $this->lang['config.max_weeks_number'], $this->config->get_max_weeks_number(), 
			array('min' => 0, 'max' => 520, 'required' => true, 'hidden' => !$this->config->is_max_weeks_number_displayed()),
			array(new FormFieldConstraintIntegerRange(0, 520))
		));
		
		$fieldset->add_field(new FormFieldCheckbox('display_mail_enabled', $this->lang['config.display_mail_enabled'], $this->config->is_mail_displayed()));
		
		$fieldset->add_field(new FormFieldCheckbox('display_pm_enabled', $this->lang['config.display_pm_enabled'], $this->config->is_pm_displayed()));
		
		$fieldset->add_field(new FormFieldCheckbox('return_to_list_enabled', $this->lang['config.return_to_list_enabled'], $this->config->is_return_to_list_displayed()));
		
		$fieldset->add_field(new FormFieldCheckbox('usage_terms_enabled', $this->lang['config.usage_terms_enabled'], $this->config->are_usage_terms_displayed(), array(
			'events' => array('click' => '
				if (HTMLForms.getField("usage_terms_enabled").getValue()) {
					HTMLForms.getField("usage_terms").enable();
				} else {
					HTMLForms.getField("usage_terms").disable();
				}'
			)
		)));
		
		$formatter = AppContext::get_content_formatting_service()->get_default_factory();
		$formatter->set_forbidden_tags(array('code', 'math', 'html'));
		
		$fieldset->add_field(new FormFieldRichTextEditor('usage_terms', $this->lang['config.usage_terms'], $this->config->get_usage_terms(), 
			array('formatter' => $formatter, 'rows' => 8, 'cols' => 47, 'required' => true, 'hidden' => !$this->config->are_usage_terms_displayed())
		));
		
		$common_lang = LangLoader::get('common');
		$fieldset_authorizations = new FormFieldsetHTML('authorizations_fieldset', $common_lang['authorizations']);
		$form->add_fieldset($fieldset_authorizations);
		
		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($common_lang['authorizations.read'], SmalladsAuthorizationsService::READ_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['authorizations.own_crud'], SmalladsAuthorizationsService::OWN_CRUD_AUTHORIZATIONS),
			new ActionAuthorization($common_lang['authorizations.contribution'], SmalladsAuthorizationsService::CONTRIBUTION_AUTHORIZATIONS),
			new ActionAuthorization($common_lang['authorizations.moderation'], SmalladsAuthorizationsService::MODERATION_AUTHORIZATIONS)
		));
		$auth_setter = new FormFieldAuthorizationsSetter('authorizations', $auth_settings);
		$auth_settings->build_from_auth_array($this->config->get_authorizations());
		$fieldset_authorizations->add_field($auth_setter);
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());
		
		$this->form = $form;
	}
	
	private function save()
	{
		$this->config->set_items_number_per_page($this->form->get_value('items_number_per_page'));
		$this->config->set_list_size($this->form->get_value('list_size'));
		$this->config->set_max_contents_length($this->form->get_value('max_contents_length'));
		
		if ($this->form->get_value('max_weeks_number_displayed'))
		{
			$this->config->display_max_weeks_number();
			$this->config->set_max_weeks_number($this->form->get_value('max_weeks_number'));
		}
		else
			$this->config->hide_max_weeks_number();
		
		if ($this->form->get_value('display_mail_enabled'))
			$this->config->display_mail();
		else
			$this->config->hide_mail();
		
		if ($this->form->get_value('display_pm_enabled'))
			$this->config->display_pm();
		else
			$this->config->hide_pm();
		
		if ($this->form->get_value('return_to_list_enabled'))
			$this->config->display_return_to_list();
		else
			$this->config->hide_return_to_list();
		
		if ($this->form->get_value('usage_terms_enabled'))
		{
			$this->config->display_usage_terms();
			$this->config->set_usage_terms($this->form->get_value('usage_terms'));
		}
		else
			$this->config->hide_usage_terms();
		
		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());
		
		SmalladsConfig::save();
		
		// Cache Regeneration
		SmalladsCache::invalidate();
	}
}
?>
