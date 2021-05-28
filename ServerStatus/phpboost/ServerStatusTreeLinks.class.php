<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 15
 * @since       PHPBoost 4.0 - 2013 08 04
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ServerStatusTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get('common', 'ServerStatus');
		$tree = new ModuleTreeLinks();

		$tree->add_link(new AdminModuleLink($lang['server.management'], ServerStatusUrlBuilder::servers_management()));
		$tree->add_link(new AdminModuleLink($lang['server.add.item'], ServerStatusUrlBuilder::add_server()));

		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin'), ServerStatusUrlBuilder::configuration()));

		return $tree;
	}
}
?>
