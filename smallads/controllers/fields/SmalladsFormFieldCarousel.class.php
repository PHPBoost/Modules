<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 5.1 - 2018 03 15
*/

class SmalladsFormFieldCarousel extends AbstractFormField
{
	private $max_input = 20;

	public function __construct($id, $label, array $value = [], array $field_options = [], array $constraints = [])
	{
		parent::__construct($id, $label, $value, $field_options, $constraints);
	}

	function display()
	{
		$template = $this->get_template_to_use();

		$view = new FileTemplate('smallads/fields/SmalladsFormFieldCarousel.tpl');
		$view->add_lang(LangLoader::get_all_langs('smallads'));

		$view->put_all([
			'C_DISABLED' => $this->is_disabled(),
			'NAME' => $this->get_html_id(),
			'ID'   => $this->get_html_id()
		]);

		$this->assign_common_template_variables($template);

		$i = 0;
		foreach ($this->get_value() as $id => $options)
		{
			$view->assign_block_vars('fieldelements', [
				'ID'          => $i,
				'PICTURE_URL' => $options['picture_url'],
				'DESCRIPTION' => $options['description']
			]);
			$i++;
		}

		if ($i == 0)
		{
			$view->assign_block_vars('fieldelements', [
				'ID'          => $i,
				'PICTURE_URL' => '',
				'DESCRIPTION' => ''
			]);
		}

		$view->put_all([
			'MAX_INPUT'     => $this->max_input,
			'FIELDS_NUMBER' => $i == 0 ? 1 : $i
		]);

		$template->assign_block_vars('fieldelements', [
			'ELEMENT' => $view->render()
		]);

		return $template;
	}

	public function retrieve_value()
	{
		$request = AppContext::get_request();
		$values = [];
		for ($i = 0; $i < $this->max_input; $i++)
		{
			$field_picture_url_id = 'field_picture_url_' . $this->get_html_id() . '_' . $i;
			if ($request->has_postparameter($field_picture_url_id))
			{
				$field_description_id = 'field_description_' . $this->get_html_id() . '_' . $i;
				$field_description = $request->get_poststring($field_description_id);
				$field_picture_url = $request->get_poststring($field_picture_url_id);

				if (!empty($field_picture_url))
					$values[] = ['description' => $field_description, 'picture_url' => $field_picture_url];
			}
		}
		$this->set_value($values);
	}

	protected function compute_options(array &$field_options)
	{
		foreach($field_options as $attribute => $value)
		{
			$attribute = TextHelper::strtolower($attribute);
			switch ($attribute)
			{
			case 'max_input':
				$this->max_input = $value;
				unset($field_options['max_input']);
				break;
			}
		}
		parent::compute_options($field_options);
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/FormField.tpl');
	}
}
?>
