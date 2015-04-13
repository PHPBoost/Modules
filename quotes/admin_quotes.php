<?php
/**
 *   admin_quotes.php
 *
 *   @author			alain91
 *   @copyright      	(C) 2008-2010 Alain Gandon
 *   @email             alain091@gmail.com
 *   @license          	GPL Version 2
 */

defined('PATH_TO_ROOT') or define('PATH_TO_ROOT', '..');
 
require_once(PATH_TO_ROOT.'/admin/admin_begin.php');
require_once(PATH_TO_ROOT.'/quotes/quotes_begin.php');
require_once(PATH_TO_ROOT.'/quotes/quotes.inc.php');
require_once(PATH_TO_ROOT.'/admin/admin_header.php');

require_once('admin_quotes_menu.php');

if( !empty($_POST['valid'])  )
{
	$config_quotes = array();
	$config_quotes['items_per_page'] = retrieve(POST, 'q_items_per_page', 10, TINTEGER);
	$config_quotes['cat_cols']		 = retrieve(POST, 'q_cat_cols', 2, TINTEGER);
	$config_quotes['mini_list_size'] = retrieve(POST, 'q_mini_list_size', 1, TINTEGER);
	//Génération du tableau des droits.
	$config_quotes['auth'] = Authorizations::build_auth_array_from_form(QUOTES_LIST_ACCESS, QUOTES_CONTRIB_ACCESS, QUOTES_WRITE_ACCESS);
		
	$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . "
						SET value = '" . addslashes(serialize($config_quotes)) . "'
						WHERE name = 'quotes'",
						__LINE__, __FILE__);
	
	$Cache->generate_module_file('quotes'); // regeneration du cache
	
	AppContext::get_response()->redirect(HOST . SCRIPT);
}
//Sinon on rempli le formulaire
else
{
	$Template = new FileTemplate('quotes/admin_quotes_config.tpl');
	
	$Cache->load('quotes');
	
	$Template->put_all(array(
		'ADMIN_MENU'	  => $admin_menu,
		'L_REQUIRE'       => $LANG['require'],
		'L_QUOTES'        => $QUOTES_LANG['q_title'],
		'L_QUOTES_CONFIG' => $QUOTES_LANG['q_config'],
		'L_UPDATE'        => $LANG['update'],
		'L_RESET'         => $LANG['reset'],
		'L_GLOBAL_AUTH'   => $QUOTES_LANG['global_auth'],
		'L_GLOBAL_AUTH_EXPLAIN' => $QUOTES_LANG['global_auth_explain']
	));
		
	$fields = array(
		array('name' => 'q_items_per_page',	'value' => $CONFIG_QUOTES['items_per_page'], 'size' => 3, 'maxlength' => 3),
		array('name' => 'q_mini_list_size',	'value' => $CONFIG_QUOTES['mini_list_size'], 'size' => 3, 'maxlength' => 3),
		array('name' => 'q_cat_cols', 'value' => $CONFIG_QUOTES['cat_cols'], 'size' => 3, 'maxlength' => 3),
	);

	foreach ($fields as $field) {
		$Template->assign_block_vars('config', array(
			'L_LABEL' => $QUOTES_LANG[$field['name']],
			'NAME'    => $field['name'],
			'VALUE'   => $field['value'],
			'SIZE'   => $field['size'],
			'MAXLENGTH'   => $field['maxlength']
		));
	}
	
	$auths = array(
		QUOTES_LIST_ACCESS   => 'q_list',
		QUOTES_CONTRIB_ACCESS => 'q_contrib',
		QUOTES_WRITE_ACCESS => 'q_write'
		);
	
	foreach ($auths as $key => $value) {
		$Template->assign_block_vars('auth', array(
			'SELECT'   => Authorizations::generate_select($key, $CONFIG_QUOTES['auth']),
			'L_SELECT' => $QUOTES_LANG[$value],
			'NAME' => $value
			));
	}
	
	$Template->display();
}

require_once(PATH_TO_ROOT.'/admin/admin_footer.php');
