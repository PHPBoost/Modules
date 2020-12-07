<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 07
 * @since       PHPBoost 4.0 - 2013 01 29
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
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
		PersistenceContext::get_dbms_utils()->drop(array(self::$smallads_table, self::$smallads_cats_table));
	}

	private function create_tables()
	{
		$this->create_smallads_table();
		$this->create_smallads_cats_table();
	}

	private function create_smallads_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_category' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'thumbnail_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'title' => array('type' => 'string', 'length' => 250, 'notnull' => 1, 'default' => "''"),
			'rewrited_title' => array('type' => 'string', 'length' => 250, 'default' => "''"),
			'summary' => array('type' => 'text', 'length' => 65000),
			'content' => array('type' => 'text', 'length' => 65000),
			'price' => array('type' => 'decimal', 'length' => 7, 'notnull' => 1, 'scale' => 2, 'default' => 0),
			'max_weeks' => array('type' => 'integer', 'length' => 11),
			'smallad_type' => array('type' => 'string', 'length' => 255),
			'brand' => array('type' => 'string', 'length' => 255),
			'completed' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'views_number' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'author_user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'location' => array('type' => 'text', 'length' => 65000),
			'other_location' => array('type' => 'string', 'length' => 255),
			'displayed_author_email' => array('type' => 'boolean', 'notnull' => 1, 'default' => 1),
			'custom_author_email' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'displayed_author_pm' => array('type' => 'boolean', 'notnull' => 1, 'default' => 1),
			'displayed_author_name' => array('type' => 'boolean', 'notnull' => 1, 'default' => 1),
			'custom_author_name' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'displayed_author_phone' => array('type' => 'boolean', 'notnull' => 1, 'default' => 1),
			'author_phone' => array('type' => 'string', 'length' => 25, 'default' => "''"),
			'published' => array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'publishing_start_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'publishing_end_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'creation_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'update_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'sources' => array('type' => 'text', 'length' => 65000),
			'carousel' => array('type' => 'text', 'length' => 65000),
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'id_category' => array('type' => 'key', 'fields' => 'id_category'),
				'title' => array('type' => 'fulltext', 'fields' => 'title'),
				'summary' => array('type' => 'fulltext', 'fields' => 'summary'),
				'content' => array('type' => 'fulltext', 'fields' => 'content')
		));
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
		PersistenceContext::get_querier()->insert(self::$smallads_cats_table, array(
			'id' => 1,
			'id_parent' => 0,
			'c_order' => 1,
			'auth' => '',
			'rewrited_name' => Url::encode_rewrite($this->messages['default.category.name']),
			'name' => $this->messages['default.category.name'],
			'description' => $this->messages['default.category.description'],
			'thumbnail' => '/templates/default/images/default_category_thumbnail.png'
		));
	}

	private function insert_smallads_data()
	{
		PersistenceContext::get_querier()->insert(self::$smallads_table, array(
			'id' => 1,
			'id_category' => 1,
			'thumbnail_url' => '/templates/default/images/default_item_thumbnail.png',
			'title' => $this->messages['default.smallad.title'],
			'rewrited_title' => Url::encode_rewrite($this->messages['default.smallad.title']),
			'summary' => $this->messages['default.smallad.summary'],
			'content' => $this->messages['default.smallad.content'],
			'views_number' => 0,
			'max_weeks' => 1,
			'smallad_type' => TextHelper::htmlspecialchars(Url::encode_rewrite($this->messages['default.smallad.type'])),
			'author_user_id' => 1,
			'custom_author_name' => '',
			'displayed_author_name' => Smallad::DISPLAYED_AUTHOR_NAME,
			'displayed_author_email' => Smallad::NOTDISPLAYED_AUTHOR_EMAIL,
			'displayed_author_pm' => Smallad::NOTDISPLAYED_AUTHOR_PM,
			'displayed_author_phone' => Smallad::NOTDISPLAYED_AUTHOR_PHONE,
			'published' => Smallad::PUBLISHED_NOW,
			'publishing_start_date' => 0,
			'publishing_end_date' => 0,
			'creation_date' => time(),
			'update_date' => 0,
			'smallad_type' => Url::encode_rewrite($this->messages['default.smallad.type']),
			'sources' => TextHelper::serialize(array()),
			'carousel' => TextHelper::serialize(array())
		));
	}
}

?>
