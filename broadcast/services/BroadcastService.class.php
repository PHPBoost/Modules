<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 10 25
 * @since       PHPBoost 6.0 - 2022 10 25
 */

class BroadcastService
{
	private static $db_querier;
	protected static $module_id = 'broadcast';

	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}

	/**
	 * @desc Count items number.
	 * @param string $condition (optional) : Restriction to apply to the list of items
	 */
	public static function count($condition = '', $parameters = array())
	{
		return self::$db_querier->count(BroadcastSetup::$broadcast_table, $condition, $parameters);
	}

	public static function add(BroadcastItem $item)
	{
		$result = self::$db_querier->insert(BroadcastSetup::$broadcast_table, $item->get_properties());

		return $result->get_last_inserted_id();
	}

	public static function update(BroadcastItem $item)
	{
		self::$db_querier->update(BroadcastSetup::$broadcast_table, $item->get_properties(), 'WHERE id=:id', array('id' => $item->get_id()));
	}

	public static function delete(int $id)
	{
		if (AppContext::get_current_user()->is_readonly()) {
			$controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($controller);
		}
		self::$db_querier->delete(BroadcastSetup::$broadcast_table, 'WHERE id=:id', array('id' => $id));

		self::$db_querier->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => 'broadcast', 'id' => $id));
	}

	public static function get_item(int $id)
	{
		$row = self::$db_querier->select_single_row_query('SELECT broadcast.*, member.*
			FROM ' . BroadcastSetup::$broadcast_table . ' broadcast
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = broadcast.author_user_id
			WHERE ' . self::$module_id . '.id=:id', array(
				'module_id'       => self::$module_id,
				'id'              => $id,
				'current_user_id' => AppContext::get_current_user()->get_id()
			)
		);

		$item = new BroadcastItem();
		$item->set_properties($row);
		return $item;
	}

	public static function clear_cache()
	{
		Feed::clear_cache('broadcast');
		KeywordsCache::invalidate();
		BroadcastCache::invalidate();
		CategoriesService::get_categories_manager()->regenerate_cache();
	}
}
?>
