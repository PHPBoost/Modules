<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2019 01 09
 * @since   	PHPBoost 4.0 - 2013 01 29
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

	public function upgrade($installed_version)
	{
		$this->columns = PersistenceContext::get_dbms_utils()->desc_table(self::$smallads_table);
		$this->disable_mini_menu();
		$this->delete_fields();
		$this->change_fields();
		$this->add_fields();
		$this->update_fields();

		$tables = PersistenceContext::get_dbms_utils()->list_tables(true);

		if (!in_array(self::$smallads_cats_table, $tables))
		{
			$this->create_smallads_cats_table();
			$this->insert_smallads_cats_data();
		}

		$this->delete_files();
		self::pics_to_upload();

		return '5.2.0';
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
		SmalladsService::get_keywords_manager()->delete_module_relations();
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
			'description' => array('type' => 'text', 'length' => 65000),
			'contents' => array('type' => 'text', 'length' => 65000),
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
			'publication_start_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'publication_end_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'creation_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'updated_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'sources' => array('type' => 'text', 'length' => 65000),
			'carousel' => array('type' => 'text', 'length' => 65000),
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'id_category' => array('type' => 'key', 'fields' => 'id_category'),
				'title' => array('type' => 'fulltext', 'fields' => 'title'),
				'description' => array('type' => 'fulltext', 'fields' => 'description'),
				'contents' => array('type' => 'fulltext', 'fields' => 'contents')
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
		$this->messages = LangLoader::get('install', 'smallads');
		PersistenceContext::get_querier()->insert(self::$smallads_cats_table, array(
			'id' => 1,
			'id_parent' => 0,
			'c_order' => 1,
			'auth' => '',
			'rewrited_name' => Url::encode_rewrite($this->messages['default.category.name']),
			'name' => $this->messages['default.category.name'],
			'description' => $this->messages['default.category.description'],
			'image' => '/smallads/smallads.png'
		));
	}

	private function insert_smallads_data()
	{
		PersistenceContext::get_querier()->insert(self::$smallads_table, array(
			'id' => 1,
			'id_category' => 1,
			'thumbnail_url' => '/smallads/templates/images/default.png',
			'title' => $this->messages['default.smallad.title'],
			'rewrited_title' => Url::encode_rewrite($this->messages['default.smallad.title']),
			'description' => $this->messages['default.smallad.description'],
			'contents' => $this->messages['default.smallad.contents'],
			'views_number' => 0,
			'max_weeks' => 1,
			'author_user_id' => 1,
			'custom_author_name' => '',
			'displayed_author_name' => Smallad::DISPLAYED_AUTHOR_NAME,
			'displayed_author_email' => Smallad::NOTDISPLAYED_AUTHOR_EMAIL,
			'displayed_author_pm' => Smallad::NOTDISPLAYED_AUTHOR_PM,
			'displayed_author_phone' => Smallad::NOTDISPLAYED_AUTHOR_PHONE,
			'published' => Smallad::PUBLISHED_NOW,
			'publication_start_date' => 0,
			'publication_end_date' => 0,
			'creation_date' => time(),
			'updated_date' => 0,
			'smallad_type' => Url::encode_rewrite($this->messages['default.smallad.type']),
			'sources' => TextHelper::serialize(array()),
			'carousel' => TextHelper::serialize(array())
		));
	}

	private function disable_mini_menu()
	{
		$menu_id = 0;
		try {
			$menu_id = PersistenceContext::get_querier()->get_column_value(DB_TABLE_MENUS, 'id', 'WHERE title = "smallads/SmalladsModuleMiniMenu"');
		} catch (RowNotFoundException $e) {}

		if ($menu_id)
 		{
			$menu = MenuService::load($menu_id);
			MenuService::delete($menu);
			MenuService::generate_cache();
		}
	}

	private function delete_fields()
	{
		if (isset($this->columns['cat_id']))
			PersistenceContext::get_dbms_utils()->drop_column(PREFIX . 'smallads', 'cat_id');
		if (isset($this->columns['links_flag']))
			PersistenceContext::get_dbms_utils()->drop_column(PREFIX . 'smallads', 'links_flag');
		if (isset($this->columns['shipping']))
			PersistenceContext::get_dbms_utils()->drop_column(PREFIX . 'smallads', 'shipping');
		if (isset($this->columns['vid']))
			PersistenceContext::get_dbms_utils()->drop_column(PREFIX . 'smallads', 'vid');
		if (isset($this->columns['id_updated']))
			PersistenceContext::get_dbms_utils()->drop_column(PREFIX . 'smallads', 'id_updated');
		if (isset($this->columns['date_approved']))
			PersistenceContext::get_dbms_utils()->drop_column(PREFIX . 'smallads', 'date_approved');
	}

	private function add_fields()
	{
		if (!isset($this->columns['id_category']))
			PersistenceContext::get_dbms_utils()->add_column(PREFIX . 'smallads', 'id_category', array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0));
		if (!isset($this->columns['rewrited_title']))
			PersistenceContext::get_dbms_utils()->add_column(PREFIX . 'smallads', 'rewrited_title', array('type' => 'string', 'length' => 255, 'default' => "''"));
		if (!isset($this->columns['description']))
			PersistenceContext::get_dbms_utils()->add_column(PREFIX . 'smallads', 'description', array('type' => 'text', 'length' => 65000));
		if (!isset($this->columns['brand']))
			PersistenceContext::get_dbms_utils()->add_column(PREFIX . 'smallads', 'brand', array('type' => 'string', 'length' => 255));
		if (!isset($this->columns['completed']))
			PersistenceContext::get_dbms_utils()->add_column(PREFIX . 'smallads', 'completed', array('type' => 'boolean', 'notnull' => 1, 'default' => 0));
		if (!isset($this->columns['location']))
			PersistenceContext::get_dbms_utils()->add_column(PREFIX . 'smallads', 'location', array('type' => 'text', 'length' => 65000));
		if (!isset($this->columns['other_location']))
			PersistenceContext::get_dbms_utils()->add_column(PREFIX . 'smallads', 'other_location', array('type' => 'string', 'length' => 255));
		if (!isset($this->columns['views_number']))
			PersistenceContext::get_dbms_utils()->add_column(PREFIX . 'smallads', 'views_number', array('type' => 'integer', 'length' => 11, 'default' => 0));
		if (!isset($this->columns['displayed_author_email']))
			PersistenceContext::get_dbms_utils()->add_column(PREFIX . 'smallads', 'displayed_author_email', array('type' => 'boolean', 'notnull' => 1, 'default' => 0));
		if (!isset($this->columns['custom_author_email']))
			PersistenceContext::get_dbms_utils()->add_column(PREFIX . 'smallads', 'custom_author_email', array('type' => 'string', 'length' => 255, 'default' => "''"));
		if (!isset($this->columns['displayed_author_pm']))
			PersistenceContext::get_dbms_utils()->add_column(PREFIX . 'smallads', 'displayed_author_pm', array('type' => 'boolean', 'notnull' => 1, 'default' => 1));
		if (!isset($this->columns['displayed_author_name']))
			PersistenceContext::get_dbms_utils()->add_column(PREFIX . 'smallads', 'displayed_author_name', array('type' => 'boolean', 'notnull' => 1, 'default' => 1));
		if (!isset($this->columns['custom_author_name']))
			PersistenceContext::get_dbms_utils()->add_column(PREFIX . 'smallads', 'custom_author_name', array('type' => 'string', 'length' => 255, 'default' => "''"));
		if (!isset($this->columns['displayed_author_phone']))
			PersistenceContext::get_dbms_utils()->add_column(PREFIX . 'smallads', 'displayed_author_phone', array('type' => 'boolean', 'notnull' => 1, 'default' => 0));
		if (!isset($this->columns['author_phone']))
			PersistenceContext::get_dbms_utils()->add_column(PREFIX . 'smallads', 'author_phone', array('type' => 'string', 'length' => 25, 'default' => "''"));
		if (!isset($this->columns['publication_start_date']))
			PersistenceContext::get_dbms_utils()->add_column(PREFIX . 'smallads', 'publication_start_date', array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0));
		if (!isset($this->columns['publication_end_date']))
			PersistenceContext::get_dbms_utils()->add_column(PREFIX . 'smallads', 'publication_end_date', array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0));
		if (!isset($this->columns['sources']))
			PersistenceContext::get_dbms_utils()->add_column(PREFIX . 'smallads', 'sources', array('type' => 'text', 'length' => 65000));
		if (!isset($this->columns['carousel']))
			PersistenceContext::get_dbms_utils()->add_column(PREFIX . 'smallads', 'carousel', array('type' => 'text', 'length' => 65000));

		$columns = PersistenceContext::get_dbms_utils()->desc_table(PREFIX . 'smallads');
		if (!isset($columns['title']['key']) || !$columns['title']['key'])
			PersistenceContext::get_querier()->inject('ALTER TABLE ' . PREFIX . 'smallads ADD FULLTEXT KEY `title` (`title`)');
		if (!isset($columns['contents']['key']) || !$columns['contents']['key'])
			PersistenceContext::get_querier()->inject('ALTER TABLE ' . PREFIX . 'smallads ADD FULLTEXT KEY `contents` (`contents`)');
		if (!isset($columns['description']['key']) || !$columns['description']['key'])
			PersistenceContext::get_querier()->inject('ALTER TABLE ' . PREFIX . 'smallads ADD FULLTEXT KEY `description` (`description`)');
		if (!isset($columns['id_category']['key']) || !$columns['id_category']['key'])
			PersistenceContext::get_querier()->inject('ALTER TABLE ' . PREFIX . 'smallads ADD FULLTEXT KEY `id_category` (`id_category`)');
	}

	private function change_fields()
	{
		//fields rename
		$rows_change = array(
			'picture' => 'thumbnail_url VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 0',
			'type' => 'smallad_type VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL',
			'id_created' => 'author_user_id INT(11) NOT NULL DEFAULT 0',
			'approved' => 'published INT(11) NOT NULL DEFAULT 0',
			'date_created' => 'creation_date INT(11) NOT NULL DEFAULT 0',
			'date_updated' => 'updated_date INT(11) NOT NULL DEFAULT 0'
		);

		foreach ($rows_change as $old_name => $new_name)
		{
			if (isset($this->columns[$old_name]))
				PersistenceContext::get_querier()->inject('ALTER TABLE ' . PREFIX . 'download CHANGE ' . $old_name . ' ' . $new_name);
		}
	}

	private function delete_files()
	{
		$file = new File(PATH_TO_ROOT . '/smallads/fields/SmalladsFormFieldSelectSources.class.php');
		$file->delete();
		$file = new File(PATH_TO_ROOT . '/smallads/templates/fields/SmalladsFormFieldSelectSources.tpl');
		$file->delete();
		$file = new File(PATH_TO_ROOT . '/smallads/controllers/AdminSmalladsConfigController.class.php');
		$file->delete();
		$file = new File(PATH_TO_ROOT . '/smallads/controllers/SmalladsHomeController.class.php');
		$file->delete();
		$file = new File(PATH_TO_ROOT . '/smallads/lang/english/smallads_french.php');
		$file->delete();
		$file = new File(PATH_TO_ROOT . '/smallads/lang/french/smallads_french.php');
		$file->delete();
		$file = new File(PATH_TO_ROOT . '/smallads/phpboost/SmalladsModuleMiniMenu.class.php');
		$file->delete();
		$file = new File(PATH_TO_ROOT . '/smallads/templates/smallads.tpl');
		$file->delete();
		$file = new File(PATH_TO_ROOT . '/smallads/templates/SmalladsModuleMiniMenu.tpl');
		$file->delete();
		$file = new File(PATH_TO_ROOT . '/smallads/smallads.class.php');
		$file->delete();
		$file = new File(PATH_TO_ROOT . '/smallads/smallads.php');
		$file->delete();
		$file = new File(PATH_TO_ROOT . '/smallads/smallads_begin.php');
		$file->delete();
	}

	private function update_fields()
	{
		$folder = new Folder(PATH_TO_ROOT . '/smallads/pics/';
		if ($folder->exists())
		{
			$this->messages = LangLoader::get('install', 'smallads');
			$result = PersistenceContext::get_querier()->select_rows(PREFIX . 'smallads', array('id', 'title', 'thumbnail_url', 'smallad_type', 'id_category'));
			while ($row = $result->fetch()) {
				PersistenceContext::get_querier()->update(PREFIX . 'smallads', array(
					'rewrited_title' => Url::encode_rewrite($row['title']),
					'smallad_type' => Url::encode_rewrite($this->messages['default.smallad.type']),
					'id_category' => 1,
				), 'WHERE id = :id', array('id' => $row['id']));
			}
			$result->dispose();
		}
	}

	public static function pics_to_upload()
	{
		// Move pics content to upload and delete pics
		$source = PATH_TO_ROOT . '/smallads/pics/';
		$folder = new Folder($source);
		if ($folder->exists())
		{
			$dest = PATH_TO_ROOT . '/upload/';
			if (is_dir($source)) {
				if ($dh = opendir($source)) {
					while (($file = readdir($dh)) !== false) {
						if ($file != '.' && $file != '..') {
							rename($source . $file, $dest . $file);
						}
					}
					closedir($dh);
				}
			}
			$folder->delete();

			// update thumbnail_url files to /upload/files
			$result = PersistenceContext::get_querier()->select_rows(PREFIX . 'smallads', array('id', 'thumbnail_url'));
			while ($row = $result->fetch()) {
				if ($row['thumbnail_url'] != "") {
					PersistenceContext::get_querier()->update(PREFIX . 'smallads', array(
						'thumbnail_url' => '/upload/' . $row['thumbnail_url'],
					), 'WHERE id = :id', array('id' => $row['id']));
				} else {
					PersistenceContext::get_querier()->update(PREFIX . 'smallads', array(
						'thumbnail_url' => '/smallads/templates/images/no-thumb.png',
					), 'WHERE id = :id', array('id' => $row['id']));
				}
			}
			$result->dispose();
		}
	}
}

?>
