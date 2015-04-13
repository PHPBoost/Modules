<?php
/*##################################################
 *                     SmalladsHomePageExtensionPoint.class.php
 *                            -------------------
 *   begin                : January 29, 2013
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

class SmalladsHomePageExtensionPoint implements HomePageExtensionPoint
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
		
		load_module_lang('smallads');
		
		return $LANG['sa_title'];
	}
	
	private function get_view()
	{
		global $LANG, $type_options, $mode_options, $sort_options, $checkboxes, $vendor_id;
	
		require_once(PATH_TO_ROOT . '/smallads/smallads_begin.php');
		require_once(PATH_TO_ROOT.'/smallads/smallads.class.php');
		
		$smallads = new Smallads();
		$smallads->check_autorisation(SMALLADS_LIST_ACCESS);

		$user = AppContext::get_current_user()->get_id();

		$tpl = new FileTemplate('smallads/smallads.tpl');

		$sort = retrieve(GET, 'sort', '', TSTRING_UNCHANGE);
		if (empty($sort) || !array_key_exists($sort, $sort_options))
		{
			$sort = 'date_created';
		}

		$mode = retrieve(GET, 'mode', '', TSTRING_UNCHANGE);
		if (empty($mode) || !array_key_exists($mode, $mode_options))
		{
			$mode = 'desc';
		}

		$order = $sort . ' ' . $mode;

		$type = retrieve(GET, 'type', 0, TINTEGER);

		$view_not_approved = retrieve(GET, 'ViewNotApproved', 0, TINTEGER);
		$filter = array('(approved = 1)');
		if ($view_not_approved) {
			if ($smallads->access_ok(SMALLADS_DELETE_ACCESS))
			{
				$filter = array('(approved = 0)');
			}
			elseif($smallads->access_ok(SMALLADS_CONTRIB_ACCESS))
			{
				$filter = array('(approved = 0) AND (id_created = '.$user.')');
			}
		}

		if (!empty($type))
		{
			$filter[] = '(type = '.intval($type).')';
		}
		
		$items_per_page = $smallads->config_get('items_per_page', SMALLADS_ITEMS_PER_PAGE);
		
		$nbr_smallads = $this->sql_querier->query("SELECT COUNT(1) AS total
			FROM ".PREFIX . "smallads
			WHERE " . ($vendor_id ? "id_created = " . $vendor_id . " AND " : "") . implode(' AND ', $filter),
			__LINE__, __FILE__);
		$nbr_smallads = intval($nbr_smallads);

		$qs = '';
		if (!empty($_SERVER['QUERY_STRING'])) {
			$t = explode('&', $_SERVER['QUERY_STRING']);
			foreach ($t as $k => $v) {
				if (strstr($v,'p=')) unset($t[$k]);
			}
			if (count($t)>0) {
				$qs = implode('&', $t);
				$qs = '&'.$qs;
			}
		}

		$max_links      = $smallads->config_get('max_links', SMALLADS_MAX_LINKS);

		$page = AppContext::get_request()->get_getint('p', 1);
		$pagination = new ModulePagination($page, $nbr_smallads, $items_per_page);
		$pagination->set_url(new Url('/smallads/smallads.php?p=%d' . $qs));
		
		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		$tpl->put_all(array(
			'C_LIST'         => $smallads->access_ok(SMALLADS_LIST_ACCESS),
			'C_DESCRIPTION'	 => FALSE,
			'C_PAGINATION'	 => $pagination->has_several_pages(),
			'PAGINATION'	 => $pagination->display(),
			'DESCRIPTION'	 => 'Champ description',
			'THEME'			 => get_utheme(),
			'LANG'			 => get_ulang(),
			'C_NB_SMALLADS'	 => $nbr_smallads > 0,
			'L_TITLE'		 => $LANG['sa_title'],
			'L_NO_SMALLADS'	 => $LANG['sa_no_smallads'],
			'L_LIST_NOT_APPROVED' => $LANG['sa_list_not_approved'],
			'L_PRICE'		 => $LANG['sa_db_price'],
			'L_PRICE_UNIT'	 => $LANG['sa_price_unit'],
			'L_SHIPPING'		 => $LANG['sa_db_shipping'],
			'L_SHIPPING_UNIT'	 => $LANG['sa_shipping_unit'],
			'TARGET_ON_CHANGE_ORDER' => PATH_TO_ROOT . '/smallads/smallads.php?',
		));

		$result = $this->sql_querier->query_while("SELECT q.*, m.*
			FROM ".PREFIX."smallads q
			LEFT JOIN ".PREFIX."member m ON m.user_id = q.id_created
			WHERE " . ($vendor_id ? "id_created = " . $vendor_id . " AND " : "") . implode(' AND ', $filter)."
			ORDER BY ".$order." "
			. $this->sql_querier->limit($pagination->get_display_from(), $items_per_page),
			__LINE__, __FILE__);

		while ($row = $this->sql_querier->fetch_assoc($result))
		{
			$id_created = (int)$row['id_created'];
			$c_edit 	= FALSE;
			$url_edit	= '';
			$c_delete	= FALSE;
			$url_delete	= '';

			$v = $smallads->check_access(SMALLADS_UPDATE_ACCESS, (SMALLADS_OWN_CRUD_ACCESS|SMALLADS_CONTRIB_ACCESS), $id_created);
			if ($v)
			{
				$url_edit 	= url('.php?edit=' . $row['id']);
				$c_edit 	= TRUE;
			}

			$v = $smallads->check_access(SMALLADS_DELETE_ACCESS, (SMALLADS_OWN_CRUD_ACCESS|SMALLADS_CONTRIB_ACCESS), $id_created);
			if ($v)
			{
				$url_delete	= url('.php?delete=' . $row['id']);
				$c_delete	= TRUE;
			}

			$is_user	= ((!empty($row['id_created']))
						&& ($row['id_created'] > 0));

			$is_pm 		= ((!empty($row['id_created']))
						&& (intval($row['id_created']) != $user)
						&& ($smallads->config_get('view_pm',0))
                        && ($row['links_flag'] & $checkboxes['view_pm']['mask']));

			$is_mail 	= ((!empty($row['user_mail']))
						&& (!empty($row['user_show_mail']))
						&& (!empty($row['id_created']))
						&& (intval($row['id_created']) != $user)
						&& ($smallads->config_get('view_mail',0))
                        && ($row['links_flag'] & $checkboxes['view_mail']['mask']));

			if ($is_mail)
			{
				$mailto = $row['user_mail'];
				$mailto .= "?subject=Petite annonce #".$row['id']." : ".$row['title'];
				$mailto .= "&body=Bonjour,";
			}

			$tpl->assign_block_vars('item',array(
				'ID' 		=> $row['id'],
				'VID'		=> empty($row['vid']) ? '' : $row['vid'],
				'TYPE'	 	=> $type_options[intval($row['type'])],
				'TITLE' 	=> $row['title'],
				'CONTENTS' 	=> FormatingHelper::second_parse($row['contents']),
				'PRICE'		=> $row['price'],
				'SHIPPING'		=> $row['shipping'],
				'C_SHIPPING' 	=> $row['shipping'] != '0.00' ? true : false,

				'DB_CREATED' => (!empty($row['date_created'])) ? $LANG['sa_created'].gmdate_format('date_format', $row['date_created']) : '',
				'DB_UPDATED' => (!empty($row['date_updated'])) ? $LANG['sa_updated'].gmdate_format('date_format', $row['date_updated']) : '',
				'C_DB_APPROVED'	 => !empty($row['approved']),
				'L_NOT_APPROVED' => $LANG['sa_not_approved'],

				'C_EDIT' 	=> $c_edit,
				'URL_EDIT'	=> $url_edit,
				'C_DELETE'	=> $c_delete,
				'URL_DELETE' => $url_delete,
				'L_CONFIRM_DELETE' => $LANG['sa_confirm_delete'],
				'URL_VIEW'	=> url('.php?id=' . $row['id']),

				'C_PICTURE'	 => !empty($row['picture']),
				'PICTURE'	 => PATH_TO_ROOT.'/smallads/pics/'.$row['picture'],

				'USER'		=> $is_user ? '<a href="'.UserUrlBuilder::profile($row['id_created'])->absolute().'">'.$row['login'].'</a>' : '',
				'USER_PM' 	=> $is_pm ? '&nbsp;: <a href="'.PATH_TO_ROOT.'/user/pm' . url('.php?pm=' . $row['id_created'], '-' . $row['id_created'] . '.php') . '" class="basic-button smaller">' . $LANG['pm'] . '</a>' : '',
				'USER_MAIL' => $is_mail ? '&nbsp;<a href="mailto:' . $mailto . '" class="basic-button smaller" title="' . $row['user_mail']  . '">' . $LANG['mail'] . '</a>' : '',
			));
		}
		
		foreach ($type_options as $k => $v)
		{
			$checked  = ($k == $type) ? 'checked' : '';
			$tpl->assign_block_vars('type_options',array(
				'NAME' 		=> $v,
				'CHECKED'	=> $checked,
				'VALUE' 	=> $k));

			if ($k == 0) continue; // don't display 'All' option if edit form
			$tpl->assign_block_vars('type_options_edit',array(
				'NAME' 		=> $v,
				'SELECTED'	=> $smallads->selected($k, intval($row['type'])),
				'VALUE' 	=> $k));
		}

		foreach ($sort_options as $k => $v)
		{
			$tpl->assign_block_vars('sort_options',array(
				'NAME' 		=> $v,
				'SELECTED'	=> $smallads->selected($k, $sort),
				'VALUE' 	=> $k));
		}

		foreach ($mode_options as $k => $v)
		{
			$tpl->assign_block_vars('mode_options',array(
				'NAME' 		=> $v,
				'SELECTED'	=> $smallads->selected($k, $mode),
				'VALUE' 	=> $k));
		}

		return $tpl;
	}
}
?>