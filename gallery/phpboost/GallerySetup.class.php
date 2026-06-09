<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2010 01 17
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class GallerySetup extends DefaultModuleSetup
{
    private $messages;
	public static $gallery_table;
	public static $gallery_cats_table;

	public static function __static()
	{
		self::$gallery_table = PREFIX . 'gallery';
		self::$gallery_cats_table = PREFIX . 'gallery_cats';
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
		ConfigManager::delete('gallery', 'config');
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop([self::$gallery_table, self::$gallery_cats_table]);
	}

	private function create_tables()
	{
		$this->create_gallery_table();
		$this->create_gallery_cats_table();
	}

	private function create_gallery_table()
	{
		$fields = [
			'id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'id_category' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'name' => ['type' => 'string', 'length' => 255, 'default' => "''"],
			'path' => ['type' => 'string', 'length' => 255, 'default' => "''"],
			'width' => ['type' => 'integer', 'length' => 9, 'notnull' => 1, 'default' => 0],
			'height' => ['type' => 'integer', 'length' => 9, 'notnull' => 1, 'default' => 0],
			'weight' => ['type' => 'integer', 'length' => 9, 'notnull' => 1, 'default' => 0],
			'user_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'aprob' => ['type' => 'boolean', 'notnull' => 1, 'default' => 0],
			'views' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'timestamp' => ['type' => 'integer', 'length' => 11, 'default' => 0]
		];
		$options = [
			'primary' => ['id'],
			'indexes' => [
				'id_category' => ['type' => 'key', 'fields' => 'id_category']
			]
		];
		PersistenceContext::get_dbms_utils()->create_table(self::$gallery_table, $fields, $options);
	}

	private function create_gallery_cats_table()
	{
		RichCategory::create_categories_table(self::$gallery_cats_table);
	}

	private function insert_data()
	{
		$this->messages = LangLoader::get('install', 'gallery');
		$this->insert_gallery_cats_data();
		$this->insert_gallery_data();
	}

	private function insert_gallery_cats_data()
	{
		PersistenceContext::get_querier()->insert(self::$gallery_cats_table, [
			'id' => 1,
			'id_parent' => 0,
			'c_order' => 1,
			'auth' => '',
			'rewrited_name' => Url::encode_rewrite($this->messages['default.cat.name']),
			'name' => $this->messages['default.cat.name'],
			'description' => $this->messages['default.cat.description'],
			'thumbnail' => '/templates/__default__/images/default_category.webp'
		]);
	}

	private function insert_gallery_data()
	{
		PersistenceContext::get_querier()->insert(self::$gallery_table, [
			'id' => 1,
			'id_category' => 1,
			'name' => $this->messages['default.gallerypicture.name'],
			'path' => 'phpboost-logo.png',
			'width' => 90,
			'height' => 90,
			'weight' => 8080,
			'user_id' => 1,
			'aprob' => 1,
			'views' => 0,
			'timestamp' => time()
		]);
	}
}
?>
