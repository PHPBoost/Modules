<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 12
 * @since       PHPBoost 4.0 - 2013 01 29
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class SmalladsSearchable extends DefaultSearchable
{
	public function __construct()
	{
		$module_id = 'smallads';
		parent::__construct($module_id);
		$this->read_authorization = CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->read();
		
		$this->table_name = SmalladsSetup::$smallads_table;
		
		$this->cats_table_name = SmalladsSetup::$smallads_cats_table;
		$this->authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, SmalladsConfig::load()->are_descriptions_displayed_to_guests(), $module_id);
		
		$this->use_keywords = true;
		
		$this->has_short_contents = true;
		$this->field_short_contents = 'smallads_table_name.description';
		
		$this->field_approbation_type = 'published';
		
		$this->has_validation_period = true;
		$this->field_validation_start_date = 'publication_start_date';
		$this->field_validation_end_date = 'publication_end_date';
	}
}
?>
