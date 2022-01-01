<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 05 29
 * @since       PHPBoost 4.1 - 2014 09 24
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class TeamspeakTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$tree = new ModuleTreeLinks();

		$tree->add_link(new AdminModuleLink(LangLoader::get_message('form.configuration', 'form-lang'), TeamspeakUrlBuilder::configuration()));

		return $tree;
	}
}
?>
