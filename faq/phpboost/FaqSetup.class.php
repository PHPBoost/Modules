<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 4.0 - 2014 09 02
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FaqSetup extends DefaultModuleSetup
{
	public static $faq_table;
	public static $faq_cats_table;

	/**
	 * @var string[string] localized messages
	 */
	private $messages;

	public static function __static()
	{
		self::$faq_table = PREFIX . 'faq';
		self::$faq_cats_table = PREFIX . 'faq_cats';
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
		ConfigManager::delete('faq', 'config');
		CacheManager::invalidate('module', 'faq');
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop([self::$faq_table, self::$faq_cats_table]);
	}

	private function create_tables()
	{
		$this->create_faq_table();
		$this->create_faq_cats_table();
	}

	private function create_faq_table()
	{
		$fields = [
			'id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'id_category' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'q_order' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'title' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'content' => ['type' => 'text', 'length' => 65000],
			'creation_date' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'author_user_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'approved' => ['type' => 'boolean', 'notnull' => 1, 'default' => 0]
		];
		$options = [
			'primary' => ['id'],
			'indexes' => [
				'id_category' => ['type' => 'key', 'fields' => 'id_category'],
				'title' => ['type' => 'fulltext', 'fields' => 'title'],
				'content' => ['type' => 'fulltext', 'fields' => 'content']
			]
		];
		PersistenceContext::get_dbms_utils()->create_table(self::$faq_table, $fields, $options);
	}

	private function create_faq_cats_table()
	{
		RichCategory::create_categories_table(self::$faq_cats_table);
	}

	private function insert_data()
	{
		$this->messages = LangLoader::get('install', 'faq');
		$this->insert_faq_cats_data();
		$this->insert_faq_data();
	}

	private function insert_faq_cats_data()
	{
		PersistenceContext::get_querier()->insert(self::$faq_cats_table, [
			'id' => 1,
			'id_parent' => 0,
			'c_order' => 1,
			'auth' => '',
			'rewrited_name' => Url::encode_rewrite($this->messages['default.cat.phpboost.name']),
			'name' => $this->messages['default.cat.phpboost.name'],
			'description' => $this->messages['default.cat.phpboost.description'],
			'thumbnail' => '/templates/__default__/images/default_category.webp'
		]);

		PersistenceContext::get_querier()->insert(self::$faq_cats_table, [
			'id' => 2,
			'id_parent' => 0,
			'c_order' => 2,
			'auth' => '',
			'rewrited_name' => Url::encode_rewrite($this->messages['default.cat.dictionary.name']),
			'name' => $this->messages['default.cat.dictionary.name'],
			'description' => $this->messages['default.cat.dictionary.description'],
			'thumbnail' => '/templates/__default__/images/default_category.webp'
		]);
	}

	private function insert_faq_data()
	{
		PersistenceContext::get_querier()->insert(self::$faq_table, [
			'id' => 1,
			'id_category' => 1,
			'q_order' => 1,
			'title' => $this->messages['default.title.what.is.phpboost'],
			'content' => $this->messages['default.content.what.is.phpboost'],
			'creation_date' => time(),
			'author_user_id' => 1,
			'approved' => 1
		]);

		PersistenceContext::get_querier()->insert(self::$faq_table, [
			'id' => 2,
			'id_category' => 2,
			'q_order' => 1,
			'title' => $this->messages['default.title.what.is.a.cms'],
			'content' => $this->messages['default.content.what.is.a.cms'],
			'creation_date' => time(),
			'author_user_id' => 1,
			'approved' => 1
		]);
	}
}
?>
