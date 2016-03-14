<?php
/*##################################################
 *                              admin_dictionary_cats.php
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

require_once('../admin/admin_begin.php');
load_module_lang('dictionary'); //Chargement de la langue du module.
define('TITLE', $LANG['dictionary']);
require_once('../admin/admin_header.php');

$config = DictionaryConfig::load();

$Template = new FileTemplate('dictionary/admin_dictionary_cats.tpl');

$get_error = retrieve(GET, 'error', '');
$get_l_error = retrieve(GET, 'erroru', '');

$nb_cat = PersistenceContext::get_querier()->count(DictionarySetup::$dictionary_cat_table);

$page = AppContext::get_request()->get_getint('p', 1);
$pagination = new ModulePagination($page, $nb_cat, $config->get_items_number_per_page());
$pagination->set_url(new Url('/dictionary/admin_dictionary_cats.php?p=%d'));

if ($pagination->current_page_is_empty() && $page > 1)
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

$Template->put_all(array(
	'L_SUBMIT' => $LANG['submit'],
	'L_RESET' => $LANG['reset'],
	'L_DICTIONARY_MANAGEMENT' => $LANG['dictionary_management'],
	'L_DICTIONARY_ADD' => $LANG['create_dictionary'],
	'L_DICTIONARY_CATS' => $LANG['dictionary_cats'],
	'L_DICTIONARY_CATS_ADD' => $LANG['dictionary_cats_add'],
	'L_LIST_DEF' => $LANG['list_def'],
	'ALERT_DEL' => $LANG['alert_del'],
	'L_GESTION_CAT' => $LANG['gestion_cat'],
	'L_VAL_INC' => $LANG['value_incorrect'],
	'C_PAGINATION' => $pagination->has_several_pages(),
	'PAGINATION' => $pagination->display()
));

if (!empty($get_l_error))
{
	$Template->put('MSG', MessageHelper::display($LANG[$get_l_error], MessageHelper::WARNING));
	$name = "";
	$id = "";
}

if (retrieve(GET,'add',false))
{
	$row = '';
	if (!empty($get_l_error))
	{
		$Template->put('MSG', MessageHelper::display($LANG[$get_l_error], MessageHelper::WARNING)); 
		$name = "";
		
		if ($id = retrieve(GET,'id_cat',false,TINTEGER))
		{
			try {
				$row = PersistenceContext::get_querier()->select_single_row(DictionarySetup::$dictionary_cat_table, array('id', 'name', 'images'), 'WHERE id=:id', array('id' => $id));
			} catch (RowNotFoundException $e) {}
			$name = $row ? $row['name'] : '';
		}
		else
		{
			$id="";
		}
	
	}
	elseif (!empty($errstr))
	{
		$Template->put('MSG', MessageHelper::display($LANG['error_upload_img'], MessageHelper::NOTICE));
		$name = "";
		$id = "";
	}
	elseif ($id = retrieve(GET,'id',false,TINTEGER)){
		try {
			$row = PersistenceContext::get_querier()->select_single_row(DictionarySetup::$dictionary_cat_table, array('id', 'name', 'images'), 'WHERE id=:id', array('id' => $id));
		} catch (RowNotFoundException $e) {}
		$name = $row ? $row['name'] : '';
	}
	else
	{
		$name = "";
	}
	$img = $row && !empty($row['images']) ? '<img src="' . $row['images'] . '" alt="' . $row['images'] . '" />' : '<i class="fa fa-folder"></i>';
	$Template->assign_block_vars('add',array(
		'LIST_CAT' => false,
		'ID_CAT' => $id,
		'NAME_CAT' => $name,
		'IMAGES' => $img,
		'L_NAME_CAT' => $LANG['name_cat'],
		'L_SUBMIT' => $LANG['submit'],
		'L_RESET' => $LANG['reset'],
		'L_VALIDATION' => $LANG['validation'],
		'L_CATEGORY' => $LANG['category'],
		'L_IMAGE' => $LANG['image'],
		'L_IMAGE_A' => $LANG['image_a'],
		'L_IMAGE_UP' => $LANG['image_up'],
		'L_WEIGHT_MAX' => $LANG['weight_max'],
		'L_HEIGHT_MAX' => $LANG['height_max'],
		'L_WIDTH_MAX' => $LANG['width_max'],
		'L_IMAGE_UP_ONE' => $LANG['image_up_one'],
		'L_IMAGE_SERV' => $LANG['image_server'],
		'L_IMAGE_LINK' => $LANG['image_link'],
		'L_IMAGE_ADR' => $LANG['image_adr'],
	));
	
	$Template->display();

	if (retrieve(POST,'valid',false) && $id_cat = retrieve(POST,'id_cat',false,TINTEGER))
	{
		try {
			$row = PersistenceContext::get_querier()->select_single_row(DictionarySetup::$dictionary_cat_table, array('id', 'name', 'images'), 'WHERE id=:id', array('id' => $id_cat));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		$dir = PATH_TO_ROOT . '/dictionary/templates/images/';
		$Upload = new Upload($dir);
		if (is_writable($dir))
		{
			if ($_FILES['images']['size'] > 0)
			{
				$Upload->file('images', '`([a-z0-9()_-])+\.(jpg|gif|png|bmp)+$`i', Upload::UNIQ_NAME, 20*1024);
				if (!empty($Upload->error)) //Erreur, on arrête ici
				{
					AppContext::get_response()->redirect(HOST . DIR . '/dictionary/admin_dictionary_cats' . url('.php?add=1&id_cat='.$id_cat.'&erroru=' . $Upload->error) . '#message_helper');
				}
				else
				{
					$path = $dir . $Upload->get_filename();
					$error = $Upload->check_img(16, 16, Upload::DELETE_ON_ERROR);
					if (!empty($error)) //Erreur, on arrête ici
					{
						AppContext::get_response()->redirect(HOST . DIR . '/dictionary/admin_dictionary_cats' . url('.php?add=1&id_cat='.$id_cat.'&erroru=' . $error) . '#message_helper');
					}
					else
					{
						$cat_img = $path; //image uploadé et validé.
					}
				}
			}
		}
		if (retrieve(POST, 'image', ''))
		{
			$path = strprotect(retrieve(POST, 'image', ''));
			$error = $Upload->check_img(16, 16,false);
			if (!empty($error)) //Erreur, on arrête ici
				AppContext::get_response()->redirect(HOST . DIR . '/dictionary/admin_dictionary_cats' . url('.php?add=1&erroru=' . $error) . '#message_helper&id_cat='.$id_cat);
			else
				$cat_img = $path; //image uploadé et validé.
		}
		$cat_img = !empty($cat_img) ? $cat_img : (!empty($row['images']) ? $row['images'] : '');
		$name_cat = retrieve(POST,'name_cat','',TSTRING);
		
		PersistenceContext::get_querier()->update(DictionarySetup::$dictionary_cat_table, array(
			'name' => addslashes(strtoupper($name_cat)),
			'images' => addslashes($cat_img)
		), 'WHERE id=:id', array('id' => $id_cat));
		
		AppContext::get_response()->redirect(HOST . SCRIPT);
	}
	elseif(retrieve(POST,'valid',false))
	{
		$cat_img = '';
		$dir = PATH_TO_ROOT . '/dictionary/templates/images/';
		$Upload = new Upload($dir);
		if (is_writable($dir))
		{
			if ($_FILES['images']['size'] > 0)
			{
				$Upload->file('images', '`([a-z0-9()_-])+\.(jpg|gif|png|bmp)+$`i',Upload::UNIQ_NAME, 20*1024);
				if ($Upload->get_error() != '') //Erreur, on arrête ici
				{
					AppContext::get_response()->redirect(HOST . DIR . '/dictionary/admin_dictionary_cats' . url('.php?add=1&erroru=' . $Upload->error) . '#message_helper');
				}
				else
				{
					$path = $dir . $Upload->get_filename();
					$error = $Upload->check_img(16, 16, Upload::DELETE_ON_ERROR);
					if (!empty($error)) //Erreur, on arrête ici
					{
						AppContext::get_response()->redirect(HOST . DIR . '/dictionary/admin_dictionary_cats' . url('.php?add=1&erroru=' . $error) . '#message_helper');
					}
					else
					{
						$cat_img = $path; //image uploadé et validé.
					}
				}
			}
		}
		if (retrieve(POST, 'image', ''))
		{
			$path = strprotect(retrieve(POST, 'image', ''));
			$error = $Upload->check_img(16, 16,false);
			if (!empty($error)) //Erreur, on arrête ici
				AppContext::get_response()->redirect(HOST . DIR . '/dictionary/admin_dictionary_cats' . url('.php?add=1&erroru=' . $error) . '#message_helper');
			else
				$cat_img = $path; //image uploadé et validé.
		}
		$name_cat = retrieve(POST,'name_cat','',TSTRING);
		
		PersistenceContext::get_querier()->insert(DictionarySetup::$dictionary_cat_table, array(
			'name' => addslashes(strtoupper($name_cat)),
			'images' => addslashes($cat_img)
		));
		
		AppContext::get_response()->redirect(HOST . SCRIPT);
	}
}
elseif (retrieve(POST,'cat_to_del',0,TINTEGER) && retrieve(POST,'id_del_a',0,TINTEGER) && retrieve(POST,'submit',false))
{
	AppContext::get_session()->csrf_get_protect();
	$id_del = retrieve(POST,'id_del_a',0,TINTEGER);
	$action = retrieve(POST, 'action', '');
	
	$delete_content = ($action && $action == 'move') ? false : true;
	if ($delete_content)
	{
		PersistenceContext::get_querier()->delete(DictionarySetup::$dictionary_table, 'WHERE cat=:id', array('id' => $id_del));
		PersistenceContext::get_querier()->delete(DictionarySetup::$dictionary_cat_table, 'WHERE id=:id', array('id' => $id_del));
		AppContext::get_response()->redirect(HOST . DIR . '/dictionary/admin_dictionary_cats.php');
	}
	else
	{
		if (retrieve(POST,'categorie_move',0,TINTEGER))
		{
			$id_move = retrieve(POST,'categorie_move',0,TINTEGER);
			$result = PersistenceContext::get_querier()->select("SELECT id, cat
			FROM ".PREFIX."dictionary
			WHERE `cat`  = '" . $id_del . "'
			ORDER BY id");
			while ($row = $result->fetch())
			{ 
				PersistenceContext::get_querier()->update(DictionarySetup::$dictionary_table, array('cat' => addslashes($id_move)), 'WHERE id=:id', array('id' => $row['id']));
			}
			$result->dispose();
			
			PersistenceContext::get_querier()->delete(DictionarySetup::$dictionary_cat_table, 'WHERE id=:id', array('id' => $id_del));
		}
		AppContext::get_response()->redirect(HOST . DIR . '/dictionary/admin_dictionary_cats.php');
	}
}
elseif (retrieve(GET,'del',false) && $id_del = retrieve(GET,'id',false,TINTEGER))
{
	AppContext::get_session()->csrf_get_protect();
	$nb_cat = PersistenceContext::get_querier()->count(DictionarySetup::$dictionary_cat_table);
	$nb_word = PersistenceContext::get_querier()->count(DictionarySetup::$dictionary_table, "WHERE cat = :id", array('id' =>$id_del));
	if ($nb_cat == 1 )
	{
		AppContext::get_response()->redirect(HOST . DIR . '/dictionary/admin_dictionary_cats' . url('.php?erroru=' . "del_cat") . '#errorh');
	}
	elseif ($nb_word > 0)
	{
		$Template->put_all(array(
			'DEL_CAT_NOEMPTY' => true,
			'ID_DEL' => $id_del,
			'L_DEL_CAT' => $LANG['del_cat'],
			'L_DEL_TEXT' => $LANG['del_text'], 
			'L_DEL_CAT_DEF' => $LANG['del_cat_def'] ,
			'L_MOVE' => $LANG['move'],
			'L_WARNING_DEL' => $LANG['warning_del'],
		));
		
		$result = PersistenceContext::get_querier()->select("SELECT id, name
		FROM ".PREFIX."dictionary_cat
		WHERE `id` != '" . $id_del . "'
		ORDER BY name");
		while ($row = $result->fetch())
		{ 
			$Template->assign_block_vars('cat_list', array(
				'NAME' => strtoupper($row['name']),
				'ID' => $row['id']
			));
		}
		$result->dispose();
		
		$Template->display();
	}
	else
	{
		PersistenceContext::get_querier()->delete(DictionarySetup::$dictionary_cat_table, 'WHERE id=:id', array('id' => $id_del));
		AppContext::get_response()->redirect(HOST . SCRIPT);
	}
}
else
{
	$result_cat = PersistenceContext::get_querier()->select("SELECT id, name,images
	FROM ".PREFIX."dictionary_cat
	ORDER BY name
	LIMIT :number_items_per_page OFFSET :display_from", array(
		'number_items_per_page' => $pagination->get_number_items_per_page(),
		'display_from' => $pagination->get_display_from()
	));
	
	while ($row_cat = $result_cat->fetch())
	{ 
		$img = empty($row_cat['images']) ? '<i class="fa fa-folder"></i>' : '<img src="' . $row_cat['images'] . '" alt="' . $row_cat['images'] . '" />';
		$Template->assign_block_vars('cat', array(
			'NAME' => strtoupper($row_cat['name']),
			'IMAGES' => $img,
			'ID_CAT' => $row_cat['id']
		));
	}
	$result_cat->dispose();

	$Template->put_all( array(
		'LIST_CAT' => true,
	));
	
	$Template->display();
}

require_once('../admin/admin_footer.php');

?>