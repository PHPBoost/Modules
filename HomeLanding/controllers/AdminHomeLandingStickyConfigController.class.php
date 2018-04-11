<?php
/*##################################################
 *		                   AdminHomeLandingDocumenatationConfigController.class.php
 *                            -------------------
 *   begin                : January 2, 2016
 *   copyright            : (C) 2016 Sebastien Lartigue
 *   email                : babso@web33.fr
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

class AdminHomeLandingStickyConfigController extends AdminModuleController
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
	 * @var HomeLandingConfig
	 */
	private $config;

	/**
	 * @var HomeLandingModulesList
	 */
	private $modules;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();

			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 4));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminHomeLandingDisplayResponse($tpl, $this->lang['homelanding.sticky.manage']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('sticky', 'HomeLanding');
		$this->config = HomeLandingConfig::load();
		$this->modules = HomeLandingModulesList::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		//Sticky
        $sticky_fieldset = new FormFieldsetHTML('sticky', $this->lang['homelanding.sticky.manage']);
        $form->add_fieldset($sticky_fieldset);

        $sticky_fieldset->add_field(new FormFieldTextEditor('sticky_title', $this->lang['homelanding.sticky.title.label'], $this->config->get_sticky_title()));

        $sticky_fieldset->add_field(new FormFieldRichTextEditor('sticky_text', $this->lang['homelanding.sticky.content.label'], $this->config->get_sticky_text(), array('rows' => 25)));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function save()
	{
		 $this->config->set_sticky_title($this->form->get_value('sticky_title'));
		 $this->config->set_sticky_text($this->form->get_value('sticky_text'));

		HomeLandingConfig::save();
	}
}
?>
