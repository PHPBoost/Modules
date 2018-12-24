<?php
/*##################################################
 *                               QuotesSetup.class.php
 *                            -------------------
 *   begin                : February 18, 2016
 *   copyright            : (C) 2016 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class QuotesSetup extends DefaultModuleSetup
{
	public static $quotes_table;
	public static $quotes_cats_table;
	
	public static function __static()
	{
		self::$quotes_table = PREFIX . 'quotes';
		self::$quotes_cats_table = PREFIX . 'quotes_cats';
	}
	
	public function install()
	{
		$this->drop_tables();
		$this->create_tables();
		$this->insert_data();
	}
	
	public function upgrade($installed_version)
	{
		$file = new File(Url::to_rel('/quotes/controllers/AdminQuotesManageController.class.php'));
		$file->delete();
		
		return '5.2.0';
	}
	
	public function uninstall()
	{
		$this->drop_tables();
		ConfigManager::delete('quotes', 'config');
		CacheManager::invalidate('module', 'quotes');
	}
	
	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$quotes_table, self::$quotes_cats_table));
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
			'id_category' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'author' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'rewrited_author' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'quote' => array('type' => 'text', 'length' => 65000),
			'creation_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'author_user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'approved' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'id_category' => array('type' => 'key', 'fields' => 'id_category'),
				'title' => array('type' => 'fulltext', 'fields' => 'author'),
				'contents' => array('type' => 'fulltext', 'fields' => 'quote')
			)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$quotes_table, $fields, $options);
	}
	
	private function create_quotes_cats_table()
	{
		RichCategory::create_categories_table(self::$quotes_cats_table);
	}
	
	private function insert_data()
	{
		$this->messages = LangLoader::get('install', 'quotes');
		
		PersistenceContext::get_querier()->insert(self::$quotes_table, array(
			'id' => 1,
			'id_category' => 0,
			'quote' => $this->messages['quotes.1.quote'],
			'author' => $this->messages['quotes.1.author'],
			'rewrited_author' => Url::encode_rewrite($this->messages['quotes.1.author']),
			'creation_date' => time(),
			'author_user_id' => 1,
			'approved' => 1
		));
		
		PersistenceContext::get_querier()->insert(self::$quotes_table, array(
			'id' => 2,
			'id_category' => 0,
			'quote' => $this->messages['quotes.2.quote'],
			'author' => $this->messages['quotes.2.author'],
			'rewrited_author' => Url::encode_rewrite($this->messages['quotes.2.author']),
			'creation_date' => time(),
			'author_user_id' => 1,
			'approved' => 1
		));
		
		PersistenceContext::get_querier()->insert(self::$quotes_table, array(
			'id' => 3,
			'id_category' => 0,
			'quote' => $this->messages['quotes.3.quote'],
			'author' => $this->messages['quotes.3.author'],
			'rewrited_author' => Url::encode_rewrite($this->messages['quotes.3.author']),
			'creation_date' => time(),
			'author_user_id' => 1,
			'approved' => 1
		));
		
		PersistenceContext::get_querier()->insert(self::$quotes_table, array(
			'id' => 4,
			'id_category' => 0,
			'quote' => $this->messages['quotes.4.quote'],
			'author' => $this->messages['quotes.4.author'],
			'rewrited_author' => Url::encode_rewrite($this->messages['quotes.4.author']),
			'creation_date' => time(),
			'author_user_id' => 1,
			'approved' => 1
		));
		
		PersistenceContext::get_querier()->insert(self::$quotes_table, array(
			'id' => 5,
			'id_category' => 0,
			'quote' => $this->messages['quotes.5.quote'],
			'author' => $this->messages['quotes.5.author'],
			'rewrited_author' => Url::encode_rewrite($this->messages['quotes.5.author']),
			'creation_date' => time(),
			'author_user_id' => 1,
			'approved' => 1
		));
	}
}
?>
