<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 11 23
 * @since   	PHPBoost 4.0 - 2013 01 29
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SmalladsSearchable extends AbstractSearchableExtensionPoint
{
	public function get_search_request($args)
	{
		$now = new Date();
		$authorized_categories = SmalladsService::get_authorized_categories(Category::ROOT_CATEGORY);
		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;

		return "SELECT " . $args['id_search'] . " AS id_search,
			smallads.id AS id_content,
			smallads.title AS title,
			(2 * FT_SEARCH_RELEVANCE(smallads.title, '" . $args['search'] . "') + (FT_SEARCH_RELEVANCE(smallads.contents, '" . $args['search'] . "') +
			FT_SEARCH_RELEVANCE(smallads.description, '" . $args['search'] . "')) / 2 ) / 3 * " . $weight . " AS relevance,
			CONCAT('" . PATH_TO_ROOT . "/smallads/" . (!ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? "index.php?url=/" : "") . "', id_category, '-', IF(id_category != 0, cat.rewrited_name, 'root'), '/', smallads.id, '-', smallads.rewrited_title) AS link
			FROM " . SmalladsSetup::$smallads_table . " smallads
			LEFT JOIN ". SmalladsSetup::$smallads_cats_table ." cat ON cat.id = smallads.id_category
			LEFT JOIN ". DB_TABLE_KEYWORDS_RELATIONS ." relation ON relation.module_id = 'smallads' AND relation.id_in_module = smallads.id
			LEFT JOIN ". DB_TABLE_KEYWORDS ." keyword ON keyword.id = relation.id_keyword
			WHERE ( FT_SEARCH(smallads.title, '" . $args['search'] . "') OR FT_SEARCH(smallads.contents, '" . $args['search'] . "') OR FT_SEARCH_RELEVANCE(smallads.description, '" . $args['search'] . "') ) OR keyword.rewrited_name = '" . Url::encode_rewrite($args['search']) . "'
			AND id_category IN(" . implode(", ", $authorized_categories) . ")
			AND (published = 1 OR (published = 2 AND publication_start_date < '" . $now->get_timestamp() . "' AND (publication_end_date > '" . $now->get_timestamp() . "' OR publication_end_date = 0)))
			ORDER BY relevance DESC
			LIMIT 100 OFFSET 0";
	}
}
?>
