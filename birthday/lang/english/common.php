<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2016 05 27
 * @since   	PHPBoost 4.0 - 2013 08 27
*/

 ####################################################
 #                     English                      #
 ####################################################

//Module title
$lang['module_title'] = 'Birthdays';

//Admin
$lang['admin.config.members_age_displayed'] = 'Display members age in the menu';
$lang['admin.config.send_pm_for_members_birthday'] = 'Send a PM on members birthday';
$lang['admin.config.pm_for_members_birthday_title'] = 'PM title';
$lang['admin.config.pm_for_members_birthday_title.explain'] = 'Use <b>:user_display_name</b> to display the name of the member in the title and <b>:user_age</b> to display his age if needed.';
$lang['admin.config.pm_for_members_birthday_content'] = 'PM content';
$lang['admin.config.pm_for_members_birthday_content.explain'] = 'Use <b>:user_display_name</b> to display the name of the member in the content and <b>:user_age</b> to display his age if needed';
$lang['admin.authorizations'] = 'Authorizations';
$lang['admin.authorizations.read']  = 'Authorization to display the menu birthdays';

//Other
$lang['happy_birthday'] = 'Happy birthday!';
$lang['year'] = 'year';
$lang['years'] = 'years';

//Error
$lang['user_born_field_disabled'] = 'The field <b>Date of birth</b> is not displayed in members profile. Please enable its display it in the <a href="' . AdminExtendedFieldsUrlBuilder::fields_list()->rel() . '">Profile field management</a> to allow members to fill the field date of birth and display their birthday date in the menu.';
?>
