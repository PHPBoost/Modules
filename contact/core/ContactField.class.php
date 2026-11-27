<?php
/**
 * This class represent a contact field
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 26
 * @since       PHPBoost 4.0 - 2013 07 31
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ContactField
{
	const DISPLAY_FIELD_AUTHORIZATION = 1;

	private string $name;
	private string $field_name;
	private string $description;
	private string $field_type;
	private string $default_value;
	private array $possible_values = [];
	private bool $required = false;
	private bool $displayed = true;
	private string $regex;
	private bool $readonly = false;
	private bool $deletable = true;
	private array $authorization = ['r-1' => 1, 'r0' => 1, 'r1' => 1];

	private mixed $fieldset;

	public function set_name(string $name): void
	{
		$this->name = $name;
	}

	public function get_name(): string
	{
		return $this->name;
	}

	public function set_field_name(string $field_name): void
	{
		$this->field_name = $field_name;
	}

	public function get_field_name(): string
	{
		return $this->field_name;
	}

	public function set_field_type(string $field_type): void
	{
		$this->field_type = $field_type;
	}

	public function get_field_type(): string
	{
		return $this->field_type;
	}

	public function set_description(string $description): void
	{
		$this->description = $description;
	}

	public function get_description(): string
	{
		return $this->description;
	}

	public function set_default_value(string $default_value): void
	{
		$this->default_value = $default_value;
	}

	public function get_default_value(): string
	{
		return $this->default_value;
	}

	public function set_possible_values(array $possible_values): void
	{
		$this->possible_values = $possible_values;
	}

	public function get_possible_values()
	{
		return $this->possible_values;
	}

	public function required(): void
	{
		$this->required = true;
	}

	public function not_required(): void
	{
		$this->required = false;
	}

	public function is_required(): bool
	{
		return $this->required;
	}

	public function set_regex(string $regex): void
	{
		$this->regex = $regex;
	}

	public function get_regex(): string
	{
		return $this->regex;
	}

	public function displayed(): void
	{
		$this->displayed = true;
	}

	public function not_displayed(): void
	{
		$this->displayed = false;
	}

	public function is_displayed(): bool
	{
		return $this->displayed;
	}

	public function readonly(): void
	{
		$this->readonly = true;
	}

	public function not_readonly(): void
	{
		$this->readonly = false;
	}

	public function is_readonly(): bool
	{
		return $this->readonly;
	}

	public function deletable(): void
	{
		$this->deletable = true;
	}

	public function not_deletable(): void
	{
		$this->deletable = false;
	}

	public function is_deletable(): bool
	{
		return $this->deletable;
	}

	public function set_authorization(array $authorization): void
	{
		$this->authorization = $authorization;
	}

	public function get_authorization(): array
	{
		return $this->authorization;
	}

	public function is_authorized(): bool
	{
		return AppContext::get_current_user()->check_auth($this->authorization, self::DISPLAY_FIELD_AUTHORIZATION);
	}

	public function set_fieldset($fieldset): void
	{
		$this->fieldset = $fieldset;
	}

	public function get_fieldset()
	{
		return $this->fieldset;
	}

	public function get_instance(): mixed
	{
		$field_type = $this->get_field_type();
		if (!empty($field_type))
		{
			$class = (string)$field_type;
			return new $class();
		}
        return null;
	}

	public static function rewrite_field_name(string $field_name): string
	{
		$field = TextHelper::strtolower($field_name);
		$field = Url::encode_rewrite($field);
		$field = str_replace('-', '_', $field);
		return 'f_' . $field;
	}

	public function get_properties(): array
	{
		return [
			'name'            => $this->get_name(),
			'field_name'      => $this->get_field_name(),
			'description'     => $this->get_description(),
			'field_type'      => $this->get_field_type(),
			'default_value'   => $this->get_default_value(),
			'possible_values' => TextHelper::serialize($this->get_possible_values()),
			'required'        => (int)$this->is_required(),
			'displayed'       => (int)$this->is_displayed(),
			'regex'           => $this->get_regex(),
			'readonly'        => (int)$this->is_readonly(),
			'deletable'       => (int)$this->is_deletable(),
			'authorization'   => TextHelper::serialize($this->get_authorization())
		];
	}

	public function set_properties(array $properties): void
	{
		$this->name            = $properties['name'];
		$this->field_name      = $properties['field_name'];
		$this->description     = $properties['description'];
		$this->field_type      = $properties['field_type'];
		$this->default_value   = $properties['default_value'] ?? '';
		$this->possible_values = !empty($properties['possible_values']) ? TextHelper::unserialize($properties['possible_values']) : [];
		$this->required        = (bool)$properties['required'];
		$this->displayed       = (bool)$properties['displayed'];
		$this->regex           = $properties['regex'];
		$this->readonly        = (bool)$properties['readonly'];
		$this->deletable       = (bool)$properties['deletable'];
		$this->authorization   = !empty($properties['authorization']) ? TextHelper::unserialize($properties['authorization']) : [];
	}
}
?>
