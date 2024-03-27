<?php
/**
 * @copyright   &copy; 2005-2024 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 04 15
 * @since       PHPBoost 6.0 - 2021 10 30
*/

class FluxService
{
	private static $db_querier;
	protected static $module_id = 'flux';

	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}

    public static function is_valid_xml($xml)
    {
        $content = file_get_contents($xml);
        return strpos($content, '<channel>');
    }

	/**
	 * @desc Count items number.
	 * @param string $condition (optional) : Restriction to apply to the list of items
	*/
	public static function count($condition = '', $parameters = array())
	{
		return self::$db_querier->count(FluxSetup::$flux_table, $condition, $parameters);
	}

	/**
	 * @desc Create a new entry in the database table.
	 * @param string[] $item : new FluxItem
	*/
	public static function add(FluxItem $item)
	{
		$result = self::$db_querier->insert(FluxSetup::$flux_table, $item->get_properties());

		return $result->get_last_inserted_id();
	}

	/**
	 * @desc Update an entry.
	 * @param string[] $item : Item to update
	*/
	public static function update(FluxItem $item)
	{
		self::$db_querier->update(FluxSetup::$flux_table, $item->get_properties(), 'WHERE id=:id', array('id' => $item->get_id()));
	}

	/**
	 * @desc Update the number of views of an item.
	 * @param string[] $item : FluxItem to update
	*/
	public static function update_views_number(FluxItem $item)
	{
		self::$db_querier->update(FluxSetup::$flux_table, array('views_number' => $item->get_views_number()), 'WHERE id=:id', array('id' => $item->get_id()));
	}

	/**
	 * @desc Update the number of visits of an website.
	 * @param string[] $item : FluxItem to update
	*/
	public static function update_visits_number(FluxItem $item)
	{
		self::$db_querier->update(FluxSetup::$flux_table, array('visits_number' => $item->get_visits_number()), 'WHERE id=:id', array('id' => $item->get_id()));
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
		self::$db_querier->delete(FluxSetup::$flux_table, 'WHERE id=:id', array('id' => $id));

		self::$db_querier->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => 'flux', 'id' => $id));
	}

	/**
	 * @desc Delete an entry.
	 * @param string $condition : Restriction to apply to the list
	 * @param string[] $parameters : Parameters of the condition
	*/
	public static function delete_xml_files()
	{
		foreach (glob(PATH_TO_ROOT . '/flux/xml/*.xml') as $filename) {
			$xml_file = new File($filename);
			$xml_file->delete();
		}
	}

	/**
	 * @desc Return the properties of an item.
	 * @param string $condition : Restriction to apply to the list
	 * @param string[] $parameters : Parameters of the condition
	*/
	public static function get_item(int $id)
	{
		$row = self::$db_querier->select_single_row_query('SELECT flux.*, member.*
		FROM ' . FluxSetup::$flux_table . ' flux
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = flux.author_user_id
		WHERE flux.id=:id', array(
			'id'              => $id,
			'current_user_id' => AppContext::get_current_user()->get_id()
		));

		$item = new FluxItem();
		$item->set_properties($row);
		return $item;
	}

	public static function clear_cache()
	{
		Feed::clear_cache('flux');
		CategoriesService::get_categories_manager('flux')->regenerate_cache();
	}
}
?>
