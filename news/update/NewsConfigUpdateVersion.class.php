<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2021 02 02
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class NewsConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('news');

		$this->config_parameters_to_modify = [
			'number_news_per_page'             => 'items_per_page',
			'number_columns_display_news'      => 'items_per_row',
			'display_condensed_enabled'        => 'full_item_display',
			'descriptions_displayed_to_guests' => 'summary_displayed_to_guests',
			'number_character_to_cut'          => 'auto_cut_characters_number',
			'news_suggestions_enabled'         => 'items_suggestions_enabled',
			'nb_view_enabled'                  => 'views_number_enabled',
			'display_type'                     => [
				'parameter_name' => 'display_type',
				'values'         => [
					'block' => 'grid_view',
					'list'  => 'list_view'
				]
			],
			'root_category_description' => [
				'parameter_name' => 'root_category_description',
				'value' => $this->get_parsed_old_content('NewsConfig', 'root_category_description')
			]
		];
	}
}
?>
