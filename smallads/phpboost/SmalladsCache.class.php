<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 20
 * @since       PHPBoost 5.0 - 2016 02 02
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

 class SmalladsCache implements CacheData
 {
 	private $item = array();

 	/**
 	 * {@inheritdoc}
 	 */
 	public function synchronize()
 	{
 		$this->items = array();
 		$now = new Date();

 		$result = PersistenceContext::get_querier()->select('
 			SELECT smallads.*
 			FROM ' . SmalladsSetup::$smallads_table . ' smallads
			WHERE (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))
            ORDER BY creation_date DESC
 			LIMIT :module_mini_items_nb OFFSET 0', array(
                'timestamp_now' => $now->get_timestamp(),
 				'module_mini_items_nb' => (int)SmalladsConfig::load()->get_mini_menu_items_nb()
 		));

 		while ($row = $result->fetch())
 		{
 			$this->items[$row['id']] = $row;
 		}
 		$result->dispose();
 	}

 	public function get_items()
 	{
 		return $this->items;
 	}

 	public function item_exists($id)
 	{
 		return array_key_exists($id, $this->items);
 	}

 	public function get_item($id)
 	{
 		if ($this->item_exists($id))
 		{
 			return $this->items[$id];
 		}
 		return null;
 	}

	public function get_items_number()
	{
		return count($this->items);
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
