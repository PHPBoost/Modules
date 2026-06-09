<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 2.0 - 2012 11 15
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      mipel <mipel@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

define('PATH_TO_ROOT', '../..');

require_once(PATH_TO_ROOT . '/admin/admin_begin.php');

$lang = LangLoader::get_all_langs('dictionary');

define('TITLE', $lang['dictionary.module.title']);
require_once(PATH_TO_ROOT . '/admin/admin_header.php');

$config = DictionaryConfig::load();

$view = new FileTemplate('dictionary/admin_dictionary_cats.tpl');
$view->add_lang($lang);
$request = AppContext::get_request();

$get_error = $request->get_getvalue('error', '');
$get_l_error = $request->get_getvalue('erroru', '');

$categories_number = PersistenceContext::get_querier()->count(DictionarySetup::$dictionary_cat_table);

$page = AppContext::get_request()->get_getint('p', 1);
$pagination = new ModulePagination($page, $categories_number, $config->get_items_per_page());
$pagination->set_url(new Url('/dictionary/admin_dictionary_cats.php?p=%d'));

if ($pagination->current_page_is_empty() && $page > 1)
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

$view->put_all([
	'C_PAGINATION' => $pagination->has_several_pages(),

	'PAGINATION' => $pagination->display(),
]);

if (!empty($get_l_error))
{
	$view->put('MESSAGE_HELPER', MessageHelper::display($lang[$get_l_error], MessageHelper::WARNING));
	$name = "";
	$id = "";
}

if ($request->get_getvalue('add', false))
{
	$row = '';
	if (!empty($get_l_error))
	{
		$view->put('MESSAGE_HELPER', MessageHelper::display($lang[$get_l_error], MessageHelper::WARNING));
		$name = "";

		if ($id = $request->get_getvalue('id_cat',false,TINTEGER))
		{
			try {
				$row = PersistenceContext::get_querier()->select_single_row(DictionarySetup::$dictionary_cat_table, ['id', 'name', 'images'], 'WHERE id=:id', ['id' => $id]);
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
	elseif ($id = $request->get_getvalue('id',false,TINTEGER)){
		try {
			$row = PersistenceContext::get_querier()->select_single_row(DictionarySetup::$dictionary_cat_table, ['id', 'name', 'images'], 'WHERE id=:id', ['id' => $id]);
		} catch (RowNotFoundException $e) {}
		$name = $row ? $row['name'] : '';
	}
	else
	{
		$name = "";
	}

	$img = $row && !empty($row['images']) ? $row['images'] : '';

	$server_img = '<option value="">--</option>';
	$image_folder_path = new Folder(PATH_TO_ROOT . '/modules/dictionary/templates/images');
	foreach ($image_folder_path->get_files('`\.(png|webp|jpg|bmp|gif|svg)$`iu') as $image)
	{
		$file = $image->get_name();
		$selected = ('../dictionary/templates/images/' . $file == $img) ? ' selected="selected"' : '';
		$server_img .= '<option value="' . $file . '"' . $selected . '>' . $file . '</option>';
	}

	$view->assign_block_vars('add',[
		'C_IS_PICTURE' => $row && !empty($row['images']),
		'CATEGORIES_LIST' => false,
		'CATEGORY_ID' => $id,
		'CATEGORY_NAME' => stripslashes($name),
		'U_CATEGORY_IMAGE' => $img,
		'CATEGORY_IMAGES_LIST' => $server_img
	]);

	$view->display();

	if ($request->get_postvalue('valid',false) && $id_cat = $request->get_postvalue('id_cat',false,TINTEGER))
	{
		try {
			$row = PersistenceContext::get_querier()->select_single_row(DictionarySetup::$dictionary_cat_table, ['id', 'name', 'images'], 'WHERE id=:id', ['id' => $id_cat]);
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		$dir = PATH_TO_ROOT . '/modules/dictionary/templates/images/';
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
		if ($request->get_postvalue('image', ''))
		{
			$path = TextHelper::strprotect($request->get_postvalue('image', ''));
			$error = $Upload->check_img(16, 16,false);
			if (!empty($error)) //Erreur, on arrête ici
				AppContext::get_response()->redirect(HOST . DIR . '/dictionary/admin_dictionary_cats' . url('.php?add=1&erroru=' . $error) . '#message_helper&id_cat='.$id_cat);
			else
				$cat_img = $dir . $path; //image uploadé et validé.
		}
		$cat_img = !empty($cat_img) ? $cat_img : (!empty($row['images']) ? $row['images'] : '');
		$name_cat = $request->get_postvalue('name_cat','',TSTRING);

		PersistenceContext::get_querier()->update(DictionarySetup::$dictionary_cat_table, [
			'name' => addslashes(TextHelper::strtoupper($name_cat)),
			'images' => addslashes($cat_img)
		], 'WHERE id=:id', ['id' => $id_cat]);

		AppContext::get_response()->redirect(HOST . SCRIPT);
	}
	elseif($request->get_postvalue('valid',false))
	{
		$cat_img = '';
		$dir = PATH_TO_ROOT . '/modules/dictionary/templates/images/';
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
		if ($request->get_postvalue('image', ''))
		{
			$path = TextHelper::strprotect($request->get_postvalue('image', ''));
			$error = $Upload->check_img(16, 16,false);
			if (!empty($error)) //Erreur, on arrête ici
				AppContext::get_response()->redirect(HOST . DIR . '/dictionary/admin_dictionary_cats' . url('.php?add=1&erroru=' . $error) . '#message_helper');
			else
				$cat_img = $dir . $path; //image uploadée et validée.
		}
		$name_cat = $request->get_postvalue('name_cat','',TSTRING);

		PersistenceContext::get_querier()->insert(DictionarySetup::$dictionary_cat_table, [
			'name' => $name_cat,
			'images' => $cat_img
		]);

		AppContext::get_response()->redirect(HOST . SCRIPT);
	}
}
elseif ($request->get_postvalue('cat_to_del',0,TINTEGER) && $request->get_postvalue('id_del_a',0,TINTEGER) && $request->get_postvalue('submit',false))
{
	AppContext::get_session()->csrf_get_protect();
	$id_del = $request->get_postvalue('id_del_a',0,TINTEGER);
	$action = $request->get_postvalue('action', '');

	$delete_content = ($action && $action == 'move') ? false : true;
	if ($delete_content)
	{
		PersistenceContext::get_querier()->delete(DictionarySetup::$dictionary_table, 'WHERE cat=:id', ['id' => $id_del]);
		PersistenceContext::get_querier()->delete(DictionarySetup::$dictionary_cat_table, 'WHERE id=:id', ['id' => $id_del]);
		AppContext::get_response()->redirect(HOST . DIR . '/dictionary/admin_dictionary_cats.php');
	}
	else
	{
		if ($request->get_postvalue('category-move',0,TINTEGER))
		{
			$id_move = $request->get_postvalue('category-move',0,TINTEGER);
			$result = PersistenceContext::get_querier()->select("SELECT id, cat
                FROM ".PREFIX."dictionary
                WHERE `cat`  = '" . $id_del . "'
                ORDER BY id"
            );
			while ($row = $result->fetch())
			{
				PersistenceContext::get_querier()->update(DictionarySetup::$dictionary_table, ['cat' => addslashes($id_move)], 'WHERE id=:id', ['id' => $row['id']]);
			}
			$result->dispose();

			PersistenceContext::get_querier()->delete(DictionarySetup::$dictionary_cat_table, 'WHERE id=:id', ['id' => $id_del]);
		}
		AppContext::get_response()->redirect(HOST . DIR . '/dictionary/admin_dictionary_cats.php');
	}
}
elseif ($request->get_getvalue('del', false) && $id_del = $request->get_getvalue('id', false, TINTEGER))
{
	AppContext::get_session()->csrf_get_protect();
	$categories_number = PersistenceContext::get_querier()->count(DictionarySetup::$dictionary_cat_table);
	$items_number = PersistenceContext::get_querier()->count(DictionarySetup::$dictionary_table, "WHERE cat = :id", ['id' =>$id_del]);
	if ($categories_number == 1 )
	{
		AppContext::get_response()->redirect(HOST . DIR . '/dictionary/admin_dictionary_cats' . url('.php?erroru=' . "del_cat") . '#errorh');
	}
	elseif ($items_number > 0)
	{
		$row_name = PersistenceContext::get_querier()->select_single_row(DictionarySetup::$dictionary_cat_table, ['id', 'name', 'images'], 'WHERE id=:id', ['id' => $id_del]);
		$view->put_all([
			'C_DELETE_CATEGORY' => true,
			'CATEGORY_ID' => $id_del,
			'CATEGORY_NAME' => stripslashes($row_name['name']),
		]);

		$result = PersistenceContext::get_querier()->select("SELECT id, name
		FROM ".PREFIX."dictionary_cat
		WHERE `id` != '" . $id_del . "'
		ORDER BY name");
		while ($row = $result->fetch())
		{
			$view->assign_block_vars('cat_list', [
				'CATEGORY_NAME' => stripslashes($row['name']),
				'CATEGORY_ID' => $row['id']
			]);
		}
		$result->dispose();

		$view->display();
	}
	else
	{
		PersistenceContext::get_querier()->delete(DictionarySetup::$dictionary_cat_table, 'WHERE id=:id', ['id' => $id_del]);
		AppContext::get_response()->redirect(HOST . SCRIPT);
	}
}
else
{
	$result_cat = PersistenceContext::get_querier()->select("SELECT id, name,images
	FROM ".PREFIX."dictionary_cat
	ORDER BY name
	LIMIT :number_items_per_page OFFSET :display_from", [
		'number_items_per_page' => $pagination->get_number_items_per_page(),
		'display_from' => $pagination->get_display_from()
	]);

	while ($row_cat = $result_cat->fetch())
	{
		$img = empty($row_cat['images']) ? '<i class="fa fa-folder"></i>' : '<img src="' . $row_cat['images'] . '" alt="' . $row_cat['images'] . '" />';
		$view->assign_block_vars('cat', [
			'CATEGORY_NAME' => stripslashes($row_cat['name']),
			'CATEGORY_IMAGE' => $img,
			'CATEGORY_ID' => $row_cat['id']
		]);
	}
	$result_cat->dispose();

	$view->put_all( [
		'CATEGORIES_LIST' => true,
	]);

	$view->display();
}

require_once(PATH_TO_ROOT . '/admin/admin_footer.php');

?>
