<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 4.0 - 2013 01 29
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @author      mipel <mipel@phpboost.com>
*/

class SmalladsSetup extends DefaultModuleSetup
{
	public static $smallads_table;
	public static $smallads_cats_table;
	/**
	 * @var string[string] localized messages
	 */
	private $messages;

	public static function __static()
	{
		self::$smallads_table = PREFIX . 'smallads';
		self::$smallads_cats_table = PREFIX . 'smallads_cats';
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
		ConfigManager::delete('smallads', 'config');
		KeywordsService::get_keywords_manager()->delete_module_relations();
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop([self::$smallads_table, self::$smallads_cats_table]);
	}

	private function create_tables()
	{
		$this->create_smallads_table();
		$this->create_smallads_cats_table();
	}

	private function create_smallads_table()
	{
		$fields = [
			'id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'id_category' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'thumbnail_url' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'title' => ['type' => 'string', 'length' => 250, 'notnull' => 1, 'default' => "''"],
			'rewrited_title' => ['type' => 'string', 'length' => 250, 'default' => "''"],
			'summary' => ['type' => 'text', 'length' => 65000],
			'content' => ['type' => 'text', 'length' => 65000],
			'price' => ['type' => 'decimal', 'length' => 7, 'notnull' => 1, 'scale' => 2, 'default' => 0],
			'max_weeks' => ['type' => 'integer', 'length' => 11],
			'smallad_type' => ['type' => 'string', 'length' => 255],
			'brand' => ['type' => 'string', 'length' => 255],
			'completed' => ['type' => 'boolean', 'notnull' => 1, 'default' => 0],
			'archived' => ['type' => 'boolean', 'notnull' => 1, 'default' => 0],
			'views_number' => ['type' => 'integer', 'length' => 11, 'default' => 0],
			'author_user_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'location' => ['type' => 'text', 'length' => 65000],
			'other_location' => ['type' => 'string', 'length' => 255],
			'displayed_author_email' => ['type' => 'boolean', 'notnull' => 1, 'default' => 1],
			'custom_author_email' => ['type' => 'string', 'length' => 255, 'default' => "''"],
			'displayed_author_pm' => ['type' => 'boolean', 'notnull' => 1, 'default' => 1],
			'displayed_author_name' => ['type' => 'boolean', 'notnull' => 1, 'default' => 1],
			'custom_author_name' => ['type' => 'string', 'length' => 255, 'default' => "''"],
			'displayed_author_phone' => ['type' => 'boolean', 'notnull' => 1, 'default' => 1],
			'author_phone' => ['type' => 'string', 'length' => 25, 'default' => "''"],
			'published' => ['type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0],
			'publishing_start_date' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'publishing_end_date' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'creation_date' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'update_date' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'sources' => ['type' => 'text', 'length' => 65000],
			'carousel' => ['type' => 'text', 'length' => 65000],
		];
		$options = [
			'primary' => ['id'],
			'indexes' => [
				'id_category' => ['type' => 'key', 'fields' => 'id_category'],
				'title' => ['type' => 'fulltext', 'fields' => 'title'],
				'summary' => ['type' => 'fulltext', 'fields' => 'summary'],
				'content' => ['type' => 'fulltext', 'fields' => 'content']
			]
		];
		PersistenceContext::get_dbms_utils()->create_table(self::$smallads_table, $fields, $options);
	}

	private function create_smallads_cats_table()
	{
		RichCategory::create_categories_table(self::$smallads_cats_table);
	}

	private function insert_data()
	{
		$this->messages = LangLoader::get('install', 'smallads');
		$this->insert_smallads_data();
		$this->insert_smallads_cats_data();
	}

	private function insert_smallads_cats_data()
	{
		PersistenceContext::get_querier()->insert(self::$smallads_cats_table, [
			'id' => 1,
			'id_parent' => 0,
			'c_order' => 1,
			'auth' => '',
			'rewrited_name' => Url::encode_rewrite($this->messages['default.category.name']),
			'name' => $this->messages['default.category.name'],
			'description' => $this->messages['default.category.description'],
			'thumbnail' => '/templates/__default__/images/default_category.webp'
		]);
	}

	private function insert_smallads_data()
	{
		$common_lang = langloader::get('common', 'smallads');
		PersistenceContext::get_querier()->insert(self::$smallads_table, [
			'id' => 1,
			'id_category' => 1,
			'thumbnail_url' => '/templates/__default__/images/default_item.webp',
			'title' => $this->messages['default.smallad.title'],
			'rewrited_title' => Url::encode_rewrite($this->messages['default.smallad.title']),
			'content' => $this->messages['default.smallad.content'],
			'views_number' => 0,
			'max_weeks' => 1,
			'smallad_type' => TextHelper::htmlspecialchars(Url::encode_rewrite($common_lang['smallads.default.type'])),
			'author_user_id' => 1,
			'custom_author_name' => '',
			'displayed_author_name' => SmalladsItem::DISPLAYED_AUTHOR_NAME,
			'displayed_author_email' => SmalladsItem::NOT_DISPLAYED_AUTHOR_EMAIL,
			'displayed_author_pm' => SmalladsItem::NOT_DISPLAYED_AUTHOR_PM,
			'displayed_author_phone' => SmalladsItem::NOT_DISPLAYED_AUTHOR_PHONE,
			'published' => SmalladsItem::PUBLISHED_NOW,
			'publishing_start_date' => 0,
			'publishing_end_date' => 0,
			'creation_date' => time(),
			'update_date' => 0,
			'sources' => TextHelper::serialize([]),
			'carousel' => TextHelper::serialize([])
		]);
	}
}

?>
