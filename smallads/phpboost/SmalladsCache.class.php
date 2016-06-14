<?php
/*##################################################
 *                               SmalladsCache.class.php
 *                            -------------------
 *   begin                : February 2, 2016
 *   copyright            : (C) 2016 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class SmalladsCache implements CacheData
{
	private $smallads = array();
	private $smallads_number;
	private $last_smallad_date;
	
	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$this->smallads = array();
		
		$config = SmalladsConfig::load();
		
		$result = PersistenceContext::get_querier()->select('SELECT *
			FROM ' . SmalladsSetup::$smallads_table . '
			WHERE approved = 1
			ORDER BY date_approved DESC
			LIMIT :files_number_in_menu OFFSET 0', array(
				'files_number_in_menu' => (int)$config->get_list_size()
		));
		
		$first_id = $i = 0;
		while ($row = $result->fetch())
		{
			if ($i == 0)
				$first_id = $row['id'];
			
			$this->smallads[$row['id']] = $row;
			$i++;
		}
		$result->dispose();
		
		$this->smallads_number = PersistenceContext::get_querier()->count(SmalladsSetup::$smallads_table, 'WHERE approved = 1');
		
		$this->last_smallad_date = $this->smallads && $first_id && isset($this->smallads[$first_id]['date_created']) ? $this->smallads[$first_id]['date_created'] : time();
	}
	
	public function get_smallads()
	{
		return $this->smallads;
	}
	
	public function smallad_exists($id)
	{
		return array_key_exists($id, $this->smallads);
	}
	
	public function get_smallad_item($id)
	{
		if ($this->smallad_exists($id))
		{
			return $this->smallads[$id];
		}
		return null;
	}
	
	public function get_number_smallads()
	{
		return $this->smallads_number;
	}
	
	public function get_last_smallad_date()
	{
		return $this->last_smallad_date;
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
