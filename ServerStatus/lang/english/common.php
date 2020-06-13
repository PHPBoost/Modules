<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 09 09
 * @since       PHPBoost 4.0 - 2013 08 04
 * @contributor mipel <mipel@phpboost.com>
*/

#####################################################
#			English									#
#####################################################

//Module title
$lang['module_title'] = 'Servers status';

//Admin
$lang['admin.config.servers.management'] = 'Servers management';
$lang['admin.config.servers.manage'] = 'Manage servers';
$lang['admin.config.servers.title.add_server'] = 'Add a new server';
$lang['admin.config.servers.title.edit_server'] = 'Server edition';
$lang['admin.config.servers.action.add_server'] = 'Add a server';
$lang['admin.config.servers.action.edit_server'] = 'Edit server';
$lang['admin.config.servers.delete_server'] = 'Delete server';
$lang['admin.config.servers.delete_server.confirm'] = 'Delete this server?';
$lang['admin.config.servers.update_fields_position'] = 'Change fields position';
$lang['admin.config.servers.move_up'] = 'Move server up';
$lang['admin.config.servers.move_down'] = 'Move server down';
$lang['admin.config.servers.no_server'] = 'No server';
$lang['admin.config.servers.status_refresh'] = 'Refresh all servers status';

//Config
$lang['admin.config.curl_extension_disabled'] = 'The <b>php_curl</b> extension is disabled on this server. Some servers access tests may not work properly.';
$lang['admin.config.refresh_delay'] = 'Automatic refresh of servers status delay';
$lang['admin.config.refresh_delay.explain'] = 'In minutes. 15 minutes by default.';
$lang['admin.config.timeout'] = 'Test duration before considering a server <b>Offline</b>';
$lang['admin.config.timeout.explain'] = 'In milliseconds. 800 milliseconds by default. Increase duration if your server is always considered as <b>Offline</b> when it is not.';
$lang['admin.config.address_displayed'] = 'Display servers address';
$lang['admin.config.address_displayed.explain'] = 'Display the address and the port of the servers dans in the menu';
$lang['admin.authorizations'] = 'Authorizations';
$lang['admin.authorizations.read']  = 'Authorization to display the servers list';
$lang['admin.authorizations.display_server']  = 'Authorization to display the server';

//Server
$lang['server.online'] = 'Online';
$lang['server.offline'] = 'Offline';
$lang['server.name'] = 'Name';
$lang['server.description'] = 'Description';
$lang['server.address_type'] = 'Address type';
$lang['server.address_type.dns'] = 'DNS';
$lang['server.address_type.ip'] = 'IP';
$lang['server.address.dns'] = '* DNS name';
$lang['server.address.dns.explain'] = 'E.g. : <b>www.test.com</b>';
$lang['server.address.ip'] = '* IP address';
$lang['server.address.ip.explain'] = 'E.g. : <b>1.2.3.4</b> or <b>2001:67c:2e8:22::c100:68b</b>';
$lang['server.port'] = 'Port';
$lang['server.port.explain'] = 'Between <b>1</b> and <b>65535</b>.';
$lang['server.type'] = 'Type';
$lang['server.icon'] = 'Picture';
$lang['server.icon.none_e'] = 'None';
$lang['server.display'] = 'Displayed';
$lang['server.not_display'] = 'Not displayed';
$lang['server.applications'] = 'Applications';

//Messages
$lang['message.empty_address'] = 'Please fill server address';
$lang['message.unexist_address'] = 'The address is invalid';
?>
