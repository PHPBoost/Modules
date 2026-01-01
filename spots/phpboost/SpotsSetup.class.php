<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 02 19
 * @since       PHPBoost 6.0 - 2021 08 22
*/

class SpotsSetup extends DefaultModuleSetup
{
	public static $spots_table;
	public static $spots_cats_table;

	/**
	 * @var string[string] localized messages
	 */
	private $messages;

	public static function __static()
	{
		self::$spots_table = PREFIX . 'spots';
		self::$spots_cats_table = PREFIX . 'spots_cats';
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
		ConfigManager::delete('spots', 'config');
		CacheManager::invalidate('module', 'spots');
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$spots_table, self::$spots_cats_table));
	}

	private function create_tables()
	{
		$this->create_spots_table();
		$this->create_spots_cats_table();
	}

	private function create_spots_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_category' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'title' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'rewrited_title' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'website_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'content' => array('type' => 'text', 'length' => 65000),
			'creation_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'update_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'published' => array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'author_user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'views_number' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'visits_number' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'thumbnail' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'location' => array('type' => 'text', 'length' => 65000),
			'latitude' => array('type' => 'decimal', 'length' => 18, 'scale' => 15, 'notnull' => 1, 'default' => 0),
			'longitude' => array('type' => 'decimal', 'length' => 18, 'scale' => 15, 'notnull' => 1, 'default' => 0),
			'route_enabled' => array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'travel_type' => array('type' => 'string', 'lenght' => 255, 'default' => 0),
            'spot_email' => array('type' => 'text', 'length' => 65000),
			'phone' => array('type' => 'text', 'length' => 65000),
			'facebook' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'twitter' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'instagram' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'youtube' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'id_category' => array('type' => 'key', 'fields' => 'id_category'),
				'title' => array('type' => 'fulltext', 'fields' => 'title'),
				'content' => array('type' => 'fulltext', 'fields' => 'content')
			)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$spots_table, $fields, $options);
	}

	private function create_spots_cats_table()
	{
		SpotsCategory::create_categories_table(self::$spots_cats_table);
	}

	private function insert_data()
	{
		$this->messages = LangLoader::get('install', 'spots');
		$this->insert_spots_cats_data();
		$this->insert_spots_data();
	}

	private function insert_spots_cats_data()
	{
		PersistenceContext::get_querier()->insert(self::$spots_cats_table, array(
			'id'            => 1,
			'id_parent'     => 0,
			'c_order'       => 1,
			'auth'          => '',
			'rewrited_name' => Url::encode_rewrite($this->messages['default.category.name']),
			'name'          => $this->messages['default.category.name'],
			'color'			=> '#366493',
			'inner_icon'	=> 'fa fa-circle'
		));
	}

	private function insert_spots_data()
	{
		PersistenceContext::get_querier()->insert(self::$spots_table, array(
			'id'             => 1,
			'id_category'    => 1,
			'title'          => $this->messages['default.item.title'],
			'rewrited_title' => Url::encode_rewrite($this->messages['default.item.title']),
			'content'        => $this->messages['default.item.content'],
			'published'      => SpotsItem::PUBLISHED,
			'location'       => $this->messages['default.item.location'],
			'route_enabled'  => 1,
			'travel_type'    => SpotsItem::TRAVEL_TYPE_DRIVING,
			'latitude'       => $this->messages['default.item.latitude'],
			'longitude'      => $this->messages['default.item.longitude'],
			'website_url'    => $this->messages['default.item.website'],
			'creation_date'  => time(),
			'update_date'    => time(),
			'author_user_id' => 1,
			'views_number'   => 0,
			'visits_number'  => 0,
			'thumbnail'      => '/templates/__default__/images/default_item.webp',
		));
	}
}
?>
