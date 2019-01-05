<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 10 29
 * @since   	PHPBoost 5.1 - 2018 03 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
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

	private $lang;
	private $admin_common_lang;

	/**
	 * @var SmalladsConfig
	 */
	private $config;
	private $comments_config;
	private $content_management_config;

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

		$fieldset = new FormFieldsetHTMLHeading('config_usage_terms_configuration', $this->lang['config.usage.terms']);
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
