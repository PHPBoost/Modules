<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 31
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
        if (filter_var($xml, FILTER_VALIDATE_URL))
        {
            if (!function_exists('curl_init'))
            {
                error_log('Unable to validate remote RSS XML: cURL is not available.');
                return false;
            }

            $ch = curl_init($xml);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            $content = curl_exec($ch);
            $http_code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($content === false)
            {
                error_log('Unable to fetch remote RSS XML: ' . curl_error($ch));
            }
            elseif ($http_code < 200 || $http_code >= 300)
            {
                error_log('Unable to fetch remote RSS XML: HTTP status ' . $http_code . '.');
                $content = false;
            }

            if (\PHP_VERSION_ID < 80100)
            {
                curl_close($ch);
            }
        }
        else
        {
            $content = @file_get_contents($xml);
            if ($content === false)
            {
                error_log('Unable to read RSS XML file: ' . $xml);
            }
        }

        return $content !== false && strpos($content, '<channel>') !== false;
    }

	/**
	 * Count items number.
	 * @param string $condition (optional) : Restriction to apply to the list of items
	*/
	public static function count($condition = '', $parameters = [])
	{
		return self::$db_querier->count(FluxSetup::$flux_table, $condition, $parameters);
	}

	/**
	 * Create a new entry in the database table.
	 * @param FluxItem $item : new FluxItem
	*/
	public static function add(FluxItem $item)
	{
		$result = self::$db_querier->insert(FluxSetup::$flux_table, $item->get_properties());

		return $result->get_last_inserted_id();
	}

	/**
	 * Update an entry.
	 * @param FluxItem $item : Item to update
	*/
	public static function update(FluxItem $item)
	{
		self::$db_querier->update(FluxSetup::$flux_table, $item->get_properties(), 'WHERE id=:id', ['id' => $item->get_id()]);
	}

	/**
	 * Update the number of views of an item.
	 * @param FluxItem $item : FluxItem to update
	*/
	public static function update_views_number(FluxItem $item)
	{
		self::$db_querier->update(FluxSetup::$flux_table, ['views_number' => $item->get_views_number()], 'WHERE id=:id', ['id' => $item->get_id()]);
	}

	/**
	 * Update the number of visits of an website.
	 * @param FluxItem $item : FluxItem to update
	*/
	public static function update_visits_number(FluxItem $item)
	{
		self::$db_querier->update(FluxSetup::$flux_table, ['visits_number' => $item->get_visits_number()], 'WHERE id=:id', ['id' => $item->get_id()]);
	}

	/**
	 * Delete an entry.
	*/
	public static function delete(int $id)
	{
		if (AppContext::get_current_user()->is_readonly())
        {
            $controller = PHPBoostErrors::user_in_read_only();
            DispatchManager::redirect($controller);
        }
		self::$db_querier->delete(FluxSetup::$flux_table, 'WHERE id=:id', ['id' => $id]);

		self::$db_querier->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', ['module' => 'flux', 'id' => $id]);
	}

	/**
	 * Delete all xml entries.
	*/
	public static function delete_xml_files()
	{
		foreach (glob(PATH_TO_ROOT . '/modules/flux/xml/*.xml') as $filename) {
			$xml_file = new File($filename);
			$xml_file->delete();
		}
	}

	/**
	 * Return the properties of an item.
	*/
	public static function get_item(int $id)
	{
		$row = self::$db_querier->select_single_row_query('
                SELECT flux.*, member.*
                FROM ' . FluxSetup::$flux_table . ' flux
                LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = flux.author_user_id
                WHERE flux.id=:id
            ', [
                'id' => $id,
            ]
        );

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
