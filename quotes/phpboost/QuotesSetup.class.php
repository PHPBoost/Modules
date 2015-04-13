<?php
/*##################################################
 *                         QuotesSetup.class.php
 *                            -------------------
 *   begin                : February 4, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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

class QuotesSetup extends DefaultModuleSetup
{
	private static $quotes_table;
	private static $quotes_cats_table;

	public static function __static()
	{
		self::$quotes_table = PREFIX . 'quotes';
		self::$quotes_cats_table = PREFIX . 'quotes_cats';
	}
	
	public function __construct()
	{
		$this->querier = PersistenceContext::get_querier();
	}
	
	public function install()
	{
		$this->drop_tables();
		$this->create_tables();
		$this->insert_data();
	}
	
	public function upgrade($installed_version)
	{
		return '4.1.0';
	}

	public function uninstall()
	{
		$this->drop_tables();
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$quotes_table));
		PersistenceContext::get_dbms_utils()->drop(array(self::$quotes_cats_table));
	}

	private function create_tables()
	{
		$this->create_quotes_table();
		$this->create_quotes_cats_table();
	}

	private function create_quotes_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'idcat' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'contents' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'author' => array('type' => 'string', 'length' => 63, 'notnull' => 1, 'default' => "''"),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'in_mini' => array('type' => 'integer', 'length' => 4, 'notnull' => 1, 'default' => 1),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'approved' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'timestamp' => array('type' => 'key', 'fields' => 'timestamp'),
				'idcat' => array('type' => 'key', 'fields' => 'idcat'),
				'contents' => array('type' => 'fulltext', 'fields' => 'contents'),
				'author' => array('type' => 'fulltext', 'fields' => 'author'),
			)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$quotes_table, $fields, $options);
	}
	
	private function create_quotes_cats_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_parent' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'c_order' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'name' => array('type' => 'string', 'length' => 150, 'notnull' => 1),
			'description' => array('type' => 'text', 'length' => 65000),
			'image' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'visible' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'auth' => array('type' => 'text', 'default' => "''")
			);
		$options = array(
			'primary' => array('id'),
			'indexes' => array('class' => array('type' => 'key', 'fields' => 'c_order'))
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$quotes_cats_table, $fields, $options);
	}
	
	private function insert_data()
	{
		$this->messages = LangLoader::get('install', 'quotes');
		
		$this->querier->insert(self::$quotes_table, array(
			'id' => 1,
			'contents' => $this->messages['quotes.1.contents'],
			'author' => $this->messages['quotes.1.author'],
			'user_id' => 1,
			'in_mini' => 1,
			'timestamp' => time(),
			'approved' => 1
		));
		
		$this->querier->insert(self::$quotes_table, array(
			'id' => 2,
			'contents' => $this->messages['quotes.2.contents'],
			'author' => $this->messages['quotes.2.author'],
			'user_id' => 1,
			'in_mini' => 1,
			'timestamp' => time(),
			'approved' => 1
		));
		
		$this->querier->insert(self::$quotes_table, array(
			'id' => 3,
			'contents' => $this->messages['quotes.3.contents'],
			'author' => $this->messages['quotes.3.author'],
			'user_id' => 1,
			'in_mini' => 1,
			'timestamp' => time(),
			'approved' => 1
		));
		
		$this->querier->insert(self::$quotes_table, array(
			'id' => 4,
			'contents' => $this->messages['quotes.4.contents'],
			'author' => $this->messages['quotes.4.author'],
			'user_id' => 1,
			'in_mini' => 1,
			'timestamp' => time(),
			'approved' => 1
		));
		
		$this->querier->insert(self::$quotes_table, array(
			'id' => 5,
			'contents' => $this->messages['quotes.5.contents'],
			'author' => $this->messages['quotes.5.author'],
			'user_id' => 1,
			'in_mini' => 1,
			'timestamp' => time(),
			'approved' => 1
		));
	}
}
?>
