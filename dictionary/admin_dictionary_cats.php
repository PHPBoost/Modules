<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 13
 * @since       PHPBoost 2.0 - 2012 11 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../admin/admin_begin.php');

$lang = LangLoader::get_all_langs('dictionary');

define('TITLE', $lang['dictionary.module.title']);
require_once('../admin/admin_header.php');

$config = DictionaryConfig::load();

$view = new FileTemplate('dictionary/admin_dictionary_cats.tpl');
$view->add_lang($lang);

$get_error = retrieve(GET, 'error', '');
$get_l_error = retrieve(GET, 'erroru', '');

$categories_number = PersistenceContext::get_querier()->count(DictionarySetup::$dictionary_cat_table);

$page = AppContext::get_request()->get_getint('p', 1);
$pagination = new ModulePagination($page, $categories_number, $config->get_items_per_page());
$pagination->set_url(new Url('/dictionary/admin_dictionary_cats.php?p=%d'));

if ($pagination->current_page_is_empty() && $page > 1)
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

$view->put_all(array(
	'C_PAGINATION' => $pagination->has_several_pages(),

	'PAGINATION' => $pagination->display(),
));

if (!empty($get_l_error))
{
	$view->put('MESSAGE_HELPER', MessageHelper::display($lang[$get_l_error], MessageHelper::WARNING));
	$name = "";
	$id = "";
}

if (retrieve(GET, 'add', false))
{
	$row = '';
	if (!empty($get_l_error))
	{
		$view->put('MESSAGE_HELPER', MessageHelper::display($lang[$get_l_error], MessageHelper::WARNING));
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
		$view->put('MESSAGE_HELPER', MessageHelper::display($lang['warning.invalid.picture'], MessageHelper::NOTICE));
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

	$img = $row && !empty($row['images']) ? $row['images'] : '';

	$server_img = '<option value="">--</option>';
	$image_folder_path = new Folder(PATH_TO_ROOT . '/dictionary/templates/images');
	foreach ($image_folder_path->get_files('`\.(png|webp|jpg|bmp|gif|svg)$`iu') as $image)
	{
		$file = $image->get_name();
		$selected = ('../dictionary/templates/images/' . $file == $img) ? ' selected="selected"' : '';
		$server_img .= '<option value="' . $file . '"' . $selected . '>' . $file . '</option>';
	}

	$view->assign_block_vars('add',array(
		'C_IS_PICTURE' => $row && !empty($row['images']),
		'CATEGORIES_LIST' => false,
		'CATEGORY_ID' => $id,
		'CATEGORY_NAME' => $name,
		'U_CATEGORY_IMAGE' => $img,
		'CATEGORY_IMAGES_LIST' => $server_img
	));

	$view->display();

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
				$Upload->file('images', '`([a-z0-9()_-])+\.(jpg|gif|png|webp|bmp|svg)+$`iu', Upload::UNIQ_NAME, 20*1024);
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
			$path = TextHelper::strprotect(retrieve(POST, 'image', ''));
			$error = $Upload->check_img(16, 16,false);
			if (!empty($error)) //Erreur, on arrête ici
				AppContext::get_response()->redirect(HOST . DIR . '/dictionary/admin_dictionary_cats' . url('.php?add=1&erroru=' . $error) . '#message_helper&id_cat='.$id_cat);
			else
				$cat_img = $dir . $path; //image uploadé et validé.
		}
		$cat_img = !empty($cat_img) ? $cat_img : (!empty($row['images']) ? $row['images'] : '');
		$name_cat = retrieve(POST,'name_cat','',TSTRING);

		PersistenceContext::get_querier()->update(DictionarySetup::$dictionary_cat_table, array(
			'name' => addslashes(TextHelper::strtoupper($name_cat)),
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
				$Upload->file('images', '`([a-z0-9()_-])+\.(jpg|gif|png|webp|bmp)+$`iu',Upload::UNIQ_NAME, 20*1024);
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
			$path = TextHelper::strprotect(retrieve(POST, 'image', ''));
			$error = $Upload->check_img(16, 16,false);
			if (!empty($error)) //Erreur, on arrête ici
				AppContext::get_response()->redirect(HOST . DIR . '/dictionary/admin_dictionary_cats' . url('.php?add=1&erroru=' . $error) . '#message_helper');
			else
				$cat_img = $dir . $path; //image uploadée et validée.
		}
		$name_cat = retrieve(POST,'name_cat','',TSTRING);

		PersistenceContext::get_querier()->insert(DictionarySetup::$dictionary_cat_table, array(
			'name' => addslashes(TextHelper::strtoupper($name_cat)),
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
		if (retrieve(POST,'category-move',0,TINTEGER))
		{
			$id_move = retrieve(POST,'category-move',0,TINTEGER);
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
	$categories_number = PersistenceContext::get_querier()->count(DictionarySetup::$dictionary_cat_table);
	$items_number = PersistenceContext::get_querier()->count(DictionarySetup::$dictionary_table, "WHERE cat = :id", array('id' =>$id_del));
	if ($categories_number == 1 )
	{
		AppContext::get_response()->redirect(HOST . DIR . '/dictionary/admin_dictionary_cats' . url('.php?erroru=' . "del_cat") . '#errorh');
	}
	elseif ($items_number > 0)
	{
		$row_name = PersistenceContext::get_querier()->select_single_row(DictionarySetup::$dictionary_cat_table, array('id', 'name', 'images'), 'WHERE id=:id', array('id' => $id_del));
		$view->put_all(array(
			'C_DELETE_CATEGORY' => true,
			'CATEGORY_ID' => $id_del,
			'CATEGORY_NAME' => $row_name['name'],
		));

		$result = PersistenceContext::get_querier()->select("SELECT id, name
		FROM ".PREFIX."dictionary_cat
		WHERE `id` != '" . $id_del . "'
		ORDER BY name");
		while ($row = $result->fetch())
		{
			$view->assign_block_vars('cat_list', array(
				'CATEGORY_NAME' => TextHelper::strtoupper($row['name']),
				'CATEGORY_ID' => $row['id']
			));
		}
		$result->dispose();

		$view->display();
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
		$view->assign_block_vars('cat', array(
			'CATEGORY_NAME' => TextHelper::strtoupper($row_cat['name']),
			'CATEGORY_IMAGE' => $img,
			'CATEGORY_ID' => $row_cat['id']
		));
	}
	$result_cat->dispose();

	$view->put_all( array(
		'CATEGORIES_LIST' => true,
	));

	$view->display();
}

require_once('../admin/admin_footer.php');

?>
