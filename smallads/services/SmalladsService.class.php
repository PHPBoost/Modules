<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 19
 * @since       PHPBoost 5.1 - 2018 03 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class SmalladsService
{
	private static $db_querier;

	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}

	 /**
	 * Count items number.
	 * @param string $condition (optional) : Restriction to apply to the list of items
	 */
	public static function count($condition = '', $parameters = array())
	{
		return self::$db_querier->count(SmalladsSetup::$smallads_table, $condition, $parameters);
	}

	public static function add(Smallad $smallad)
	{
		$result = self::$db_querier->insert(SmalladsSetup::$smallads_table, $smallad->get_properties());
		return $result->get_last_inserted_id();
	}

	public static function update(Smallad $smallad)
	{
		self::$db_querier->update(SmalladsSetup::$smallads_table, $smallad->get_properties(), 'WHERE id=:id', array('id', $smallad->get_id()));
	}

	public static function delete(int $id)
	{
		if (AppContext::get_current_user()->is_readonly())
		{
			$controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($controller);
		}
		
		self::$db_querier->delete(SmalladsSetup::$smallads_table, 'WHERE id=:id', array('id' => $id));
		
		KeywordsService::get_keywords_manager()->delete_relations($id);

		self::$db_querier->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => 'smallads', 'id' => $id));

		CommentsService::delete_comments_topic_module('smallads', $id);
	}

	public static function get_smallad($condition, array $parameters)
	{
		$row = self::$db_querier->select_single_row_query('SELECT smallads.*, member.*
		FROM ' . SmalladsSetup::$smallads_table . ' smallads
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = smallads.author_user_id
		' . $condition, $parameters);

		$smallad = new Smallad();
		$smallad->set_properties($row);
		return $smallad;
	}

	public static function clear_cache()
	{
		Feed::clear_cache('smallads');
		SmalladsCache::invalidate();
		SmalladsCategoriesCache::invalidate();
		KeywordsCache::invalidate();
	}

	public static function update_views_number(Smallad $smallad)
	{
		self::$db_querier->update(SmalladsSetup::$smallads_table, array('views_number' => $smallad->get_views_number()), 'WHERE id=:id', array('id' => $smallad->get_id()));
	}
}
?>
