<?php
/*##################################################
 *                         SmalladsSetup.class.php
 *                            -------------------
 *   begin                : January 29, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

class SmalladsSetup extends DefaultModuleSetup
{
	public static $smallads_table;

	public static function __static()
	{
		self::$smallads_table = PREFIX . 'smallads';
	}

	public function install()
	{
		$this->drop_tables();
		$this->create_tables();
	}
	
	public function upgrade($installed_version)
	{
		$columns = PersistenceContext::get_dbms_utils()->desc_table(PREFIX . 'smallads');
		
		if (!$columns['title']['key'])
			PersistenceContext::get_querier()->inject('ALTER TABLE ' . PREFIX . 'smallads ADD FULLTEXT KEY `title` (`title`)');
		if (!$columns['contents']['key'])
			PersistenceContext::get_querier()->inject('ALTER TABLE ' . PREFIX . 'smallads ADD FULLTEXT KEY `contents` (`contents`)');
		
		//Delete old files
		$file = new File(Url::to_rel('/smallads/admin_smallads.php'));
		$file->delete();
		$file = new File(Url::to_rel('/smallads/smallads.inc.php'));
		$file->delete();
		$file = new File(Url::to_rel('/smallads/templates/admin_smallads_config.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/smallads/templates/smallads_search_form.tpl'));
		$file->delete();
		
		return '5.0.5';
	}

	public function uninstall()
	{
		$this->drop_tables();
		ConfigManager::delete('smallads', 'config');
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$smallads_table));
	}

	private function create_tables()
	{
		$this->create_smallads_table();
	}

	private function create_smallads_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'cat_id' => array('type' => 'integer', 'length' => 11),
			'title' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'contents' => array('type' => 'text', 'length' => 65000, 'notnull' => 1, 'default' => "''"),
			'picture' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'id_created' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'date_created' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'type' => array('type' => 'integer', 'length' => 4, 'notnull' => 1, 'default' => 0),
			'price' => array('type' => 'decimal', 'length' => 10, 'scale' => 2, 'notnull' => 1, 'default' => 0.00),
			'shipping' => array('type' => 'decimal', 'length' => 10, 'scale' => 2, 'notnull' => 1, 'default' => 0.00),
			'approved' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'date_approved' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'id_updated' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'date_updated' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'links_flag' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'vid' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'max_weeks' => array('type' => 'integer', 'length' => 11)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'title' => array('type' => 'fulltext', 'fields' => 'title'),
				'contents' => array('type' => 'fulltext', 'fields' => 'contents'),
				'date_created' => array('type' => 'key', 'fields' => 'date_created'),
				'vid' => array('type' => 'key', 'fields' => 'vid'),
				'date_approved' => array('type' => 'key', 'fields' => 'date_approved'),
				'approved' => array('type' => 'key', 'fields' => 'approved'),
				'id_created' => array('type' => 'key', 'fields' => 'id_created'),
			)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$smallads_table, $fields, $options);
	}
}
?>
