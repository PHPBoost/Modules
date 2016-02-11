<?php
/*##################################################
 *                                 common.php
 *                            -------------------
 *   begin                : August 27, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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


 ####################################################
 #                     English                      #
 ####################################################

//Module title
$lang['module_title'] = 'Birthdays';

//Admin
$lang['admin.config.members_age_displayed'] = 'Display members age in the menu';
$lang['admin.config.send_pm_for_members_birthday'] = 'Send a PM on members birthday';
$lang['admin.config.pm_for_members_birthday_title'] = 'PM title';
$lang['admin.config.pm_for_members_birthday_title.explain'] = 'Use <b>:user_login</b> to display the login of the member in the title and <b>:user_age</b> to display his age if needed.';
$lang['admin.config.pm_for_members_birthday_content'] = 'PM content';
$lang['admin.config.pm_for_members_birthday_content.explain'] = 'Use <b>:user_login</b> to display the login of the member in the content and <b>:user_age</b> to display his age if needed';
$lang['admin.authorizations'] = 'Authorizations';
$lang['admin.authorizations.read']  = 'Authorization to display the menu birthdays';

//Other
$lang['happy_birthday'] = 'Happy birthday!';
$lang['year'] = 'year';
$lang['years'] = 'years';

//Error
$lang['user_born_field_disabled'] = 'The field <b>Date of birth</b> is not displayed in members profile. Please enable its display it in the <a href="' . AdminExtendedFieldsUrlBuilder::fields_list()->rel() . '">Profile field management</a> to allow members to fill the field date of birth and display their birthday date in the menu.';
?>
