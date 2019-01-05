<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 10 29
 * @since   	PHPBoost 3.0 - 2012 12 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

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
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
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

		$fieldset = new FormFieldsetHTMLHeading('configuration', $this->lang['configuration']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('identifier', $this->lang['identifier'], $this->config->get_identifier(),
			array('class' => 'half-field', 'description' => $this->lang['identifier.explain'], 'required' => true)));

		$fieldset->add_field(new FormFieldFree('login', LangLoader::get_message('connection', 'user-common'),
			StringVars::replace_vars($this->lang['analytics_login'], array('link' => 'https://www.google.com/analytics/')),
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
