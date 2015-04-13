<?php
/*##################################################
 *                              dictionary.inc.php
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

if( defined('PHPBOOST') !== true) exit;

define('DICTIONARY_CREATE_ACCESS',		0x01);
define('DICTIONARY_UPDATE_ACCESS',		0x02);
define('DICTIONARY_DELETE_ACCESS',		0x04);
define('DICTIONARY_LIST_ACCESS',		0x08);
define('DICTIONARY_CONTRIB_ACCESS',		0x10);
load_module_lang('dictionary'); //Chargement de la langue du module.

function access_ok($mask, $config_auth)
{
	global $User;
	
	return $User->check_auth($config_auth, $mask);
}

function trigger_error_if_no_access($mask, $config_auth)
{
	if (!access_ok($mask, $config_auth))
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}
}
?>