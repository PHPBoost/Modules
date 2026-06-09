<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2021 04 06
*/

class SmalladsConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('smallads');

		$this->config_parameters_to_modify = [
			'items_number_per_page'            => 'items_per_page',
			'descriptions_displayed_to_guests' => 'summaries_displayed_to_guests',
			'displayed_cols_number_per_line'   => 'items_per_row',
			'root_category_description'        => [
				'parameter_name' => 'root_category_description',
				'value' => $this->get_parsed_old_content('SmalladsConfig', 'root_category_description')
			]
		];
	}
}
?>
