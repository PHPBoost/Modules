<?php
/**
 * smallads_begin.php
 * 
 * @author         alain91
 * @copyright      (C) 2009-2010 Alain Gandon
 * @email          alain091@gmail.com
 * @license        GPL version 2
 */

defined('PHPBOOST') OR die('Direct script access not allowed');

if (version_compare(PHP_VERSION, '5.0.0', '<')) {
    die ('Le module Smallads necessite PHP version 5');
}
	
load_module_lang('smallads'); //Chargement de la langue du module.
define('TITLE', $LANG['sa_title']);

require_once(PATH_TO_ROOT . '/smallads/smallads.inc.php');

$type_options = array(0 => $LANG['sa_group_all']);
for ($i = 1; $i <= 9; $i++) {
	if (!empty($LANG['sa_group_'.$i]))
		$type_options[$i] = $LANG['sa_group_'.$i];
	else
		break;
}

$checkboxes = array(
	'view_mail'	=> array('mask' => 0x01, 'value' => null, 'default' => 0, 'type' => TINTEGER),
	'view_pm'	=> array('mask' => 0x02, 'value' => null, 'default' => 0, 'type' => TINTEGER),
	);
	
$sort_options = array( 'title' => $LANG['sa_sort_title'], 'date_created' => $LANG['sa_sort_date'], 'price' => $LANG['sa_sort_price']);
$mode_options = array( 'asc' => $LANG['sa_mode_asc'], 'desc' => $LANG['sa_mode_desc']);

$forbidden_tags = array('code', 'math', 'html');

?>