<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 07 17
 * @since       PHPBoost 5.0 - 2016 02 18
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class QuotesSetup extends DefaultModuleSetup
{
	private $messages;
	public static $quotes_table;
	public static $quotes_cats_table;

	public static function __static()
	{
		self::$quotes_table = PREFIX . 'quotes';
		self::$quotes_cats_table = PREFIX . 'quotes_cats';
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
		ConfigManager::delete('quotes', 'config');
		CacheManager::invalidate('module', 'quotes');
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$quotes_table, self::$quotes_cats_table));
	}

	private function create_tables()
	{
		$this->create_quotes_table();
		$this->create_quotes_cats_table();
	}

	private function create_quotes_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_category' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'writer' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'rewrited_writer' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'content' => array('type' => 'text', 'length' => 65000),
			'creation_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'author_user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'approved' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'id_category' => array('type' => 'key', 'fields' => 'id_category'),
				'writer' => array('type' => 'fulltext', 'fields' => 'writer'),
				'content' => array('type' => 'fulltext', 'fields' => 'content')
			)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$quotes_table, $fields, $options);
	}

	private function create_quotes_cats_table()
	{
		RichCategory::create_categories_table(self::$quotes_cats_table);
	}

	private function insert_data()
	{
		$this->messages = LangLoader::get('install', 'quotes');

		PersistenceContext::get_querier()->insert(self::$quotes_table, array(
			'id' => 1,
			'id_category' => 0,
			'content' => $this->messages['quotes.1.content'],
			'writer' => $this->messages['quotes.1.writer'],
			'rewrited_writer' => Url::encode_rewrite($this->messages['quotes.1.writer']),
			'creation_date' => time(),
			'author_user_id' => 1,
			'approved' => 1
		));

		PersistenceContext::get_querier()->insert(self::$quotes_table, array(
			'id' => 2,
			'id_category' => 0,
			'content' => $this->messages['quotes.2.content'],
			'writer' => $this->messages['quotes.2.writer'],
			'rewrited_writer' => Url::encode_rewrite($this->messages['quotes.2.writer']),
			'creation_date' => time(),
			'author_user_id' => 1,
			'approved' => 1
		));

		PersistenceContext::get_querier()->insert(self::$quotes_table, array(
			'id' => 3,
			'id_category' => 0,
			'content' => $this->messages['quotes.3.content'],
			'writer' => $this->messages['quotes.3.writer'],
			'rewrited_writer' => Url::encode_rewrite($this->messages['quotes.3.writer']),
			'creation_date' => time(),
			'author_user_id' => 1,
			'approved' => 1
		));

		PersistenceContext::get_querier()->insert(self::$quotes_table, array(
			'id' => 4,
			'id_category' => 0,
			'content' => $this->messages['quotes.4.content'],
			'writer' => $this->messages['quotes.4.writer'],
			'rewrited_writer' => Url::encode_rewrite($this->messages['quotes.4.writer']),
			'creation_date' => time(),
			'author_user_id' => 1,
			'approved' => 1
		));

		PersistenceContext::get_querier()->insert(self::$quotes_table, array(
			'id' => 5,
			'id_category' => 0,
			'content' => $this->messages['quotes.5.content'],
			'writer' => $this->messages['quotes.5.writer'],
			'rewrited_writer' => Url::encode_rewrite($this->messages['quotes.5.writer']),
			'creation_date' => time(),
			'author_user_id' => 1,
			'approved' => 1
		));
	}
}
?>
