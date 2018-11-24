<?php
/*##################################################
 *                              DictionaryModuleMiniMenu.class.php
 *                            -------------------
 *   begin                : November 15, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
		global $LANG;
		load_module_lang('dictionary');
		
		return $LANG['random.def'];
	}
	
	public function is_displayed()
	{
		return DictionaryAuthorizationsService::check_authorizations()->read();
	}
	
	public function get_menu_content()
	{
		$tpl = new FileTemplate('dictionary/dictionary_mini.tpl');
		
		$dictionary_cache = DictionaryCache::load();
		$random_def = $dictionary_cache->get_dictionary_word(array_rand($dictionary_cache->get_dictionary_words()));
		$description = stripslashes($random_def['description']);
		$word = Url::encode_rewrite(TextHelper::strtolower($random_def['word']));
		
		$tpl->put_all(array(
			'RANDOM_NAME' => stripslashes($random_def['word']),
			'RANDOM_DEF' => (TextHelper::strlen($description) > 149) ? TextHelper::substr($description, 0, 150) . "..." : $description,
			'URL'=> PATH_TO_ROOT . "/dictionary/".url('dictionary.php?l=' . $word . '&amp;cat=' . $random_def['cat'], 'dictionary-' . $word . '-' . $random_def['cat'] . '.php'),
		));
		
		return $tpl->render();
	}
}
?>