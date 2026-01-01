<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 10 17
 * @since       PHPBoost 6.0 - 2022 10 17
 */

class VideoSetup extends DefaultModuleSetup
{
	public static $video_table;
	public static $video_cats_table;

	/**
	 * @var string[string] localized messages
	 */
	private $messages;

	public static function __static()
	{
		self::$video_table = PREFIX . 'video';
		self::$video_cats_table = PREFIX . 'video_cats';
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
		ConfigManager::delete('video', 'config');
		CacheManager::invalidate('module', 'video');
		KeywordsService::get_keywords_manager()->delete_module_relations();
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$video_table, self::$video_cats_table));
	}

	private function create_tables()
	{
		$this->create_video_table();
		$this->create_video_cats_table();
	}

	private function create_video_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_category' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'thumbnail' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'title' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'rewrited_title' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'file_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'mime_type' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => 0),
			'width' => array('type' => 'integer', 'length' => 9, 'notnull' => 1, 'default' => 100),
			'height' => array('type' => 'integer', 'length' => 9, 'notnull' => 1, 'default' => 100),
			'content' => array('type' => 'text', 'length' => 65000),
			'summary' => array('type' => 'text', 'length' => 65000),
			'views_number' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'author_custom_name' => array('type' =>  'string', 'length' => 255, 'default' => "''"),
			'author_user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'published' => array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'publishing_start_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'publishing_end_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'creation_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'update_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'id_category' => array('type' => 'key', 'fields' => 'id_category'),
				'title' => array('type' => 'fulltext', 'fields' => 'title'),
				'content' => array('type' => 'fulltext', 'fields' => 'content'),
				'summary' => array('type' => 'fulltext', 'fields' => 'summary')
			)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$video_table, $fields, $options);
	}

	private function create_video_cats_table()
	{
		RichCategory::create_categories_table(self::$video_cats_table);
	}

	private function insert_data()
	{
		$this->messages = LangLoader::get('install', 'video');
		$this->insert_video_cats_data();
		$this->insert_video_data();
	}

	private function insert_video_cats_data()
	{
		PersistenceContext::get_querier()->insert(self::$video_cats_table, array(
			'id' => 1,
			'id_parent' => 0,
			'c_order' => 1,
			'auth' => '',
			'rewrited_name' => Url::encode_rewrite($this->messages['default.cat.name']),
			'name' => $this->messages['default.cat.name'],
			'description' => $this->messages['default.cat.description'],
			'thumbnail' => FormFieldThumbnail::DEFAULT_VALUE
		));
	}

	private function insert_video_data()
	{
		PersistenceContext::get_querier()->insert(self::$video_table, array(
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
		));
	}
}
?>
