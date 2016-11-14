<?php
/*##################################################
 *                               AdminTeamspeakConfigController.class.php
 *                            -------------------
 *   begin                : September 24, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
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

class AdminTeamspeakConfigController extends AdminModuleController
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
	 * @var TeamspeakConfig
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
		
		return new AdminTeamspeakDisplayResponse($tpl, $this->lang['ts_title']);
	}
	
	private function init()
	{
		$this->config = TeamspeakConfig::load();
		$this->lang = LangLoader::get('common', 'teamspeak');
	}
	
	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('config', LangLoader::get_message('configuration', 'admin'));
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('ts_ip', $this->lang['ts_ip'], $this->config->get_ip(),
			array('maxlength' => 255, 'description' => $this->lang['ts_ip_explain'], 'required' => true),
			array(new FormFieldConstraintRegex('`^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$|^(([a-zA-Z]|[a-zA-Z][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z]|[A-Za-z][A-Za-z0-9\-]*[A-Za-z0-9])$|^((([0-9A-Fa-f]{1,4}:){7}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}:[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){5}:([0-9A-Fa-f]{1,4}:)?[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){4}:([0-9A-Fa-f]{1,4}:){0,2}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){3}:([0-9A-Fa-f]{1,4}:){0,3}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){2}:([0-9A-Fa-f]{1,4}:){0,4}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}((b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b).){3}(b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b))|(([0-9A-Fa-f]{1,4}:){0,5}:((b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b).){3}(b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b))|(::([0-9A-Fa-f]{1,4}:){0,5}((b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b).){3}(b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b))|([0-9A-Fa-f]{1,4}::([0-9A-Fa-f]{1,4}:){0,5}[0-9A-Fa-f]{1,4})|(::([0-9A-Fa-f]{1,4}:){0,6}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){1,7}:))$`iu'))
		));
		
		$fieldset->add_field(new FormFieldTextEditor('ts_voice', $this->lang['ts_voice'], $this->config->get_voice(),
			array('maxlength' => 5, 'size' => 5, 'description' => $this->lang['ts_voice_explain'], 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 65535))
		));
		
		$fieldset->add_field(new FormFieldTextEditor('ts_query', $this->lang['ts_query'], $this->config->get_query(),
			array('maxlength' => 5, 'size' => 5, 'description' => $this->lang['ts_query_explain'], 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 65535))
		));
		
		$fieldset->add_field(new FormFieldTextEditor('ts_user', $this->lang['ts_user'], $this->config->get_user(), 
			array('description' => $this->lang['ts_user_explain'], 'required' => true)
		));
		
		$fieldset->add_field($password = new FormFieldPasswordEditor('ts_pass', $this->lang['ts_pass'], $this->config->get_pass(),
			array('description' => $this->lang['ts_pass_explain'], 'required' => true)
		));
		
		$fieldset->add_field(new FormFieldTextEditor('ts_refresh_delay', $this->lang['ts_refresh_delay'], $this->config->get_refresh_delay(), 
			array('maxlength' => 2, 'size' => 2, 'description' => $this->lang['ts_refresh_delay_explain'], 'required' => true),
			array(new FormFieldConstraintIntegerRange(0, 60))
		));
		
		$fieldset->add_field(new FormFieldCheckbox('clients_number_displayed', $this->lang['admin.clients_number_displayed'], $this->config->is_clients_number_displayed()));
		
		$fieldset_authorizations = new FormFieldsetHTML('authorizations_fieldset', LangLoader::get_message('authorizations', 'common'));
		$form->add_fieldset($fieldset_authorizations);
		
		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['admin.authorizations.read'], TeamspeakAuthorizationsService::READ_AUTHORIZATIONS),
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
	}
}
?>
