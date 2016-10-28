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
load_module_lang('dictionary'); //Chargement de la langue du module.
define('TITLE', $LANG['dictionary']);
require_once('../kernel/header.php');

$config = DictionaryConfig::load();
$Template = new FileTemplate('dictionary/dictionary.tpl');

$get_l_error = retrieve(GET, 'erroru', '');

if (!empty($get_l_error))
	$Template->put('MSG', MessageHelper::display($LANG[$get_l_error], MessageHelper::WARNING));

if (retrieve(GET, 'add', false) || retrieve(POST, 'previs', false) || retrieve(POST, 'valid', false) || $id_get = retrieve(GET, 'edit', 0, TINTEGER))// ajout, previsualisation,edition.
{
	$user_id = AppContext::get_current_user()->get_id();
	if (!(DictionaryAuthorizationsService::check_authorizations()->write() || DictionaryAuthorizationsService::check_authorizations()->contribution() || DictionaryAuthorizationsService::check_authorizations()->moderation()))
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}

	$result_cat = $result = PersistenceContext::get_querier()->select("SELECT id, name
	FROM ".PREFIX."dictionary_cat
	ORDER BY id");
	while ($row_cat = $result_cat->fetch())
	{ 
		$Template->assign_block_vars('cat_list_add', array(
			'VALUE' => $row_cat['id'],
			'NAME' => TextHelper::strtoupper(stripslashes($row_cat['name'])),
		));
	}
	$result_cat->dispose();
	
	$contents_editor = AppContext::get_content_formatting_service()->get_default_editor();
	$contents_editor->set_identifier('contents', $config->get_forbidden_tags());
	
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
		'C_EDIT' => DictionaryAuthorizationsService::check_authorizations()->write() || DictionaryAuthorizationsService::check_authorizations()->contribution(),
		'TITLE' => $LANG['dictionary'],
		'KERNEL_EDITOR' => $contents_editor->display()
	));

	$c_contrib = !DictionaryAuthorizationsService::check_authorizations()->write() && DictionaryAuthorizationsService::check_authorizations()->contribution();

	$Template->put_all(array(
		'C_CONTRIBUTION' => $c_contrib,
		'L_CONTRIBUTION' => $LANG['dictionary_contribution_legend'],
		'L_CONTRIBUTION_NOTICE' => $LANG['dictionary_contribution_notice'],
		'L_CONTRIBUTION_COUNTERPART' => $LANG['dictionary_contribution_counterpart'],
		'L_CONTRIBUTION_COUNTERPART_EXPLAIN' => $LANG['dictionary_contribution_counterpart_explain'],
		'CONTRIBUTION_COUNTERPART_EDITOR' => $counterpart_editor->display(),
		'REWRITE'=> (int)ServerEnvironmentConfig::load()->is_url_rewriting_enabled(),
		'C_APPROVED' => TRUE
	));
	
	if (retrieve(POST, 'previs', false)) // prévisualisation
	{
		$word = retrieve(POST, 'word', 'word', TSTRING);
		$contents = retrieve(POST, 'contents', '', TSTRING_AS_RECEIVED);
		$contents_preview = retrieve(POST, 'contents', '' , TSTRING_UNCHANGE);
		$category_id = retrieve(POST,'category_add','',TINTEGER);
		$id = retrieve(POST,'dictionary_id','');
		
		$cat_name = '';
		try {
			$cat_name = PersistenceContext::get_querier()->get_column_value(DictionarySetup::$dictionary_cat_table, 'name', 'WHERE id = ' . $category_id);
		} catch (RowNotFoundException $e) {}
		$Template->put_all(array(
			'C_ARTICLES_PREVIEW' => true,
			'WORD' => stripslashes($word),
			'ID' => $id,
			'L_PREVISUALIATION' => $LANG['previsualisation'],
			'CONTENTS_PRW' => FormatingHelper::second_parse(stripslashes(FormatingHelper::strparse($contents,$config->get_forbidden_tags()))),
			'CONTENTS' => $contents_preview,
			'NAME_CAT_SELECT'=>$cat_name,
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
		$contents = FormatingHelper::second_parse(stripslashes(FormatingHelper::strparse($contents,$config->get_forbidden_tags())));
		
		$row = $row1 = '';
		try {
			$row = PersistenceContext::get_querier()->select_single_row(DictionarySetup::$dictionary_table, array('id', 'word', 'description'), 'WHERE id=:id', array('id' => $id));
		} catch (RowNotFoundException $e) {}
		
		try {
			$row1 = PersistenceContext::get_querier()->select_single_row(DictionarySetup::$dictionary_table, array('id', 'word', 'description', 'approved'), 'WHERE word=:word', array('word' => $word));
		} catch (RowNotFoundException $e) {}
		
		if ($row && $row['id'] != '')
		{
			PersistenceContext::get_querier()->update(DictionarySetup::$dictionary_table, array(
				'cat' => addslashes($contents_cat),
				'description' => addslashes($contents),
				'word' => addslashes($word),
				'approved' => (int)DictionaryAuthorizationsService::check_authorizations()->write(),
				'timestamp' => $timestamp
			), 'WHERE id=:id', array('id' => $row['id']));
			
			$contributions = ContributionService::find_by_criteria('dictionary', $row['id']);
			
			if (count($contributions) > 0)
			{
				foreach( $contributions as $contribution) {
					$contribution->set_status(CONTRIBUTION_STATUS_PROCESSED);
					ContributionService::save_contribution($contribution);
				}
			}
			DictionaryCache::invalidate();
			AppContext::get_response()->redirect(HOST . DIR . '/dictionary/dictionary.php');
		}
		elseif ($row1 && $row1['id'] != "")
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
			$result = PersistenceContext::get_querier()->insert(DictionarySetup::$dictionary_table, array(
				'word' => addslashes($word),
				'cat' => addslashes($contents_cat),
				'description' => addslashes($contents),
				'user_id' => $user_id,
				'approved' => (int)DictionaryAuthorizationsService::check_authorizations()->write(),
				'timestamp' => $timestamp
			));
			
			$last_msg_id = $result->get_last_inserted_id();
			
			if (!DictionaryAuthorizationsService::check_authorizations()->write() && DictionaryAuthorizationsService::check_authorizations()->contribution()) 
			{
				$dictionary_contribution = new Contribution();
				$dictionary_contribution->set_id_in_module($last_msg_id);
				$dictionary_contribution->set_description(retrieve(POST, 'counterpart', '', TSTRING_PARSE));
				$dictionary_contribution->set_entitled(sprintf($LANG['dictionary_contribution_entitled'], $last_msg_id));
				$dictionary_contribution->set_fixing_url('/dictionary/dictionary.php?edit=' . $last_msg_id);
				$dictionary_contribution->set_poster_id($user_id);
				$dictionary_contribution->set_module('dictionary');
				$dictionary_contribution->set_auth(Authorizations::capture_and_shift_bit_auth($config->get_authorizations(), DictionaryAuthorizationsService::MODERATION_AUTHORIZATIONS, Contribution::CONTRIBUTION_AUTH_BIT));
				ContributionService::save_contribution($dictionary_contribution);
				AppContext::get_response()->redirect(HOST . DIR . '/dictionary/contribution.php');
				exit;
			}
			DictionaryCache::invalidate();
		}
		AppContext::get_response()->redirect(HOST . DIR . '/dictionary/dictionary.php');
	}
	elseif ($id_get = retrieve(GET, 'edit', 0, TINTEGER)) // édition
	{
		if (!DictionaryAuthorizationsService::check_authorizations()->moderation())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		
		$row = PersistenceContext::get_querier()->select_single_row_query("SELECT q.*, m.display_name AS mlogin,c.id AS cat,c.name AS cat_name
		FROM ".PREFIX."dictionary q
		LEFT JOIN ".PREFIX."member m ON m.user_id = q.user_id
		LEFT JOIN ".PREFIX."dictionary_cat c ON q.cat = c.id
		WHERE q.id = :id", array('id' => $id_get));

		if (empty($row)) {
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		$Template->put_all(array(
			'C_EDIT' => TRUE,
			'C_CONTRIBUTION' => FALSE,
			'C_APPROVED' => !empty($row['approved']),
			'ID' => $id_get,
			'CONTENTS' => FormatingHelper::unparse(stripslashes($row['description'])),
			'WORD' => stripslashes($row['word']),
			'APPROVED' => '',
			'ID_CAT_SELECT' => $row['cat'],
			'NAME_CAT_SELECT' => $row['cat_name'],
		));
	}
	DictionaryCache::invalidate(); //Régénération du cache
	$Template->display();
}
elseif ($id_get = retrieve(GET, 'del', 0, TINTEGER))//Supression
{
	AppContext::get_session()->csrf_get_protect();
	$nb_word = PersistenceContext::get_querier()->count(DictionarySetup::$dictionary_table, "WHERE (approved = 1)");
	if ($nb_word == 1 )
	{
		AppContext::get_response()->redirect(HOST . DIR . '/dictionary/dictionary' . url('.php?erroru=' . "del_word") . '#errorh');
	}
	else
	{
		if (!DictionaryAuthorizationsService::check_authorizations()->moderation())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		PersistenceContext::get_querier()->delete(DictionarySetup::$dictionary_table, 'WHERE id=:id', array('id' => $id_get));
		DictionaryCache::invalidate(); //Régénération du cache du mini-module.
		AppContext::get_response()->redirect(HOST . DIR . '/dictionary/dictionary.php');
	}
}
else // Affichage
{
	$modulesLoader = AppContext::get_extension_provider_service();
	$module = $modulesLoader->get_provider('dictionary');
	echo $module->get_extension_point(HomePageExtensionPoint::EXTENSION_POINT)->get_home_page()->get_view()->display();
}

require_once('../kernel/footer.php'); 

?>