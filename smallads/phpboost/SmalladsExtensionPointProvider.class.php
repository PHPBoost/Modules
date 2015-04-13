<?php
/*##################################################
 *                              SmalladsExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : January 29, 2013
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

class SmalladsExtensionPointProvider extends ExtensionPointProvider
{
	private $sql_querier;

	public function __construct()
	{
		$this->sql_querier = PersistenceContext::get_sql();
		parent::__construct('smallads');
	}
	
	/**
	*  @method  Mise à jour du cache
	*/
	function get_cache()
	{
		$smallads_code = 'global $CONFIG_SMALLADS;' . "\n";

		//Récupération du tableau linéarisé dans la bdd.
		$CONFIG_SMALLADS = unserialize($this->sql_querier->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'smallads'", __LINE__, __FILE__));
		$CONFIG_SMALLADS = is_array($CONFIG_SMALLADS) ? $CONFIG_SMALLADS : array();

		$smallads_code .= '$CONFIG_SMALLADS = ' . var_export($CONFIG_SMALLADS, true) . ";\n";

		$rand_limit = !empty($CONFIG_SMALLADS['list_size']) ? $CONFIG_SMALLADS['list_size'] : 1;

		$smallads_code .= "\n\n" . 'global $_smallads_mini;' . "\n";

		$result = $this->sql_querier->query_while(
			"SELECT q.*, m.login AS mlogin
			FROM ".PREFIX."smallads q
			LEFT JOIN ".PREFIX."member m ON m.user_id = q.id_created
			WHERE (q.approved = 1)
			ORDER BY q.date_approved DESC "
			. $this->sql_querier->limit(0, $rand_limit),
			__LINE__, __FILE__);

		$items = array();
		while ($row = $this->sql_querier->fetch_assoc($result))
		{
			$items[] = $row;
		}
		
		$smallads_code .= '$_smallads_mini = ' . var_export($items, true) .";\n";
		
		$smallads_code .= "\n\n" . 'global $_smallads_mini_info;' . "\n";
		
		$last = !empty($items[0]['date_approved']) ? $items[0]['date_approved'] : time();
		$count = $this->sql_querier->query("SELECT COUNT(1) FROM ".PREFIX."smallads WHERE (approved = 1) ", __LINE__, __FILE__);
		
		$smallads_code .= '$_smallads_mini_info = ' . var_export(array('count'=>$count, 'date_last'=>$last), true) .";\n";

		return $smallads_code;
	}
	
	public function home_page()
	{
		return new SmalladsHomePageExtensionPoint();
	}
	
	public function menus()
	{
		return new ModuleMenus(array(new SmalladsModuleMiniMenu()));
	}

	public function scheduled_jobs()
	{
		return new SmalladsScheduledJobs();
	}

	public function search()
	{
		return new SmalladsSearchable();
	}
	
	public function tree_links()
	{
		return new SmalladsTreeLinks();
	}
}
?>