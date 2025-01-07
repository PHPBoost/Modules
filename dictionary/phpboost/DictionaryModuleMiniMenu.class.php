<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 16
 * @since       PHPBoost 3.0 - 2012 11 15
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DictionaryModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__LEFT;
	}

	public function get_menu_id()
	{
		return 'module-mini-dictionary';
	}

	public function get_menu_title()
	{
		return LangLoader::get_message('dictionary.mini.module.title', 'common', 'dictionary');
	}

	public function get_formated_title()
	{
		return LangLoader::get_message('dictionary.module.title', 'common', 'dictionary');
	}

	public function is_displayed()
	{
		return DictionaryAuthorizationsService::check_authorizations()->read();
	}

	public function get_menu_content()
	{
		$view = new FileTemplate('dictionary/dictionary_mini.tpl');

		$dictionary_cache = DictionaryCache::load();
		$random_def = $dictionary_cache->get_dictionary_word(array_rand($dictionary_cache->get_dictionary_words()));
		$description = stripslashes($random_def['description']);
		$word = Url::encode_rewrite(TextHelper::strtolower($random_def['word']));

		$view->put_all(array(
			'RANDOM_NAME' => stripslashes($random_def['word']),
			'RANDOM_DEF' => (TextHelper::strlen($description) > 149) ? TextHelper::substr($description, 0, 150) . "..." : $description,
			'U_ITEM'=> Url::to_rel('/dictionary/' . url('dictionary.php?l=' . $word . '&amp;cat=' . $random_def['cat'] . '#' . $word, 'dictionary-' . $word . '-' . $random_def['cat'] . '.php' . '#' . $word))
		));

		return $view->render();
	}
}
?>
