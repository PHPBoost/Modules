<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 04 15
 * @since       PHPBoost 6.0 - 2022 10 17
 */

class VideoService
{
	private static $db_querier;
	protected static $module_id = 'video';

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
		return self::$db_querier->count(VideoSetup::$video_table, $condition, $parameters);
	}

	/**
	 * @desc Create a new entry in the database table.
	 * @param string[] $item : new VideoItem
	 */
	public static function add(VideoItem $item)
	{
		$result = self::$db_querier->insert(VideoSetup::$video_table, $item->get_properties());

		return $result->get_last_inserted_id();
	}

	/**
	 * @desc Update an entry.
	 * @param string[] $item : VideoItem to update
	 */
	public static function update(VideoItem $item)
	{
		self::$db_querier->update(VideoSetup::$video_table, $item->get_properties(), 'WHERE id=:id', array('id' => $item->get_id()));
	}

	public static function update_views_number(VideoItem $item)
	{
		self::$db_querier->update(VideoSetup::$video_table, array('views_number' => $item->get_views_number()), 'WHERE id=:id', array('id' => $item->get_id()));
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
		self::$db_querier->delete(VideoSetup::$video_table, 'WHERE id=:id', array('id' => $id));

		self::$db_querier->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => 'video', 'id' => $id));

		CommentsService::delete_comments_topic_module('video', $id);
		KeywordsService::get_keywords_manager()->delete_relations($id);
		NotationService::delete_notes_id_in_module('video', $id);
	}

	/**
	 * @desc Return the item with all its properties from its id.
	 * @param int $id Item identifier
	 */
	public static function get_item(int $id)
	{
		$row = self::$db_querier->select_single_row_query('SELECT ' . self::$module_id . '.*, member.*, notes.average_notes, notes.notes_number, note.note
		FROM ' . VideoSetup::$video_table . ' ' . self::$module_id . '
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = ' . self::$module_id . '.author_user_id
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = ' . self::$module_id . '.id AND notes.module_name = :module_id
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = ' . self::$module_id . '.id AND note.module_name = :module_id AND note.user_id = :current_user_id
		WHERE ' . self::$module_id . '.id=:id', array(
			'module_id'       => self::$module_id,
			'id'              => $id,
			'current_user_id' => AppContext::get_current_user()->get_id()
		));

		$item = new VideoItem();
		$item->set_properties($row);
		return $item;
	}

	public static function clear_cache()
	{
		Feed::clear_cache('video');
		KeywordsCache::invalidate();
		VideoCache::invalidate();
        CategoriesService::get_categories_manager('video')->regenerate_cache();
	}
}
?>
