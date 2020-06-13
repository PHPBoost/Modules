<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 10 13
 * @since       PHPBoost 5.2 - 2018 11 27
*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(new UrlControllerMapper('AdminNewscatConfigController', '`^/admin(?:/config)?/?$`'));
DispatchManager::dispatch($url_controller_mappers);
?>
