<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 13
 * @since       PHPBoost 2.0 - 2012 11 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../kernel/begin.php');

$lang = LangLoader::get_all_langs('dictionary');

define('TITLE', $lang['contribution.contribution']);

if (!DictionaryAuthorizationsService::check_authorizations()->contribution())
{
	$error_controller = PHPBoostErrors::user_not_authorized();
	DispatchManager::redirect($error_controller);
}

$Bread_crumb->add("dictionary", url('dictionary.php'));
$Bread_crumb->add($lang['contribution.contribution'], url('contribution.php'));

require_once('../kernel/header.php');

$view = new FileTemplate('dictionary/contribution.tpl');
$view->add_lang(array_merge($lang));

$view->display();

require_once('../kernel/footer.php');

?>
