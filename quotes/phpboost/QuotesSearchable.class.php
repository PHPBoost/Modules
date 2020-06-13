<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 02 06
 * @since       PHPBoost 6.0 - 2019 11 03
*/

class QuotesSearchable extends DefaultSearchable
{
	public function __construct()
	{
		parent::__construct('quotes', '', "'" . PATH_TO_ROOT . "/quotes/" . (!ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? "index.php?url=/" : "") . "author/', rewrited_author");

		$this->table_name = QuotesSetup::$quotes_table;

		$this->field_title = 'author';
		$this->field_rewrited_title = 'rewrited_author';
		$this->field_content = 'quote';

		$this->field_published = 'approved';

		$this->group_by = 'rewrited_author';
	}
}
?>
