<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 10 17
 * @since       PHPBoost 6.0 - 2022 10 17
 */

class VideoFormFieldPlayer extends AbstractFormField
{
	private $max_input = 200;

	public function __construct($id, $label, array $value = array(), array $field_options = array(), array $constraints = array())
	{
		parent::__construct($id, $label, $value, $field_options, $constraints);
	}

	function display()
	{
		$template = $this->get_template_to_use();

		$view = new FileTemplate('video/fields/VideoFormFieldPlayer.tpl');
		$view->add_lang(LangLoader::get_all_langs('video'));

		$view->put_all(array(
			'NAME'       => $this->get_html_id(),
			'ID'         => $this->get_html_id()
		));

		$this->assign_common_template_variables($template);

		$i = 0;
		foreach ($this->get_value() as $id => $options)
		{
			$view->assign_block_vars('fieldelements', array(
				'ID'   => $i,
				'PLATFORM' => $options['platform'],
				'DOMAIN' => $options['domain'],
				'PLAYER' => $options['player']
			));
			$i++;
		}

		if ($i == 0)
		{
			$view->assign_block_vars('fieldelements', array(
				'ID'   => $i,
				'PLATFORM' => '',
				'DOMAIN' => '',
				'PLAYER' => ''
			));
		}

		$view->put_all(array(
			'NAME'          => $this->get_html_id(),
			'ID'            => $this->get_html_id(),
			'MAX_INPUT'     => $this->max_input,
			'FIELDS_NUMBER' => $i == 0 ? 1 : $i
		));

		$template->assign_block_vars('fieldelements', array(
			'ELEMENT' => $view->render()
		));

		return $template;
	}

	public function retrieve_value()
	{
		$request = AppContext::get_request();
		$values = array();
		for ($i = 0; $i < $this->max_input; $i++)
		{
			$field_domain_id = 'field_domain_' . $this->get_html_id() . '_' . $i;
			if ($request->has_postparameter($field_domain_id))
			{
				$field_platform_id = 'field_platform_' . $this->get_html_id() . '_' . $i;
				$field_platform = $request->get_poststring($field_platform_id);
				$field_domain = $request->get_poststring($field_domain_id);
				$field_player_id = 'field_player_' . $this->get_html_id() . '_' . $i;
				$field_player = $request->get_poststring($field_player_id);
				if(!empty($field_platform) && !empty($field_domain) && !empty($field_player))
					$values[] = array('platform' => $field_platform, 'domain' => $field_domain, 'player' => $field_player);
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
