<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 05 27
 * @since       PHPBoost 3.0 - 2012 12 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminGoogleAnalyticsController extends AdminModuleController
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

		$view = new StringTemplate('# INCLUDE MESSAGE_HELPER # # INCLUDE FORM #');

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$view->put('MESSAGE_HELPER', MessageHelper::display(LangLoader::get_message('warning.message.success.config', 'warning-lang'), MessageHelper::SUCCESS, 5));
		}

		$view->put('FORM', $this->form->display());

		return new DefaultAdminDisplayResponse($view);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'GoogleAnalytics');
		$this->config = GoogleAnalyticsConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm('GoogleAnalytics');

		$fieldset = new FormFieldsetHTML('configuration', StringVars::replace_vars(LangLoader::get_message('form.module.title', 'form-lang'), array('module_name' => self::get_module()->get_configuration()->get_name())));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('identifier', $this->lang['ga.identifier'], $this->config->get_identifier(),
			array(
				'class' => 'half-field', 'required' => true,
				'description' => $this->lang['ga.identifier.clue']
			)
		));

		$fieldset->add_field(new FormFieldFree('login', LangLoader::get_message('user.sign.in', 'user-lang'), StringVars::replace_vars($this->lang['ga.analytics.login'], array('link' => 'https://www.google.com/analytics/')),
			array('class' => 'half-field')
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
