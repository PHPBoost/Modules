<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 10 17
 * @since       PHPBoost 6.0 - 2022 10 17
 */

class VideoCache implements CacheData
{
	private $items = array();

	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$this->items = array();

		$now = new Date();
		$config = VideoConfig::load();

		$result = PersistenceContext::get_querier()->select('
			SELECT video.*, notes.average_notes, notes.notes_number
			FROM ' . VideoSetup::$video_table . ' video
			LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = video.id AND notes.module_name = \'video\'
			WHERE (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))
			ORDER BY ' . $config->get_sort_type() . ' DESC
			LIMIT :files_number_in_menu OFFSET 0', array(
				'timestamp_now' => $now->get_timestamp(),
				'files_number_in_menu' => (int)$config->get_files_number_in_menu()
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
	 * Loads and returns the video cached data.
	 * @return VideoCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'video', 'minimenu');
	}

	/**
	 * Invalidates the current video cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('video', 'minimenu');
	}
}
?>
