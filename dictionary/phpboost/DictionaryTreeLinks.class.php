<?php
/*##################################################
 *		                         DictionaryTreeLinks.class.php
 *                            -------------------
 *   begin                : January 31, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
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

/**
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
 */
class DictionaryTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		global $LANG, $CONFIG_DICTIONARY, $Cache;
		load_module_lang('dictionary'); //Chargement de la langue du module.
		$Cache->load('dictionary');
		require_once(PATH_TO_ROOT . '/dictionary/dictionary.inc.php');
		
		$tree = new ModuleTreeLinks();
		
		$manage_categories_link = new AdminModuleLink($LANG['admin.categories.manage'], new Url('/dictionary/admin_dictionary_cats.php'));
		$manage_categories_link->add_sub_link(new AdminModuleLink($LANG['admin.categories.manage'], new Url('/dictionary/admin_dictionary_cats.php')));
		$manage_categories_link->add_sub_link(new AdminModuleLink($LANG['dictionary_cats_add'], new Url('/dictionary/admin_dictionary_cats.php?add=1')));
		$tree->add_link($manage_categories_link);
		
		$manage_dictionary_link = new AdminModuleLink($LANG['admin.words.manage'], new Url('/dictionary/admin_dictionary_list.php'));
		$manage_dictionary_link->add_sub_link(new AdminModuleLink($LANG['admin.words.manage'], new Url('/dictionary/admin_dictionary_list.php')));
		$manage_dictionary_link->add_sub_link(new AdminModuleLink($LANG['create_dictionary'], new Url('/dictionary/dictionary.php?add=1')));
		$tree->add_link($manage_dictionary_link);
		
		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin'), new Url('/dictionary/admin_dictionary.php')));
		
		if (!AppContext::get_current_user()->check_level(User::ADMIN_LEVEL))
		{
			$tree->add_link(new ModuleLink($LANG['create_dictionary'], new Url('/dictionary/dictionary.php?add=1'), AppContext::get_current_user()->check_auth($CONFIG_DICTIONARY['auth'], DICTIONARY_CREATE_ACCESS) || AppContext::get_current_user()->check_auth($CONFIG_DICTIONARY['auth'], DICTIONARY_CONTRIB_ACCESS)));
		}
		
		return $tree;
	}
}
?>