<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 02 03
 * @since       PHPBoost 4.0 - 2013 08 27
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                     English                      #
####################################################

// Titles
$lang['birthday.module.title'] = 'Birthdays';
$lang['birthday.happy.birthday'] = 'Happy birthday!';
$lang['birthday.next.days'] = 'In :coming_next next days';

// Configuration
$lang['birthday.coming.next.number']                   = 'Number of days to display upcoming birthdays';
$lang['birthday.members.age.displayed']                = 'Display members age in the menu';
$lang['birthday.send.pm.for.members.birthday']         = 'Send a PM on members birthday';
$lang['birthday.pm.for.members.birthday.title']        = 'PM title';
$lang['birthday.pm.for.members.birthday.title.clue']   = 'Use <b>:user_display_name</b> to display the name of the member in the title and <b>:user_age</b> to display his age if needed.';
$lang['birthday.pm.for.members.birthday.content']      = 'PM content';
$lang['birthday.pm.for.members.birthday.content.clue'] = 'Use <b>:user_display_name</b> to display the name of the member in the content and <b>:user_age</b> to display his age if needed';

// Errors
$lang['birthday.user.born.field.disabled'] = 'The field <b>Date of birth</b> is not displayed in members profile. Please enable its display it in the <a class="offload" href="' . AdminExtendedFieldsUrlBuilder::fields_list()->rel() . '">Profile field management</a> to allow members to fill the field date of birth and display their birthday date in the menu.';

// Default config
$lang['birthday.config.pm.for.members.birthday.default.title'] = 'Happy birthday';
$lang['birthday.config.pm.for.members.birthday.default.content'] = 'The community wish you a happy birthday !';
?>
