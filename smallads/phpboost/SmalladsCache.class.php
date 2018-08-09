<?php
/*##################################################
 *                      SmalladsComments.class.php
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

 class SmalladsCache implements CacheData
 {
 	private $smallad = array();

 	/**
 	 * {@inheritdoc}
 	 */
 	public function synchronize()
 	{
 		$this->smallad = array();
 		$now = new Date();

 		$result = PersistenceContext::get_querier()->select('
 			SELECT smallads.*
 			FROM ' . SmalladsSetup::$smallads_table . ' smallads
			WHERE (published = 1 OR (published = 2 AND publication_start_date < :timestamp_now AND (publication_end_date > :timestamp_now OR publication_end_date = 0)))
            ORDER BY creation_date DESC
 			LIMIT :module_mini_items_nb OFFSET 0', array(
                'timestamp_now' => $now->get_timestamp(),
 				'module_mini_items_nb' => (int)SmalladsConfig::load()->get_mini_menu_items_nb()
 		));

 		while ($row = $result->fetch())
 		{
 			$this->smallad[$row['id']] = $row;
 		}
 		$result->dispose();
 	}

 	public function get_smallad()
 	{
 		return $this->smallad;
 	}

 	public function smallad_exists($id)
 	{
 		return array_key_exists($id, $this->smallad);
 	}

 	public function get_smallad_item($id)
 	{
 		if ($this->smallad_exists($id))
 		{
 			return $this->smallad[$id];
 		}
 		return null;
 	}

	public function get_items_number()
	{
		return count($this->smallad);
	}

 	/**
 	 * Loads and returns the smallads cached data.
 	 * @return SmalladsCache The cached data
 	 */
 	public static function load()
 	{
 		return CacheManager::load(__CLASS__, 'smallads', 'minimenu');
 	}

 	/**
 	 * Invalidates the current smallads cached data.
 	 */
 	public static function invalidate()
 	{
 		CacheManager::invalidate('smallads', 'minimenu');
 	}
 }
 ?>
