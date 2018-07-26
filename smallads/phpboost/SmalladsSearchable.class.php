<?php
/*##################################################
 *                      SmalladsSearchable.class.php
 *                            -------------------
 *   begin                : March 15, 2018 
 *   copyright            : (C) 2018 Sebastien LARTIGUE
 *   email                : babsolune@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
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
			WHERE ( FT_SEARCH(smallads.title, '" . $args['search'] . "') OR FT_SEARCH(smallads.contents, '" . $args['search'] . "') OR FT_SEARCH_RELEVANCE(smallads.description, '" . $args['search'] . "') )
			AND id_category IN(" . implode(", ", $authorized_categories) . ")
			AND (published = 1 OR (published = 2 AND publication_start_date < '" . $now->get_timestamp() . "' AND (publication_end_date > '" . $now->get_timestamp() . "' OR publication_end_date = 0)))
			ORDER BY relevance DESC
			LIMIT 100 OFFSET 0";
	}
}
?>
