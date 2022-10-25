<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 10 25
 * @since       PHPBoost 6.0 - 2022 10 25
 */

class BroadcastCache implements CacheData
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
			SELECT broadcast.*
			FROM ' . BroadcastSetup::$broadcast_table . ' broadcast
			WHERE published = 1
			ORDER BY creation_date DESC', array());

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
	 * Loads and returns the broadcast cached data.
		* @return BroadcastCache The cached data
		*/
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'broadcast', 'minimenu');
	}

	/**
	 * Invalidates the current broadcast cached data.
		*/
	public static function invalidate()
	{
		CacheManager::invalidate('broadcast', 'minimenu');
	}
}
?>
