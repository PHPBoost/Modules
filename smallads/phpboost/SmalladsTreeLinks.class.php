<?php
/*##################################################
 *		                         SmalladsTreeLinks.class.php
 *                            -------------------
 *   begin                : February 3, 2014
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
class SmalladsTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		global $LANG, $CONFIG_SMALLADS, $Cache;
		load_module_lang('smallads'); //Chargement de la langue du module.
		$Cache->load('smallads');
		require_once(PATH_TO_ROOT . '/smallads/smallads.inc.php');
		
		$tree = new ModuleTreeLinks();
		
		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin'), new Url('/smallads/admin_smallads.php')));
		
		$tree->add_link(new ModuleLink($LANG['sa_create'], new Url('/smallads/smallads.php?add=1'), AppContext::get_current_user()->check_auth($CONFIG_SMALLADS['auth'], SMALLADS_OWN_CRUD_ACCESS) || AppContext::get_current_user()->check_auth($CONFIG_SMALLADS['auth'], SMALLADS_CONTRIB_ACCESS)));
		
		return $tree;
	}
}
?>