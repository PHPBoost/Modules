<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 08 09
 * @since       PHPBoost 5.1 - 2018 03 15
*/

class SmalladsFormFieldCarousel extends AbstractFormField
{
	private $max_input = 20;

	public function __construct($id, $label, array $value = array(), array $field_options = array(), array $constraints = array())
	{
		parent::__construct($id, $label, $value, $field_options, $constraints);
	}

	function display()
	{
		$template = $this->get_template_to_use();

		$tpl = new FileTemplate('smallads/fields/SmalladsFormFieldCarousel.tpl');
		$tpl->add_lang(LangLoader::get('common'));
		$tpl->add_lang(LangLoader::get('common', 'smallads'));

		$tpl->put_all(array(
			'NAME' => $this->get_html_id(),
			'ID' => $this->get_html_id(),
			'C_DISABLED' => $this->is_disabled()
		));

		$this->assign_common_template_variables($template);

		$i = 0;
		foreach ($this->get_value() as $id => $options)
		{
			$tpl->assign_block_vars('fieldelements', array(
				'ID' => $i,
				'PICTURE_URL' => $options['picture_url'],
				'DESCRIPTION' => $options['description']
			));
			$i++;
		}

		if ($i == 0)
		{
			$tpl->assign_block_vars('fieldelements', array(
				'ID' => $i,
				'PICTURE_URL' => '',
				'DESCRIPTION' => ''
			));
		}

		$tpl->put_all(array(
			'MAX_INPUT' => $this->max_input,
			'NBR_FIELDS' => $i == 0 ? 1 : $i
		));

		$template->assign_block_vars('fieldelements', array(
			'ELEMENT' => $tpl->render()
		));

		return $template;
	}

	public function retrieve_value()
	{
		$request = AppContext::get_request();
		$values = array();
		for ($i = 0; $i < $this->max_input; $i++)
		{
			$field_picture_url_id = 'field_picture_url_' . $this->get_html_id() . '_' . $i;
			if ($request->has_postparameter($field_picture_url_id))
			{
				$field_description_id = 'field_description_' . $this->get_html_id() . '_' . $i;
				$field_description = $request->get_poststring($field_description_id);
				$field_picture_url = $request->get_poststring($field_picture_url_id);

				if (!empty($field_picture_url))
					$values[] = array('description' => $field_description, 'picture_url' => $field_picture_url);
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
