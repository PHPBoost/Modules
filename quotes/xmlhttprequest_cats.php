<?php
/**
 *   xmlhttprequest_cats.php
 *
 *   @author            alain91
 *   @version          	$id$
 *   @copyright     	(C) 2010 Alain Gandon (based on Guestbook)
 *   @email             alain091@gmail.com
 *   @license          	GPL Version 2
 */

defined('PATH_TO_ROOT') or define('PATH_TO_ROOT', '..');

require_once(PATH_TO_ROOT.'/kernel/begin.php');
require_once(PATH_TO_ROOT.'/kernel/header_no_display.php');

if ($User->is_admin()) //Admin
{
	$quotes_categories = new QuotesCats();
	
	$id_up 		= retrieve(GET, 'id_up', 0);
	$id_down	= retrieve(GET, 'id_down', 0);
	$id_show	= retrieve(GET, 'show', 0);
	$id_hide	= retrieve(GET, 'hide', 0);
	$cat_to_del = retrieve(GET, 'del', 0);
	
	$result = false;
	
	if ($id_up > 0)
		$result = $quotes_categories->move($id_up, MOVE_CATEGORY_UP);
	elseif ($id_down > 0)
		$result = $quotes_categories->move($id_down, MOVE_CATEGORY_DOWN);
	elseif ($id_show > 0)
		$result = $quotes_categories->change_visibility($id_show, CAT_VISIBLE, LOAD_CACHE);
	elseif ($id_hide > 0)
		$result = $quotes_categories->change_visibility($id_hide, CAT_UNVISIBLE, LOAD_CACHE);
	
	//Operation was successfully
	if ($result)
	{	
		$cat_config = array(
			'xmlhttprequest_file' => 'xmlhttprequest_cats.php',
			'administration_file_name' => 'admin_quotes_cat.php',
			'url' => array(
				'unrewrited' => 'quotes.php?id=%d',
				'rewrited' => 'category-%d+%s.php'),
			);
		
		$quotes_categories->set_display_config($cat_config);
		
		$Cache->load('quotes', RELOAD_CACHE);
	
		echo $quotes_categories->build_administration_interface(AJAX_MODE);
	}
}
include_once(PATH_TO_ROOT.'/kernel/footer_no_display.php');
