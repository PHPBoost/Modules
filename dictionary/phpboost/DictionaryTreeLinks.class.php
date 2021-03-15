<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 15
 * @since       PHPBoost 4.0 - 2014 01 31
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DictionaryTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		global $LANG;
		load_module_lang('dictionary'); //Chargement de la langue du module.

		$tree = new ModuleTreeLinks();

		$tree->add_link(new AdminModuleLink($LANG['admin.categories.manage'], new Url('/dictionary/admin_dictionary_cats.php')));
		$tree->add_link(new AdminModuleLink($LANG['dictionary.cats.add'], new Url('/dictionary/admin_dictionary_cats.php?add=1')));

		$tree->add_link(new AdminModuleLink($LANG['admin.words.manage'], new Url('/dictionary/admin_dictionary_list.php')));
		$tree->add_link(new AdminModuleLink($LANG['create.dictionary'], new Url('/dictionary/dictionary.php?add=1')));

		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin-common'), DictionaryUrlBuilder::configuration()));

		if (!AppContext::get_current_user()->check_level(User::ADMIN_LEVEL))
		{
			$tree->add_link(new ModuleLink($LANG['create.dictionary'], new Url('/dictionary/dictionary.php?add=1'), DictionaryAuthorizationsService::check_authorizations()->write() || DictionaryAuthorizationsService::check_authorizations()->contribution()));
		}

		return $tree;
	}
}
?>
