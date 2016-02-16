<?php
/*##################################################
 *                           SmalladsSearchable.class.php
 *                            -------------------
 *   begin                : January 29, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

class SmalladsSearchable extends AbstractSearchableExtensionPoint
{
	public function get_search_request($args)
	{
		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;

		if (SmalladsAuthorizationsService::check_authorizations()->read())
		{
			return "SELECT " . $args['id_search'] . " AS id_search,
			q.id AS id_content,
			q.title AS title,
			( 2 * FT_SEARCH_RELEVANCE(q.title, '" . $args['search'] . "') + FT_SEARCH_RELEVANCE(q.contents, '" . $args['search'] . "') ) / 3 * " . $weight . " AS relevance,
			CONCAT('" . PATH_TO_ROOT . "/smallads/smallads.php?id=', q.id) AS link
			FROM " . PREFIX . "smallads q
			WHERE ( FT_SEARCH(q.title, '" . $args['search'] . "') OR FT_SEARCH(q.contents, '" . $args['search'] . "') )
			ORDER BY relevance DESC
			LIMIT 100 OFFSET 0";
		}
		
		return '';
	}
}
?>