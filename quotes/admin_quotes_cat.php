<?php
/**
 *   admin_quotes_cat.php
 *
 *   @author			alain91
 *   @copyright			(C) 2010 Alain Gandon
 *   @email             alain091@gmail.com
 *   @license          	GPL Version 2
 */

defined('PATH_TO_ROOT') or define('PATH_TO_ROOT', '..');

require_once(PATH_TO_ROOT.'/admin/admin_begin.php');
require_once(PATH_TO_ROOT.'/quotes/quotes_begin.php');
require_once(PATH_TO_ROOT.'/quotes/quotes.inc.php');
require_once(PATH_TO_ROOT.'/admin/admin_header.php');

require_once('admin_quotes_menu.php');

$Cache->load('quotes');

$quotes = new Quotes();
$quotes_categories = new QuotesCats();

$id_up = retrieve(GET, 'id_up', 0);
$id_down = retrieve(GET, 'id_down', 0);
$cat_to_del = retrieve(GET, 'del', 0);
$cat_to_del_post = retrieve(POST, 'cat_to_del', 0);
$id_edit = retrieve(GET, 'edit', 0);
$new_cat = !empty($_GET['new']) ? true : false;
$error = retrieve(GET, 'error', '');

if ($id_up > 0)
{
	$Session->csrf_get_protect();
	$quotes_categories->move($id_up, MOVE_CATEGORY_UP);
	AppContext::get_response()->redirect(url('admin_quotes_cat.php'));
}
elseif ($id_down > 0)
{
	$Session->csrf_get_protect();
	$quotes_categories->move($id_down, MOVE_CATEGORY_DOWN);
	AppContext::get_response()->redirect(url('admin_quotes_cat.php'));
}
elseif ($cat_to_del > 0)
{
	$Template = new FileTemplate('quotes/admin_quotes_cat_remove.tpl');
	
	$Admin_menu = new FileTemplate('quotes/admin_quotes_menu.tpl');
	$Admin_menu->put_all(array(
		'L_QUOTES_MANAGEMENT' => $quotes->lang_get('q_management'),
		'L_CATS_MANAGEMENT' => $quotes->lang_get('q_cat_management'),
		'L_QUOTES_CONFIG' => $quotes->lang_get('q_config'),
		'L_ADD_CATEGORY' => $quotes->lang_get('q_add_category'),
		'U_QUOTES_CONFIG' => url('admin_quotes.php'),
		'U_QUOTES_CATS_MANAGEMENT' => url('admin_quotes_cat.php'),
		'U_QUOTES_ADD_CAT' => url('admin_quotes_cat.php?new=1')
	));
	
	$Template->put_all(array(
		'ADMIN_MENU' => $Admin_menu,
		'CATEGORY_TREE' => $quotes_categories->build_select_form(0, 'id_parent', 'id_parent', $cat_to_del),
		'IDCAT' => $cat_to_del,
		'L_REMOVING_CATEGORY' => $quotes->lang_get('removing_category'),
		'L_EXPLAIN_REMOVING' => $quotes->lang_get('explain_removing_category'),
		'L_DELETE_CATEGORY_AND_CONTENT' => $quotes->lang_get('delete_category_and_its_content'),
		'L_MOVE_CONTENT' => $quotes->lang_get('move_category_content'),
		'L_SUBMIT' => $quotes->lang_get('delete'),
		'U_FORM_TARGET' => HOST . DIR . url('/quotes/admin_quotes_cat.php?token=' . $Session->get_token())
		));
		
	$Template->display();
}
elseif (retrieve(POST, 'submit', false))
{
	$error_string = 'e_success';
	
	//Deleting a category
	if ($cat_to_del_post > 0)
	{
		$action = retrieve(POST, 'action', '');
		$delete_content = $action != 'move';
		$id_parent = retrieve(POST, 'id_parent', 0);
		
		if ($delete_content)
			$quotes_categories->delete_category_recursively($cat_to_del_post);
		else
			$quotes_categories->delete_category_and_move_content($cat_to_del_post, $id_parent);
	}
	else
	{

		$_POST = $quotes->sanitize($_POST);
		
		$id_cat = retrieve(POST, 'idcat', 0);
		$id_parent = retrieve(POST, 'id_parent', 0);
		$name = retrieve(POST, 'name', '');
		$description = retrieve(POST, 'description', '');
		$image = retrieve(POST, 'image', '');
		$icon_path = retrieve(POST, 'alt_image', '');
		$visible = retrieve(POST, 'visible_cat', false);
		$secure = retrieve(POST, 'secure', -1);
		


		if (!empty($icon_path))
			$image = $icon_path;
		
		//Autorisations
		if (!empty($_POST['special_auth']))
		{
			$array_auth_all = Authorizations::build_auth_array_from_form(QUOTES_LIST_ACCESS, QUOTES_WRITE_ACCESS, QUOTES_CONTRIB_ACCESS);
			$new_auth = serialize($array_auth_all);
		}
		else
			$new_auth = '';

		if (empty($name))
			AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=e_required_fields_empty#errorh'), '', '&');

		if ($id_cat > 0)
			$error_string = $quotes_categories->update_category($id_cat, $id_parent, $name, $description, $image, $new_auth, $visible);
		else
			$error_string = $quotes_categories->add_category($id_parent, $name, $description, $image, $new_auth,$visible);
	}

	$Cache->Generate_module_file('quotes');
	
	AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=' . $error_string  . '#errorh'), '', '&');
}
elseif ($new_cat XOR $id_edit > 0)
{
	$Template = new FileTemplate('quotes/admin_quotes_cat_edition.tpl');

	//Images disponibles
	$img_str = '<option value="">--</option>';
	$in_dir_icon = false;
	$image_folder_path = new Folder('./');
	foreach ($image_folder_path->get_files('`\.(png|jpg|bmp|gif|jpeg|tiff)$`i') as $images)
	{
		$image = $images->get_name();
		if ($id_edit > 0 && $QUOTES_CAT[$id_edit]['image'] == $image)
		{
			$img_str .= '<option selected="selected" value="' . $image . '">' . $image . '</option>'; //On ajoute l'image sélectionnée
			$in_dir_icon = true;
		}
		else
			$img_str .= '<option value="' . $image . '">' . $image . '</option>'; //On ajoute l'image non sélectionnée
	}
	
	$editor = AppContext::get_content_formatting_service()->get_default_editor();
	
	$Template->put_all(array(
		'ADMIN_MENU'  => $admin_menu,
		'KERNEL_EDITOR' => $editor->display(),
		'IMG_LIST' => $img_str,
		'L_CATEGORY' => $quotes->lang_get('category'),
		'L_REQUIRE' => $LANG['require'],
		'L_NAME' => $quotes->lang_get('category_name'),
		'L_LOCATION' => $quotes->lang_get('category_location'),
		'L_DESCRIPTION' => $quotes->lang_get('cat_description'),
		'L_IMAGE' => $quotes->lang_get('icon_cat'),
		'L_VISIBLE' => $quotes->lang_get('visible'),
		'L_EXPLAIN_IMAGE' => $quotes->lang_get('explain_icon_cat'),
		'L_PREVIEW' => $quotes->lang_get('preview'),
		'L_RESET' => $quotes->lang_get('reset'),
		'L_SUBMIT' => $id_edit > 0 ? $quotes->lang_get('edit') : $quotes->lang_get('add'),
		'L_REQUIRE_TITLE' => $quotes->lang_get('require_title'),
		'L_READ_AUTH' => $quotes->lang_get('auth_read'),
		'L_WRITE_AUTH' => $quotes->lang_get('auth_write'),
		'L_CONTRIBUTION_AUTH' => $quotes->lang_get('auth_contribute'),
		'L_SPECIAL_AUTH' => $quotes->lang_get('special_auth'),
		'L_SPECIAL_AUTH_EXPLAIN' => $quotes->lang_get('special_auth_explain'),
		'L_OR_DIRECT_PATH' => $QUOTES_LANG['or_direct_path'],
		'U_FORM_TARGET' => HOST . DIR . url('/quotes/admin_quotes_cat.php?token=' . $Session->get_token())
	));
		
	if ($id_edit > 0 && array_key_exists($id_edit, $QUOTES_CAT))
	{
		$cat = $QUOTES_CAT[$id_edit];
		$Template->put_all(array(
			'NAME' => $cat['name'],
			'DESCRIPTION' => FormatingHelper::unparse($cat['description']),
			'IMAGE' => $cat['image'],
			'CATEGORIES_TREE' => $quotes_categories->build_select_form($cat['id_parent'], 'id_parent', 'id_parent', $id_edit, 0),
			'IDCAT' => $id_edit,
			'VISIBLE_CHECKED' => $cat['visible'] ? 'checked="checked"' : '',
			'IMG_ICON' => !empty($cat['icon']) ? '<img src="' . $cat['image'] . '" alt="" class="valign_middle" />' : '',
			'IMG_PATH' => !$in_dir_icon ? $cat['image'] : '',
			'JS_SPECIAL_AUTH' => !empty($cat['auth']) ? 'true' : 'false',
			'DISPLAY_SPECIAL_AUTH' => !empty($cat['auth']) ? 'block' : 'none',
			'SPECIAL_CHECKED' => !empty($cat['auth']) ? 'checked="checked"' : '',
			'READ_AUTH' => Authorizations::generate_select(QUOTES_LIST_ACCESS, !empty($cat['auth']) ? $cat['auth'] : $CONFIG_QUOTES['auth']),
			'WRITE_AUTH' => Authorizations::generate_select(QUOTES_WRITE_ACCESS, !empty($cat['auth']) ? $cat['auth'] : $CONFIG_QUOTES['auth']),
			'CONTRIBUTION_AUTH' => Authorizations::generate_select(QUOTES_CONTRIB_ACCESS, !empty($cat['auth']) ? $cat['auth'] : $CONFIG_QUOTES['auth'])
			));
	}
	else
	{
		$id_edit = 0;
		$Template->put_all(array(
			'NAME' => '',
			'DESCRIPTION' => '',
			'IMAGE' => '',
			'CATEGORIES_TREE' => $quotes_categories->build_select_form($id_edit, 'id_parent', 'id_parent'),
			'IDCAT' => $id_edit,
			'VISIBLE_CHECKED' => 'checked="checked"',
			'JS_SPECIAL_AUTH' => 'false',
			'DISPLAY_SPECIAL_AUTH' => 'none',
			'SPECIAL_CHECKED' => '',
			'READ_AUTH' => Authorizations::generate_select(QUOTES_LIST_ACCESS, $CONFIG_QUOTES['auth']),
			'WRITE_AUTH' => Authorizations::generate_select(QUOTES_WRITE_ACCESS, $CONFIG_QUOTES['auth']),
			'CONTRIBUTION_AUTH' => Authorizations::generate_select(QUOTES_CONTRIB_ACCESS, $CONFIG_QUOTES['auth'])
			));
	}
	
	$Template->display();
}
else
{
	$Template = new FileTemplate('quotes/admin_quotes_cat.tpl');

	if (!empty($error))
	{
		switch ($error)
		{
			case 'e_required_fields_empty' :
				$Template->put('message_helper', MessageHelper::display($quotes->lang_get('required_fields_empty'), E_USER_WARNING));
				break;
			case 'e_unexisting_category' :
				$Template->put('message_helper', MessageHelper::display($quotes->lang_get('unexisting_category'), E_USER_WARNING));
				break;
			case 'e_new_cat_does_not_exist' :
				$Template->put('message_helper', MessageHelper::display($quotes->lang_get('new_cat_does_not_exist'), E_USER_WARNING));
				break;
			case 'e_infinite_loop' :
				$Template->put('message_helper', MessageHelper::display($quotes->lang_get('infinite_loop'), E_USER_WARNING));
				break;
			case 'e_success' :
				$Template->put('message_helper', MessageHelper::display($quotes->lang_get('successful_operation'), E_USER_SUCCESS));
				break;
		}
	}
	
	$cat_config = array(
		'xmlhttprequest_file' => 'xmlhttprequest_cats.php',
		'administration_file_name' => 'admin_quotes_cat.php',
		'url' => array(
			'unrewrited' => 'quotes.php?cat=%d',
			'rewrited' => 'category-%d+%s.php'),
		);
		
	$quotes_categories->set_display_config($cat_config);
	
	$Template->put_all(array(
		'ADMIN_MENU'  => $admin_menu,
		'L_CATS_MANAGEMENT' => $QUOTES_LANG['q_cat_management'],
		'CATEGORIES' => $quotes_categories->build_administration_interface()
	));

	$Template->display();
}

require_once(PATH_TO_ROOT.'/admin/admin_footer.php');

?>