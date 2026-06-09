<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 4.0 - 2013 11 13
 * @author      xela <xela@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SearchTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$tree = new ModuleTreeLinks();

		$tree->add_link(new AdminModuleLink(LangLoader::get_message('form.configuration', 'form-lang'), new Url('/search/admin_search.php')));
		$tree->add_link(new AdminModuleLink(LangLoader::get_message('search.config.weighting', 'common', 'search'), new Url('/search/admin_search.php?weighting=true')));

		if (ModulesManager::get_module('search')->get_configuration()->get_documentation())
			$tree->add_link(new AdminModuleLink(LangLoader::get_message('form.documentation', 'form-lang'), ModulesManager::get_module('search')->get_configuration()->get_documentation()));

		return $tree;
	}
}
?>
