<?php
/*##################################################
 *                              QuotesExtensionPointProvider.class.php
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

if (defined('PHPBOOST') !== true) exit;

class QuotesExtensionPointProvider extends ExtensionPointProvider
{
	private $sql_querier;

	public function __construct()
	{
		$this->sql_querier = PersistenceContext::get_sql();
		parent::__construct('quotes');
	}
	
	/**
	*  @method  Mise à jour du cache
	*/
	function get_cache()
	{
		global $LANG;
		
		$config_quotes = unserialize($this->sql_querier->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'quotes'", __LINE__, __FILE__));
		
		$string = 'global $CONFIG_QUOTES, $QUOTES_CAT;' . "\n\n" . '$CONFIG_QUOTES = $QUOTES_CAT = array();' . "\n\n";
		$string .= '$CONFIG_QUOTES = ' . var_export($config_quotes, true) . ';' . "\n\n";

		//List of categories and their own properties
		$result = $this->sql_querier->query_while("SELECT id, id_parent, c_order, auth, name, description, visible, image
			FROM " . PREFIX . "quotes_cats
			ORDER BY id_parent, c_order", __LINE__, __FILE__);
		
		//Root cat
		$string .= '$QUOTES_CAT[0] = ' . var_export(array('name' => $LANG['root'], 'order' => 0, 'visible' => true, 'auth' => $config_quotes['auth']), true) . ';' . "\n\n";
		
		while ($row = $this->sql_querier->fetch_assoc($result))
		{
			$string .= '$QUOTES_CAT[' . $row['id'] . '] = ' .
				var_export(array(
					'id_parent' => (int)$row['id_parent'],
					'order' => (int)$row['c_order'],
					'name' => $row['name'],
					'description' => $row['description'],
					'visible' => (bool)$row['visible'],
					'image' => $row['image'],
					'auth' => unserialize($row['auth'])
			), true) . ';' . "\n\n";
		}
		
		$rand_limit = !empty($config_quotes['quotes_list_size']) ? $config_quotes['quotes_list_size'] : 1;

		$string .= 'global $_quotes_rand_msg;' . "\n\n";
		$string .= '$_quotes_rand_msg = array();' . "\n\n";
		
		$result = $this->sql_querier->query_while("SELECT q.*, m.login AS mlogin
			FROM " . PREFIX . "quotes q
			LEFT JOIN ".PREFIX."member m ON m.user_id = q.user_id
			WHERE (q.in_mini = 1) AND (q.approved = 1)
			ORDER BY rand()" . $this->sql_querier->limit(0, $rand_limit), __LINE__, __FILE__);
		
		while ($row = $this->sql_querier->fetch_assoc($result))
		{
			$string .= '$_quotes_rand_msg[] = array(\'id\' => ' . var_export($row['id'], true) . ', \'author\' => ' . var_export($row['author'], true) . ', \'contents\' => ' . var_export($row['contents'], true) . ', \'user_id\' => ' . var_export($row['user_id'], true) . ');' . "\n";
		}
		
		return $string;
	}
	
	public function home_page()
	{
		return new QuotesHomePageExtensionPoint();
	}
	
	public function menus()
	{
		return new ModuleMenus(array(new QuotesModuleMiniMenu()));
	}
	
	public function scheduled_jobs()
	{
		return new QuotesScheduledJobs();
	}
	
	public function search()
	{
		return new QuotesSearchable();
	}
	
	public function tree_links()
	{
		return new QuotesTreeLinks();
	}
}
?>