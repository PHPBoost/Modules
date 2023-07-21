<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 07 10
 * @since       PHPBoost 4.0 - 2013 01 29
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class SmalladsSearchable extends DefaultSearchable
{
	public function __construct()
	{
		$module_id = 'smallads';
		parent::__construct($module_id);

		$this->table_name = SmalladsSetup::$smallads_table;

		$this->authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, SmalladsConfig::load()->are_summaries_displayed_to_guests(), $module_id);

		$this->use_keywords = true;

		$this->field_title = 'title';
		$this->field_rewrited_title = 'rewrited_title';
		$this->field_content = 'content';

		$this->has_summary = true;
		$this->field_summary = 'summary';

		$this->field_published = 'published';

		$this->has_validation_period = true;
		$this->field_validation_start_date = 'publishing_start_date';
		$this->field_validation_end_date = 'publishing_end_date';
	}
}
?>
