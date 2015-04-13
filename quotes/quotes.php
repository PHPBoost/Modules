<?php
/**
 *   quotes.php
 *
 *   @author            alain91
 *   @version          	$id$
 *   @copyright     	(C) 2008-2010 Alain Gandon (based on Guestbook)
 *   @email             alain091@gmail.com
 *   @license          	GPL Version 2
 */

defined('PATH_TO_ROOT') or define('PATH_TO_ROOT', '..');

require_once(PATH_TO_ROOT.'/kernel/begin.php'); 
require_once(PATH_TO_ROOT.'/quotes/quotes_begin.php');
require_once(PATH_TO_ROOT.'/quotes/quotes.inc.php');

require_once(PATH_TO_ROOT.'/kernel/header.php'); 

//Chargement du cache
$Cache->load('quotes');

$quotes = new Quotes();

if( retrieve(POST, 'valid', false) ) //Enregistrement du formulaire
{
	$quotes->cats->access_ok($category_id, QUOTES_CONTRIB_ACCESS|QUOTES_WRITE_ACCESS, TRUE);
	
	$_POST = $quotes->sanitize($_POST);
	
	$quotes_contents = retrieve(POST, 'quotes_contents', '', TNONE);
	$quotes_author   = retrieve(POST, 'quotes_author', '', TNONE);
	$in_mini         = retrieve(POST, 'quotes_in_mini', '', TNONE);
	$idcat	         = retrieve(POST, 'idcat', 0, TINTEGER);	
	$quotes_in_mini  = !empty($in_mini) ? 1 : 0;
	$id_post         = intval(retrieve(POST, 'id', 0, TINTEGER));
	$approved        = retrieve(POST, 'quotes_approved', '', TNONE);
	$quotes_approved = !empty($approved) ? 1 : 0;
	
	if ( empty($quotes_contents) OR empty($quotes_author) )
	{
		$controller = new UserErrorController(LangLoader::get_message('error', 'errors-common'), LangLoader::get_message('e_incomplete', 'errors'));
		DispatchManager::redirect($controller);
	}
	
	//Mod anti-flood
	$check_time = 0;
	$user_id = $User->get_attribute('user_id');
	if ($user_id !== -1 AND ContentManagementConfig::load()->is_anti_flood_enabled())
	{
		$check_time = $Sql->query("SELECT MAX(timestamp) as timestamp
									FROM ".PREFIX."quotes
									WHERE user_id = '" . $user_id . "'",
									__LINE__, __FILE__);
	}
	if( !empty($check_time) )
	{
		if( $check_time >= (time() - ContentManagementConfig::load()->get_anti_flood_duration()) )
		{ //On calcul la fin du delai.
			$controller = new UserErrorController(LangLoader::get_message('error', 'errors-common'), LangLoader::get_message('e_flood', 'errors'));
			DispatchManager::redirect($controller);
		}
	}
	
	if (empty($id_post)) {
		$quotes_approved = 0;
		if ( $quotes->cats->access_ok($category_id, QUOTES_WRITE_ACCESS) )
		{
			$quotes_approved = 1;
		}
		$requete = "INSERT INTO ".PREFIX."quotes
				SET contents  = '" . addslashes($quotes_contents) . "',
					idcat	  = '" . intval($idcat) . "',
					author    = '" . addslashes($quotes_author) . "',
					in_mini   = '" . intval($quotes_in_mini) ."',
					user_id   = '" . intval($user_id) . "',
					timestamp = '" . time() . "',
					approved  = '" . intval($quotes_approved) . "'";
		$Sql->query_inject($requete, __LINE__, __FILE__);
		$last_msg_id = $Sql->insert_id("");
		
		if (! $quotes->cats->access_ok($category_id, QUOTES_WRITE_ACCESS) && $quotes_cat->access_ok($category_id, QUOTES_CONTRIB_ACCESS))
		{
			$quotes_contribution = new Contribution();
			$quotes_contribution->set_id_in_module($last_msg_id);
			$quotes_contribution->set_description($contribution_counterpart);
			$quotes_contribution->set_entitled(sprintf($quotes->lang_get('contribution_entitled'), $last_msg_id));
			$quotes_contribution->set_fixing_url('/quotes/quotes.php?edit=' . $last_msg_id);
			$quotes_contribution->set_poster_id($User->get_attribute('user_id'));
			$quotes_contribution->set_module('quotes');
			$quotes_contribution->set_auth(Authorizations::capture_and_shift_bit_auth($CONFIG_QUOTES['auth'], QUOTES_WRITE_ACCESS, QUOTES_CONTRIB_ACCESS));
			ContributionService::save_contribution($quotes_contribution);
			AppContext::get_response()->redirect(HOST . DIR . '/quotes/contribution.php');
			exit;
		}
		$Cache->generate_module_file('quotes'); //Régénération du cache
		AppContext::get_response()->redirect(HOST . DIR . '/quotes/quotes.php?cat=' . $category_id);
		exit;
	}
	else
	{
		$quotes->cats->access_ok($category_id, QUOTES_WRITE_ACCESS, TRUE);
		
		$quotes_properties = $Sql->query_array(PREFIX . "quotes", "approved", "WHERE id = '" . $id_post . "'", __LINE__, __FILE__);
		$requete = "UPDATE ".PREFIX."quotes
				SET contents  = '" . addslashes($quotes_contents) . "',
					idcat	  = '" . intval($idcat) . "',
					author    = '" . addslashes($quotes_author) . "',
					in_mini   = '" . intval($quotes_in_mini) ."',
					user_id   = '" . intval($user_id) . "',
					timestamp = '" . time() . "'
				WHERE id ='".$id_post."'
				LIMIT 1";
		$Sql->query_inject($requete, __LINE__, __FILE__);
		
		if ($quotes_approved && empty($quotes_properties['approved']))
		{

			$Sql->query_inject(
				"UPDATE ".PREFIX."quotes
					SET approved  = '" . $quotes_approved . "'
					WHERE id ='".$id_post."'
					LIMIT 1",
					__LINE__, __FILE__);
				
			$contributions = ContributionService::find_by_criteria('quotes', $id_post);
			if (count($contributions) > 0)
			{
				foreach( $contributions as $contribution) {
					$contribution->set_status(EVENT_STATUS_PROCESSED);
					ContributionService::save_contribution($contribution);
				}
			}
		}
		
		$Cache->generate_module_file('quotes'); //Régénération du cache
		AppContext::get_response()->redirect(HOST . DIR . '/quotes/quotes.php?edit=' . $id_post . '&token=' . $Session->get_token());
		exit;
	}

}
elseif ( $id_get = retrieve(GET, 'delete', 0, TINTEGER) )
{
	$quotes->cats->access_ok($category_id, QUOTES_WRITE_ACCESS, TRUE);

	$Session->csrf_get_protect(); //Protection csrf
	
	if ( $id_get > 0 ) {
		$Sql->query_inject("DELETE FROM ".PREFIX."quotes WHERE id = '" . $id_get . "'", __LINE__, __FILE__);
		$Cache->generate_module_file('quotes'); //Régénération du cache
	}
	AppContext::get_response()->redirect(HOST . SCRIPT . SID2);
	exit;
}
elseif ( $id_get = retrieve(GET, 'edit', 0, TINTEGER) )
{
	$quotes->cats->access_ok($category_id, QUOTES_WRITE_ACCESS, TRUE);
	
	if (!empty($_GET['token']))
	{
		$Session->csrf_get_protect(); //Protection csrf
	}
	
	$Template = new FileTemplate('quotes/quotes.tpl');
	
	$result = $Sql->query_while("SELECT q.*, m.login AS mlogin
			FROM ".PREFIX."quotes q
			LEFT JOIN ".PREFIX."member m ON m.user_id = q.user_id
			WHERE q.id = ".$id_get,	__LINE__, __FILE__);
			
	if ( $Sql->num_rows($result, '') == 0 ) {
		DispatchManager::redirect(PHPBoostErrors::unexisting_page());
	}
		
	$row = $Sql->fetch_assoc($result);	
	$Template->put_all(array(
		'C_EDIT'			=> TRUE,
		'C_CONTRIBUTION'	=> FALSE,
		'C_APPROVED'		=> !empty($row['approved']) ? TRUE : FALSE,
		'ID'				=> $id_get,
		'CONTENTS'			=> FormatingHelper::unparse($row['contents']),
		'AUTHOR'			=> FormatingHelper::unparse($row['author']),
		'IN_MINI'			=> !empty($row['in_mini']) ? 'checked="checked"' : '',
		'APPROVED'			=> '',
		'DATE'				=> gmdate_format('date_format_short', $row['timestamp']),
		'THEME'				=> get_utheme(),
		'L_ALERT_TEXT'		=> $quotes->lang_get('require_text'),
		'L_UPDATE_QUOTE'	=> $quotes->lang_get('q_update'),
		'L_CONTENTS'		=> $quotes->lang_get('q_contents'),
		'L_AUTHOR'			=> $quotes->lang_get('q_author'),
		'L_IN_MINI'			=> $quotes->lang_get('q_in_mini'),
		'L_APPROVED'		=> $quotes->lang_get('q_approved'),
		'L_REQUIRE'			=> $quotes->lang_get('require'),
		'L_SUBMIT'			=> $quotes->lang_get('update'),
		'L_RESET'			=> $quotes->lang_get('reset'),
		'L_CATEGORY'		=> $quotes->lang_get('q_category'),
		'CATEGORIES_TREE'	=> $quotes->cats->build_select_form($row['idcat'], 'idcat', 'idcat', $id_get,
									QUOTES_WRITE_ACCESS|QUOTES_CONTRIB_ACCESS, $CONFIG_QUOTES['auth'],
									IGNORE_AND_CONTINUE_BROWSING_IF_A_CATEGORY_DOES_NOT_MATCH)
		));
	
	$Template->display();
}
else //Affichage.
{
	$modulesLoader = AppContext::get_extension_provider_service();
	$module_name = 'quotes';
	$module = $modulesLoader->get_provider($module_name);
	echo $module->get_extension_point(HomePageExtensionPoint::EXTENSION_POINT)->get_home_page()->get_view()->display();
}

require_once(PATH_TO_ROOT.'/kernel/footer.php'); 
