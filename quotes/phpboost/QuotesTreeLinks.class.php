<?php
/*##################################################
 *		                         QuotesTreeLinks.class.php
 *                            -------------------
 *   begin                : December 26, 2013
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

/**
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
 */
class QuotesTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		global $QUOTES_LANG, $QUOTES_CAT, $Cache;
		load_module_lang('quotes'); //Chargement de la langue du module.
		$Cache->load('quotes');
		require_once(PATH_TO_ROOT . '/quotes/quotes.inc.php');
		
		$tree = new ModuleTreeLinks();
		
		$manage_categories_link = new AdminModuleLink($QUOTES_LANG['admin.categories.manage'], new Url('/quotes/admin_quotes_cat.php'));
		$manage_categories_link->add_sub_link(new AdminModuleLink($QUOTES_LANG['admin.categories.manage'], new Url('/quotes/admin_quotes_cat.php')));
		$manage_categories_link->add_sub_link(new AdminModuleLink($QUOTES_LANG['q_add_category'], new Url('/quotes/admin_quotes_cat.php?new=1')));
		$tree->add_link($manage_categories_link);
		
		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin'), new Url('/quotes/admin_quotes.php')));
		
		$tree->add_link(new ModuleLink($QUOTES_LANG['q_create'], new Url('/quotes/quotes.php#add'), AppContext::get_current_user()->check_auth($QUOTES_CAT[0]['auth'], QUOTES_WRITE_ACCESS) || AppContext::get_current_user()->check_auth($QUOTES_CAT[0]['auth'], QUOTES_CONTRIB_ACCESS)));
		
		return $tree;
	}
}
?>