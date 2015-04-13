<?php
/*##################################################
 *                     QuotesHomePageExtensionPoint.class.php
 *                            -------------------
 *   begin                : February 4, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
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

class QuotesHomePageExtensionPoint implements HomePageExtensionPoint
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
		global $QUOTES_LANG;
		
		load_module_lang('quotes');
		
		return $QUOTES_LANG['q_title'];
	}
	
	private function get_view()
	{
		global $CONFIG_QUOTES, $QUOTES_CAT, $Cache, $QUOTES_LANG, $LANG, $category_id, $id;
		
		require_once(PATH_TO_ROOT.'/quotes/quotes.inc.php');
		$quotes = new Quotes();
		
		$quotes->cats->access_ok($category_id, QUOTES_LIST_ACCESS, TRUE);
		
		$Template = new FileTemplate('quotes/quotes.tpl');

		if ($category_id > 0)
		{
			$clause_cat = " WHERE qc.id_parent = '" . $category_id . "' AND qc.visible = 1";
		}
		else //Racine.
		{
			$clause_cat = " WHERE qc.id_parent = '0' AND qc.visible = 1";
		}
		
		//Catégories non autorisées.
		$unauth_cats_sql = array();
		foreach ($QUOTES_CAT as $id => $key)
		{
			if (!$quotes->cats->access_ok($id, QUOTES_LIST_ACCESS, TRUE))
			$unauth_cats_sql[] = $id;
		}
		$nbr_unauth_cats = count($unauth_cats_sql);
		$clause_unauth_cats = ($nbr_unauth_cats > 0) ? " AND qc.id NOT IN (" . implode(', ', $unauth_cats_sql) . ")" : '';

		##### Catégories disponibles #####
		$result = $this->sql_querier->query_while("SELECT @id_cat:= qc.id, qc.id, qc.name, qc.auth, qc.description, qc.image, 
		(		SELECT  COUNT(*)
				FROM " . PREFIX . "quotes q
				WHERE idcat = @id_cat AND q.approved = 1
        )		AS      nbr_quotes
		FROM " . PREFIX . "quotes_cats qc
		" . $clause_cat . $clause_unauth_cats . "
		ORDER BY qc.id_parent, qc.c_order
		LIMIT 30", __LINE__, __FILE__);

		$total_cat = 0;
		while ($row = $this->sql_querier->fetch_assoc($result))
		{
			$Template->assign_block_vars('list_cats', array(
				'ID' => $row['id'],
				'NAME' => $row['name'],
				'WIDTH' => intval(100 / $CONFIG_QUOTES['cat_cols']),
				'DESC' => FormatingHelper::strparse($row['description']),
				'SRC' => ($row['image'] == 'quotes.png' || $row['image'] == 'quotes_mini.png') ? PATH_TO_ROOT . '/quotes/' . $row['image'] : $row['image'],
				'IMG_NAME' => $row['name'],
				'U_CAT' => PATH_TO_ROOT . '/quotes/' . url('quotes.php?cat=' . $row['id'], 'category-' . $row['id'] . '+' . Url::encode_rewrite($row['name']) . '.php'),
				'U_ADMIN_CAT' 	=> PATH_TO_ROOT . '/quotes/' . url('admin_quotes_cat.php?edit=' . $row['id']),
				'C_CAT_IMG' => !empty($row['image']),
				'L_NBR_QUOTES' => sprintf($QUOTES_LANG['nbr_quotes_info'], $row['nbr_quotes']),
			));
			
			if (!empty($row['id']))
			{
				$total_cat++;
			}
		}
		
		if ($total_cat > 0)
		{
			$Template->put_all(array(
				'C_SUB_CATS' => true
			));
		}
		
		$this->sql_querier->query_close($result);
		
		$where = '(approved = 1) AND (idcat='.intval($category_id).')';
		if( intval($category_id) > 0 && !empty($QUOTES_CAT[$category_id]['description'])) 
		{
			$Template->put_all(array(
				'C_DESCRIPTION' => true,
				'DESCRIPTION' 	=> FormatingHelper::second_parse(stripslashes($QUOTES_CAT[$category_id]['description']))
			));
		}

		$nbr_quotes = $this->sql_querier->query("SELECT COUNT(1) AS total FROM ".PREFIX . "quotes WHERE ".$where, __LINE__, __FILE__);
		$nbr_quotes = intval($nbr_quotes);

		$page = AppContext::get_request()->get_getint('p', 1);
		$pagination = new ModulePagination($page, $nbr_quotes, $CONFIG_QUOTES['items_per_page']);
		$pagination->set_url(new Url('/quotes/quotes.php?cat=' . $category_id . '&amp;p=%d'));
		
		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		$Template->put_all(array(
			'C_EDIT'         => $quotes->cats->access_ok($category_id, QUOTES_CONTRIB_ACCESS|QUOTES_WRITE_ACCESS),
			'C_LIST'         => $quotes->cats->access_ok($category_id, QUOTES_LIST_ACCESS),
			'C_PAGINATION'	 => $pagination->has_several_pages(),
			'PAGINATION'	 => $pagination->display(),
			'IN_MINI'        => 'checked="checked"',
			'L_ALERT_TEXT'   => $quotes->lang_get('require_text'),
			'L_DELETE_QUOTE' => $quotes->lang_get('q_delete'),
			'L_ADD_QUOTE'    => $quotes->lang_get('q_create'),
			'L_CONTENTS'     => $quotes->lang_get('q_contents'),
			'L_AUTHOR'       => $quotes->lang_get('q_author'),
			'L_IN_MINI'      => $quotes->lang_get('q_in_mini'),
			'L_REQUIRE'      => $quotes->lang_get('require'),
			'L_PSEUDO'       => $quotes->lang_get('pseudo'),
			'L_SUBMIT'       => $quotes->lang_get('submit'),
			'L_RESET'        => $quotes->lang_get('reset'),
			'L_ON'           => $quotes->lang_get('on'),
			'L_CAT_NAME' 		=> $category_id > 0 ? $QUOTES_CAT[$category_id]['name'] : $QUOTES_LANG['q_title'],
			'L_CATEGORY'		=> $quotes->lang_get('q_category'),
			'CATEGORIES_TREE'	=> $quotes->cats->build_select_form($category_id, 'idcat', 'idcat', 0,
										QUOTES_WRITE_ACCESS|QUOTES_CONTRIB_ACCESS, $CONFIG_QUOTES['auth'],
										IGNORE_AND_CONTINUE_BROWSING_IF_A_CATEGORY_DOES_NOT_MATCH)
			));
		
		$c_contrib = ! $quotes->cats->access_ok($category_id, QUOTES_WRITE_ACCESS)
							&& $quotes->cats->access_ok($category_id, QUOTES_CONTRIB_ACCESS);
		
		$editor = AppContext::get_content_formatting_service()->get_default_editor();
		$editor->set_identifier('counterpart');
		
		$Template->put_all(array(
			'C_CONTRIBUTION' 					=> $c_contrib,
			'L_CONTRIBUTION'					=> $quotes->lang_get('contribution_legend'),
			'L_CONTRIBUTION_NOTICE' 			=> $quotes->lang_get('contribution_notice'),
			'L_CONTRIBUTION_COUNTERPART' 		=> $quotes->lang_get('contribution_counterpart'),
			'L_CONTRIBUTION_COUNTERPART_EXPLAIN' => $quotes->lang_get('contribution_counterpart_explain'),
			'CONTRIBUTION_COUNTERPART_EDITOR' 	=> $editor->display(),
			'C_APPROVED'						=> TRUE
			));

		if ($nbr_quotes > 0)
		{
			$result = $this->sql_querier->query_while("SELECT q.*
				FROM ".PREFIX."quotes q
				WHERE ".$where."
				ORDER BY q.timestamp DESC"
				. $this->sql_querier->limit($pagination->get_display_from(), $CONFIG_QUOTES['items_per_page']),
				__LINE__, __FILE__);
				
			while ($row = $this->sql_querier->fetch_assoc($result))
			{
				$user_id = (int)$row['user_id'];
				$c_edit 	= FALSE;
				$l_edit		= '';
				$url_edit	= '';
				$c_delete	= FALSE;
				$l_delete	= '';
				$url_delete	= '';
				
				if ( $quotes->cats->access_ok($category_id, QUOTES_WRITE_ACCESS) )
				{
					$url_edit 	= url('.php?edit=' . $row['id']);
					$c_edit 	= TRUE;
					$l_edit 	= $LANG['edit'];
					$url_delete	= url('.php?delete=' . $row['id']);
					$c_delete	= TRUE;
					$l_delete 	= $LANG['delete'];
				}
				
				$Template->assign_block_vars('quotes',array(
					'ID' 		=> $row['id'],
					'CONTENTS' 	=> FormatingHelper::unparse($row['contents']),
					'AUTHOR' 	=> FormatingHelper::unparse($row['author']),
					'DATE' 		=> $LANG['on'] . ': ' . gmdate_format('date_format', $row['timestamp']),
					'IN_MINI' 	=> $row['in_mini'] == 1 ? $LANG['yes'] : $LANG['no'],
					'C_EDIT' 	=> $c_edit,
					'L_EDIT'	=> $l_edit,
					'URL_EDIT'	=> $url_edit,
					'C_DELETE'	=> $c_delete,
					'L_DELETE'	=> $l_delete,
					'URL_DELETE'	=> $url_delete,
					'THEME'		=> get_utheme(),
					'LANG'		=> get_ulang(),
				));
			}
			$this->sql_querier->query_close($result);
		}
		else
		{
			$Template->put_all(array(
				'L_NO_ITEMS' => $QUOTES_LANG['q_no_items'],
				'C_NO_ITEMS' => $category_id > 0 ? true : false
			));
		}
		
		return $Template;
	}
}
?>