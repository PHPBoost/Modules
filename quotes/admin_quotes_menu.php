<?php
/*##################################################
 *                               admin_quotes_menu.php
 *                            -------------------
 *   begin               	: February 14, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
 *
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

if (defined('PHPBOOST') !== true) exit;

$tpl_menu = new FileTemplate('quotes/admin_quotes_menu.tpl');

$tpl_menu->put_all(array(
	'L_QUOTES_MANAGEMENT' => $QUOTES_LANG['q_management'],
	'L_CATS_MANAGEMENT' => $QUOTES_LANG['q_cat_management'],
	'L_QUOTES_CONFIG' => $QUOTES_LANG['q_config'],
	'L_ADD_CATEGORY' => $QUOTES_LANG['q_add_category'],
	'U_QUOTES_CONFIG' => url('admin_quotes.php'),
	'U_QUOTES_CATS_MANAGEMENT' => url('admin_quotes_cat.php'),
	'U_QUOTES_ADD_CAT' => url('admin_quotes_cat.php?new=1')
));

$admin_menu = $tpl_menu->display();

?>