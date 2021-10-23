<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2016 06 13
 * @since       PHPBoost 5.0 - 2016 04 22
*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
//Admin
    new UrlControllerMapper('AdminEasyCssThemeController', '`^/theme/?$`'),
    new UrlControllerMapper('AdminEasyCssEditController', '`^/edit/(.+)/(.+)/?$`', array('theme', 'file')),
);
DispatchManager::dispatch($url_controller_mappers);
