<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 12 04
 * @since       PHPBoost 2.0 - 2012 11 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../kernel/begin.php');
load_module_lang('dictionary'); //Chargement de la langue du module.
define('TITLE', $LANG['dictionary']);

if (!DictionaryAuthorizationsService::check_authorizations()->contribution())
{
	$error_controller = PHPBoostErrors::user_not_authorized();
	DispatchManager::redirect($error_controller);
}

$Bread_crumb->add("dictionary", url('dictionary.php'));
$Bread_crumb->add($LANG['contribution.confirmation'], url('contribution.php'));

require_once('../kernel/header.php');

$template = new FileTemplate('dictionary/contribution.tpl');

$template->put_all(array(
	'L_CONTRIBUTION_CONFIRMATION' => $LANG['contribution.confirmation'],
	'L_CONTRIBUTION_SUCCESS' => $LANG['contribution.success'],
	'L_CONTRIBUTION_CONFIRMATION_EXPLAIN' => $LANG['contribution.confirmation.explain']
));

$template->display();

require_once('../kernel/footer.php');

?>
