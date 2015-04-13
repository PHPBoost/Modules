<?php
 
define('PATH_TO_ROOT', '..');
 
require_once PATH_TO_ROOT . '/kernel/init.php';
 
$url_controller_mappers = array(
	//Config
	new UrlControllerMapper('AdminTeamspeakConfigController', '`^/admin(?:/config)?/?$`'),
	
	new UrlControllerMapper('TeamspeakAjaxViewerController', '`^/ajax_refresh_viewer/?$`'),
	new UrlControllerMapper('TeamspeakHomeController', '`^/?$`'),
);
 
DispatchManager::dispatch($url_controller_mappers);
?>