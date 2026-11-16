<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 1116
 * @since       PHPBoost 3.0 - 2010 05 28
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class GuestbookSetup extends DefaultModuleSetup
{
	public static string $guestbook_table;

	public static function __static(): void
	{
		self::$guestbook_table = PREFIX . 'guestbook';
	}

	public function install(): void
	{
		$this->drop_tables();
		$this->create_tables();
	}

	public function uninstall(): void
	{
		$this->drop_tables();
		$this->delete_configuration();
	}

	private function drop_tables(): void
	{
		PersistenceContext::get_dbms_utils()->drop([self::$guestbook_table]);
	}

	private function delete_configuration(): void
	{
		ConfigManager::delete('guestbook', 'config');
	}

	private function create_tables(): void
	{
		$this->create_guestbook_table();
	}

	private function create_guestbook_table(): void
	{
		$fields = [
			'id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'content' => ['type' => 'text', 'length' => 65000],
			'login' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'user_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'timestamp' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0]
		];
		$options = [
			'primary' => ['id'],
			'indexes' => [
				'timestamp' => ['type' => 'key', 'fields' => 'timestamp']
			]
		];
		PersistenceContext::get_dbms_utils()->create_table(self::$guestbook_table, $fields, $options);
	}
}
?>
