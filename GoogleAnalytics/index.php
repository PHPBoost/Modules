<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2012 12 20
 * @author      Arnaud GENET <elenwii@phpboost.com>
 */

define('PATH_TO_ROOT', '../..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = [
    new UrlControllerMapper('AdminGoogleAnalyticsController', '`^/admin(?:/config)?/?$`'),
];

DispatchManager::dispatch($url_controller_mappers);
