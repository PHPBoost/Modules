<?php
/*##################################################
 *                              DictionaryHomePageExtensionPoint.class.php
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

class DictionaryHomePageExtensionPoint implements HomePageExtensionPoint
{
	private $sql_querier;

	public function __construct()
	{
		$this->sql_querier = PersistenceContext::get_sql();
	}
	
	public function get_home_page()
	{
		return new DefaultHomePage($this->get_title(), $this->get_view());
	}
	
	private function get_title()
	{
		global $LANG;
		
		load_module_lang('dictionary');
		
		return $LANG['dictionary'];
	}
	
	private function get_view()
	{
		global $Cache, $CONFIG_DICTIONARY, $LANG;
		
		$current_user = AppContext::get_current_user();
		
		require_once(PATH_TO_ROOT . '/dictionary/dictionary_begin.php'); 
		
		//checking authorization
		if (!$current_user->check_auth($CONFIG_DICTIONARY['auth'], DICTIONARY_LIST_ACCESS))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		
		$Cache->load('dictionary');
		$Template = new FileTemplate('dictionary/dictionary.tpl');
		
		$letter = retrieve(GET, 'l', 'tous', TSTRING);
		
		$nbr_words = $this->sql_querier->query("SELECT COUNT(1) AS total FROM ". PREFIX . "dictionary WHERE word LIKE '" . $letter . "%'", __LINE__, __FILE__);
		$nbr_words = intval($nbr_words);
		
		$page = AppContext::get_request()->get_getint('p', 1);
		$pagination = new ModulePagination($page, $nbr_words, $CONFIG_DICTIONARY['pagination']);
		$pagination->set_url(new Url('/dictionary/dictionary.php?l=' . $letter . '&amp;p=%d'));
		
		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		$aff = false;
		$quotes_approved = 1;
		if ($letter == "tous")
		{
			$result1 = $this->sql_querier->query_while("SELECT l.id, l.description, l.word,l.cat,c.images,l.approved
			FROM ".PREFIX."dictionary AS l
			LEFT JOIN ".PREFIX."dictionary_cat AS c ON l.cat = c.id
			WHERE `approved`  = '" . $quotes_approved . "'
			ORDER BY l.word". $this->sql_querier->limit($pagination->get_display_from(), $CONFIG_DICTIONARY['pagination']), __LINE__, __FILE__);
			$nb_word = $this->sql_querier->query("SELECT COUNT(1) AS total FROM ".PREFIX . "dictionary WHERE (approved = 1)", __LINE__, __FILE__);

		}
		elseif ($letter != "tous" && strlen($letter) > 1)
		{
			$result1 = $this->sql_querier->query_while("SELECT l.id, l.description, l.word,l.cat,c.images
			FROM ".PREFIX."dictionary AS l
			LEFT JOIN ".PREFIX."dictionary_cat AS c ON l.cat = c.id
			WHERE l.word LIKE '%" .$letter. "%' AND `approved`  = '" . $quotes_approved . "'
			ORDER BY l.word". $this->sql_querier->limit($pagination->get_display_from(), $CONFIG_DICTIONARY['pagination']), __LINE__, __FILE__);
			$aff=true;
		}
		else
		{
			$result1 = $this->sql_querier->query_while("SELECT l.id, l.description, l.word,l.cat,c.images
			FROM ".PREFIX."dictionary AS l
			LEFT JOIN ".PREFIX."dictionary_cat AS c ON l.cat = c.id
			WHERE l.word LIKE '" .$letter. "%' AND `approved`  = '" . $quotes_approved . "'
			ORDER BY l.word". $this->sql_querier->limit($pagination->get_display_from(), $CONFIG_DICTIONARY['pagination']), __LINE__, __FILE__);
			
		}

		$edit = false;
		$del = false;
		if (access_ok(DICTIONARY_UPDATE_ACCESS, $CONFIG_DICTIONARY['auth']))
		{
			$edit = true;
		}
		if (access_ok(DICTIONARY_DELETE_ACCESS, $CONFIG_DICTIONARY['auth']))
		{
			$del = true;
		}

		while( $row = $this->sql_querier->fetch_assoc($result1))
		{ 
			$img = empty($row['images']) ? '<i class="fa fa-folder"></i>' : '<img src="' . $row['images'] . '" alt="' . $row['images'] . '" />';
			$Template->assign_block_vars('dictionary', array(
				'NAME' => ucfirst(strtolower(str_replace("'", "", stripslashes($row['word'])))),
				'PROPER_NAME' => ucfirst(strtolower(stripslashes($row['word']))),
				'DESC' => ucfirst(FormatingHelper::second_parse(stripslashes($row['description']))),
				'CAT' => strtoupper($row['cat']),
				'CAT_IMG' => $img,
				'EDIT_CODE' => $edit,
				'ID_EDIT' => $row['id'],
				'THEME' => get_utheme(),
				'LANG' => get_ulang(),
				'LANG_EDIT' => $LANG['edit'],
				'ID_DEL' => $row['id'],
				'DEL_CODE' => $del,
				'C_AFF' => $aff
				));
		}	
		$letters = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
		foreach ($letters as $key => $value) 
		{
			$Template->assign_block_vars('letter', array(
				'LETTER' => strtoupper($value),
				));
		}
		$result_cat = $this->sql_querier->query_while("SELECT id, name
		FROM ".PREFIX."dictionary_cat
		ORDER BY id", __LINE__, __FILE__);
		
		while( $row_cat = $this->sql_querier->fetch_assoc($result_cat) )
		{ 
			$Template->assign_block_vars('cat', array(
					'ID' => $row_cat['id'],
					'NAME' => strtoupper($row_cat['name']),
					));	
		}
		$result_cat = $this->sql_querier->query_while("SELECT id, name
		FROM ".PREFIX."dictionary_cat
		ORDER BY id", __LINE__, __FILE__);
		while( $row_cat = $this->sql_querier->fetch_assoc($result_cat) )
		{ 
			$Template->assign_block_vars('cat_list', array(
					'ID' => $row_cat['id'],
					'NAME' => strtoupper($row_cat['name']),
					));
		}
		$Template->put_all(array(
				'C_EDIT' => false,
				'TITLE' => $LANG['dictionary'],
				'L_NO_SCRIPT' => $LANG['no_script'],
				'C_AJOUT' => access_ok(DICTIONARY_CREATE_ACCESS|DICTIONARY_CONTRIB_ACCESS, $CONFIG_DICTIONARY['auth']),
				'L_DELETE_DICTIONARY' => $LANG['delete_dictionary'],
				'L_ADD_DICTIONARY'    => $LANG['create_dictionary'],
				'L_ALL' => $LANG['all'],
				'L_ALL_CAT' => $LANG['all_cat'],
				'L_CATEGORY' => $LANG['category'],
				'L_NB_DEF' => $LANG['nb_def'],
				'L_DEF_REP' => $LANG['def_set'],
				'L_CAT_S' => $LANG['cat_s'],
				'REWRITE'=> (int)ServerEnvironmentConfig::load()->is_url_rewriting_enabled(),
				'C_PAGINATION' => $pagination->has_several_pages(),
				'PAGINATION' => $pagination->display()
				));

		return $Template;
	}
}
?>
