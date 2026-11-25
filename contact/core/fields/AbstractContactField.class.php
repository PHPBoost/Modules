<?php
/**
 * Abstract class that proposes a default implementation for the ContactFieldType interface.
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 25
 * @since       PHPBoost 4.0 - 2013 07 31
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      mipel <mipel@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class AbstractContactField implements ContactFieldType
{
	protected HTMLForm $form;
	protected array $disable_fields_configuration = [];
	protected string $name;

	public function __construct()
	{
		$this->name = 'ContactField';
	}

	public function display_field(ContactField $field): void
	{
		$fieldset = $field->get_fieldset();
		return;
	}

	public function get_value(HTMLForm $form, ContactField $field): mixed
	{
		$field_name = $field->get_field_name();
		return $form->get_value($field_name, '');
	}

	public function constraint(mixed $value): mixed
	{
		if (is_numeric($value))
		{
			switch ($value)
			{
				case 2:
					return new FormFieldConstraintRegex('`^[a-zA-Z]+$`iu');
				case 3:
					return new FormFieldConstraintRegex('`^[a-zA-Z0-9]+$`iu');
				case 7:
					return new FormFieldConstraintRegex('`^[a-zA-Zàáâãäåçèéêëìíîïðòóôõöùúûüýÿ-]+$`iu');
			}
		}
		elseif (is_string($value) && !empty($value))
		{
			return new FormFieldConstraintRegex($value);
		}
        return null;
	}

	public function set_disable_fields_configuration(array $names): void
	{
		foreach($names as $name)
		{
			$name = TextHelper::strtolower($name);
			switch ($name)
			{
				case 'name':
					$this->disable_fields_configuration[] = $name;
					break;
				case 'description':
					$this->disable_fields_configuration[] = $name;
					break;
				case 'field_type':
					$this->disable_fields_configuration[] = $name;
					break;
				case 'regex':
					$this->disable_fields_configuration[] = $name;
					break;
				case 'possible_values':
					$this->disable_fields_configuration[] = $name;
					break;
				case 'default_value_small':
					$this->disable_fields_configuration[] = $name;
					break;
				case 'default_value_medium':
					$this->disable_fields_configuration[] = $name;
					break;
				case 'authorizations':
					$this->disable_fields_configuration[] = $name;
					break;
				default :
					throw new Exception('Field name ' . $name . ' not exist');
			}
		}
	}

	public function get_disable_fields_configuration(): array
	{
		return $this->disable_fields_configuration;
	}

	public function set_name(string $name): void
	{
		$this->name = $name;
	}

	public function get_name(): string
	{
		return $this->name;
	}

	public function set_form(HTMLForm $form): void
	{
		$this->form = $form;
	}

	public function get_form(): HTMLForm
	{
		return $this->form;
	}
}
?>
