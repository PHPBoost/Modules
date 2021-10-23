<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 11 05
 * @since       PHPBoost 4.1 - 2016 02 15
*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	//Config
	new UrlControllerMapper('AdminDictionaryConfigController', '`^/admin(?:/config)?/?$`'),

	new UrlControllerMapper('DictionaryHomeController', '`^/?$`'),
);
DispatchManager::dispatch($url_controller_mappers);
?>
