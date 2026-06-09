<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2010 01 17
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CalendarSetup extends DefaultModuleSetup
{
	public static $calendar_events_table;
	public static $calendar_events_content_table;
	public static $calendar_cats_table;
	public static $calendar_users_relation_table;

	public static function __static()
	{
		self::$calendar_events_table = PREFIX . 'calendar_events';
		self::$calendar_events_content_table = PREFIX . 'calendar_events_content';
		self::$calendar_cats_table = PREFIX . 'calendar_cats';
		self::$calendar_users_relation_table = PREFIX . 'calendar_users_relation';
	}

	public function install()
	{
		$this->drop_tables();
		$this->create_tables();
	}

	public function uninstall()
	{
		$this->drop_tables();
		ConfigManager::delete('calendar', 'config');
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop([self::$calendar_events_table, self::$calendar_events_content_table, self::$calendar_cats_table, self::$calendar_users_relation_table]);
	}

	private function create_tables()
	{
		$this->create_calendar_events_table();
		$this->create_calendar_events_content_table();
		$this->create_calendar_cats_table();
		$this->create_calendar_users_relation_table();
	}

	private function create_calendar_events_table()
	{
		$fields = [
			'id_event' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'content_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'start_date' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'end_date' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'parent_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0]
		];
		$options = [
			'primary' => ['id_event'],
			'indexes' => [
				'content_id' => ['type' => 'key', 'fields' => 'content_id'],
				'parent_id' => ['type' => 'key', 'fields' => 'parent_id']
			]
		];
		PersistenceContext::get_dbms_utils()->create_table(self::$calendar_events_table, $fields, $options);
	}

	private function create_calendar_events_content_table()
	{
		$fields = [
			'id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'id_category' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'thumbnail' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'title' => ['type' => 'string', 'length' => 150, 'notnull' => 1, 'default' => "''"],
			'rewrited_title' => ['type' => 'string', 'length' => 250, 'default' => "''"],
			'content' => ['type' => 'text', 'length' => 65000],
			'location' => ['type' => 'text', 'length' => 65000],
			'map_displayed' => ['type' => 'boolean', 'notnull' => 1, 'default' => 0],
			'creation_date' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'update_date' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'author_user_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'cancelled' => ['type' => 'boolean', 'notnull' => 1, 'default' => 0],
			'approved' => ['type' => 'boolean', 'notnull' => 1, 'default' => 0],
			'registration_authorized' => ['type' => 'boolean', 'notnull' => 1, 'default' => 0],
			'registration_limit' => ['type' => 'boolean', 'notnull' => 1, 'default' => 0],
			'max_registered_members' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => -1],
			'last_registration_date' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'register_authorizations' => ['type' => 'text', 'length' => 65000],
			'repeat_number' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'repeat_type' => ['type' => 'string', 'length' => 25, 'notnull' => 1, 'default' => '\'' . CalendarItemContent::NEVER . '\'']
		];
		$options = [
			'primary' => ['id'],
			'indexes' => [
				'id_category' => ['type' => 'key', 'fields' => 'id_category'],
				'title' => ['type' => 'fulltext', 'fields' => 'title'],
				'content' => ['type' => 'fulltext', 'fields' => 'content']
			]
		];
		PersistenceContext::get_dbms_utils()->create_table(self::$calendar_events_content_table, $fields, $options);
	}

	private function create_calendar_cats_table()
	{
		CalendarCategory::create_categories_table(self::$calendar_cats_table);
	}

	private function create_calendar_users_relation_table()
	{
		$fields = [
			'event_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'user_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0]
		];
		$options = [
			'indexes' => [
				'event_id' => ['type' => 'key', 'fields' => 'event_id'],
				'user_id' => ['type' => 'key', 'fields' => 'user_id']
			]
		];
		PersistenceContext::get_dbms_utils()->create_table(self::$calendar_users_relation_table, $fields, $options);
	}
}
?>
