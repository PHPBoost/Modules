<?php
/*##################################################
 *                       AdminServerStatusConfigController.class.php
 *                            -------------------
 *   begin                : August 4, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

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
		
		if (!extension_loaded('curl'))
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
		
		$fieldset = new FormFieldsetHTML('configuration', LangLoader::get_message('configuration', 'admin'));
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('refresh_delay', $this->lang['admin.config.refresh_delay'], $this->config->get_refresh_delay(), array(
			'maxlength' => 4, 'size' => 4, 'description' => $this->lang['admin.config.refresh_delay.explain'], 'required' => true),
			array(new FormFieldConstraintRegex('`^[0-9]+$`i'))
		));
		
		$fieldset->add_field(new FormFieldTextEditor('timeout', $this->lang['admin.config.timeout'], $this->config->get_timeout(),
			array('maxlength' => 5, 'size' => 5, 'description' => $this->lang['admin.config.timeout.explain'], 'required' => true)
		));
		
		$fieldset->add_field(new FormFieldCheckbox('address_displayed', $this->lang['admin.config.address_displayed'], $this->config->is_address_displayed(),
			array('description' => $this->lang['admin.config.address_displayed.explain'])
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
