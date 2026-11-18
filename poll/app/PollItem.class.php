<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      xela <xela@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 10
 * @since       PHPBoost 6.0 - 2020 05 14
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class PollItem extends RichItem
{
	const MODULE_ID = 'poll';

	const DEFAULT_VALUE_QUESTION          = '';
	const DEFAULT_VALUE_ANSWERS_TYPE      = '1';
	const DEFAULT_VALUE_ANSWERS           = [];
	const DEFAULT_VALUE_VOTES             = [];
	const DEFAULT_VALUE_VOTES_NUMBER      = 0;
	const DEFAULT_VALUE_COUNTDOWN_DISPLAY = 2;

	const COUNTDOWN_DISPLAY_WITHOUT_S = '2';
	const COUNTDOWN_DISPLAY_WITH_S    = '1';
	const COUNTDOWN_NO_DISPLAY        = '0';

	public int $current_user_id = 0;
	public array $lang = [];

	public function __construct()
	{
		$this->content_field_enabled = false;
		$this->summary_field_enabled = false;
		$this->lang = LangLoader::get_all_langs(self::MODULE_ID);
		$this->current_user_id = AppContext::get_current_user()->get_id();

		parent::__construct(self::MODULE_ID);
	}

	protected function set_additional_attributes_list(): void
	{
		$this->add_additional_attribute('question', ['type' => 'text', 'length' => 65000, 'notnull' => 1, 'attribute_pre_content_field_parameters' => [
			'field_class' => 'FormFieldRichTextEditor',
			'label'       => $this->lang['poll.form.question'],
			'value'       => self::DEFAULT_VALUE_QUESTION,
			'options'     => ['required' => true]
			]
		]);

		$this->add_additional_attribute('answers_type', ['type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 1, 'attribute_pre_content_field_parameters' => [
			'field_class'    => 'FormFieldRadioChoice',
			'label'          => $this->lang['poll.form.answers.type'],
			'value'          => self::DEFAULT_VALUE_ANSWERS_TYPE,
			'options'        => [
									new FormFieldRadioChoiceOption($this->lang['poll.form.single'], '1'),
									new FormFieldRadioChoiceOption($this->lang['poll.form.multiple'], '2')
								],
			'field_options'  => ['required' => true, 'class' => 'inline-radio']
			]
		]);

		$this->add_additional_attribute('answers', ['type' => 'text', 'length' => 65000, 'notnull' => 1, 'is_array' => true, 'attribute_pre_content_field_parameters' => [
			'field_class' => 'FormFieldPossibleValues',
			'label'       => $this->lang['poll.form.answers'],
			'value'       => self::DEFAULT_VALUE_ANSWERS,
			'options'     => ['required' => true, 'unique_input_value' => true, 'min_input' => 2, 'display_default' => false, 'placeholder' => $this->lang['poll.form.answer.placeholder']]
			]
		]);

		$this->add_additional_attribute('votes', ['type' => 'text', 'length' => 65000, 'notnull' => 1]);

		$this->add_additional_attribute('votes_number', ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0]);

		$this->add_additional_attribute('countdown_display', ['type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 2]);

		$this->add_additional_attribute('close_poll', ['type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0]);
	}

	public function get_question(): string
	{
		return $this->get_additional_property('question');
	}

	public function set_question(string $value): void
	{
		$this->set_additional_property('question', $value);
	}

	public function get_answers_type(): string
	{
		return $this->get_additional_property('answers_type');
	}

	public function set_answers_type(string $value): void
	{
		$this->set_additional_property('answers_type', $value);
	}

	public function get_answers(): array
	{
		return $this->get_additional_property('answers');
	}

	public function get_answers_list(): array
	{
		$answers_list = [];
		foreach ($this->get_answers() as $answer)
		{
			$answers_list[] = stripslashes($answer['title']);
		}
		return $answers_list;
	}

	public function get_votes(): array
	{
		return TextHelper::unserialize($this->get_additional_property('votes'));
	}

	public function has_votes(): bool
	{
		return $this->get_votes_number() > 0;
	}

	public function set_votes(array $value): void
	{
		$value = TextHelper::serialize($value);
		$this->set_additional_property('votes', $value);
	}

	public function get_votes_number(): int
	{
		return $this->get_additional_property('votes_number');
	}

	public function set_votes_number(int $value): void
	{
		$this->set_additional_property('votes_number', $value);
	}

	public function get_close_poll(): bool
	{
		return $this->get_additional_property('close_poll');
	}

	public function is_closed(): bool
	{
		return $this->get_close_poll() > 0;
	}

	public function set_close_poll(bool $value): void
	{
		$this->set_additional_property('close_poll', $value);
	}

	public function get_mini_module_map(): mixed
	{
		return $this->load_conf_parameters()->get_mini_module_selected_items();
	}

	public function is_in_mini_module_map(): bool
	{
		return in_array($this->get_id(), $this->get_mini_module_map());
	}

	public function set_item_in_mini_module_map(): void
	{
		if (!$this->is_in_mini_module_map())
		{
			$update_mini_module_map = $this->get_mini_module_map();
			$update_mini_module_map[] = $this->get_id();

			$this->load_conf_parameters()->set_mini_module_selected_items(array_unique($update_mini_module_map));
			$this->save_conf_parameters();
		}
	}

	public function unset_item_in_mini_module_map(): void
	{
		if ($this->is_in_mini_module_map())
		{
			$update_mini_module_map = $this->get_mini_module_map();
			$item_id_key = array_search($this->get_id(), $update_mini_module_map);
			unset($update_mini_module_map[$item_id_key]);

			$this->load_conf_parameters()->set_mini_module_selected_items($update_mini_module_map);
			$this->save_conf_parameters();
		}
	}

	public function get_countdown_display(): bool
	{
		return $this->get_additional_property('countdown_display');
	}

	public function set_countdown_display(int $value): void
	{
		$this->set_additional_property('countdown_display', $value);
	}

	public function user_has_vote_authorization(): bool
	{
		return PollAuthorizationsService::check_authorizations()->vote();
	}

	private function get_poll_manager(): PollManager
	{
		$manager = ItemsService::get_items_manager(self::MODULE_ID);
		if (!$manager instanceof PollManager)
		{
			throw new LogicException('The poll items manager is not available.');
		}

		return $manager;
	}

	public function user_has_voted(): bool
	{
		return $this->has_votes() ? $this->get_poll_manager()->user_has_voted($this->current_user_id, $this->get_id()) : false;
	}

	public function user_is_empowered_to_vote(): bool
	{
		return !$this->is_closed()
				&& $this->is_published()
				&& $this->user_has_vote_authorization()
				&& !$this->user_has_voted();
	}
	// evolution
	public function get_voter_id(): int
	{
		return $this->get_poll_manager()->get_voter($this->get_id(), $this->current_user_id)['id'];
	}
	// evolution
	public function get_voter_user_id(): int
	{
		return $this->get_poll_manager()->get_voter($this->get_id(), $this->current_user_id)['voter_id'];
	}
	// evolution
	public function get_voter_ip(): string
	{
		return $this->get_poll_manager()->get_voter($this->get_id(), $this->current_user_id)['voter_ip'];
	}
	// evolution
	public function get_voter_vote_date(): int
	{
		return $this->get_poll_manager()->get_voter($this->get_id(), $this->current_user_id)['vote_timestamp'];
	}

	protected function default_properties(): void
	{
		$this->set_votes(self::DEFAULT_VALUE_VOTES);
		$this->set_votes_number(self::DEFAULT_VALUE_VOTES_NUMBER);
		$this->set_close_poll(FormFieldCheckbox::UNCHECKED);
		$this->set_countdown_display(self::DEFAULT_VALUE_COUNTDOWN_DISPLAY);
	}

	public function load_conf_parameters(): mixed
	{
		return $this->poll_module()->get_configuration()->get_configuration_parameters();
	}

	public function save_conf_parameters(): void
	{
		$configuration_class_name = $this->poll_module()->get_configuration()->get_configuration_name();
		$configuration_class_name::save(self::MODULE_ID);
	}

	public function poll_module(): mixed
	{
		return ModulesManager::get_module(self::MODULE_ID);
	}

	protected function get_additional_sorting_fields(): array
	{
		return ['close_poll' => ['database_field' => 'close_poll', 'label' => $this->lang['poll.sorting.field.closed'], 'icon' => 'fas fa-window-close']];
	}

	public function get_additional_template_vars(): array
	{
		return ['C_COMPLETED' => $this->is_closed()];
	}
}
?>
