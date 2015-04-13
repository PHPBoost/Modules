<?php
/**
 * smallads.php
 * $Id: smallads.php 18 2012-11-11 19:36:17Z alain091@gmail.com $
 *
 * @author         alain91
 * @copyright      (C) 2008-2012 Alain Gandon
 * @email          alain091@gmail.com
 * @license        GPL version 2
 */

defined('PATH_TO_ROOT') OR define('PATH_TO_ROOT','..');

require_once(PATH_TO_ROOT.'/kernel/begin.php');
require_once(PATH_TO_ROOT.'/smallads/smallads_begin.php');
require_once(PATH_TO_ROOT.'/smallads/smallads.class.php');
require_once(PATH_TO_ROOT.'/kernel/header.php');

//Chargement du cache
$Cache->load('smallads');

$smallads = new Smallads();
$smallads->on_changeday();

$id_delete		= retrieve(GET, 'delete', 0, TINTEGER);
$id_delete_pict	= retrieve(GET, 'delete_picture', 0, TINTEGER);
$id_edit 		= retrieve(GET, 'edit', 0, TINTEGER);
$id_add 		= retrieve(GET, 'add', 0, TINTEGER);
$id_view 		= retrieve(GET, 'id', 0, TINTEGER);
$vendor_id 		= retrieve(GET, 'vendor_id', 0, TINTEGER);

function render_view($smallads, $row, $tpl)
{
	global $LANG, $type_options, $checkboxes;

	$user = AppContext::get_current_user()->get_id();
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
		'TITLE' 	=> htmlentities($row['title']),
		'CONTENTS' 	=> FormatingHelper::second_parse($row['contents']),
		'PRICE'		=> $row['price'],
		'SHIPPING'	=> $row['shipping'],
		'C_SHIPPING' 	=> $row['shipping'] != '0.00',
		
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

		'USER'		=> $is_user ? '<a href="'.UserUrlBuilder::profile($row['id_created'])->absolute() .'">'.$row['login'].'</a>' : '',
		'USER_PM' 	=> $is_pm ? '&nbsp;: <a href="'.PATH_TO_ROOT.'/user/pm' . url('.php?pm=' . $row['id_created'], '-' . $row['id_created'] . '.php') . '" class="basic-button smaller">' . $LANG['pm'] . '</a>' : '',
		'USER_MAIL' => $is_mail ? '&nbsp;<a href="mailto:' . $mailto . '" class="basic-button smaller" title="' . $row['user_mail']  . '">' . $LANG['mail'] . '</a>' : '',
	));
}

if( retrieve(POST, 'submit', false) ) //Enregistrement du formulaire
{
	$id_post        = intval(retrieve(POST, 'id', 0, TINTEGER));

	if (empty($id_post)) // Creation
	{
		$smallads->check_autorisation(SMALLADS_OWN_CRUD_ACCESS|SMALLADS_CONTRIB_ACCESS);
	}
	else
	{
		$smallads->check_autorisation(SMALLADS_OWN_CRUD_ACCESS|SMALLADS_UPDATE_ACCESS|SMALLADS_CONTRIB_ACCESS);
	}

	$sa_title 		= retrieve(POST, 'smallads_title', '', TSTRING_UNCHANGE);
	$sa_contents 	= retrieve(POST, 'smallads_contents', '', TSTRING_UNCHANGE);
	$sa_contents	= FormatingHelper::strparse($sa_contents, $forbidden_tags, FALSE); // TSTRING_PARSE ne permet pas les parametres
	$sa_price 		= retrieve(POST, 'smallads_price', 0.0, TFLOAT);
	$sa_shipping	= retrieve(POST, 'smallads_shipping', 0.0, TFLOAT);
	$sa_type   		= retrieve(POST, 'smallads_type', 0, TINTEGER);
	$sa_max_weeks	= retrieve(POST, 'smallads_max_weeks', 0, TINTEGER);
	$sa_max_weeks	= empty($sa_max_weeks) ? 'NULL' : abs($sa_max_weeks);

	$flag = 0;
    $config_flag = 0;
	foreach($checkboxes as $k => $v)
	{
		$link = retrieve(POST, $k, $v['default'], $v['type']);
        $config_link = $smallads->config_get($k, 0) * $v['mask'];
		$flag |= $link; // logical or between each checkbox value
        $config_flag |= $config_link; // logical or between each checkbox value
	}
    $flag &= $config_flag; // verifie par rapport aux parametres admin

	if ( empty($sa_contents) || empty($sa_title) )
	{
		$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), LangLoader::get_message('e_incomplete', 'errors'));
		DispatchManager::redirect($controller);
	}

	//Mod anti-flood
	$check_time = 0;
	$user_id = $User->get_id();
	if ($user_id > 0 && ContentManagementConfig::load()->is_anti_flood_enabled())
	{
		$check_time = $Sql->query("SELECT MAX(date_created) as date_created
									FROM ".PREFIX."smallads
									WHERE id_created = '" . $user_id . "'",
									__LINE__, __FILE__);
	}
	if (!empty($check_time))
	{
		if( $check_time >= (time() - ContentManagementConfig::load()->get_anti_flood_duration()) ) //On calcul la fin du delai.
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), LangLoader::get_message('e_flood', 'errors'));
			DispatchManager::redirect($controller);
		}
	}

	if (empty($id_post)) // Creation
	{
		if ($user_id  <= 0)
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}

		$db_approved = $smallads->access_ok(SMALLADS_OWN_CRUD_ACCESS) ? 1 : 0;
		$date = time();

		$sql = "INSERT INTO ".PREFIX."smallads
				SET
					title     = '" . addslashes($sa_title) . "',
					contents  = '" . addslashes($sa_contents) . "',
					type      = '" . $sa_type . "',
					price     = " . $sa_price . ",
					shipping     = " . $sa_shipping . ",
					id_created    = " . $user_id . ",
					date_created  = " . $date . ",
					links_flag    = " . $flag . ",
					max_weeks = " . $sa_max_weeks;

		if ($db_approved) {
			$sql .= ",
					approved      = " . $db_approved.",
					date_approved = " . $date;
		} else {
			$sql .= ",
					approved      = 0,
					date_approved = 0";
		}

		$Sql->query_inject($sql, __LINE__, __FILE__);

		$last_id = intval($Sql->insert_id(""));
		$smallads->upload_picture($last_id);

		// Feeds Regeneration
		Feed::clear_cache('smallads');
		// Cache Regeneration
		$Cache->generate_module_file('smallads');

		if (!$smallads->access_ok(SMALLADS_OWN_CRUD_ACCESS) && $smallads->access_ok(SMALLADS_CONTRIB_ACCESS))
		{
			$contribution_counterpart = retrieve(POST, 'contribution_counterpart', '', TSTRING_UNCHANGE);
			$smallads->contribution_add($last_id, $contribution_counterpart);
			AppContext::get_response()->redirect(UserUrlBuilder::contribution_success());
			exit;
		}

	} else { // Modification

		$smallads_properties = $Sql->query_array(PREFIX . "smallads", 'approved', 'id_created', "WHERE id = '" . $id_post . "'", __LINE__, __FILE__);

		if ( $smallads_properties === FALSE) {
			DispatchManager::redirect(PHPBoostErrors::unexisting_page());
		}

		$update_ok = $smallads->check_access(SMALLADS_UPDATE_ACCESS, (SMALLADS_OWN_CRUD_ACCESS|SMALLADS_CONTRIB_ACCESS), intval($smallads_properties['id_created']));
		if (!$update_ok)
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}

		if ($smallads->access_ok(SMALLADS_UPDATE_ACCESS|SMALLADS_OWN_CRUD_ACCESS))
		{
			if ( !empty($smallads_properties['approved']) )
			{
				$Sql->query_inject(
					"UPDATE ".PREFIX."smallads
						SET
							title     = '" . addslashes($sa_title) . "',
							contents  = '" . addslashes($sa_contents) . "',
							type      = " . $sa_type . ",
							price     = " . $sa_price . ",
							shipping     = " . $sa_shipping . ",
							id_updated   = " . $user_id . ",
							date_updated = " . time() . ",
							links_flag   = " . $flag . ",
							max_weeks = " . $sa_max_weeks . "
						WHERE id ='".$id_post."'
						LIMIT 1",
						__LINE__, __FILE__);

				$smallads->upload_picture($id_post);
			}

			$sa_approved  	= retrieve(POST, 'smallads_approved', '', TNONE);
			$db_approved	= !empty($sa_approved) ? 1 : 0;

			if ($smallads->access_ok(SMALLADS_UPDATE_ACCESS) && $db_approved && empty($smallads_properties['approved']))
			{
				$date = time();

				$doublon = $Sql->query_array(PREFIX . "smallads", 'vid', 'id_created', 'date_created', "WHERE id = " . $id_post, __LINE__, __FILE__);
				if ( !empty($doublon['vid']) )
				{
					$Sql->query_inject(
						"UPDATE ".PREFIX."smallads
							SET
								title     = '" . addslashes($sa_title) . "',
								contents  = '" . addslashes($sa_contents) . "',
								type      = " . $sa_type . ",
								price     = " . $sa_price . ",
								shipping     = " . $sa_shipping . ",
								id_created    = ".$doublon['id_created'].",
								date_created  = ".$doublon['date_created'].",
								id_updated    = " . $user_id . ",
								date_updated  = " . $date . ",
								links_flag    = " . $flag . ",
								approved  	  = " . $db_approved . ",
								date_approved = " . $date . "
							WHERE id =".$doublon['vid']."
							LIMIT 1",
							__LINE__, __FILE__);

					$Sql->query_inject(
						"UPDATE ".PREFIX."smallads
							SET
								title     	= '',
								contents  	= '',
								type      	= 0,
								price     	= 0.0,
								shipping     	= 0.0,
								id_created    	= 0,
								date_created 	= 0,
								id_updated   	= 0,
								date_updated 	= 0,
								links_flag   	= 0,
								approved  		= 2,
								date_approved 	= 0
							WHERE id =". $id_post."
							LIMIT 1",
							__LINE__, __FILE__);
				}
				else
				{
					$Sql->query_inject(
						"UPDATE ".PREFIX."smallads
							SET
								title     = '" . addslashes($sa_title) . "',
								contents  = '" . addslashes($sa_contents) . "',
								type      = " . $sa_type . ",
								price     = " . $sa_price . ",
								shipping     = " . $sa_shipping . ",
								id_updated    = " . $user_id . ",
								date_updated  = " . $date . ",
								links_flag    = " . $flag . ",
								approved  	  = " . $db_approved . ",
								date_approved = " . $date . "
							WHERE id =".$id_post."
							LIMIT 1",
							__LINE__, __FILE__);
				}

				$smallads->contribution_set_processed($id_post);
			}

			// Feeds Regeneration
			Feed::clear_cache('smallads');
			// Cache Regeneration
			$Cache->generate_module_file('smallads');
		}
		elseif($smallads->access_ok(SMALLADS_CONTRIB_ACCESS))
		{
			$in_progress = $smallads->contribution_is_in_progress($id_post);
			if ($in_progress)
			{
				$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), $LANG['sa_contrib_in_progress']);
				DispatchManager::redirect($controller);
			}

			if ( empty($smallads_properties['approved']) )
			{
				$Sql->query_inject(
					"UPDATE ".PREFIX."smallads
						SET
							title     = '" . addslashes($sa_title) . "',
							contents  = '" . addslashes($sa_contents) . "',
							type      = " . $sa_type . ",
							price     = " . $sa_price . ",
							shipping     = " . $sa_shipping . ",
							id_updated   = " . $user_id . ",
							date_updated = " . time() . ",
							links_flag   = " . $flag . "
						WHERE id ='".$id_post."'
						LIMIT 1",
						__LINE__, __FILE__);

				$id_picture = $id_post;
				$id_contrib = $id_post;
			}
			else
			{
				$row = $Sql->query_array(PREFIX . "smallads", 'id',"WHERE vid = " . $id_post, __LINE__, __FILE__);
				$row2 = $Sql->query_array(PREFIX . "smallads", '*',"WHERE id = " . $id_post, __LINE__, __FILE__);
				//Le doublon existe-t-il ?
				if ( $row === FALSE ) {
					// NON on le crée
					$sql = "INSERT INTO ".PREFIX."smallads
							SET
								title     = '" . addslashes($sa_title) . "',
								contents  = '" . addslashes($sa_contents) . "',
								type      = '" . $sa_type . "',
								price     = " . $sa_price . ",
								shipping     = " . $sa_shipping . ",
								id_created    = " . $user_id . ",
								date_created  = " . $row2['date_created'] . ",
								approved  	  = 0,
								date_approved = 0,
								vid           = " . $row2['id'] .",
								links_flag    = " . $flag;

					$Sql->query_inject($sql, __LINE__, __FILE__);

					$id_contrib = intval($Sql->insert_id(''));
					$id_picture = $id_contrib;
				}
				else
				{
					// OUI Mise a jour du doublon
					$Sql->query_inject(
						"UPDATE ".PREFIX."smallads
							SET
								title     = '" . addslashes($sa_title) . "',
								contents  = '" . addslashes($sa_contents) . "',
								type      = " . $sa_type . ",
								price     = " . $sa_price . ",
								shipping     = " . $sa_shipping . ",
								id_created    = " . $user_id . ",
								date_created  = " . $row2['date_created'] . ",
								id_updated    = " . $user_id . ",
								date_updated  = " . time() . ",
								approved      = 0,
								date_approved = 0,
								links_flag    = " . $flag . "
							WHERE id = ".$row['id']."
							LIMIT 1",
							__LINE__, __FILE__);

					$id_contrib = $row['id'];
					$id_picture = $id_contrib;
				}
			}

			$smallads->upload_picture($id_picture);

			// update d'une contribution non traitée
			$contribution_counterpart = retrieve(POST, 'contribution_counterpart', '', TSTRING_UNCHANGE);
			if (!$smallads->contribution_update($id_contrib, 'Modif id # '.$row2['id'].' - '.$contribution_counterpart))
			{
				// il faut creer une nouvelle demande de contribution
				$smallads->contribution_add($id_contrib, 'Modif id # '.$row2['id'].' - '.$contribution_counterpart);
			}

			// Feeds Regeneration
			Feed::clear_cache('smallads');
			// Cache Regeneration
			$Cache->generate_module_file('smallads');
			AppContext::get_response()->redirect(UserUrlBuilder::contribution_success());
			exit;
		}
	}

	if (empty($id_post))
	{
		AppContext::get_response()->redirect(HOST . SCRIPT . SID2);
	}
	else
	{
		$v = $smallads->config_get('return_to_list',0);
		if ( $v == 0 )
		{
			AppContext::get_response()->redirect(HOST . SCRIPT . SID2 . '?edit=' . $id_post . '&s=1');
		}
		else
		{
			AppContext::get_response()->redirect(HOST . SCRIPT . SID2);
		}
	}
	exit;
}
elseif ($id_delete)
{
	AppContext::get_session()->csrf_post_protect(); //Protection csrf

	$smallads->check_autorisation(SMALLADS_DELETE_ACCESS|SMALLADS_OWN_CRUD_ACCESS|SMALLADS_CONTRIB_ACCESS);

	$row = $Sql->query_array(PREFIX . "smallads", 'picture', 'id_created', 'approved', 'vid', "WHERE id = " . $id_delete, __LINE__, __FILE__);
	if ( $row === FALSE) {
		DispatchManager::redirect(PHPBoostErrors::unexisting_page());
	}

	$delete_ok = $smallads->check_access(SMALLADS_DELETE_ACCESS, (SMALLADS_OWN_CRUD_ACCESS|SMALLADS_CONTRIB_ACCESS), intval($row['id_created']));
	if (!$delete_ok)
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}

	$filename = $row['picture'];

	if ( $id_delete > 0 )
	{
		if ($smallads->access_ok(SMALLADS_DELETE_ACCESS|SMALLADS_OWN_CRUD_ACCESS))
		{
			$Sql->query_inject("DELETE FROM ".PREFIX."smallads WHERE (id = " . $id_delete .") LIMIT 1", __LINE__, __FILE__);
			if ( !empty($filename) )
			{
				@unlink (PATH_TO_ROOT.'/smallads/pics/'.$filename);
			}
		}
		elseif($smallads->access_ok(SMALLADS_CONTRIB_ACCESS))
		{
			// PA originale
			if ( empty($row['vid']) )
			{
				$doublon = 	$row = $Sql->query_array(PREFIX . "smallads", 'approved', 'id', 'picture', "WHERE vid = " . $id_delete, __LINE__, __FILE__);

				if ( $doublon === FALSE ) // Pas de doublon
				{
					$in_progress = $smallads->contribution_is_in_progress($id_delete);
					if ($in_progress)
					{
						$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), $LANG['sa_contrib_in_progress']);
						DispatchManager::redirect($controller);
					}
					$smallads->contribution_delete($id_delete);

					$Sql->query_inject("DELETE FROM ".PREFIX."smallads WHERE (id = " . $id_delete .") LIMIT 1", __LINE__, __FILE__);
					if ( !empty($filename) )
					{
						@unlink (PATH_TO_ROOT.'/smallads/pics/'.$filename);
					}
				}
				else
				{
					$id_doublon = $doublon['id'];
					$in_progress = $smallads->contribution_is_in_progress($id_doublon);
					if ($in_progress)
					{
						$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), $LANG['sa_contrib_in_progress']);
						DispatchManager::redirect($controller);
					}
					$smallads->contribution_delete($id_doublon);

					$Sql->query_inject("DELETE FROM ".PREFIX."smallads WHERE (id = " . $id_doublon .") LIMIT 1", __LINE__, __FILE__);
					if ( !empty($filename) )
					{
						@unlink (PATH_TO_ROOT.'/smallads/pics/'.$doublon['picture']);
					}
				}
			}
			else // C'est le doublon
			{
				if ( empty($row['approved']) )
				{
					$in_progress = $smallads->contribution_is_in_progress($id_delete);
					if ($in_progress)
					{
						$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), $LANG['sa_contrib_in_progress']);
						DispatchManager::redirect($controller);
					}

					$Sql->query_inject(
						"UPDATE ".PREFIX."smallads
							SET
								title     	= '',
								contents  	= '',
								type      	= 0,
								price     	= 0.0,
								shipping     	= 0.0,
								id_created    	= 0,
								date_created 	= 0,
								id_updated   	= 0,
								date_updated 	= 0,
								links_flag   	= 0,
								approved  		= 2,
								date_approved 	= 0
							WHERE id =". $id_delete."
							LIMIT 1",
							__LINE__, __FILE__);
					$smallads->contribution_delete($id_delete);
				}
			}
		}

		// Feeds Regeneration
		Feed::clear_cache('smallads');
		// Cache Regeneration
		$Cache->generate_module_file('smallads');
	}
	AppContext::get_response()->redirect(HOST . SCRIPT . SID2);
	exit;
}
elseif ($id_delete_pict)
{
	AppContext::get_session()->csrf_post_protect(); //Protection csrf

	$smallads->check_autorisation(SMALLADS_UPDATE_ACCESS|SMALLADS_OWN_CRUD_ACCESS);

	$row = $Sql->query_array(PREFIX . "smallads", 'picture', 'id_created', "WHERE id = " . $id_delete_pict, __LINE__, __FILE__);
	if ( $row === FALSE) {
		DispatchManager::redirect(PHPBoostErrors::unexisting_page());
	}

	$delete_ok = $smallads->check_access(SMALLADS_UPDATE_ACCESS, (SMALLADS_OWN_CRUD_ACCESS), intval($row['id_created']));
	if (!delete_ok)
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}

	$filename = $row['picture'];

	if ( $id_delete_pict > 0 )
	{
		$Sql->query_inject("UPDATE ".PREFIX."smallads SET picture = NULL WHERE id = " . $id_delete_pict . " LIMIT 1", __LINE__, __FILE__);
		if ( !empty($filename) )
		{
			@unlink (PATH_TO_ROOT.'/smallads/pics/'.$filename);
		}
		$Cache->generate_module_file('smallads'); //Régénération du cache
	}

	AppContext::get_response()->redirect(HOST . SCRIPT . SID2 . '?edit=' . $id_delete_pict . '&s=1');
	exit;
}
elseif ($id_view)
{
	$result = $Sql->query_while("SELECT q.*, m.*
		FROM ".PREFIX."smallads q
		LEFT JOIN ".PREFIX."member m ON m.user_id = q.id_created
		WHERE  id=" . $id_view ."
		LIMIT 1",
		__LINE__, __FILE__);

	$row = $Sql->fetch_assoc($result);
	if ( $row === FALSE) {
		DispatchManager::redirect(PHPBoostErrors::unexisting_page());
	}
	else
	{
		$smallads->check_autorisation(SMALLADS_LIST_ACCESS);
	}

	$tpl = new FileTemplate('smallads/smallads.tpl');

	$v = ($smallads->access_ok(SMALLADS_OWN_CRUD_ACCESS|SMALLADS_CONTRIB_ACCESS));
	if ($v)
	{
		$url_edit 	= url('.php?edit='.$id_view);
		$c_edit	 	= TRUE;
	}
	unset($v);

	$v = ($smallads->access_ok(SMALLADS_DELETE_ACCESS));
	if ($v)
	{
		$url_delete	= url('.php?delete='.$id_view);
		$c_delete 	= TRUE;
	}
	unset($v);

	$tpl->put_all(array(
		'C_VIEW'         => TRUE,
		'C_DESCRIPTION'	 => FALSE,
		'DESCRIPTION'	 => 'Champ description',
		'THEME'			 => get_utheme(),
		'LANG'			 => get_ulang(),
		'C_NB_SMALLADS'	 => TRUE,
		'L_NO_SMALLADS'	 => $LANG['sa_no_smallads'],
		'L_LIST_NOT_APPROVED'	=> $LANG['sa_list_not_approved'],
		'L_PRICE'		 => $LANG['sa_db_price'],
		'L_PRICE_UNIT'	 => $LANG['sa_price_unit'],
		'L_SHIPPING'		 => $LANG['sa_db_shipping'],
		'L_SHIPPING_UNIT'	 => $LANG['sa_shipping_unit'],
		'TARGET_ON_CHANGE_ORDER' => 'smallads.php?',
	));

	render_view($smallads, $row, $tpl);

	$tpl->display();
}
elseif ($id_edit || $id_add)
{
	if ($id_edit != 0)
	{
		$smallads->check_autorisation(SMALLADS_UPDATE_ACCESS|SMALLADS_OWN_CRUD_ACCESS|SMALLADS_CONTRIB_ACCESS);
	}
	elseif($id_add != 0)
	{
		$smallads->check_autorisation(SMALLADS_OWN_CRUD_ACCESS|SMALLADS_CONTRIB_ACCESS);
	}
	else
	{
		DispatchManager::redirect(PHPBoostErrors::unexisting_page());
	}

	$tpl = new FileTemplate('smallads/smallads.tpl');

	//Gestion erreur.
	$get_error = retrieve(GET, 's', 0, TINTEGER);
	if ($get_error == 1)
	{
		$tpl->put('MSG', MessageHelper::display($LANG['sa_edit_success'], MessageHelper::SUCCESS));
	}

	$id = 0;
	$c_contribution = FALSE;
	$c_can_approve 	= FALSE;

	if ($id_edit != 0)
	{
		$row = $Sql->query_array(PREFIX . "smallads", '*', "WHERE id = " . $id_edit, __LINE__, __FILE__);
		if ( $row === FALSE)
		{
			DispatchManager::redirect(PHPBoostErrors::unexisting_page());
		}

		$update_ok = $smallads->check_access(SMALLADS_UPDATE_ACCESS, (SMALLADS_OWN_CRUD_ACCESS|SMALLADS_CONTRIB_ACCESS), intval($row['id_created']));
		if (!$update_ok)
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}

		$legend 		= $LANG['sa_update_legend'];
		$id 			= $id_edit;
		$c_contribution = (!$smallads->access_ok(SMALLADS_OWN_CRUD_ACCESS|SMALLADS_UPDATE_ACCESS) && $smallads->access_ok(SMALLADS_CONTRIB_ACCESS));
		$c_can_approve	= ( $smallads->access_ok(SMALLADS_UPDATE_ACCESS) && empty($row['approved']));
	}
	elseif($id_add != 0)
	{
		$result = $Sql->query_while("SHOW COLUMNS FROM ".PREFIX."smallads", __LINE__, __FILE__);
		$row = array();
		while ($field = $Sql->fetch_assoc($result)) {
			$row[$field['Field']] = '';
		}
		$row['approved'] = 1; // on simule "approuvé" sur add
		$legend 		= $LANG['sa_add_legend'];
		$c_contribution = !$smallads->access_ok(SMALLADS_OWN_CRUD_ACCESS) AND $smallads->access_ok(SMALLADS_CONTRIB_ACCESS);
	}

	$maxlen = $smallads->config_get('maxlen_contents', MAXLEN_CONTENTS);

	$usage_terms = $smallads->config_get('usage_terms',0);
	$cgu_contents = '';
	if (!empty($usage_terms))
		$cgu_contents = $smallads->get_cgu();

	$max_weeks_default = '';
	if (!empty($CONFIG_SMALLADS['max_weeks']))
	{
		$max_weeks_default = sprintf($LANG['sa_max_weeks_default'], $CONFIG_SMALLADS['max_weeks']);
	}
	
	$editor = AppContext::get_content_formatting_service()->get_default_editor();
	$editor->set_identifier('smallads_contents');
	$editor->set_forbidden_tags($forbidden_tags);
	
	$tpl->put_all(array(
		'C_FORM'			=> TRUE,
		'C_CONTRIBUTION'	=> $c_contribution,
		'C_CAN_APPROVE'		=> $c_can_approve,
		'C_PICTURE'			=> empty($row['picture']) ? FALSE : TRUE,

		'THEME'				=> get_utheme(),
		'LANG'				=> get_ulang(),
		'KERNEL_EDITOR'		=> $editor->display(),

		'L_ALERT_TEXT'		=> $LANG['require_text'],
		'L_ALERT_FLOAT'		=> $LANG['sa_require_float'],
		'L_ALERT_UPLOAD'	=> $LANG['sa_require_upload'],
		'L_REQUIRE'			=> $LANG['require'],
		'L_LEGEND'			=> $legend,
		'L_CONFIRM_DELETE_PICTURE' 	=> $LANG['sa_confirm_delete_picture'],
		'C_USAGE_TERMS'		=> $smallads->config_get('usage_terms',0),
		'L_USAGE_LEGEND'	=> $LANG['sa_usage_legend'],
		'L_AGREE_TERMS'		=> $LANG['sa_agree_terms'],
		'CGU_CONTENTS'		=> FormatingHelper::second_parse($cgu_contents),
		'L_CGU_NOT_AGREED'	=> $LANG['sa_e_cgu_not_agreed'],
		'L_MAX_WEEKS_DEFAULT' => $max_weeks_default,

		'MAX_FILESIZE_KO'	=> MAX_FILESIZE_KO,

		'ID'				=> $id,
		'DB_TYPE'	 		=> $type_options[intval($row['type'])],
		'DB_TITLE'			=> $row['title'],
		'DB_CONTENTS'		=> FormatingHelper::unparse($row['contents']),
		'DB_PRICE'			=> !empty($row['price']) ? $row['price'] : '0.00',
		'DB_SHIPPING'		=> !empty($row['shipping']) ? $row['shipping'] : '0.00',
		'DB_APPROVED'		=> $row['approved'] != 0 ? 'checked' : '',
		'DB_CREATED'		=> (!empty($row['date_created'])) ? $LANG['sa_created'].gmdate_format('date_format', $row['date_created']) : '',
		'DB_UPDATED'		=> (!empty($row['date_updated'])) ? $LANG['sa_updated'].gmdate_format('date_format', $row['date_updated']) : '',
		'DB_PICTURE'		=> PATH_TO_ROOT.'/smallads/pics/'.$row['picture'].'?'.uniqid(rand()),
		'DB_MAX_WEEKS'		=> $row['max_weeks'],

		'DB_MAXLEN'			=> $maxlen,
		'DB_CONTENTS_REMAIN'	=>  $maxlen - strlen($row['contents']),

		'L_DB_TYPE'			=> $LANG['sa_db_type'],
		'L_DB_TITLE'		=> $LANG['sa_db_title'],
		'L_DB_CONTENTS'		=> $LANG['sa_db_contents'],
		'L_DB_PRICE'		=> $LANG['sa_db_price'],
		'L_DB_SHIPPING'		=> $LANG['sa_db_shipping'],
		'L_DB_APPROVED'		=> $LANG['sa_db_approved'],
		'L_DB_PICTURE'		=> $LANG['sa_db_picture'],
		'L_DB_MAX_WEEKS'	=> $LANG['sa_max_weeks'],

		'L_PRICE_UNIT'		=> $LANG['sa_price_unit'],
		'L_SHIPPING_UNIT'	=> $LANG['sa_shipping_unit'],

		'L_SUBMIT'			=> $LANG['submit'],
		'L_PREVIEW'			=> $LANG['preview'],
		'L_RESET'			=> $LANG['reset']
	));


	$flag = empty($row['links_flag']) ? 0 : intval($row['links_flag']);

	foreach ($checkboxes as $k => $v)
	{
		if ($id_edit != 0)
		{
			$t = $v['mask'] & $flag;
		}
		elseif($id_add != 0)
		{
			$t = 1; // Force to not empty
		}
        if ($smallads->config_get($k, 0))
        {
            $tpl->assign_block_vars('checkbox', array(
                'L_LABEL' => $LANG['sa_'.$k],
                'NAME'    => $k,
                'CHECKED' => (!empty($t)) ? 'checked="checked"' : '',
                'VALUE'   => $v['mask']
            ));
        }
	}

	$c_contrib = !$smallads->access_ok(SMALLADS_OWN_CRUD_ACCESS) && $smallads->access_ok(SMALLADS_CONTRIB_ACCESS);
	
	$editor_counterpart = AppContext::get_content_formatting_service()->get_default_editor();
	$editor_counterpart->set_identifier('counterpart');
	
	$tpl->put_all(array(
		'C_CONTRIBUTION' 					 => $c_contrib,
		'L_CONTRIBUTION'					 => $LANG['sa_contribution_legend'],
		'L_CONTRIBUTION_NOTICE' 			 => $LANG['sa_contribution_notice'],
		'L_CONTRIBUTION_COUNTERPART' 		 => $LANG['sa_contribution_counterpart'],
		'L_CONTRIBUTION_COUNTERPART_EXPLAIN' => $LANG['sa_contribution_counterpart_explain'],
		'CONTRIBUTION_COUNTERPART_EDITOR' 	 => $editor_counterpart->display()
	));

	$types = $type_options;
	unset($types[0]);
	foreach ($types as $k => $v) {
		$tpl->assign_block_vars('type_options_edit',array(
			'NAME' 		=> $v,
			'SELECTED'	=> $smallads->selected($k, intval($row['type'])),
			'VALUE' 	=> $k));
	}

	$tpl->display();
}
else //Affichage en liste par défaut
{
	$modulesLoader = AppContext::get_extension_provider_service();
	$module_name = 'smallads';
	$module = $modulesLoader->get_provider($module_name);
	echo $module->get_extension_point(HomePageExtensionPoint::EXTENSION_POINT)->get_home_page()->get_view()->display();
}

require_once(PATH_TO_ROOT.'/kernel/footer.php');

?>