<?php
/**
 *  quotes_begin.php
 *
 *   @author            alain91
 *   @copyright      	(C) 2008-2010 Alain Gandon
 *   @email    			alain091@gmail.com
 *   @license          	GPL Version 2
 */

defined('PHPBOOST') or exit;
	
load_module_lang('quotes'); //Chargement de la langue du module.
define('TITLE', $QUOTES_LANG['q_title']);

$Cache->load('quotes');

// BARRE DES TITRES : A FAIRE AVANT HEADER.PHP
$category_id = retrieve(GET, 'cat', 0);
$id = $category_id;
while ($id > 0)
{
	$Bread_crumb->add($QUOTES_CAT[$id]['name'], url('quotes.php?cat=' . $id, 'category-' . $id . '+' . Url::encode_rewrite($QUOTES_CAT[$id]['name']) . '.php'));
	if (!empty($QUOTES_CAT[$id]['id_parent']))
	{
		$id = (int)$QUOTES_CAT[$id]['id_parent'];
	}
	else
	{
		$id = 0;
	}
}
$Bread_crumb->add($QUOTES_LANG['q_title'], url('quotes.php'));
$Bread_crumb->reverse();

?>
