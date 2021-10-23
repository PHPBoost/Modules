<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 25
 * @since       PHPBoost 5.0 - 2016 01 02
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

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

			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('warning.success.config', 'warning-lang'), MessageHelper::SUCCESS, 4));
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
