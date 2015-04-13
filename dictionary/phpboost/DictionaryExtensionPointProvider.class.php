<?php
/*##################################################
 *                              DictionaryExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : November 15, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
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

class DictionaryExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct() //Constructeur de la classe
	{
		parent::__construct('dictionary');
	}
	
	/**
	* @method Recuperation du cache
	*/
	function get_cache()
	{
		$code = 'global $CONFIG_DICTIONARY;' . "\n";
		
		//Récupération du tableau linéarisé dans la bdd.
		$CONFIG_DICTIONARY = unserialize(PersistenceContext::get_querier()->get_column_value(DB_TABLE_CONFIGS, 'value', "WHERE name='dictionary'"));
		$CONFIG_DICTIONARY = is_array($CONFIG_DICTIONARY) ? $CONFIG_DICTIONARY : array();
			
		$code .= '$CONFIG_DICTIONARY = ' . var_export($CONFIG_DICTIONARY, true) . ';' . "\n";
		
		return $code;
	}
	
	 /**
	 * @method Get css files
	 */
	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('dictionary.css');
		return $module_css_files;
	}
	
	public function home_page()
	{
		return new DictionaryHomePageExtensionPoint();
	}
	
	public function menus()
	{
		return new ModuleMenus(array(new DictionaryModuleMiniMenu()));
	}
	
	public function search()
	{
		return new DictionarySearchable();
	}
	
	public function tree_links()
	{
		return new DictionaryTreeLinks();
	}
}
?>