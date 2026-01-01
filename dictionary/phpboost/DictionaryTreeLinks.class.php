<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 13
 * @since       PHPBoost 4.0 - 2014 01 31
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DictionaryTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get_all_langs('dictionary');

		$tree = new ModuleTreeLinks();

		$tree->add_link(new AdminModuleLink($lang['category.categories.management'], new Url('/dictionary/admin_dictionary_cats.php')));
		$tree->add_link(new AdminModuleLink($lang['category.add'], new Url('/dictionary/admin_dictionary_cats.php?add=1')));

		$tree->add_link(new AdminModuleLink($lang['dictionary.items.management'], new Url('/dictionary/admin_dictionary_list.php')));
		$tree->add_link(new AdminModuleLink($lang['dictionary.add.item'], new Url('/dictionary/dictionary.php?add=1')));

		$tree->add_link(new AdminModuleLink(LangLoader::get_message('form.configuration', 'form-lang'), DictionaryUrlBuilder::configuration()));

		if (!AppContext::get_current_user()->check_level(User::ADMINISTRATOR_LEVEL))
		{
			$tree->add_link(new ModuleLink($lang['dictionary.add.item'], new Url('/dictionary/dictionary.php?add=1'), DictionaryAuthorizationsService::check_authorizations()->write() || DictionaryAuthorizationsService::check_authorizations()->contribution()));
		}

		return $tree;
	}
}
?>
