<?php
/*##################################################
 *                              DictionaryModuleMiniMenu.class.php
 *                            -------------------
 *   begin                : November 15, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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
	
	public function display($tpl = false)
	{
		global $Cache, $Sql,$LANG;
	
		load_module_lang('dictionary');
		$Cache->load('dictionary'); 
		$tpl = new FileTemplate('dictionary/dictionary_mini.tpl');
		MenuService::assign_positions_conditions($tpl, $this->get_block());
		
		$query = $Sql->query_while ("SELECT id, word,cat,description FROM " . PREFIX . "dictionary LIMIT 0, 20", __LINE__, __FILE__);
		$def = array();
		while ($row = $Sql->fetch_assoc($query))
		{
			$def[] = array('id' => $row['id'], 'word' => $row['word'], 'description' => $row['description'], 'idcat' => $row['cat']);
		}

		$random_def=$def[array_rand($def)];
		$no_random_question = array(
			'RANDOM_NAME' => stripslashes($random_def['word']),
			'RANDOM_DEF' => (strlen($random_def['description']) > 149) ? substr($random_def['description'],0,150)."..." : $random_def['description'],
			'L_RANDOM_DEF' => $LANG['random_def'],
			'URL'=> PATH_TO_ROOT . "/dictionary/".url('dictionary.php?l=' . strtolower($random_def['word']) . '&amp;cat=' . $random_def['idcat'], 'dictionary-' . strtolower($random_def['word']) . '-' . $random_def['idcat'] . '.php'),

		);

		$tpl->put_all($no_random_question);
		
		return $tpl->render();
	}
}
?>