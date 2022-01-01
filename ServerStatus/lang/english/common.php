<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 05 28
 * @since       PHPBoost 4.0 - 2013 08 04
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

#####################################################
#			English									#
#####################################################

$lang['server.module.title'] = 'Servers status';

// Labels
$lang['server.management']     = 'Servers management';
$lang['server.add.item']       = 'Add a server';
$lang['server.edit.item']      = 'Edit a server';
$lang['server.refresh.status'] = 'Refresh all servers status';
$lang['server.online']         = 'Online';
$lang['server.offline']        = 'Offline';

// Configuration
$lang['server.refresh.delay'] = 'Automatic refresh of servers status delay';
$lang['server.refresh.delay.clue'] = 'In minutes. 15 minutes by default.';
$lang['server.timeout'] = 'Test duration before considering a server <b>Offline</b>';
$lang['server.timeout.clue'] = 'In milliseconds. 800 milliseconds by default. Increase duration if your server is always considered as <b>Offline</b> when it is not.';
$lang['server.display.address'] = 'Display servers address';
$lang['server.display.address.clue'] = 'Display the address and the port of the servers dans in the menu';

// Form
$lang['server.address.type']     = 'Address type';
$lang['server.address.type.dns'] = 'DNS';
$lang['server.address.type.ip']  = 'IP';
$lang['server.address.dns']      = '* DNS name';
$lang['server.address.dns.clue'] = 'E.g. : <b>www.test.com</b>';
$lang['server.address.ip']       = '* IP address';
$lang['server.address.ip.clue']  = 'E.g. : <b>1.2.3.4</b> or <b>2001:67c:2e8:22::c100:68b</b>';
$lang['server.port']             = 'Port';
$lang['server.port.clue']        = 'Between <b>1</b> and <b>65535</b>.';
$lang['server.type']             = 'Type';
$lang['server.icon']             = 'Icon';

// Messages helper
$lang['server.warning.curl.extension'] = 'The <b>php_curl</b> extension is disabled on this server. Some servers access tests may not work properly.';
$lang['server.warning.empty.address'] = 'Please fill server address';
?>
