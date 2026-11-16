<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 16
 * @since       PHPBoost 4.1 - 2014 08 21
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class WebSetup extends DefaultModuleSetup
{
	public static string $web_table;
	public static string $web_cats_table;
	private array $messages;

	public static function __static(): void
	{
		self::$web_table = PREFIX . 'web';
		self::$web_cats_table = PREFIX . 'web_cats';
	}

	public function install(): void
	{
		$this->drop_tables();
		$this->create_tables();
		$this->insert_data();
	}

	public function uninstall(): void
	{
		$this->drop_tables();
		ConfigManager::delete('web', 'config');
		CacheManager::invalidate('module', 'web');
		KeywordsService::get_keywords_manager()->delete_module_relations();
	}

	private function drop_tables(): void
	{
		PersistenceContext::get_dbms_utils()->drop([self::$web_table, self::$web_cats_table]);
	}

	private function create_tables(): void
	{
		$this->create_web_table();
		$this->create_web_cats_table();
	}

	private function create_web_table(): void
	{
		$fields = [
			'id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'id_category' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'title' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'rewrited_title' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'website_url' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'content' => ['type' => 'text', 'length' => 65000],
			'summary' => ['type' => 'text', 'length' => 65000],
			'published' => ['type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0],
			'publishing_start_date' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'publishing_end_date' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'creation_date' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'update_date' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'author_user_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'views_number' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'thumbnail' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'partner' => ['type' => 'boolean', 'notnull' => 1, 'default' => 0],
			'partner_thumbnail' => ['type' => 'string', 'length' => 255, 'default' => "''"],
			'privileged_partner' => ['type' => 'boolean', 'notnull' => 1, 'default' => 0]
		];
		$options = [
			'primary' => ['id'],
			'indexes' => [
				'id_category' => ['type' => 'key', 'fields' => 'id_category'],
				'title' => ['type' => 'fulltext', 'fields' => 'title'],
				'content' => ['type' => 'fulltext', 'fields' => 'content'],
				'summary' => ['type' => 'fulltext', 'fields' => 'summary']
			]
		];
		PersistenceContext::get_dbms_utils()->create_table(self::$web_table, $fields, $options);
	}

	private function create_web_cats_table(): void
	{
		RichCategory::create_categories_table(self::$web_cats_table);
	}

	private function insert_data(): void
	{
		$this->messages = LangLoader::get('install', 'web');
		$this->insert_web_cats_data();
		$this->insert_web_data();
	}

	private function insert_web_cats_data(): void
	{
		PersistenceContext::get_querier()->insert(self::$web_cats_table, [
			'id' => 1,
			'id_parent' => 0,
			'c_order' => 1,
			'auth' => '',
			'rewrited_name' => Url::encode_rewrite($this->messages['default.category.name']),
			'name' => $this->messages['default.category.name'],
			'description' => $this->messages['default.category.description'],
			'thumbnail' =>  FormFieldThumbnail::DEFAULT_VALUE
		]);
	}

	private function insert_web_data(): void
	{
		PersistenceContext::get_querier()->insert(self::$web_table, [
			'id' => 1,
			'id_category' => 1,
			'title' => $this->messages['default.item.title'],
			'rewrited_title' => Url::encode_rewrite($this->messages['default.item.title']),
			'website_url' => 'https://www.phpboost.com',
			'content' => $this->messages['default.item.content'],
			'summary' => '',
			'published' => WebItem::PUBLISHED,
			'publishing_start_date' => 0,
			'publishing_end_date' => 0,
			'creation_date' => time(),
			'update_date' => time(),
			'author_user_id' => 1,
			'views_number' => 0,
			'partner' => 1,
			'partner_thumbnail' => '/modules/web/templates/images/phpboost_banner.png'
		]);
	}
}
?>
