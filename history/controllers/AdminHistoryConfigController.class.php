<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 11 19
 * @since       PHPBoost 6.0 - 2021 10 22
*/

class AdminHistoryConfigController extends DefaultAdminModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		$title = StringVars::replace_vars($this->lang['form.module.title'], array('module_name' => self::get_module_configuration()->get_name()));
		$this->build_form($title);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.success.config'], MessageHelper::SUCCESS, 5));
		}

		$this->view->put('CONTENT', $this->form->display());

		return new AdminHistoryDisplayResponse($this->view, $title);
	}

	private function build_form($title)
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('configuration', $title);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldMultipleCheckbox('history_topics_disabled', $this->lang['history.config.topics.disabled'], $this->get_selected_topics_list(), $this->build_available_topics_options(),
			array(
				'class' => 'top-field mini-checkbox'
			)
		));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('log_retention_period', $this->lang['history.config.log.retention.period'], $this->config->get_log_retention_period(), $this->build_log_retention_period_options(),
			array(
				'class' => 'top-field'
			)
		));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function get_selected_topics_list()
	{
		$history_topics_disabled = $this->config->get_history_topics_disabled();
		$list = array();

		foreach ($this->get_available_topics_list() as $topic)
		{
			if (!in_array($topic, $history_topics_disabled))
				$list[] = $topic;
		}

		foreach (HooksService::get_modules_with_specific_hooks_list() as $module_id)
		{
			if (!in_array($module_id, $history_topics_disabled))
				$list[] = $module_id;
		}

		return $list;
	}

	private function get_available_topics_list()
	{
		return array('items', 'categories', 'contributions', 'moderation', 'comments', 'notation', 'config');
	}

	private function build_available_topics_options()
	{
		$list = array();

		foreach ($this->get_available_topics_list() as $topic)
		{
			$list[] = new FormFieldMultipleCheckboxOption($topic, $this->lang['history.config.topic.' . $topic]);
		}

		foreach (HooksService::get_modules_with_specific_hooks_list() as $module_id)
		{
			$list[] = new FormFieldMultipleCheckboxOption($module_id, StringVars::replace_vars($this->lang['history.config.topic.module_specific'], array('module_name' => ModulesManager::get_module($module_id)->get_configuration()->get_name())));
		}

		return $list;
	}
	
	private function build_log_retention_period_options()
	{
		$options = array();
		foreach (self::get_log_retention_periods() as $duration => $label)
		{
			$options[] = new FormFieldSelectChoiceOption($label, $duration);
		}
		return $options;
	}

	public static function get_log_retention_periods()
	{
		$lang = LangLoader::get_all_langs();
		
		return array(
			2629800  => '1 ' . $lang['date.month'],
			5259600  => '2 ' . $lang['date.months'],
			7889400  => '3 ' . $lang['date.months'],
			15778800 => '6 ' . $lang['date.months'],
			31557600 => '1 ' . $lang['date.year'],
			63115200 => '2 ' . $lang['date.years'],
			0        => $lang['common.always']
		);
	}

	private function save()
	{
		$history_topics_disabled = array_merge($this->get_available_topics_list(), HooksService::get_modules_with_specific_hooks_list());
		foreach ($this->form->get_value('history_topics_disabled') as $field => $value)
		{
			unset($history_topics_disabled[array_search((string)$value->get_id(), $history_topics_disabled)]);
		}
		$this->config->set_history_topics_disabled($history_topics_disabled);
		
		$this->config->set_log_retention_period($this->form->get_value('log_retention_period')->get_raw_value());

		HistoryConfig::save();
	}
}
?>
