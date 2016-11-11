<?php
/*##################################################
 *                       AdminLastcomsConfigController.class.php
 *                            -------------------
 *   begin                       : July 26, 2009
 *   copyright                   : (C) 2009 ROGUELON Geoffrey
 *   email                       : liaght@gmail.com
 *   Adapted for Phpboost 4.1 by : babsolune - babso@web33.fr 
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

		return new AdminLastcomsDisplayResponse($tpl, $this->lang['module_config_title']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'lastcoms');
		$this->config = LastcomsConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm('lastcoms');

		$fieldset = new FormFieldsetHTML('configuration', LangLoader::get_message('configuration', 'admin'));
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('lastcoms_number', $this->lang['lastcoms_number'], $this->config->get_lastcoms_number(),
			array('description' => $this->lang['lastcoms_number.explain'])));
		
		$fieldset->add_field(new FormFieldTextEditor('lastcoms_char', $this->lang['lastcoms_char'], $this->config->get_lastcoms_char(),
			array('description' => $this->lang['lastcoms_char.explain'])));
		
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