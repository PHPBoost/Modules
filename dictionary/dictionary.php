<?php
/*##################################################
 *                              dictionary.php
 *                            -------------------
 *   begin                : March  3, 2009 
 *   copyright            : (C) 2009 Nicolas Maurel
 *   email                :  crunchfamily@free.fr
 *
 *  
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../kernel/begin.php'); 
require_once('../dictionary/dictionary_begin.php');
require_once('../kernel/header.php');

$Cache->load('dictionary');
$Template = new FileTemplate('dictionary/dictionary.tpl');

$get_l_error = retrieve(GET, 'erroru', '');

if (!empty($get_l_error))
	$Template->put('MSG', MessageHelper::display($LANG[$get_l_error], E_USER_WARNING));

if (retrieve(GET, 'add', false) || retrieve(POST, 'previs', false) || retrieve(POST, 'valid', false) || $id_get = retrieve(GET, 'edit', 0, TINTEGER))// ajout, previsualisation,edition.
{
	$user_id = $User->get_attribute('user_id');
	trigger_error_if_no_access(DICTIONARY_CREATE_ACCESS|DICTIONARY_CONTRIB_ACCESS|DICTIONARY_UPDATE_ACCESS, $CONFIG_DICTIONARY['auth']);

	$result_cat = $Sql->query_while("SELECT id, name
	FROM ".PREFIX."dictionary_cat
	ORDER BY id", __LINE__, __FILE__);
	while( $row_cat = $Sql->fetch_assoc($result_cat))
	{ 
		$Template->assign_block_vars('cat_list_add', array(
			'VALUE' => $row_cat['id'],
			'NAME' => strtoupper(stripslashes($row_cat['name'])),
			));
	}
	
	$contents_editor = AppContext::get_content_formatting_service()->get_default_editor();
	$contents_editor->set_identifier('contents', $CONFIG_DICTIONARY['dictionary_forbidden_tags']);
	
	$counterpart_editor = AppContext::get_content_formatting_service()->get_default_editor();
	$counterpart_editor->set_identifier('counterpart');
	
	$Template->put_all(array(
		'L_ALERT_TEXT_DESC' => $LANG['require_text_desc'],
		'L_ALERT_TEXT_MOTS' => $LANG['require_text_word'],
		'L_DELETE_DICTIONARY' => $LANG['delete_dictionary_conf'],
		'L_ALL' => $LANG['all'],
		'L_ADD_DICTIONARY' => $LANG['create_dictionary'],
		'L_CONTENTS' => $LANG['dictionary_contents'],
		'L_WORD' => $LANG['dictionary_word'],
		'L_SUBMIT' => $LANG['submit'],
		'L_PREVIS' => $LANG['previs'],
		'L_RESET' => $LANG['reset'],
		'L_VALIDATION' => $LANG['validation'],
		'L_CATEGORY' => $LANG['category'],
		'C_EDIT' => access_ok(DICTIONARY_CREATE_ACCESS|DICTIONARY_CONTRIB_ACCESS, $CONFIG_DICTIONARY['auth']),
		'TITLE' => $LANG['dictionary'],
		'KERNEL_EDITOR' => $contents_editor->display()
	));

	$c_contrib = !access_ok(DICTIONARY_CREATE_ACCESS, $CONFIG_DICTIONARY['auth']) && access_ok(DICTIONARY_CONTRIB_ACCESS, $CONFIG_DICTIONARY['auth']);

	$Template->put_all(array(
		'C_CONTRIBUTION' => $c_contrib,
		'L_CONTRIBUTION' => $LANG['dictionary_contribution_legend'],
		'L_CONTRIBUTION_NOTICE' => $LANG['dictionary_contribution_notice'],
		'L_CONTRIBUTION_COUNTERPART' => $LANG['dictionary_contribution_counterpart'],
		'L_CONTRIBUTION_COUNTERPART_EXPLAIN' => $LANG['dictionary_contribution_counterpart_explain'],
		'CONTRIBUTION_COUNTERPART_EDITOR' => $counterpart_editor->display(),
		'C_APPROVED' => TRUE
	));
	
	if (retrieve(POST, 'previs', false)) // prévisualisation
	{
		$word = retrieve(POST, 'word', 'word', TSTRING);
		$contents = retrieve(POST, 'contents', '', TSTRING_AS_RECEIVED);
		$contents_preview = retrieve(POST, 'contents', '' , TSTRING_UNCHANGE);
		$category_id = retrieve(POST,'category_add','',TINTEGER);
		$id = retrieve(POST,'dictionary_id','');
		
		$result = $Sql->query_while("SELECT id ,name
		FROM ".PREFIX."dictionary_cat
		WHERE id = ".$category_id,	__LINE__, __FILE__);
		$row = $Sql->fetch_assoc($result);	
		$Template->assign_vars(array(
			'C_ARTICLES_PREVIEW' => true,
			'WORD' => stripslashes($word),
			'ID' => $id,
			'L_PREVISUALIATION' => $LANG['previsualisation'],
			'CONTENTS_PRW' => FormatingHelper::second_parse(stripslashes(FormatingHelper::strparse($contents,$CONFIG_DICTIONARY['dictionary_forbidden_tags']))),
			'CONTENTS' => $contents_preview,
			'NAME_CAT_SELECT'=>$row['name'],
			'ID_CAT_SELECT'=>$category_id,
		));
	}
	elseif (retrieve(POST, 'valid', false)) // ajout
	{
		$timestamp = time();
		$id = retrieve(POST,'dictionary_id','');
		$word = retrieve(POST, 'word', 'word', TSTRING);
		$contents = retrieve(POST, 'contents', 'contents', TSTRING_AS_RECEIVED);
		$contents_cat = retrieve(POST,'category_add','',TINTEGER);
		$contents = FormatingHelper::second_parse(stripslashes(FormatingHelper::strparse($contents,$CONFIG_DICTIONARY['dictionary_forbidden_tags'])));
		$row = $Sql->query_array(PREFIX."dictionary", "id", "word","description", "WHERE id = '" . $id . "'", __LINE__, __FILE__);
		$row1 = $Sql->query_array(PREFIX."dictionary", "id", "word","description","approved", "WHERE word = '" . $word . "'", __LINE__, __FILE__);
		if ($row['id'] != '')
		{
			$quotes_approved = 0;
			if (access_ok(DICTIONARY_CREATE_ACCESS, $CONFIG_DICTIONARY['auth'])) 
			{
				$quotes_approved = 1;
			}
			
			$Sql->query_inject("UPDATE " . PREFIX . "dictionary SET cat ='".addslashes($contents_cat)."',description = '" . addslashes($contents) . "', word = '" . addslashes($word) . "',`approved`  = '" . $quotes_approved . "',`timestamp` ='".$timestamp."' WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
			$contributions = ContributionService::find_by_criteria('dictionary', $row['id']);
			
			if (count($contributions) > 0)
			{
				foreach( $contributions as $contribution) {
					$contribution->set_status(CONTRIBUTION_STATUS_PROCESSED);
					ContributionService::save_contribution($contribution);
				}
			}
			$Cache->Generate_module_file('dictionary');
			AppContext::get_response()->redirect(HOST . DIR . '/dictionary/dictionary.php');
		}
		elseif ($row1['id'] != "")
		{
			if ($row1['approved'] == 0)
			{
				AppContext::get_response()->redirect(HOST . DIR . '/dictionary/dictionary' . url('.php?erroru=' . "word_exist_contrib") . '#errorh');
			}
			else
			{
				AppContext::get_response()->redirect(HOST . DIR . '/dictionary/dictionary' . url('.php?erroru=' . "word_exist") . '#errorh');
			
			}
		}
		else
		{
			$quotes_approved = 0;
			if (access_ok(DICTIONARY_CREATE_ACCESS, $CONFIG_DICTIONARY['auth'])) 
			{
				$quotes_approved = 1;
			}
			$Sql->query_inject("INSERT INTO `".PREFIX."dictionary` SET
		
			`word` = '".addslashes($word)."',
			`cat` = '".addslashes($contents_cat)."',
			`description`	= '".addslashes($contents)."',
			`approved`  = '" . $quotes_approved . "',
			`user_id`   = '" . $user_id . "',
			`timestamp` = '".$timestamp."'
			"	
			, __LINE__, __FILE__);
			
			$last_msg_id = $Sql->insert_id("");
			if (!access_ok(DICTIONARY_CREATE_ACCESS, $CONFIG_DICTIONARY['auth']) && access_ok(DICTIONARY_CONTRIB_ACCESS, $CONFIG_DICTIONARY['auth'])) 
			{
				$dictionary_contribution = new Contribution();
				$dictionary_contribution->set_id_in_module($last_msg_id);
				$dictionary_contribution->set_description(retrieve(POST, 'counterpart', '', TSTRING_PARSE));
				$dictionary_contribution->set_entitled(sprintf($LANG['dictionary_contribution_entitled'], $last_msg_id));
				$dictionary_contribution->set_fixing_url('/dictionary/dictionary.php?edit=' . $last_msg_id);
				$dictionary_contribution->set_poster_id($User->get_attribute('user_id'));
				$dictionary_contribution->set_module('dictionary');
				$dictionary_contribution->set_auth(Authorizations::capture_and_shift_bit_auth($CONFIG_DICTIONARY['auth'], DICTIONARY_UPDATE_ACCESS, CONTRIBUTION_AUTH_BIT));
				ContributionService::save_contribution($dictionary_contribution);
				AppContext::get_response()->redirect(HOST . DIR . '/dictionary/contribution.php');
				exit;
			}
			$Cache->Generate_module_file('dictionary');
		}
		AppContext::get_response()->redirect(HOST . DIR . '/dictionary/dictionary.php');
	}
	elseif ($id_get = retrieve(GET, 'edit', 0, TINTEGER)) // édition
	{
		trigger_error_if_no_access(DICTIONARY_UPDATE_ACCESS, $CONFIG_DICTIONARY['auth']);
				
		$result = $Sql->query_while("SELECT q.*, m.login AS mlogin,c.id AS cat,c.name AS cat_name
				FROM ".PREFIX."dictionary q
				LEFT JOIN ".PREFIX."member m ON m.user_id = q.user_id
				LEFT JOIN ".PREFIX."dictionary_cat c ON q.cat = c.id
				WHERE q.id = ".$id_get,	__LINE__, __FILE__);
				
		if ( $Sql->num_rows($result, '') == 0 ) 
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		$row = $Sql->fetch_assoc($result);
		$Template->assign_vars(array(
			'C_EDIT' => TRUE,
			'C_CONTRIBUTION' => FALSE,
			'C_APPROVED' => !empty($row['approved']) ? TRUE : FALSE,
			'ID' => $id_get,
			'CONTENTS' => FormatingHelper::unparse(stripslashes($row['description'])),
			'WORD' => stripslashes($row['word']),
			'APPROVED' => '',
			'THEME' => get_utheme(),
			'ID_CAT_SELECT' => $row['cat'],
			'NAME_CAT_SELECT' => $row['cat_name'],
		));
	}
	$Cache->generate_module_file('dictionary'); //Régénération du cache
	$Template->display();
		
}
elseif ($id_get = retrieve(GET, 'del', 0, TINTEGER))//Supression
{
	$Session->csrf_get_protect();
	$nb_word = $Sql->query("SELECT COUNT(1) AS total FROM ".PREFIX . "dictionary WHERE (approved = 1)", __LINE__, __FILE__);
	if ($nb_word == 1 )
	{
		AppContext::get_response()->redirect(HOST . DIR . '/dictionary/dictionary' . url('.php?erroru=' . "del_word") . '#errorh');
	}
	else
	{
		trigger_error_if_no_access(DICTIONARY_DELETE_ACCESS, $CONFIG_DICTIONARY['auth']);
		$Sql->query_inject("DELETE FROM " . PREFIX . "dictionary WHERE id = '" . $id_get . "'", __LINE__, __FILE__);
		$Cache->Generate_module_file('dictionary'); //Régénération du cache du mini-module.
		AppContext::get_response()->redirect(HOST . DIR . '/dictionary/dictionary.php');
	}
}
else // Affichage
{
	$modulesLoader = AppContext::get_extension_provider_service();
	$module_name = 'dictionary';
	$module = $modulesLoader->get_provider($module_name);
	if ($module->has_extension_point(HomePageExtensionPoint::EXTENSION_POINT))
	{
		echo $module->get_extension_point(HomePageExtensionPoint::EXTENSION_POINT)->get_home_page()->get_view()->display();
	}
	elseif (!$no_alert_on_error) 
	{
		//TODO Gestion de la langue
		$controller = new UserErrorController(LangLoader::get_message('error', 'errors'), 
            'Le module <strong>' . $module_name . '</strong> n\'a pas de fonction get_home_page!', UserErrorController::FATAL);
        DispatchManager::redirect($controller);
	}
}

require_once('../kernel/footer.php'); 

?>