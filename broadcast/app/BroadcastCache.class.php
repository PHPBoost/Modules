<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 11
 * @since       PHPBoost 6.0 - 2022 10 25
 */

class BroadcastCache implements CacheData
{
	private array $items = [];

	public function synchronize(): void
    {
		$result = PersistenceContext::get_querier()->select('
			SELECT broadcast.*
			FROM ' . BroadcastSetup::$broadcast_table . ' broadcast
			WHERE published = 1
			ORDER BY creation_date DESC'
            , []
        );

		while ($row = $result->fetch())
		{
			$this->items[$row['id']] = $row;
		}
		$result->dispose();
	}

	public function get_items(): array
	{
		return $this->items;
	}

	public function item_exists(int $id): bool
	{
		return array_key_exists($id, $this->items);
	}

	public function get_item(int $id): ?BroadcastItem
	{
		if ($this->item_exists($id))
		{
			return $this->items[$id];
		}
		return null;
	}

	public function get_items_number(): int
	{
		return count($this->items);
	}

	/**
	 * Loads and returns the broadcast cached data.
		* @return BroadcastCache The cached data
		*/
	public static function load(): BroadcastCache
	{
		return CacheManager::load(self::class, 'broadcast', 'minimenu');
	}

	/**
	 * Invalidates the current broadcast cached data.
		*/
	public static function invalidate(): void
	{
		CacheManager::invalidate('broadcast', 'minimenu');
	}
}
?>
