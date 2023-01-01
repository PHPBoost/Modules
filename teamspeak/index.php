<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2015 04 13
 * @since       PHPBoost 4.1 - 2014 09 26
*/

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
