<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2012 12 11
 * @author      xela <xela@phpboost.com>
 */

define('PATH_TO_ROOT', '../..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = [
    new UrlControllerMapper('AdminGuestbookConfigController', '`^/admin(?:/config)?/?$`'),

    new UrlControllerMapper('GuestbookFormController', '`^/add/?$`'),
    new UrlControllerMapper('GuestbookFormController', '`^/([0-9]+)/edit/?([0-9]+)?/?$`', ['id', 'page']),
    new UrlControllerMapper('GuestbookDeleteController', '`^/([0-9]+)/delete/?$`', ['id']),
    new UrlControllerMapper('GuestbookController', '`^(?:/manage)?(?:/([0-9]+))?/?$`', ['page']),
];
DispatchManager::dispatch($url_controller_mappers);
