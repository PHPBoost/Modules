<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 14
 * @since       PHPBoost 5.0 - 2016 01 02
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class AdminHomeLandingStickyConfigController extends DefaultAdminModuleController
{
	/**
	 * @var HomeLandingModulesList
	 */
	private $modules;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();

			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.success.config'], MessageHelper::SUCCESS, 4));
		}

		$this->view->put('CONTENT', $this->form->display());

		return new AdminHomeLandingDisplayResponse($this->view, $this->lang['homelanding.sticky.manage']);
	}

	private function init()
	{
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
