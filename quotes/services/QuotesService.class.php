<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 14
 * @since       PHPBoost 5.0 - 2016 02 18
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class QuotesService
{
	private static $db_querier;

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
		return self::$db_querier->count(QuotesSetup::$quotes_table, $condition, $parameters);
	}

	 /**
	 * @desc Create a new item in the database table.
	 * @param string[] $item : new QuotesItem
	 */
	public static function add(QuotesItem $item)
	{
		$result = self::$db_querier->insert(QuotesSetup::$quotes_table, $item->get_properties());

		return $result->get_last_inserted_id();
	}

	 /**
	 * @desc Update an item.
	 * @param string[] $item : QuotesItem to update
	 */
	public static function update(QuotesItem $item)
	{
		self::$db_querier->update(QuotesSetup::$quotes_table, $item->get_properties(), 'WHERE id=:id', array('id' => $item->get_id()));
	}

	 /**
	 * @desc Delete an item.
	 * @param int $id Item identifier
	 */
	public static function delete(int $id)
	{
		if (AppContext::get_current_user()->is_readonly())
		{
			$controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($controller);
		}

		self::$db_querier->delete(QuotesSetup::$quotes_table, 'WHERE id=:id', array('id' => $id));

		self::$db_querier->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => 'quotes', 'id' => $id));
	}

	 /**
	 * @desc Return the properties of an item.
	 * @param int $id Item identifier
	 */
	public static function get_item(int $id)
	{
		$row = self::$db_querier->select_single_row_query('SELECT quotes.*, member.*
		FROM ' . QuotesSetup::$quotes_table . ' quotes
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = quotes.author_user_id
		WHERE quotes.id=:id', array(
			'id' => $id
		));

		$item = new QuotesItem();
		$item->set_properties($row);
		return $item;
	}

	 /**
	 * @desc Clears all module elements in cache.
	 */
	public static function clear_cache()
	{
		QuotesCache::invalidate();
		QuotesCategoriesCache::invalidate();
	}
}
?>
