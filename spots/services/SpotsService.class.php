<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 04 15
 * @since       PHPBoost 6.0 - 2021 08 22
*/

class SpotsService
{
	private static $db_querier;
	protected static $module_id = 'spots';

	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}

    /**
	 * @desc Count items number.
	 * @param string $condition (optional) : Restriction to apply to the list of items
	 */
	public static function is_gmap_enabled()
	{
		return ModulesManager::is_module_installed('GoogleMaps') && ModulesManager::is_module_activated('GoogleMaps') && !empty(GoogleMapsConfig::load()->get_api_key()) && !empty(GoogleMapsConfig::load()->get_default_marker_latitude()) && !empty(GoogleMapsConfig::load()->get_default_marker_longitude());
	}

    /**
	 * @desc Count items number.
	 * @param string $condition (optional) : Restriction to apply to the list of items
	 */
	public static function count($condition = '', $parameters = array())
	{
		return self::$db_querier->count(SpotsSetup::$spots_table, $condition, $parameters);
	}

    /**
	 * @desc Create a new entry in the database table.
	 * @param string[] $item : new SpotsItem
	 */
	public static function add(SpotsItem $item)
	{
		$result = self::$db_querier->insert(SpotsSetup::$spots_table, $item->get_properties());

		return $result->get_last_inserted_id();
	}

    /**
	 * @desc Update an entry.
	 * @param string[] $item : Item to update
	 */
	public static function update(SpotsItem $item)
	{
		self::$db_querier->update(SpotsSetup::$spots_table, $item->get_properties(), 'WHERE id=:id', array('id' => $item->get_id()));
	}

    /**
	 * @desc Update the number of views of an item.
	 * @param string[] $item : SpotsItem to update
	 */
	public static function update_views_number(SpotsItem $item)
	{
		self::$db_querier->update(SpotsSetup::$spots_table, array('views_number' => $item->get_views_number()), 'WHERE id=:id', array('id' => $item->get_id()));
	}

    /**
	 * @desc Update the number of visits of an website.
	 * @param string[] $item : SpotsItem to update
	 */
	public static function update_visits_number(SpotsItem $item)
	{
		self::$db_querier->update(SpotsSetup::$spots_table, array('visits_number' => $item->get_visits_number()), 'WHERE id=:id', array('id' => $item->get_id()));
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
			self::$db_querier->delete(SpotsSetup::$spots_table, 'WHERE id=:id', array('id' => $id));

			self::$db_querier->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => 'spots', 'id' => $id));
	}

    /**
	 * @desc Return the properties of an item.
	 * @param string $condition : Restriction to apply to the list
	 * @param string[] $parameters : Parameters of the condition
	 */
	public static function get_item(int $id)
	{
		$row = self::$db_querier->select_single_row_query('SELECT ' . self::$module_id . '.*, member.*
		FROM ' . SpotsSetup::$spots_table . ' ' . self::$module_id . '
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = ' . self::$module_id . '.author_user_id
		WHERE ' . self::$module_id . '.id=:id', array(
			'id'              => $id,
			'current_user_id' => AppContext::get_current_user()->get_id()
		));

		$item = new SpotsItem();
		$item->set_properties($row);
		return $item;
	}

	public static function clear_cache()
	{
		Feed::clear_cache('spots');
		CategoriesService::get_categories_manager('spots')->regenerate_cache();
	}
}
?>
