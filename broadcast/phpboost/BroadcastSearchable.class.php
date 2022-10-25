<?php

/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 10 17
 * @since       PHPBoost 4.1 - 2017 02 21
 */

class BroadcastSearchable extends DefaultSearchable
{
	public function __construct()
	{
		$module_id = 'broadcast';
		parent::__construct($module_id);

		$this->table_name = BroadcastSetup::$broadcast_table;

		$this->authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, BroadcastConfig::load()->are_descriptions_displayed_to_guests(), $module_id);

		$this->field_title = 'title';
		$this->field_rewrited_title = 'rewrited_title';
		$this->field_content = 'content';

		$this->field_published = 'published';

		$this->has_validation_period = true;
		$this->field_validation_start_time = 'start_time';
		$this->field_validation_end_time = 'end_time';
	}
}
?>
