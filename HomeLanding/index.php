<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 12 29
 * @since   	PHPBoost 5.0 - 2016 01 02
*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	new UrlControllerMapper('AdminHomeLandingConfigController', '`^/admin(?:/config)?/?$`'),
	new UrlControllerMapper('AdminHomeLandingModulesPositionController', '`^/admin/positions/?$`'),
	new UrlControllerMapper('HomeLandingAjaxChangeModuleDisplayController', '`^/admin/positions/change_display/?$`'),

	new UrlControllerMapper('AdminHomeLandingStickyConfigController', '`^/admin/sticky/?$`'),
	new UrlControllerMapper('HomeLandingStickyController', '`^/sticky/?$`'),

	new UrlControllerMapper('HomeLandingHomeController', '`^(?:/([0-9]+))?/?$`')
);
DispatchManager::dispatch($url_controller_mappers);

?>
