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

$config = SmalladsConfig::load();
$request = AppContext::get_request();

$smallads = new Smallads();

$id_delete		= retrieve(GET, 'delete', 0, TINTEGER);
$id_delete_pict	= retrieve(GET, 'delete_picture', 0, TINTEGER);
$id_edit 		= retrieve(GET, 'edit', 0, TINTEGER);
$id_add 		= retrieve(GET, 'add', 0, TINTEGER);
$id_view 		= retrieve(GET, 'id', 0, TINTEGER);

function render_view($smallads, $row, $tpl)
{
	global $LANG, $type_options, $config;

	$user = AppContext::get_current_user()->get_id();
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
	
	$tpl->assign_block_vars('item',array(
		'ID' 		=> $row['id'],
		'VID'		=> empty($row['vid']) ? '' : $row['vid'],
		'TYPE'	 	=> $type_options[intval($row['type'])],
		'TITLE' 	=> htmlentities($row['title']),
		'CONTENTS' 	=> FormatingHelper::second_parse(stripslashes($row['contents'])),
		'PRICE'		=> $row['price'],
		'SHIPPING'	=> $row['shipping'],
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

if( retrieve(POST, 'submit', false) ) //Enregistrement du formulaire
{
	$id_post        = intval(retrieve(POST, 'id', 0, TINTEGER));

	if (empty($id_post)) // Creation
	{
		$smallads->check_autorisation(SmalladsAuthorizationsService::OWN_CRUD_AUTHORIZATIONS|SmalladsAuthorizationsService::CONTRIBUTION_AUTHORIZATIONS);
	}
	else
	{
		$smallads->check_autorisation(SmalladsAuthorizationsService::OWN_CRUD_AUTHORIZATIONS|SmalladsAuthorizationsService::MODERATION_AUTHORIZATIONS|SmalladsAuthorizationsService::CONTRIBUTION_AUTHORIZATIONS);
	}

	$sa_title 		= retrieve(POST, 'smallads_title', '', TSTRING_UNCHANGE);
	$sa_contents 	= retrieve(POST, 'smallads_contents', '', TSTRING_UNCHANGE);
	$sa_contents	= FormatingHelper::strparse($sa_contents, $forbidden_tags, FALSE); // TSTRING_PARSE ne permet pas les parametres
	$sa_price 		= retrieve(POST, 'smallads_price', 0.0, TFLOAT);
	$sa_shipping	= retrieve(POST, 'smallads_shipping', 0.0, TFLOAT);
	$sa_type   		= retrieve(POST, 'smallads_type', 0, TINTEGER);
	$sa_max_weeks	= $config->is_max_weeks_number_displayed() ? retrieve(POST, 'smallads_max_weeks', 0, TINTEGER) : 0;
	$sa_max_weeks	= abs($sa_max_weeks);

	$flag = 0;
	
	$view_mail = (int)($request->has_postparameter('view_mail') && $request->get_value('view_mail') == 'on');
	$flag += (int)$config->is_mail_displayed() * $view_mail;
	
	$view_pm = 2 * (int)($request->has_postparameter('view_pm') && $request->get_value('view_pm') == 'on');
	$flag += (int)$config->is_pm_displayed() * $view_pm;

	if ( empty($sa_contents) || empty($sa_title) )
	{
		$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), LangLoader::get_message('e_incomplete', 'errors'));
		DispatchManager::redirect($controller);
	}

	//Mod anti-flood
	$check_time = 0;
	$user_id = AppContext::get_current_user()->get_id();
	if ($user_id > 0 && ContentManagementConfig::load()->is_anti_flood_enabled())
	{
		try {
			$check_time = PersistenceContext::get_querier()->get_column_value(SmalladsSetup::$smallads_table, 'MAX(date_created)', 'WHERE id_created = ' . $user_id);
		} catch (RowNotFoundException $e) {}
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

		$db_approved = (int)SmalladsAuthorizationsService::check_authorizations()->own_crud();
		$date = time();
		
		$result = PersistenceContext::get_querier()->insert(SmalladsSetup::$smallads_table, array(
			'title' => addslashes($sa_title),
			'contents' => addslashes($sa_contents),
			'type' => $sa_type,
			'price' => $sa_price,
			'shipping' => $sa_shipping,
			'id_created' => $user_id,
			'date_created' => $date,
			'links_flag' => $flag,
			'max_weeks' => $sa_max_weeks,
			'approved' => (int)$db_approved,
			'date_approved' => $db_approved ? $date : 0
		));

		$last_id = $result->get_last_inserted_id();
		
		$smallads->upload_picture($last_id);

		// Feeds Regeneration
		Feed::clear_cache('smallads');
		// Cache Regeneration
		SmalladsCache::invalidate();

		if (!$smallads->access_ok(SmalladsAuthorizationsService::OWN_CRUD_AUTHORIZATIONS) && $smallads->access_ok(SmalladsAuthorizationsService::CONTRIBUTION_AUTHORIZATIONS))
		{
			$contribution_counterpart = retrieve(POST, 'contribution_counterpart', '', TSTRING_UNCHANGE);
			$smallads->contribution_add($last_id, $sa_title, $contribution_counterpart);
			DispatchManager::redirect(new UserContributionSuccessController());
		}

	} else { // Modification

		try {
			$smallads_properties = PersistenceContext::get_querier()->select_single_row(SmalladsSetup::$smallads_table, array('approved', 'id_created'), 'WHERE id=:id', array('id' => $id_post));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}

		$update_ok = $smallads->check_access(SmalladsAuthorizationsService::MODERATION_AUTHORIZATIONS, (SmalladsAuthorizationsService::OWN_CRUD_AUTHORIZATIONS|SmalladsAuthorizationsService::CONTRIBUTION_AUTHORIZATIONS), intval($smallads_properties['id_created']));
		if (!$update_ok)
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}

		if ($smallads->access_ok(SmalladsAuthorizationsService::MODERATION_AUTHORIZATIONS|SmalladsAuthorizationsService::OWN_CRUD_AUTHORIZATIONS))
		{
			if ( !empty($smallads_properties['approved']) )
			{
				PersistenceContext::get_querier()->update(SmalladsSetup::$smallads_table, array(
					'title' => addslashes($sa_title),
					'contents' => addslashes($sa_contents),
					'type' => $sa_type,
					'price' => $sa_price,
					'shipping' => $sa_shipping,
					'id_updated' => $user_id,
					'date_updated' => time(),
					'links_flag' => $flag,
					'max_weeks' => $sa_max_weeks
				), 'WHERE id=:id', array('id' => $id_post));
				
				$smallads->upload_picture($id_post);
			}

			$sa_approved  	= retrieve(POST, 'smallads_approved', '', TNONE);
			$db_approved	= !empty($sa_approved) ? 1 : 0;

			if ($smallads->access_ok(SmalladsAuthorizationsService::MODERATION_AUTHORIZATIONS) && $db_approved && empty($smallads_properties['approved']))
			{
				$date = time();
				$doublon = array();
				try {
					$doublon = PersistenceContext::get_querier()->select_single_row(SmalladsSetup::$smallads_table, array('vid', 'id_created', 'date_created'), 'WHERE id=:id', array('id' => $id_post));
				} catch (RowNotFoundException $e) {}
				
				if (!empty($doublon) &&  !empty($doublon['vid']) )
				{
					PersistenceContext::get_querier()->update(SmalladsSetup::$smallads_table, array(
						'title' => addslashes($sa_title),
						'contents' => addslashes($sa_contents),
						'type' => $sa_type,
						'price' => $sa_price,
						'shipping' => $sa_shipping,
						'id_created' => $doublon['id_created'],
						'date_created' => $doublon['date_created'],
						'id_updated' => $user_id,
						'date_updated' => $date,
						'links_flag' => $flag,
						'approved' => $db_approved,
						'date_approved' => $date
					), 'WHERE id=:id', array('id' => $doublon['vid']));
					
					PersistenceContext::get_querier()->update(SmalladsSetup::$smallads_table, array(
						'title' => '',
						'contents' => '',
						'type' => 0,
						'price' => 0.0,
						'shipping' => 0.0,
						'id_created' => 0,
						'date_created' => 0,
						'id_updated' => 0,
						'date_updated' => 0,
						'links_flag' => 0,
						'approved' => 2,
						'date_approved' => 0
					), 'WHERE id=:id', array('id' => $id_post));

				}
				else
				{
					PersistenceContext::get_querier()->update(SmalladsSetup::$smallads_table, array(
						'title' => addslashes($sa_title),
						'contents' => addslashes($sa_contents),
						'type' => $sa_type,
						'price' => $sa_price,
						'shipping' => $sa_shipping,
						'id_updated' => $user_id,
						'date_updated' => $date,
						'links_flag' => $flag,
						'approved' => $db_approved,
						'date_approved' => $date
					), 'WHERE id=:id', array('id' => $id_post));
				}

				$smallads->contribution_set_processed($id_post);
			}

			// Feeds Regeneration
			Feed::clear_cache('smallads');
			// Cache Regeneration
			SmalladsCache::invalidate();
		}
		elseif($smallads->access_ok(SmalladsAuthorizationsService::CONTRIBUTION_AUTHORIZATIONS))
		{
			$in_progress = $smallads->contribution_is_in_progress($id_post);
			if ($in_progress)
			{
				$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), $LANG['sa_contrib_in_progress']);
				DispatchManager::redirect($controller);
			}

			if ( empty($smallads_properties['approved']) )
			{
				PersistenceContext::get_querier()->update(SmalladsSetup::$smallads_table, array(
					'title' => addslashes($sa_title),
					'contents' => addslashes($sa_contents),
					'type' => $sa_type,
					'price' => $sa_price,
					'shipping' => $sa_shipping,
					'id_updated' => $user_id,
					'date_updated' => time(),
					'links_flag' => $flag
				), 'WHERE id=:id', array('id' => $id_post));
				
				$id_picture = $id_post;
				$id_contrib = $id_post;
			}
			else
			{
				$row = $row2 = array();
				try {
					$row = PersistenceContext::get_querier()->select_single_row(SmalladsSetup::$smallads_table, array('id'), 'WHERE vid=:id', array('id' => $id_post));
				} catch (RowNotFoundException $e) {}
				
				try {
					$row2 = PersistenceContext::get_querier()->select_single_row(SmalladsSetup::$smallads_table, array('*'), 'WHERE id=:id', array('id' => $id_post));
				} catch (RowNotFoundException $e) {}
				
				//Le doublon existe-t-il ?
				if ( !empty($row) ) {
					// NON on le crée
					$result = PersistenceContext::get_querier()->insert(SmalladsSetup::$smallads_table, array(
						'title' => addslashes($sa_title),
						'contents' => addslashes($sa_contents),
						'type' => $sa_type,
						'price' => $sa_price,
						'shipping' => $sa_shipping,
						'id_created' => $user_id,
						'date_created' => $row2['date_created'],
						'links_flag' => $flag,
						'vid' => $row2['id'],
						'approved' => 0,
						'date_approved' => 0
					));

					$id_picture = $id_contrib = $result->get_last_inserted_id();
				}
				else
				{
					// OUI Mise a jour du doublon
					PersistenceContext::get_querier()->update(SmalladsSetup::$smallads_table, array(
						'title' => addslashes($sa_title),
						'contents' => addslashes($sa_contents),
						'type' => $sa_type,
						'price' => $sa_price,
						'shipping' => $sa_shipping,
						'id_created' => $user_id,
						'date_created' => $row2['date_created'],
						'id_updated' => $user_id,
						'date_updated' => 0,
						'approved' => 0,
						'date_approved' => time(),
						'links_flag' => $flag
					), 'WHERE id=:id', array('id' => $id_post));

					$id_picture = $id_contrib = $id_post;
				}
			}

			$smallads->upload_picture($id_picture);

			// update d'une contribution non traitée
			$contribution_counterpart = retrieve(POST, 'contribution_counterpart', '', TSTRING_UNCHANGE);
			if (!$smallads->contribution_update($row2['id'], 'Modif id # '.$row2['id'].' - '.$contribution_counterpart))
			{
				// il faut creer une nouvelle demande de contribution
				$smallads->contribution_add($row2['id'], $sa_title, 'Modif id # '.$row2['id'].' - '.$contribution_counterpart);
			}

			// Feeds Regeneration
			Feed::clear_cache('smallads');
			// Cache Regeneration
			SmalladsCache::invalidate();
			DispatchManager::redirect(new UserContributionSuccessController());
		}
	}

	if (empty($id_post))
	{
		AppContext::get_response()->redirect(HOST . SCRIPT);
	}
	else
	{
		$v = $config->is_return_to_list_displayed();
		if ( $v == 0 )
		{
			AppContext::get_response()->redirect(HOST . SCRIPT . '?edit=' . $id_post . '&s=1');
		}
		else
		{
			AppContext::get_response()->redirect(HOST . SCRIPT);
		}
	}
	exit;
}
elseif ($id_delete)
{
	AppContext::get_session()->csrf_post_protect(); //Protection csrf

	$smallads->check_autorisation(SmalladsAuthorizationsService::MODERATION_AUTHORIZATIONS|SmalladsAuthorizationsService::OWN_CRUD_AUTHORIZATIONS|SmalladsAuthorizationsService::CONTRIBUTION_AUTHORIZATIONS);

	try {
		$row = PersistenceContext::get_querier()->select_single_row(SmalladsSetup::$smallads_table, array('picture', 'id_created', 'approved', 'vid'), 'WHERE id=:id', array('id' => $id_delete));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_element();
		DispatchManager::redirect($error_controller);
	}
	
	$delete_ok = $smallads->check_access(SmalladsAuthorizationsService::MODERATION_AUTHORIZATIONS, (SmalladsAuthorizationsService::OWN_CRUD_AUTHORIZATIONS|SmalladsAuthorizationsService::CONTRIBUTION_AUTHORIZATIONS), intval($row['id_created']));
	if (!$delete_ok)
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}

	$filename = $row['picture'];

	if ( $id_delete > 0 )
	{
		if ($smallads->access_ok(SmalladsAuthorizationsService::MODERATION_AUTHORIZATIONS|SmalladsAuthorizationsService::OWN_CRUD_AUTHORIZATIONS))
		{
			PersistenceContext::get_querier()->delete(SmalladsSetup::$smallads_table, 'WHERE id=:id', array('id' => $id_delete));
			
			if ( !empty($filename) )
			{
				@unlink (PATH_TO_ROOT.'/smallads/pics/'.$filename);
			}
		}
		elseif($smallads->access_ok(SmalladsAuthorizationsService::CONTRIBUTION_AUTHORIZATIONS))
		{
			// PA originale
			if ( empty($row['vid']) )
			{
				$doublon = false;
				
				try {
					$doublon = PersistenceContext::get_querier()->select_single_row(SmalladsSetup::$smallads_table, array('approved', 'id', 'picture'), 'WHERE vid=:id', array('id' => $id_delete));
				} catch (RowNotFoundException $e) {}

				if ( $doublon === FALSE ) // Pas de doublon
				{
					$in_progress = $smallads->contribution_is_in_progress($id_delete);
					if ($in_progress)
					{
						$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), $LANG['sa_contrib_in_progress']);
						DispatchManager::redirect($controller);
					}
					$smallads->contribution_delete($id_delete);

					PersistenceContext::get_querier()->delete(SmalladsSetup::$smallads_table, 'WHERE id=:id', array('id' => $id_delete));
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

					PersistenceContext::get_querier()->delete(SmalladsSetup::$smallads_table, 'WHERE id=:id', array('id' => $id_doublon));
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
					
					PersistenceContext::get_querier()->update(SmalladsSetup::$smallads_table, array(
						'title' => '',
						'contents' => '',
						'type' => 0,
						'price' => 0.0,
						'shipping' => 0.0,
						'id_created' => 0,
						'date_created' => 0,
						'id_updated' => 0,
						'date_updated' => 0,
						'links_flag' => 0,
						'approved' => 2,
						'date_approved' => 0
					), 'WHERE id=:id', array('id' => $id_delete));
					
					$smallads->contribution_delete($id_delete);
				}
			}
		}

		// Feeds Regeneration
		Feed::clear_cache('smallads');
		// Cache Regeneration
		SmalladsCache::invalidate();
	}
	AppContext::get_response()->redirect(HOST . SCRIPT);
	exit;
}
elseif ($id_delete_pict)
{
	AppContext::get_session()->csrf_post_protect(); //Protection csrf

	$smallads->check_autorisation(SmalladsAuthorizationsService::MODERATION_AUTHORIZATIONS|SmalladsAuthorizationsService::OWN_CRUD_AUTHORIZATIONS);

	try {
		$row = PersistenceContext::get_querier()->select_single_row(SmalladsSetup::$smallads_table, array('picture', 'id_created'), 'WHERE id=:id', array('id' => $id_delete_pict));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_element();
		DispatchManager::redirect($error_controller);
	}

	$delete_ok = $smallads->check_access(SmalladsAuthorizationsService::MODERATION_AUTHORIZATIONS, (SmalladsAuthorizationsService::OWN_CRUD_AUTHORIZATIONS), intval($row['id_created']));
	if (!$delete_ok)
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}

	$filename = $row['picture'];

	if ( $id_delete_pict > 0 )
	{
		PersistenceContext::get_querier()->update(SmalladsSetup::$smallads_table, array('picture' => ''), 'WHERE id=:id', array('id' => $id_delete_pict));
		if ( !empty($filename) )
		{
			@unlink (PATH_TO_ROOT.'/smallads/pics/'.$filename);
		}
		SmalladsCache::invalidate(); //Régénération du cache
	}

	AppContext::get_response()->redirect(HOST . SCRIPT . '?edit=' . $id_delete_pict . '&s=1');
	exit;
}
elseif ($id_view)
{
	try {
		$row = PersistenceContext::get_querier()->select_single_row_query("SELECT q.*, m.*
		FROM " . SmalladsSetup::$smallads_table . " q
		LEFT JOIN ".PREFIX."member m ON m.user_id = q.id_created
		WHERE id = :id", array('id' => $id_view));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
	
	if (!SmalladsAuthorizationsService::check_authorizations()->read())
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}

	$tpl = new FileTemplate('smallads/smallads.tpl');

	$v = ($smallads->access_ok(SmalladsAuthorizationsService::OWN_CRUD_AUTHORIZATIONS|SmalladsAuthorizationsService::CONTRIBUTION_AUTHORIZATIONS));
	if ($v)
	{
		$url_edit 	= url('.php?edit='.$id_view);
		$c_edit	 	= TRUE;
	}
	unset($v);

	$v = ($smallads->access_ok(SmalladsAuthorizationsService::MODERATION_AUTHORIZATIONS));
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
		'C_NB_SMALLADS'	 => TRUE,
		'L_NO_SMALLADS'	 => $LANG['sa_no_smallads'],
		'L_LIST_NOT_APPROVED'	=> $LANG['sa_list_not_approved'],
		'L_MAX_PICTURE_WEIGHT' => $LANG['sa_max_picture_weight'],
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
		$smallads->check_autorisation(SmalladsAuthorizationsService::MODERATION_AUTHORIZATIONS|SmalladsAuthorizationsService::OWN_CRUD_AUTHORIZATIONS|SmalladsAuthorizationsService::CONTRIBUTION_AUTHORIZATIONS);
	}
	elseif($id_add != 0)
	{
		$smallads->check_autorisation(SmalladsAuthorizationsService::OWN_CRUD_AUTHORIZATIONS|SmalladsAuthorizationsService::CONTRIBUTION_AUTHORIZATIONS);
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
	
	$Bread_crumb->add($LANG['sa_title'], SmalladsUrlBuilder::home());
	
	if ($id_edit != 0)
	{
		try {
			$row = PersistenceContext::get_querier()->select_single_row(SmalladsSetup::$smallads_table, array('*'), 'WHERE id=:id', array('id' => $id_edit));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}
		
		$update_ok = $smallads->check_access(SmalladsAuthorizationsService::MODERATION_AUTHORIZATIONS, (SmalladsAuthorizationsService::OWN_CRUD_AUTHORIZATIONS|SmalladsAuthorizationsService::CONTRIBUTION_AUTHORIZATIONS), intval($row['id_created']));
		if (!$update_ok)
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		
		$Bread_crumb->add($LANG['sa_update_legend'], url('smallads.php?edit=' . $id_edit));
		$legend 		= $LANG['sa_update_legend'];
		$id 			= $id_edit;
		$c_contribution = (!$smallads->access_ok(SmalladsAuthorizationsService::OWN_CRUD_AUTHORIZATIONS|SmalladsAuthorizationsService::MODERATION_AUTHORIZATIONS) && $smallads->access_ok(SmalladsAuthorizationsService::CONTRIBUTION_AUTHORIZATIONS));
		$c_can_approve	= ( $smallads->access_ok(SmalladsAuthorizationsService::MODERATION_AUTHORIZATIONS) && empty($row['approved']));
	}
	elseif($id_add != 0)
	{
		$Bread_crumb->add($LANG['sa_add_legend'], url('smallads.php?add=1'));
		$columns = PersistenceContext::get_dbms_utils()->desc_table(SmalladsSetup::$smallads_table);
		$row = array();
		foreach (array_keys($columns) as $column) {
			$row[$column] = '';
		}
		$row['approved'] = 1; // on simule "approuvé" sur add
		$legend 		= $LANG['sa_add_legend'];
		$c_contribution = !$smallads->access_ok(SmalladsAuthorizationsService::OWN_CRUD_AUTHORIZATIONS) AND $smallads->access_ok(SmalladsAuthorizationsService::CONTRIBUTION_AUTHORIZATIONS);
	}

	$maxlen = $config->get_max_contents_length();

	$usage_terms = $config->are_usage_terms_displayed();
	$cgu_contents = '';
	if (!empty($usage_terms))
		$cgu_contents = $config->get_usage_terms();

	$max_weeks_default = '';
	if ($config->get_max_weeks_number())
	{
		$max_weeks_default = sprintf($LANG['sa_max_weeks_default'], $config->get_max_weeks_number());
	}
	
	$editor = AppContext::get_content_formatting_service()->get_default_editor();
	$editor->set_identifier('smallads_contents');
	$editor->set_forbidden_tags($forbidden_tags);
	
	$date_created = !empty($row['date_created']) ? new Date($row['date_created'], Timezone::SERVER_TIMEZONE) : null;
	$date_updated = !empty($row['date_updated']) ? new Date($row['date_updated'], Timezone::SERVER_TIMEZONE) : null;
	
	$flag = empty($row['links_flag']) ? 0 : intval($row['links_flag']);

	$tpl->put_all(array(
		'C_FORM'			=> TRUE,
		'C_CONTRIBUTION'	=> $c_contribution,
		'C_MAX_WEEKS'		=> $config->is_max_weeks_number_displayed(),
		'C_CAN_APPROVE'		=> $c_can_approve,
		'C_PICTURE'			=> !empty($row['picture']),

		'KERNEL_EDITOR'		=> $editor->display(),

		'L_ALERT_TEXT'		=> $LANG['require_text'],
		'L_ALERT_FLOAT'		=> $LANG['sa_require_float'],
		'L_ALERT_UPLOAD'	=> $LANG['sa_require_upload'],
		'L_REQUIRE'			=> LangLoader::get_message('form.explain_required_fields', 'status-messages-common'),
		'L_LEGEND'			=> $legend,
		'L_CONFIRM_DELETE_PICTURE' 	=> $LANG['sa_confirm_delete_picture'],
		'L_MAX_PICTURE_WEIGHT' => $LANG['sa_max_picture_weight'],
		'C_USAGE_TERMS'		=> $config->are_usage_terms_displayed(),
		'L_USAGE_LEGEND'	=> $LANG['sa_usage_legend'],
		'L_AGREE_TERMS'		=> $LANG['sa_agree_terms'],
		'CGU_CONTENTS'		=> FormatingHelper::second_parse(stripslashes($cgu_contents)),
		'L_CGU_NOT_AGREED'	=> $LANG['sa_e_cgu_not_agreed'],
		'L_MAX_WEEKS_DEFAULT' => $max_weeks_default,

		'ID'				=> $id,
		'DB_TYPE'	 		=> $type_options[intval($row['type'])],
		'DB_TITLE'			=> $row['title'],
		'DB_CONTENTS'		=> FormatingHelper::unparse($row['contents']),
		'DB_PRICE'			=> !empty($row['price']) ? $row['price'] : '0.00',
		'DB_SHIPPING'		=> !empty($row['shipping']) ? $row['shipping'] : '0.00',
		'DB_APPROVED'		=> $row['approved'] != 0 ? 'checked' : '',
		'DB_CREATED'		=> (!empty($date_created)) ? $LANG['sa_created'] . $date_created->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE_TEXT) : '',
		'DB_UPDATED'		=> (!empty($date_updated)) ? $LANG['sa_updated'] . $date_updated->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE_TEXT) : '',
		'DB_PICTURE'		=> PATH_TO_ROOT.'/smallads/pics/'.$row['picture'].'?'.uniqid(rand()),
		'DB_MAX_WEEKS'		=> $row['max_weeks'],

		'DB_MAXLEN'			=> $maxlen,
		'DB_CONTENTS_REMAIN'	=> max($maxlen - strlen(@strip_tags($row['contents'], '<br><br/><br />')), 0),

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
		
		'L_VIEW_MAIL_ENABLED'	=> LangLoader::get_message('config.display_mail_enabled', 'common', 'smallads'),
		'C_VIEW_MAIL_CHECKED'	=> $config->is_mail_displayed() ? ($id_edit > 0 ? in_array($flag, array(1, 3)) : true) : false,
		'L_VIEW_PM_ENABLED'	=> LangLoader::get_message('config.display_pm_enabled', 'common', 'smallads'),
		'C_VIEW_PM_CHECKED'	=> $config->is_pm_displayed() ? ($id_edit > 0 ? in_array($flag, array(2, 3)) : true) : false,

		'L_SUBMIT'			=> $LANG['submit'],
		'L_PREVIEW'			=> $LANG['preview'],
		'L_RESET'			=> $LANG['reset']
	));


	$c_contrib = !$smallads->access_ok(SmalladsAuthorizationsService::OWN_CRUD_AUTHORIZATIONS) && $smallads->access_ok(SmalladsAuthorizationsService::CONTRIBUTION_AUTHORIZATIONS);
	
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