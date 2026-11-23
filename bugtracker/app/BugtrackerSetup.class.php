<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2012 04 16
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class BugtrackerSetup extends DefaultModuleSetup
{
	public static $bugtracker_table;
	public static $bugtracker_history_table;
	public static $bugtracker_users_filters_table;

	public static function __static()
	{
		self::$bugtracker_table = PREFIX . 'bugtracker';
		self::$bugtracker_history_table = PREFIX . 'bugtracker_history';
		self::$bugtracker_users_filters_table = PREFIX . 'bugtracker_users_filters';
	}

	public function install()
	{
		$this->drop_tables();
		$this->create_tables();
		$this->insert_data();
	}

	public function uninstall()
	{
		$this->drop_tables();
		ConfigManager::delete('bugtracker', 'config');
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop([self::$bugtracker_table, self::$bugtracker_history_table, self::$bugtracker_users_filters_table]);
	}

	private function create_tables()
	{
		$this->create_bugtracker_table();
		$this->create_bugtracker_history_table();
		$this->create_bugtracker_users_filters_table();
	}

	private function create_bugtracker_table()
	{
		$fields = [
			'id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'title' => ['type' => 'string', 'length' => 255, 'default' => "''"],
			'content' => ['type' => 'text', 'length' => 65000],
			'author_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'submit_date' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'fix_date' => ['type' => 'integer', 'length' => 11, 'default' => 0],
			'status' => ['type' => 'string', 'length' => 255, 'default' => "''"],
			'severity' => ['type' => 'integer', 'length' => 11, 'default' => 0],
			'priority' => ['type' => 'integer', 'length' => 11, 'default' => 0],
			'type' => ['type' => 'integer', 'length' => 11, 'default' => 0],
			'category' => ['type' => 'integer', 'length' => 11, 'default' => 0],
			'reproductible' => ['type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 1],
			'reproduction_method' => ['type' => 'text', 'length' => 65000],
			'detected_in' => ['type' => 'integer', 'length' => 11, 'default' => 0],
			'fixed_in' => ['type' => 'integer', 'length' => 11, 'default' => 0],
			'assigned_to_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0]
		];
		$options = [
			'primary' => ['id'],
			'indexes' => [
				'title' => ['type' => 'fulltext', 'fields' => 'title'],
				'content' => ['type' => 'fulltext', 'fields' => 'content']
			]
		];
		PersistenceContext::get_dbms_utils()->create_table(self::$bugtracker_table, $fields, $options);
	}

	private function create_bugtracker_history_table()
	{
		$fields = [
			'id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'bug_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'updater_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'update_date' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'updated_field' => ['type' => 'string', 'length' => 64, 'default' => 0],
			'old_value' => ['type' => 'text', 'length' => 65000],
			'new_value' => ['type' => 'text', 'length' => 65000],
			'change_comment' => ['type' => 'text', 'length' => 65000]
		];
		$options = [
			'primary' => ['id'],
			'indexes' => ['bug_id' => ['type' => 'key', 'fields' => 'bug_id']]
		];
		PersistenceContext::get_dbms_utils()->create_table(self::$bugtracker_history_table, $fields, $options);
	}

	private function create_bugtracker_users_filters_table()
	{
		$fields = [
			'id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'user_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'page' => ['type' => 'string', 'length' => 64, 'notnull' => 1, 'default' => "''"],
			'filters' => ['type' => 'string', 'length' => 64, 'notnull' => 1, 'default' => "''"],
			'filters_ids' => ['type' => 'string', 'length' => 64, 'notnull' => 1, 'default' => "''"],
		];
		$options = [
			'primary' => ['id'],
			'indexes' => ['user_id' => ['type' => 'key', 'fields' => 'user_id']]
		];
		PersistenceContext::get_dbms_utils()->create_table(self::$bugtracker_users_filters_table, $fields, $options);
	}

	private function insert_data()
	{
		$lang = LangLoader::get('install', 'bugtracker');

		PersistenceContext::get_querier()->insert(self::$bugtracker_table, [
			'id' => 1,
			'title' => $lang['bug.1.title'],
			'content' => $lang['bug.1.content'],
			'author_id' => 1,
			'submit_date' => time(),
			'fix_date' => 0,
			'status' => BugtrackerItem::NEW_BUG,
			'severity' => 1,
			'priority' => 3,
			'type' => 1,
			'category' => 1,
			'reproductible' => 1,
			'reproduction_method' => '',
			'detected_in' => 0,
			'fixed_in' => 0,
			'assigned_to_id' => 0
		]);

		PersistenceContext::get_querier()->insert(self::$bugtracker_table, [
			'id' => 2,
			'title' => $lang['bug.2.title'],
			'content' => $lang['bug.2.content'],
			'author_id' => 1,
			'submit_date' => time() - 1000,
			'fix_date' => time(),
			'status' => BugtrackerItem::FIXED,
			'severity' => 2,
			'priority' => 4,
			'type' => 1,
			'category' => 2,
			'reproductible' => 1,
			'reproduction_method' => '',
			'detected_in' => 0,
			'fixed_in' => 0,
			'assigned_to_id' => 0
		]);

		PersistenceContext::get_querier()->insert(self::$bugtracker_table, [
			'id' => 3,
			'title' => $lang['bug.3.title'],
			'content' => $lang['bug.3.content'],
			'author_id' => 1,
			'submit_date' => time(),
			'fix_date' => 0,
			'status' => BugtrackerItem::REOPEN,
			'severity' => 3,
			'priority' => 5,
			'type' => 1,
			'category' => 3,
			'reproductible' => 1,
			'reproduction_method' => '',
			'detected_in' => 0,
			'fixed_in' => 0,
			'assigned_to_id' => 0
		]);
	}
}
?>
