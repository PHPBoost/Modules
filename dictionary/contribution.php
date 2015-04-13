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
require_once('../dictionary/dictionary_begin.php');

$Cache->load('dictionary');

if (!access_ok(DICTIONARY_CONTRIB_ACCESS, $CONFIG_DICTIONARY['auth']))
{
	$error_controller = PHPBoostErrors::user_not_authorized();
	DispatchManager::redirect($error_controller);
}

$Bread_crumb->add("dictionary", url('dictionary.php'));
$Bread_crumb->add($LANG['contribution_confirmation'], url('contribution.php'));

require_once('../kernel/header.php');

$template = new FileTemplate('dictionary/contribution.tpl');

$template->assign_vars(array(
	'L_CONTRIBUTION_CONFIRMATION' => $LANG['contribution_confirmation'],
	'L_CONTRIBUTION_SUCCESS' => $LANG['contribution_success'],
	'L_CONTRIBUTION_CONFIRMATION_EXPLAIN' => $LANG['contribution_confirmation_explain']
));

$template->display();

require_once('../kernel/footer.php'); 

?>