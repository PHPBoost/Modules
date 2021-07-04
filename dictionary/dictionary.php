<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 07 04
 * @since       PHPBoost 2.0 - 2012 11 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../kernel/begin.php');

$lang = LangLoader::get('common', 'dictionary');

define('TITLE', $lang['dictionary.module.title']);
require_once('../kernel/header.php');

$config = DictionaryConfig::load();
$view = new FileTemplate('dictionary/dictionary.tpl');

$Bread_crumb->add($lang['dictionary.module.title'], url('dictionary.php'));

$get_l_error = retrieve(GET, 'erroru', '');
if (!empty($get_l_error))
{
	$view->put('MESSAGE_HELPER', MessageHelper::display($lang[$get_l_error], MessageHelper::WARNING));
}

if (retrieve(GET, 'add', false) || retrieve(POST, 'preview', false) || retrieve(POST, 'valid', false) || $id_get = retrieve(GET, 'edit', 0, TINTEGER))// ajout, previsualisation,edition.
{
	if(retrieve(GET, 'add', false)) $Bread_crumb->add($lang['dictionary.add.item'], url('dictionary.php?add=1'));
	$view->add_lang(array_merge(
		$lang,
		LangLoader::get('common-lang'),
		LangLoader::get('contribution-lang'),
		LangLoader::get('form-lang'),
		LangLoader::get('warning-lang')
	));
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
		$view->assign_block_vars('cat_list_add', array(
			'VALUE' => $row_cat['id'],
			'NAME'  => stripslashes($row_cat['name']),
		));
	}
	$result_cat->dispose();

	$contents_editor = AppContext::get_content_formatting_service()->get_default_editor();
	$contents_editor->set_identifier('contents', $config->get_forbidden_tags());

	$counterpart_editor = AppContext::get_content_formatting_service()->get_default_editor();
	$counterpart_editor->set_identifier('counterpart');

	$view->put_all(array(
		'C_EDIT'     => DictionaryAuthorizationsService::check_authorizations()->write() || DictionaryAuthorizationsService::check_authorizations()->contribution(),
		'C_ADD_ITEM' => true,

		'KERNEL_EDITOR' => $contents_editor->display(),
	));

	$c_contrib = !DictionaryAuthorizationsService::check_authorizations()->write() && DictionaryAuthorizationsService::check_authorizations()->contribution();

	$view->put_all(array(
		'C_CONTRIBUTION' => $c_contrib,
		'C_APPROVED'     => true,

		'CONTRIBUTION_EDITOR' => $counterpart_editor->display(),
		'REWRITE'             => (int)ServerEnvironmentConfig::load()->is_url_rewriting_enabled(),
	));

	if (retrieve(POST, 'preview', false)) // prévisualisation
	{
		$word = retrieve(POST, 'word', 'word', TSTRING);
		$contents = retrieve(POST, 'description', '', TSTRING_AS_RECEIVED);
		$contents_preview = retrieve(POST, 'description', '' , TSTRING_UNCHANGE);
		$category_id = retrieve(POST,'category_add','',TINTEGER);
		$id = retrieve(POST,'dictionary_id','');

		$cat_name = '';
		try {
			$cat_name = PersistenceContext::get_querier()->get_column_value(DictionarySetup::$dictionary_cat_table, 'name', 'WHERE id = ' . $category_id);
		} catch (RowNotFoundException $e) {}
		$view->put_all(array(
			'C_ITEM_PREVIEW' => true,

			'WORD'            => stripslashes($word),
			'ITEM_ID'         => $id,
			'CONTENT_PREVIEW' => FormatingHelper::second_parse(stripslashes(FormatingHelper::strparse($contents,$config->get_forbidden_tags()))),
			'CONTENT'         => $contents_preview,
			'CATEGORY_NAME'   =>$cat_name,
			'CATEGORY_ID'     =>$category_id,
		));
	}
	elseif (retrieve(POST, 'valid', false)) // ajout
	{
		$timestamp = time();
		$id = retrieve(POST,'dictionary_id','');
		$word = retrieve(POST, 'word', 'word', TSTRING);
		$contents = retrieve(POST, 'description', 'description', TSTRING_AS_RECEIVED);
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
				AppContext::get_response()->redirect(HOST . DIR . '/dictionary/dictionary' . url('.php?erroru=' . "dictionary_word_already_exists") . '#errorh');
			}
			else
			{
				AppContext::get_response()->redirect(HOST . DIR . '/dictionary/dictionary' . url('.php?erroru=' . "dictionary_word_already_exists") . '#errorh');
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
				$dictionary_contribution->set_entitled(sprintf($lang['dictionary.contribution.entitled'], $last_msg_id));
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
		$Bread_crumb->add($lang['dictionary.edit.item'], url('dictionary.php?edit=' . $id_get));
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

		$view->put_all(array(
			'C_EDIT'         => true,
			'C_ADD_ITEM'     => false,
			'C_CONTRIBUTION' => false,
			'C_APPROVED'     => !empty($row['approved']),

			'ITEM_ID'       => $id_get,
			'CONTENT'       => FormatingHelper::unparse(stripslashes($row['description'])),
			'WORD'          => stripslashes($row['word']),
			'APPROVED'      => '',
			'CATEGORY_ID'   => $row['cat'],
			'CATEGORY_NAME' => $row['cat_name'],
		));
	}
	DictionaryCache::invalidate(); //Régénération du cache
	$view->display();
}
elseif ($id_get = retrieve(GET, 'del', 0, TINTEGER))//Supression
{
	AppContext::get_session()->csrf_get_protect();
	$words_number = PersistenceContext::get_querier()->count(DictionarySetup::$dictionary_table, "WHERE (approved = 1)");
	if ($words_number == 1 )
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
