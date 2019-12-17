<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 24
 * @since       PHPBoost 4.1 - 2016 02 15
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminDictionaryDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);

		global $LANG;
		load_module_lang('dictionary'); //Chargement de la langue du module.

		$lang = LangLoader::get('common', 'dictionary');
		$this->set_title($lang['module_title']);

		$this->add_link($LANG['admin.categories.manage'], new Url('/dictionary/admin_dictionary_cats.php'));
		$this->add_link($LANG['dictionary.cats.add'], new Url('/dictionary/admin_dictionary_cats.php?add=1'));
		$this->add_link($LANG['admin.words.manage'], new Url('/dictionary/admin_dictionary_list.php'));
		$this->add_link($LANG['create.dictionary'], new Url('/dictionary/dictionary.php?add=1'));
		$this->add_link(LangLoader::get_message('configuration', 'admin-common'), DictionaryUrlBuilder::configuration());

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page, $lang['module_title']);
	}
}
?>
