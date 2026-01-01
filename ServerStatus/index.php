<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2016 02 11
 * @since       PHPBoost 4.0 - 2013 08 04
*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	//Config
	new UrlControllerMapper('AdminServerStatusConfigController', '`^/admin(?:/config)?/?$`'),

	//Servers
	new UrlControllerMapper('AdminServerStatusServersListController', '`^/admin/servers(?:/list)?/?$`'),
	new UrlControllerMapper('AdminServerStatusServerFormController', '`^/admin/servers/add/?$`'),
	new UrlControllerMapper('AdminServerStatusServerFormController', '`^/admin/servers/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('ServerStatusAjaxDeleteServerController', '`^/admin/servers/delete/?$`'),
	new UrlControllerMapper('ServerStatusAjaxChangeServerDisplayController', '`^/admin/servers/change_display/?$`'),

	//Servers list
	new UrlControllerMapper('ServerStatusController', '`^(?:/([0-9]+))?/?$`', array('id'))
);

DispatchManager::dispatch($url_controller_mappers);
?>
