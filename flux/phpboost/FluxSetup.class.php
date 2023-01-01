<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 01
 * @since       PHPBoost 6.0 - 2021 10 30
*/

class FluxSetup extends DefaultModuleSetup
{
	public static $flux_table;
	public static $flux_cats_table;

	/**
	 * @var string[string] localized messages
	*/
	private $messages;

	public static function __static()
	{
		self::$flux_table = PREFIX . 'flux';
		self::$flux_cats_table = PREFIX . 'flux_cats';
	}

	public function upgrade($installed_version)
	{
		return '6.0.0';
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
		ConfigManager::delete('flux', 'config');
		CacheManager::invalidate('module', 'flux');
		FluxService::delete_xml_files();
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$flux_table, self::$flux_cats_table));
	}

	private function create_tables()
	{
		$this->create_flux_table();
		$this->create_flux_cats_table();
	}

	private function create_flux_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_category' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'title' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'rewrited_title' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'website_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'website_xml' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'xml_path' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'content' => array('type' => 'text', 'length' => 65000),
			'creation_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'update_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'published' => array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'author_user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'views_number' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'visits_number' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'thumbnail' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'id_category' => array('type' => 'key', 'fields' => 'id_category'),
				'title' => array('type' => 'fulltext', 'fields' => 'title'),
				'content' => array('type' => 'fulltext', 'fields' => 'content')
			)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$flux_table, $fields, $options);
	}

	private function create_flux_cats_table()
	{
		RichCategory::create_categories_table(self::$flux_cats_table);
	}

	private function insert_data()
	{
		$this->messages = LangLoader::get('install', 'flux');
		$this->insert_flux_cats_data();
		$this->insert_flux_data();
	}

	private function insert_flux_cats_data()
	{
		PersistenceContext::get_querier()->insert(self::$flux_cats_table, array(
			'id'            => 1,
			'id_parent'     => 0,
			'c_order'       => 1,
			'auth'          => '',
			'rewrited_name' => Url::encode_rewrite($this->messages['default.category.name']),
			'name'          => $this->messages['default.category.name'],
			'description'   => $this->messages['default.category.description'],
		));
	}

	private function insert_flux_data()
	{
		PersistenceContext::get_querier()->insert(self::$flux_table, array(
			'id'             => 1,
			'id_category'    => 1,
			'title'          => $this->messages['default.item.title'],
			'rewrited_title' => Url::encode_rewrite($this->messages['default.item.title']),
			'content'        => $this->messages['default.item.content'],
			'published'      => FluxItem::PUBLISHED,
			'website_url'    => $this->messages['default.item.website'],
			'website_xml'    => $this->messages['default.item.website.xml'],
			'creation_date'  => time(),
			'update_date'    => time(),
			'author_user_id' => 1,
			'views_number'   => 0,
			'visits_number'  => 0,
			'thumbnail'  => '/templates/__default__/images/default_item.webp',
		));
	}
}
?>
