<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2012 11 20
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 */

define('PATH_TO_ROOT', '../..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = [
    // Admin
    new UrlControllerMapper('AdminCalendarConfigController', '`^/admin(?:/config)?/?$`'),

    // Categories
    new UrlControllerMapper('DefaultCategoriesManagementController', '`^/categories/?$`'),
    new UrlControllerMapper('DefaultCategoriesFormController', '`^/categories/add/?$`'),
    new UrlControllerMapper('DefaultCategoriesFormController', '`^/categories/([0-9]+)/edit/?$`', ['id']),
    new UrlControllerMapper('DefaultDeleteCategoryController', '`^/categories/([0-9]+)/delete/?$`', ['id']),

    // Display items
    new UrlControllerMapper('CalendarItemController', '`^/([0-9]+)-([a-z0-9-_]+)/([0-9]+)-([a-z0-9-_]+)/?$`', ['id_category', 'rewrited_name_category', 'id', 'rewrited_name']),

    // Manage items
    new UrlControllerMapper('CalendarItemsManagerController', '`^/manage/?$`'),
    new UrlControllerMapper('CalendarItemFormController', '`^/add/?([0-9]+)?/?([0-9]+)?/?([0-9]+)?/?([0-9]+)?/?([0-9]+)?/?$`', ['year', 'month', 'day', 'id_category']),
    new UrlControllerMapper('CalendarItemFormController', '`^/([0-9]+)/edit/?$`', ['id']),
    new UrlControllerMapper('CalendarItemFormController', '`^/([0-9]+)/duplicate/?$`', ['id']),
    new UrlControllerMapper('CalendarDeleteItemController', '`^/([0-9]+)/delete/?$`', ['id']),
    new UrlControllerMapper('CalendarSuscribeController', '`^/([0-9]+)/suscribe/?$`', ['event_id']),
    new UrlControllerMapper('CalendarUnsuscribeController', '`^/([0-9]+)/unsuscribe/?$`', ['event_id']),

    new UrlControllerMapper('CalendarPendingItemsController', '`^/pending/?([0-9]+)?/?$`', ['page']),
    new UrlControllerMapper('CalendarMemberItemsController', '`^/member/([0-9]+)?/?([0-9]+)?/?$`', ['user_id', 'page']),
    new UrlControllerMapper('CalendarItemsListController', '`^/items_list/today/?$`'),
    new UrlControllerMapper('CalendarItemsListController', '`^/items_list/?$`'),

    // Display calendar
    new UrlControllerMapper('CalendarAjaxCalendarController', '`^/ajax_month_calendar/([0-9]+)?/?([0-9]+)?/?([0-9]+)?/?(\d{0,1})?/?$`', ['calendar_ajax_year', 'calendar_ajax_month', 'id_category', 'calendar_mini']),
    new UrlControllerMapper('CalendarAjaxEventsController', '`^/ajax_month_events/([0-9]+)?/?([0-9]+)?/?([0-9]+)?/?([0-9]+)?/?$`', ['calendar_ajax_year', 'calendar_ajax_month', 'calendar_ajax_day', 'id_category']),
    new UrlControllerMapper('CalendarHomeController', '`^/?([0-9]+)?/?([0-9]+)?/?([0-9]+)?/?$`', ['year', 'month', 'day']),
    new UrlControllerMapper('CalendarHomeController', '`^(?:/([0-9]+)-([a-z0-9-_]+))?/?([0-9]+)?/?([0-9]+)?/?([0-9]+)?/?$`', ['id_category', 'rewrited_name', 'year', 'month', 'day']),
];
DispatchManager::dispatch($url_controller_mappers);
