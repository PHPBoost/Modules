<?php
/*##################################################
 *		                         HomeLandingTreeLinks.class.php
 *                            -------------------
 *   begin                : January 2, 2016
 *   copyright            : (C) 2016 Sebastien Lartigue
 *   email                : babso@web33.fr
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

class HomeLandingTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$tree = new ModuleTreeLinks();
		$config = HomeLandingConfig::load();
		$sticky_title = $config->get_sticky_title();

		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin-common'), HomeLandingUrlBuilder::configuration()));
		$tree->add_link(new AdminModuleLink(LangLoader::get_message('admin.elements_position', 'common', 'HomeLanding'), HomeLandingUrlBuilder::positions()));

		if(AppContext::get_current_user()->check_level(User::MODERATOR_LEVEL)){
			$sticky_link = new ModuleLink($sticky_title, HomeLandingUrlBuilder::sticky());
			$sticky_link->add_sub_link(new AdminModuleLink(LangLoader::get_message('homelanding.sticky.manage', 'sticky', 'HomeLanding').': '.$sticky_title, HomeLandingUrlBuilder::sticky_manage()));
			$sticky_link->add_sub_link(new ModuleLink($sticky_title, HomeLandingUrlBuilder::sticky()));
			$tree->add_link($sticky_link);
		}

		if(AppContext::get_current_user()->check_level(User::ADMIN_LEVEL))
		$tree->add_link(new AdminModuleLink(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module('HomeLanding')->get_configuration()->get_documentation()));

		return $tree;
	}
}
?>
