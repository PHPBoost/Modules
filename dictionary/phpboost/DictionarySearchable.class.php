<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 02 06
 * @since       PHPBoost 3.0 - 2012 11 15
*/

class DictionarySearchable extends DefaultSearchable
{
	public function __construct()
	{
		parent::__construct('dictionary', '', "'" . PATH_TO_ROOT . "/dictionary/dictionary.php?l=', word");
		$this->read_authorization = DictionaryAuthorizationsService::check_authorizations()->read();
		
		$this->table_name = PREFIX . 'dictionary';
		
		$this->field_title = 'word';
		$this->field_content = 'description';
		
		$this->has_approbation = false;
	}
}
?>
