<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 11 23
 * @since       PHPBoost 5.1 - 2018 03 15
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

		$view = new StringTemplate('# INCLUDE MESSAGE_HELPER # # INCLUDE FORM #');

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('usage_terms')->set_hidden(!$this->config->are_usage_terms_displayed());
			$view->put('MESSAGE_HELPER', MessageHelper::display(LangLoader::get_message('warning.success.config', 'warning-lang'), MessageHelper::SUCCESS, 4));
		}

		$view->put('FORM', $this->form->display());

		return new AdminSmalladsDisplayResponse($view, $this->lang['smallads.usage.terms.management']);
	}

	private function init()
	{
		$this->lang = array_merge(
			LangLoader::get('common', 'smallads'),
			LangLoader::get('form-lang')
		);
		$this->config = SmalladsConfig::load();
		$this->comments_config = CommentsConfig::load();
		$this->content_management_config = ContentManagementConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('config_usage_terms_configuration', $this->lang['smallads.usage.terms.management']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('usage_terms_displayed', $this->lang['smallads.display.usage.terms'], $this->config->are_usage_terms_displayed(),
			array(
				'class' => 'custom-checkbox',
				'events' => array('click' => '
					if (HTMLForms.getField("usage_terms_displayed").getValue()) {
						HTMLForms.getField("usage_terms").enable();
					} else {
						HTMLForms.getField("usage_terms").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldRichTextEditor('usage_terms', $this->lang['smallads.usage.terms.clue'], $this->config->get_usage_terms(),
			array(
				'rows' => 25,
				'hidden' => !$this->config->are_usage_terms_displayed()
			)
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
		CategoriesService::get_categories_manager()->regenerate_cache();
		HooksService::execute_hook_action('edit_config', self::$module_id, array('title' => StringVars::replace_vars($this->lang['form.module.title'], array('module_name' => self::get_module_configuration()->get_name())), 'url' => ModulesUrlBuilder::configuration()->rel()));
	}
}
?>
