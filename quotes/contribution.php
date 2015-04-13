<?php
/**
 *   contribution.php
 *
 *   @author            alain91
 *   @license          	GPL Version 2
 */
 
defined('PATH_TO_ROOT') or define('PATH_TO_ROOT', '..');

require_once(PATH_TO_ROOT.'/kernel/begin.php');
require_once(PATH_TO_ROOT.'/quotes/quotes_begin.php');
require_once(PATH_TO_ROOT.'/quotes/quotes.inc.php');

$Cache->load('quotes');

$quotes = new Quotes();

if (! $quotes->access_ok(QUOTES_CONTRIB_ACCESS) )
{
	DispatchManager::redirect(PHPBoostErrors::user_not_authorized());
	exit;
}

$Bread_crumb->add($quotes->lang_get('quotes'), url('quotes.php'));
$Bread_crumb->add($quotes->lang_get('contribution_confirmation'), url('contribution.php'));

require_once(PATH_TO_ROOT.'/kernel/header.php');

$template = new FileTemplate('quotes/contribution.tpl');

$template->put_all(array(
	'L_CONTRIBUTION_CONFIRMATION' 	=> $quotes->lang_get('contribution_confirmation'),
	'L_CONTRIBUTION_SUCCESS' 		=> $quotes->lang_get('contribution_success'),
	'L_CONTRIBUTION_CONFIRMATION_EXPLAIN' => $quotes->lang_get('contribution_confirmation_explain')
));

$template->render();

require_once(PATH_TO_ROOT.'/kernel/footer.php'); 

?>