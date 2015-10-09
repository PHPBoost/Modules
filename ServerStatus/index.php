<?php
/*##################################################
 *                                 index.php
 *                            -------------------
 *   begin                : August 4, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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
