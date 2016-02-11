<?php
/*##################################################
 *                         SmalladsHomeController.class.php
 *                            -------------------
 *   begin                : February 2, 2016
 *   copyright            : (C) 2016 Julien BRISWALTER
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

class SmalladsHomeController extends ModuleController
{
	private $view;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->build_view();
		
		return $this->generate_response();
	}
	
	private function build_view()
	{
		global $LANG, $type_options, $mode_options, $sort_options;
	
		require_once(PATH_TO_ROOT . '/smallads/smallads_begin.php');
		require_once(PATH_TO_ROOT.'/smallads/smallads.class.php');
		
		$config = SmalladsConfig::load();
		
		$smallads = new Smallads();
		
		if (SmalladsAuthorizationsService::check_authorizations()->read())
		{
			$user = AppContext::get_current_user()->get_id();

			$this->view = new FileTemplate('smallads/smallads.tpl');

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
			
			$nbr_unapproved_smallads_user = PersistenceContext::get_querier()->count(SmalladsSetup::$smallads_table, 'WHERE (approved = 0) AND (id_created = '.$user.')');

			$view_not_approved = retrieve(GET, 'ViewNotApproved', 0, TINTEGER);
			$filter = array('(approved = 1)');
			if ($view_not_approved) {
				if (SmalladsAuthorizationsService::check_authorizations()->moderation())
				{
					$filter = array('(approved = 0)');
				}
				else if (SmalladsAuthorizationsService::check_authorizations()->contribution() && $nbr_unapproved_smallads_user)
				{
					$filter = array('(approved = 0) AND (id_created = '.$user.')');
				}
				else
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			}

			if (!empty($type))
			{
				$filter[] = '(type = '.intval($type).')';
			}
			
			$nbr_smallads = PersistenceContext::get_querier()->count(SmalladsSetup::$smallads_table, "WHERE " . implode(' AND ', $filter));

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

			$page = AppContext::get_request()->get_getint('p', 1);
			$pagination = new ModulePagination($page, $nbr_smallads, $config->get_items_number_per_page());
			$pagination->set_url(new Url('/smallads/smallads.php?p=%d' . $qs));
			
			if ($pagination->current_page_is_empty() && $page > 1)
			{
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
			
			$this->view->put_all(array(
				'C_LIST'         => true,
				'C_DESCRIPTION'	 => FALSE,
				'C_DISPLAY_NOT_APPROVED'	 => SmalladsAuthorizationsService::check_authorizations()->moderation() || (SmalladsAuthorizationsService::check_authorizations()->contribution() && $nbr_unapproved_smallads_user),
				'C_PAGINATION'	 => $pagination->has_several_pages(),
				'PAGINATION'	 => $pagination->display(),
				'DESCRIPTION'	 => 'Champ description',
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

			$result = PersistenceContext::get_querier()->select("SELECT q.*, m.*
				FROM " . SmalladsSetup::$smallads_table . " q
				LEFT JOIN ".PREFIX."member m ON m.user_id = q.id_created
				WHERE " . implode(' AND ', $filter)."
				ORDER BY ".$order."
				LIMIT :number_items_per_page OFFSET :display_from", array(
					'number_items_per_page' => $pagination->get_number_items_per_page(),
					'display_from' => $pagination->get_display_from()
				));

			while ($row = $result->fetch())
			{
				$id_created = (int)$row['id_created'];
				$c_edit 	= FALSE;
				$url_edit	= '';
				$c_delete	= FALSE;
				$url_delete	= '';

				$v = $smallads->check_access(SmalladsAuthorizationsService::MODERATION_AUTHORIZATIONS, (SmalladsAuthorizationsService::OWN_CRUD_AUTHORIZATIONS|SmalladsAuthorizationsService::CONTRIBUTION_AUTHORIZATIONS), $id_created);
				if ($v)
				{
					$url_edit 	= url('.php?edit=' . $row['id']);
					$c_edit 	= TRUE;
				}

				$v = $smallads->check_access(SmalladsAuthorizationsService::MODERATION_AUTHORIZATIONS, (SmalladsAuthorizationsService::OWN_CRUD_AUTHORIZATIONS|SmalladsAuthorizationsService::CONTRIBUTION_AUTHORIZATIONS), $id_created);
				if ($v)
				{
					$url_delete	= url('.php?delete=' . $row['id']);
					$c_delete	= TRUE;
				}

				$is_user	= ((!empty($row['id_created']))
							&& ($row['id_created'] > 0));

				$is_pm 		= ((!empty($row['id_created']))
							&& (intval($row['id_created']) != $user)
							&& ($config->is_pm_displayed())
							&& (in_array($row['links_flag'], array(2, 3))));

				$is_mail 	= ((!empty($row['email']))
							&& (!empty($row['id_created']))
							&& (intval($row['id_created']) != $user)
							&& ($config->is_mail_displayed())
							&& (in_array($row['links_flag'], array(1, 3))));

				if ($is_mail)
				{
					$mailto = $row['email'];
					$mailto .= "?subject=Petite annonce #".$row['id']." : ".$row['title'];
					$mailto .= "&body=Bonjour,";
				}
				
				$date_created = !empty($row['date_created']) ? new Date($row['date_created'], Timezone::SERVER_TIMEZONE) : null;
				$date_updated = !empty($row['date_updated']) ? new Date($row['date_updated'], Timezone::SERVER_TIMEZONE) : null;
				
				$author = new User();
				if (!empty($row['user_id']))
					$author->set_properties($row);
				else
					$author->init_visitor_user();
				
				$author_group_color = User::get_group_color($author->get_groups(), $author->get_level(), true);
				
				$this->view->assign_block_vars('item',array(
					'ID' 		=> $row['id'],
					'VID'		=> empty($row['vid']) ? '' : $row['vid'],
					'TYPE'	 	=> $type_options[intval($row['type'])],
					'TITLE' 	=> $row['title'],
					'CONTENTS' 	=> FormatingHelper::second_parse(stripslashes($row['contents'])),
					'PRICE'		=> $row['price'],
					'SHIPPING'		=> $row['shipping'],
					'C_SHIPPING' 	=> $row['shipping'] != '0.00',

					'DB_CREATED' => (!empty($date_created)) ? $LANG['sa_created'] . $date_created->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE_TEXT) : '',
					'DB_UPDATED' => (!empty($date_updated)) ? $LANG['sa_updated'] . $date_updated->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE_TEXT) : '',
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

					'USER'		=> $is_user && $author->get_id() !== User::VISITOR_LEVEL ? '<a itemprop="author" href="'.UserUrlBuilder::profile($author->get_id())->absolute().'" class="'.UserService::get_level_class($author->get_level()).'" ' . ($author_group_color ? 'style="color:' . $author_group_color . '"' : '') . '>'.$author->get_display_name().'</a>' : '',
					'USER_PM' 	=> $is_pm ? '&nbsp;: <a href="'.PATH_TO_ROOT.'/user/pm' . url('.php?pm=' . $row['id_created'], '-' . $row['id_created'] . '.php') . '" class="basic-button smaller">' . $LANG['pm'] . '</a>' : '',
					'USER_MAIL' => $is_mail ? '&nbsp;<a href="mailto:' . $mailto . '" class="basic-button smaller" title="' . $row['email']  . '">' . $LANG['mail'] . '</a>' : '',
				));
			}
			$result->dispose();
			
			foreach ($type_options as $k => $v)
			{
				$checked  = ($k == $type) ? 'checked' : '';
				$this->view->assign_block_vars('type_options',array(
					'NAME' 		=> $v,
					'CHECKED'	=> $checked,
					'VALUE' 	=> $k));

				if ($k == 0) continue; // don't display 'All' option if edit form
				$this->view->assign_block_vars('type_options_edit',array(
					'NAME' 		=> $v,
					'SELECTED'	=> $smallads->selected($k, intval($row['type'])),
					'VALUE' 	=> $k));
			}

			foreach ($sort_options as $k => $v)
			{
				$this->view->assign_block_vars('sort_options',array(
					'NAME' 		=> $v,
					'SELECTED'	=> $smallads->selected($k, $sort),
					'VALUE' 	=> $k));
			}

			foreach ($mode_options as $k => $v)
			{
				$this->view->assign_block_vars('mode_options',array(
					'NAME' 		=> $v,
					'SELECTED'	=> $smallads->selected($k, $mode),
					'VALUE' 	=> $k));
			}

			return $this->view;
		}
		else
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function generate_response()
	{
		global $LANG;
		load_module_lang('smallads');
		
		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($LANG['sa_title']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(SmalladsUrlBuilder::home());
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($LANG['sa_title'], SmalladsUrlBuilder::home());
		
		return $response;
	}
	
	public static function get_view()
	{
		$object = new self();
		$object->build_view();
		return $object->view;
	}
}
?>
