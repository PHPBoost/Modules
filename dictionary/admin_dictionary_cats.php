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
require_once('../dictionary/dictionary_begin.php');
require_once('../admin/admin_header.php');

$Cache->load('dictionary');

$Template = new FileTemplate('dictionary/admin_dictionary_cats.tpl');

$get_error = retrieve(GET, 'error', '');
$get_l_error = retrieve(GET, 'erroru', '');

$nb_cat = $Sql->count_table(PREFIX . "dictionary_cat", __LINE__, __FILE__);

$page = AppContext::get_request()->get_getint('p', 1);
$pagination = new ModulePagination($page, $nb_cat, $CONFIG_DICTIONARY['pagination']);
$pagination->set_url(new Url('/dictionary/admin_dictionary_cats.php?p=%d'));

if ($pagination->current_page_is_empty() && $page > 1)
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

$Template->put_all(array(
	'L_DICTIONARY_CONFIG' => $LANG['dictionary_config'],
	'L_SUBMIT' => $LANG['submit'],
	'L_RESET' => $LANG['reset'],
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
	$Template->put('MSG', MessageHelper::display($LANG[$get_l_error], E_USER_WARNING));
	$name = "";
	$id = "";
}

if (retrieve(GET,'add',false))
{
	if (!empty($get_l_error))
	{
		$Template->put('MSG', MessageHelper::display($LANG[$get_l_error], E_USER_WARNING)); 
		$name = "";
		
		if ($id = retrieve(GET,'id_cat',false,TINTEGER))
		{
			$row = $Sql->query_array(PREFIX."dictionary_cat", "id", "name","images", "WHERE id = '" . $id . "'", __LINE__, __FILE__);
			$name = $row['name'];
		}
		else
		{
			$id="";
		}
	
	}
	elseif (!empty($errstr))
	{
		$Template->put('MSG', MessageHelper::display($LANG['error_upload_img'], E_USER_NOTICE));
		$name = "";
		$id = "";
	}
	elseif ($id = retrieve(GET,'id',false,TINTEGER)){
		$row = $Sql->query_array(PREFIX."dictionary_cat", "id", "name","images", "WHERE id = '" . $id . "'", __LINE__, __FILE__);
		$name = $row['name'];
	}
	else
	{
		$name = "";
	}
	$img = empty($row['images']) ? '<i class="fa fa-folder"></i>' : '<img src="' . $row['images'] . '" alt="' . $row['images'] . '" />';
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
		$row = $Sql->query_array(PREFIX."dictionary_cat", "id", "name","images", "WHERE id = '" . $id_cat . "'", __LINE__, __FILE__);
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
		if (!empty($_POST['image']))
		{
			$path = strprotect($_POST['image']);
			$error = $Upload->check_img(16, 16,false);
			if (!empty($error)) //Erreur, on arrête ici
				AppContext::get_response()->redirect(HOST . DIR . '/dictionary/admin_dictionary_cats' . url('.php?add=1&erroru=' . $error) . '#message_helper&id_cat='.$id_cat);
			else
				$cat_img = $path; //image uploadé et validé.
		}
		$cat_img = !empty($cat_img) ? $cat_img : (!empty($row['images']) ? $row['images'] : PATH_TO_ROOT . "/templates/". $User->get_theme() ."/images/upload/folder.png");
		$name_cat = retrieve(POST,'name_cat','',TSTRING);
		$Sql->query_inject("UPDATE " . PREFIX . "dictionary_cat SET name ='".addslashes($name_cat)."', images ='".addslashes($cat_img)."' WHERE id = '" . $id_cat . "'", __LINE__, __FILE__);
		AppContext::get_response()->redirect(HOST . SCRIPT . SID2);	
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
		if (!empty($_POST['image']))
		{
			$path = strprotect($_POST['image']);
			$error = $Upload->check_img(16, 16,false);
			if (!empty($error)) //Erreur, on arrête ici
				AppContext::get_response()->redirect(HOST . DIR . '/dictionary/admin_dictionary_cats' . url('.php?add=1&erroru=' . $error) . '#message_helper');
			else
				$cat_img = $path; //image uploadé et validé.
		}
		$name_cat = retrieve(POST,'name_cat','',TSTRING);
		$Sql->query_inject("INSERT INTO `".PREFIX."dictionary_cat` SET
				`name` = '".addslashes(strtoupper($name_cat))."',
				`images`='".addslashes($cat_img)."'
				"
				, __LINE__, __FILE__);
				
		AppContext::get_response()->redirect(HOST . SCRIPT . SID2);
	}
}
elseif (retrieve(POST,'cat_to_del',0,TINTEGER) && retrieve(POST,'id_del_a',false,TINTEGER) && retrieve(POST,'submit',false))
{
	$Session->csrf_get_protect();
	$id_del = retrieve(POST,'id_del_a',false,TINTEGER);
	$delete_content = (!empty($_POST['action']) && $_POST['action'] == 'move') ? false : true;
	if ($delete_content)
	{
		$Sql->query_inject("DELETE FROM " . PREFIX . "dictionary WHERE cat = '" . $id_del . "'", __LINE__, __FILE__);
		$Sql->query_inject("DELETE FROM " . PREFIX . "dictionary_cat WHERE id = '" . $id_del . "'", __LINE__, __FILE__);
		AppContext::get_response()->redirect(HOST . DIR . '/dictionary/admin_dictionary_cats.php');
	}
	else
	{
		if (retrieve(POST,'categorie_move',false,TINTEGER))
		{
			$id_move = retrieve(POST,'categorie_move',false,TINTEGER);
			$result = $Sql->query_while("SELECT id, cat
			FROM ".PREFIX."dictionary
			WHERE `cat`  = '" . $id_del . "'
			ORDER BY id", __LINE__, __FILE__);
			while( $row = $Sql->fetch_assoc($result))
			{ 
				$Sql->query_inject("UPDATE " . PREFIX . "dictionary SET cat ='".addslashes($id_move)."' WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);

			}	
			$Sql->query_inject("DELETE FROM " . PREFIX . "dictionary_cat WHERE id = '" . $id_del . "'", __LINE__, __FILE__);
		}
		AppContext::get_response()->redirect(HOST . DIR . '/dictionary/admin_dictionary_cats.php');
	}
}
elseif (retrieve(GET,'del',false) && $id_del = retrieve(GET,'id',false,TINTEGER))
{
	$Session->csrf_get_protect();
	$nb_cat = $Sql->count_table(PREFIX . "dictionary_cat", __LINE__, __FILE__);
	$nb_word = $Sql->query("SELECT COUNT(1) AS total FROM ".PREFIX . "dictionary WHERE (cat = '".$id_del."')", __LINE__, __FILE__);
	if ($nb_cat == 1 )
	{	
		AppContext::get_response()->redirect(HOST . DIR . '/dictionary/admin_dictionary_cats' . url('.php?erroru=' . "del_cat") . '#errorh');
	}
	elseif ($nb_word > 0)
	{
		$cat = $Sql->query_while("SELECT id, name
		FROM ".PREFIX."dictionary_cat
		WHERE `id` != '" . $id_del . "'
		ORDER BY name
		", __LINE__, __FILE__);	
		
		$Template->put_all(array(
			'DEL_CAT_NOEMPTY' => true,
			'ID_DEL' => $id_del,
			'L_DEL_CAT' => $LANG['del_cat'],
			'L_DEL_TEXT' => $LANG['del_text'], 
			'L_DEL_CAT_DEF' => $LANG['del_cat_def'] ,
			'L_MOVE' => $LANG['move'],
			'L_DEL' => $LANG['del'],
			'L_WARNING_DEL' => $LANG['warning_del'],
			));	
		
		while( $cat_list = $Sql->fetch_assoc($cat))
		{ 
			$Template->assign_block_vars('cat_list', array(
				'NAME' => strtoupper($cat_list['name']),
				'ID' => $cat_list['id']
				));
		}
		$Template->display();
	}
	else
	{
		$Sql->query_inject("DELETE FROM " . PREFIX . "dictionary_cat WHERE id = '" . $id_del . "'", __LINE__, __FILE__);
		AppContext::get_response()->redirect(HOST . SCRIPT . SID2);
	}
}
else
{
	$result_cat = $Sql->query_while("SELECT id, name,images
	FROM ".PREFIX."dictionary_cat
	ORDER BY name
	". $Sql->limit($pagination->get_display_from(),$CONFIG_DICTIONARY['pagination']), __LINE__, __FILE__);
	
	while( $row_cat = $Sql->fetch_assoc($result_cat))
	{ 
		$img = empty($row_cat['images']) ? '<i class="fa fa-folder"></i>' : '<img src="' . $row_cat['images'] . '" alt="' . $row_cat['images'] . '" />';
		$Template->assign_block_vars('cat', array(
			'NAME' => strtoupper($row_cat['name']),
			'IMAGES' => $img,
			'ID_CAT' => $row_cat['id']
			));
	}

	$Template->put_all( array(
		'LIST_CAT' => true,
		));
	
	$Template->display();
}

require_once('../admin/admin_footer.php');

?>