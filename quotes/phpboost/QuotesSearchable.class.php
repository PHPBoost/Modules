<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.   3 - last update: 2019 11 03
 * @since       PHPBoost 5.3 - 2019 11 03
*/

class QuotesSearchable extends DefaultSearchable
{
	public function __construct()
	{
		$module_id = 'quotes';
		parent::__construct($module_id);
		$this->read_authorization = CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->read();

		$this->table_name = QuotesSetup::$quotes_table;

		$this->cats_table_name = QuotesSetup::$quotes_cats_table;
		$this->authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, true, $module_id);

		$this->field_title = 'author';
		$this->field_rewrited_title = 'rewrited_author';
		$this->field_contents = 'quote';

		$this->field_approbation_type = 'approved';

		$this->custom_all_link = "'" . PATH_TO_ROOT . "/quotes/" . (!ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? "index.php?url=/" : "") . "author/', " . $this->field_rewrited_title;

		$this->group_by = 'rewrited_author';
	}
}
?>
