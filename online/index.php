<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2012 01 30
 */

define('PATH_TO_ROOT', '../..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = [
    new UrlControllerMapper('AdminOnlineConfigController', '`^/admin(?:/config)?/?$`'),
    new UrlControllerMapper('OnlineHomeController', '`^(?:/([0-9]+))?/?$`', ['page']),
];
DispatchManager::dispatch($url_controller_mappers);
