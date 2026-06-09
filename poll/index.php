<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      xela <xela@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2020 05 14
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 */

define('PATH_TO_ROOT', '../..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = [
    // Configuration
    new UrlControllerMapper('AdminPollConfigController', '`^/admin(?:/config)?/?$`'),

    // Form
    new UrlControllerMapper('PollItemFormController', '`^/add/?([0-9]+)?/?$`', ['id_category']),
    new UrlControllerMapper('PollItemFormController', '`^/([0-9]+)/edit/?$`', ['id']),

    // Item
    new UrlControllerMapper('PollItemController', '`^/([0-9]+)-([a-z0-9-_]+)/([0-9]+)-([a-z0-9-_]+)/?$`', ['id_category', 'rewrited_name_category', 'id', 'rewrited_name']),

    // Items manage
    new UrlControllerMapper('PollItemsManagementController', '`^/manage/?$`'),

    // Mini
    new UrlControllerMapper('AjaxPollMiniController', '`^/ajax_send/$`'),
];

ModuleDispatchManager::dispatch($url_controller_mappers);
