<?php
/*##################################################
 *                       AdminGoogleAnalyticsController.class.php
 *                            -------------------
 *   begin                : December 20, 2012
 *   copyright            : (C) 2012 Kévin MASSY
 *   email                : kevin.massy@phpboost.com
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

class AdminGoogleAnalyticsController extends AdminController
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
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), E_USER_SUCCESS, 5));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminGoogleAnalyticsDisplayResponse($tpl, $this->lang['configuration']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'GoogleAnalytics');
		$this->config = GoogleAnalyticsConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm('GoogleAnalytics');

		$fieldset = new FormFieldsetHTML('configuration', $this->lang['configuration']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('identifier', $this->lang['identifier'], $this->config->get_identifier(),
			array('description' => $this->lang['identifier.explain'], 'required' => true)));

		$fieldset->add_field(new FormFieldFree('login', LangLoader::get_message('connect', 'user-common'), 
			StringVars::replace_vars($this->lang['analytics_login'], array('link' => 'https://www.google.com/analytics/'))
		));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());
		
		$this->form = $form;
	}
	
	private function save()
	{
		$this->config->set_identifier($this->form->get_value('identifier'));
		GoogleAnalyticsConfig::save();
	}
}
?>