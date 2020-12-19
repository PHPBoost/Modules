<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 12 17
 * @since       PHPBoost 5.0 - 2016 02 18
 * @contributor mipel <mipel@phpboost.com>
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
	 * @desc Create a new entry in the database table.
	 * @param string[] $quote : new Quote
	 */
	public static function add(QuotesItem $quote)
	{
		$result = self::$db_querier->insert(QuotesSetup::$quotes_table, $quote->get_properties());

		return $result->get_last_inserted_id();
	}

	 /**
	 * @desc Update an entry.
	 * @param string[] $quote : Quote to update
	 */
	public static function update(QuotesItem $quote)
	{
		self::$db_querier->update(QuotesSetup::$quotes_table, $quote->get_properties(), 'WHERE id=:id', array('id' => $quote->get_id()));
	}

	 /**
	 * @desc Delete an entry.
	 * @param string $condition : Restriction to apply to the list
	 * @param string[] $parameters : Parameters of the condition
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
	 * @desc Return the properties of a quotes.
	 * @param string $condition : Restriction to apply to the list
	 * @param string[] $parameters : Parameters of the condition
	 */
	public static function get_quote($condition, array $parameters)
	{
		$row = self::$db_querier->select_single_row_query('SELECT quotes.*, member.*
		FROM ' . QuotesSetup::$quotes_table . ' quotes
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = quotes.author_user_id
		' . $condition, $parameters);

		$quote = new QuotesItem();
		$quote->set_properties($row);
		return $quote;
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
