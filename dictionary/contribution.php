<?php
/*##################################################
 *                             contribution.php
 *                            -------------------
 *   begin                : March  3, 2009 
 *   copyright            : (C) 2009 Nicolas Maurel
 *   email                :  crunchfamily@free.fr
 *
 *  
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

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