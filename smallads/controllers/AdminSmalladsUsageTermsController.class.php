<?php
/*##################################################
 *                   AdminSmalladsUsageTermsController.class.php
 *                            -------------------
 *   begin                : March 15, 2018
 *   copyright            : (C) 2018 Sebastien LARTIGUE
 *   email                : babsolune@phpboost.com
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

/**
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class AdminSmalladsUsageTermsController extends AdminModuleController
{
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonSubmit
	 */
	private $submit_button;

	private $comments_config;
	private $content_management_config;

	private $lang;
	private $admin_common_lang;

	/**
	 * @var SmalladsConfig
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
			$this->form->get_field_by_id('usage_terms')->set_hidden(!$this->config->are_usage_terms_displayed());
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 4));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminSmalladsDisplayResponse($tpl, $this->lang['config.usage.terms']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'smallads');
		$this->admin_common_lang = LangLoader::get('admin-common');
		$this->config = SmalladsConfig::load();
		$this->comments_config = CommentsConfig::load();
		$this->content_management_config = ContentManagementConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('config_usage_terms_configuration', $this->lang['config.usage.terms']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('usage_terms_displayed', $this->lang['config.usage.terms.displayed'], $this->config->are_usage_terms_displayed(),
			array('events' => array('click' => '
				if (HTMLForms.getField("usage_terms_displayed").getValue()) {
					HTMLForms.getField("usage_terms").enable();
				} else {
					HTMLForms.getField("usage_terms").disable();
				}'
			)
		)));

		$fieldset->add_field(new FormFieldRichTextEditor('usage_terms', $this->lang['config.usage.terms.desc'], $this->config->get_usage_terms(),
			array('rows' => 25, 'hidden' => !$this->config->are_usage_terms_displayed())
		));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}


	private function save()
	{

		if ($this->form->get_value('usage_terms_displayed'))
		{
			$this->config->display_usage_terms();
			$this->config->set_usage_terms($this->form->get_value('usage_terms'));
		}
		else
			$this->config->hide_usage_terms();

		SmalladsConfig::save();
		SmalladsService::get_categories_manager()->regenerate_cache();
	}
}
?>
