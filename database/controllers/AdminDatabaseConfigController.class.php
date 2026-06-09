<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 4.1 - 2015 09 30
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminDatabaseConfigController extends DefaultAdminModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('database_tables_optimization_day')->set_hidden(!$this->config->is_database_tables_optimization_enabled());
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.success.config'], MessageHelper::SUCCESS, 5));
		}

		$this->view->put('CONTENT', $this->form->display());

		return new AdminDatabaseDisplayResponse($this->view, StringVars::replace_vars($this->lang['form.module.title'], ['module_name' => self::get_module()->get_configuration()->get_name()]));
	}

	private function build_form()
	{
		$form = new HTMLForm(self::class);

		$fieldset = new FormFieldsetHTML('configuration', StringVars::replace_vars($this->lang['form.module.title'], ['module_name' => self::get_module()->get_configuration()->get_name()]));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('database_tables_optimization_enabled', $this->lang['database.config.enable.tables.optimization'], $this->config->is_database_tables_optimization_enabled(),
			[
				'class' => 'half-field top-field custom-checkbox',
				'events' => ['change' => '
					if (HTMLForms.getField("database_tables_optimization_enabled").getValue()) {
						HTMLForms.getField("database_tables_optimization_day").enable();
					} else {
						HTMLForms.getField("database_tables_optimization_day").disable();
					}'
				]
			]
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('database_tables_optimization_day', $this->lang['database.config.tables.optimization.day'], $this->config->get_database_tables_optimization_day(),
			[
				new FormFieldSelectChoiceOption($this->lang['date.sunday'], 0),
				new FormFieldSelectChoiceOption($this->lang['date.monday'], 1),
				new FormFieldSelectChoiceOption($this->lang['date.tuesday'], 2),
				new FormFieldSelectChoiceOption($this->lang['date.wednesday'], 3),
				new FormFieldSelectChoiceOption($this->lang['date.thursday'], 4),
				new FormFieldSelectChoiceOption($this->lang['date.friday'], 5),
				new FormFieldSelectChoiceOption($this->lang['date.saturday'], 6),
				new FormFieldSelectChoiceOption($this->lang['date.every.month'], 7)
			],
			[
				'description' => $this->lang['database.config.tables.optimization.day.clue'],
				'hidden' => !$this->config->is_database_tables_optimization_enabled()
			]
		));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function save()
	{
		$this->config->set_database_tables_optimization_enabled($this->form->get_value('database_tables_optimization_enabled'));

		if (!$this->form->field_is_disabled('database_tables_optimization_day'))
		{
			$this->config->set_database_tables_optimization_day($this->form->get_value('database_tables_optimization_day')->get_raw_value());
		}

		DatabaseConfig::save();

		HooksService::execute_hook_action('edit_config', self::$module_id, ['title' => StringVars::replace_vars($this->lang['form.module.title'], ['module_name' => self::get_module_configuration()->get_name()]), 'url' => ModulesUrlBuilder::configuration()->rel()]);
	}
}
?>
