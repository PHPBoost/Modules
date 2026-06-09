<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2013 01 06
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
*/

class StatsSetup extends DefaultModuleSetup
{
	private static $db_utils;
	public static $stats_table;
	public static $stats_referer_table;

	public static function __static()
	{
		self::$db_utils = PersistenceContext::get_dbms_utils();
		self::$stats_table = PREFIX . 'stats';
		self::$stats_referer_table = PREFIX . 'stats_referer';
	}

	public function install()
	{
		$this->drop_tables();
		$this->create_tables();
	}

	public function uninstall()
	{
		$this->drop_tables();
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop([self::$stats_table, self::$stats_referer_table,]);
	}

	private function create_tables()
	{
		$this->create_stats_table();
		$this->create_stats_referer_table();
	}

	private function create_stats_table()
	{
		$fields = [
			'id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'stats_year' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'stats_month' => ['type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0],
			'stats_day' => ['type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0],
			'nbr' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'pages' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'pages_detail' => ['type' => 'text', 'length' => 65000]
		];
		$options = [
			'primary' => ['id'],
			'indexes' => [
				'stats_day' => ['type' => 'unique', 'fields' => ['stats_day', 'stats_month', 'stats_year']]
			]
		];
		self::$db_utils->create_table(self::$stats_table, $fields, $options);
	}

	private function create_stats_referer_table()
	{
		$fields = [
			'id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'url' => ['type' => 'string', 'length' => 255, 'default' => "''"],
			'relative_url' => ['type' => 'string', 'length' => 255, 'default' => "''"],
			'total_visit' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'today_visit' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'yesterday_visit' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'nbr_day' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'last_update' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'type' => ['type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0]
		];
		$options = [
			'primary' => ['id'],
			'indexes' => [
				'url' => ['type' => 'key', 'fields' => ['url', 'relative_url']]
			],
			'charset' => 'latin1'
		];
		self::$db_utils->create_table(self::$stats_referer_table, $fields, $options);
	}
}
?>
