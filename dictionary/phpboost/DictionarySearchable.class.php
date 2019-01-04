<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2016 02 17
 * @since   	PHPBoost 3.0 - 2012 11 15
*/

class DictionarySearchable extends AbstractSearchableExtensionPoint
{
	public function get_search_request($args)
	{
		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;

		if (DictionaryAuthorizationsService::check_authorizations()->read())
		{
			return "SELECT " . $args['id_search'] . " AS id_search,
			q.id AS id_content,
			q.word AS title,
			( 2 * FT_SEARCH_RELEVANCE(q.word, '" . $args['search'] . "') + FT_SEARCH_RELEVANCE(q.description, '" . $args['search'] . "') ) / 3 * " . $weight . " AS relevance,
			CONCAT('" . PATH_TO_ROOT . "/dictionary/dictionary.php?l=', q.word) AS link
			FROM " . PREFIX . "dictionary q
			WHERE ( FT_SEARCH(q.word, '" . $args['search'] . "') OR FT_SEARCH(q.description, '" . $args['search'] . "') )
			ORDER BY relevance DESC
			LIMIT 100 OFFSET 0";
		}

		return '';
	}
}
?>
