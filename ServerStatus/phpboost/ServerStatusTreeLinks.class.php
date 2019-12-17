<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 12 24
 * @since       PHPBoost 4.0 - 2013 08 04
 * @contributor mipel <mipel@phpboost.com>
*/

class ServerStatusTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get('common', 'ServerStatus');
		$tree = new ModuleTreeLinks();

		$manage_servers_link = new AdminModuleLink($lang['admin.config.servers.manage'], ServerStatusUrlBuilder::servers_management());
		$manage_servers_link->add_sub_link(new AdminModuleLink($lang['admin.config.servers.manage'], ServerStatusUrlBuilder::servers_management()));
		$manage_servers_link->add_sub_link(new AdminModuleLink($lang['admin.config.servers.action.add_server'], ServerStatusUrlBuilder::add_server()));
		$tree->add_link($manage_servers_link);

		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin'), ServerStatusUrlBuilder::configuration()));

		return $tree;
	}
}
?>
