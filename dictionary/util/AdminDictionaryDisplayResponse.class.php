<?php
/**
 * @copyright   &copy; 2005-2024 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 13
 * @since       PHPBoost 4.1 - 2016 02 15
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminDictionaryDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $page_title)
	{
		parent::__construct($view);
		$lang = LangLoader::get_all_langs('dictionary');

		$this->add_link($lang['category.categories.management'], new Url('/dictionary/admin_dictionary_cats.php'));
		$this->add_link($lang['category.add'], new Url('/dictionary/admin_dictionary_cats.php?add=1'));
		$this->add_link($lang['dictionary.items.management'], new Url('/dictionary/admin_dictionary_list.php'));
		$this->add_link($lang['dictionary.add.item'], new Url('/dictionary/dictionary.php?add=1'));
		$this->add_link(LangLoader::get_message('form.configuration', 'form-lang'), $this->module->get_configuration()->get_admin_main_page());

		$this->get_graphical_environment()->set_page_title($page_title);
	}
}
?>
