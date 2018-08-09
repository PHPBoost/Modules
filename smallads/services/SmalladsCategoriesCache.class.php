<?php
/*##################################################
 *                        SmalladsCategoriesCache.class.php
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

class SmalladsCategoriesCache extends CategoriesCache
{
	public function get_table_name()
	{
		return SmalladsSetup::$smallads_cats_table;
	}

	public function get_category_class()
	{
		return CategoriesManager::RICH_CATEGORY_CLASS;
	}

	public function get_module_identifier()
	{
		return 'smallads';
	}

	protected function get_category_elements_number($id_category)
	{
		$now = new Date();
		return SmalladsService::count('WHERE id_category = :id_category AND (published = 1 OR (published = 2 AND publication_start_date < :timestamp_now AND (publication_end_date > :timestamp_now OR publication_end_date = 0)))',
			array(
				'timestamp_now' => $now->get_timestamp(),
				'id_category' => $id_category
			)
		);
	}

	public function get_root_category()
	{
		$root = new RichRootCategory();
		$root->set_authorizations(SmalladsConfig::load()->get_authorizations());
		$root->set_description(SmalladsConfig::load()->get_root_category_description());
		return $root;
	}
}
?>
