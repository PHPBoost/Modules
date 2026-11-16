<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 16
 * @since       PHPBoost 6.0 - 2022 10 17
 */

class VideoSetup extends DefaultModuleSetup
{
	public static string $video_table;
	public static string $video_cats_table;
	private array $messages;

	public static function __static(): void
	{
		self::$video_table = PREFIX . 'video';
		self::$video_cats_table = PREFIX . 'video_cats';
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
		ConfigManager::delete('video', 'config');
		CacheManager::invalidate('module', 'video');
		KeywordsService::get_keywords_manager()->delete_module_relations();
	}

	private function drop_tables(): void
	{
		PersistenceContext::get_dbms_utils()->drop([self::$video_table, self::$video_cats_table]);
	}

	private function create_tables(): void
	{
		$this->create_video_table();
		$this->create_video_cats_table();
	}

	private function create_video_table(): void
	{
		$fields = [
			'id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'id_category' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'thumbnail' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'title' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'rewrited_title' => ['type' => 'string', 'length' => 255, 'default' => "''"],
			'file_url' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'mime_type' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => 0],
			'width' => ['type' => 'integer', 'length' => 9, 'notnull' => 1, 'default' => 100],
			'height' => ['type' => 'integer', 'length' => 9, 'notnull' => 1, 'default' => 100],
			'content' => ['type' => 'text', 'length' => 65000],
			'summary' => ['type' => 'text', 'length' => 65000],
			'views_number' => ['type' => 'integer', 'length' => 11, 'default' => 0],
			'author_custom_name' => ['type' =>  'string', 'length' => 255, 'default' => "''"],
			'author_user_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'published' => ['type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0],
			'publishing_start_date' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'publishing_end_date' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'creation_date' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'update_date' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0]
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
		PersistenceContext::get_dbms_utils()->create_table(self::$video_table, $fields, $options);
	}

	private function create_video_cats_table(): void
	{
		RichCategory::create_categories_table(self::$video_cats_table);
	}

	private function insert_data(): void
	{
		$this->messages = LangLoader::get('install', 'video');
		$this->insert_video_cats_data();
		$this->insert_video_data();
	}

	private function insert_video_cats_data(): void
	{
		PersistenceContext::get_querier()->insert(self::$video_cats_table, [
			'id' => 1,
			'id_parent' => 0,
			'c_order' => 1,
			'auth' => '',
			'rewrited_name' => Url::encode_rewrite($this->messages['default.cat.name']),
			'name' => $this->messages['default.cat.name'],
			'description' => $this->messages['default.cat.description'],
			'thumbnail' => FormFieldThumbnail::DEFAULT_VALUE
		]);
	}

	private function insert_video_data(): void
	{
		PersistenceContext::get_querier()->insert(self::$video_table, [
			'id' => 1,
			'id_category' => 1,
			'title' => $this->messages['default.title'],
			'rewrited_title' => Url::encode_rewrite($this->messages['default.title']),
			'file_url' => $this->messages['default.file.url'],
			'mime_type' => 'video/host',
			'width' => '800',
			'height' => '450',
			'content' => $this->messages['default.content'],
			'summary' => '',
			'published' => VideoItem::PUBLISHED,
			'publishing_start_date' => 0,
			'publishing_end_date' => 0,
			'creation_date' => time(),
			'update_date' => time(),
			'author_custom_name' => '',
			'author_user_id' => 1,
			'views_number' => 0,
			'thumbnail' => FormFieldThumbnail::DEFAULT_VALUE
		]);
	}
}
?>
