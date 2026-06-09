<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2022 10 25
 */

class BroadcastSetup extends DefaultModuleSetup
{
	public static $broadcast_table;
	public static $broadcast_cats_table;

	public static function __static()
	{
		self::$broadcast_table = PREFIX . 'broadcast';
		self::$broadcast_cats_table = PREFIX . 'broadcast_cats';
	}

	public function install()
	{
		$this->drop_tables();
		$this->create_tables();
	}

	public function uninstall()
	{
		$this->drop_tables();
		ConfigManager::delete('broadcast', 'config');
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop([self::$broadcast_table, self::$broadcast_cats_table]);
	}

	private function create_tables()
	{
		$this->create_broadcast_table();
		$this->create_broadcast_cats_table();
	}

	private function create_broadcast_table()
	{
		$fields = [
			'id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'id_category' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'title' => ['type' => 'string', 'length' => 250, 'notnull' => 1, 'default' => "''"],
			'rewrited_title' => ['type' => 'string', 'length' => 250, 'default' => "''"],
			'content' => ['type' => 'text', 'length' => 65000],
			'published' => ['type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0],
			'release_days' => ['type' =>  'text', 'length' => 65000],
			'start_time' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'end_time' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'creation_date' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'update_date' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'thumbnail_url' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'author_custom_name' => ['type' =>  'string', 'length' => 255, 'default' => "''"],
			'author_user_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
		];
		$options = [
			'primary' => ['id'],
			'indexes' => [
				'id_category' => ['type' => 'key', 'fields' => 'id_category'],
				'title' => ['type' => 'fulltext', 'fields' => 'title'],
				'content' => ['type' => 'fulltext', 'fields' => 'content']
		]];
		PersistenceContext::get_dbms_utils()->create_table(self::$broadcast_table, $fields, $options);
	}

	private function create_broadcast_cats_table()
	{
		RichCategory::create_categories_table(self::$broadcast_cats_table);
	}
}
?>
