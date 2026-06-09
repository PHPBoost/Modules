<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 4.1 - 2014 08 21
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 */

define('PATH_TO_ROOT', '../..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = [
    // Configuration
    new UrlControllerMapper('AdminWebConfigController', '`^/admin(?:/config)?/?$`'),

    // Categories
    new UrlControllerMapper('DefaultCategoriesManagementController', '`^/categories/?$`'),
    new UrlControllerMapper('DefaultCategoriesFormController', '`^/categories/add/?([0-9]+)?/?$`', ['id_parent']),
    new UrlControllerMapper('DefaultCategoriesFormController', '`^/categories/([0-9]+)/edit/?$`', ['id']),
    new UrlControllerMapper('DefaultDeleteCategoryController', '`^/categories/([0-9]+)/delete/?$`', ['id']),

    // Management
    new UrlControllerMapper('WebItemsManagerController', '`^/manage/?$`'),
    new UrlControllerMapper('WebItemFormController', '`^/add/?([0-9]+)?/?$`', ['id_category']),
    new UrlControllerMapper('WebItemFormController', '`^/([0-9]+)/edit/?$`', ['id']),
    new UrlControllerMapper('WebItemFormController', '`^/([0-9]+)/duplicate/?$`', ['id']),
    new UrlControllerMapper('WebDeleteItemController', '`^/([0-9]+)/delete/?$`', ['id']),
    new UrlControllerMapper('WebItemController', '`^/([0-9]+)-([a-z0-9-_]+)/([0-9]+)-([a-z0-9-_]+)?/?$`', ['id_category', 'rewrited_name_category', 'id', 'rewrited_name']),

    // Keywords
    new UrlControllerMapper('WebTagController', '`^/tag/([a-z0-9-_]+)?/?([a-z]+)?/?([a-z]+)?/?([0-9]+)?/?$`', ['tag', 'field', 'sort', 'page']),

    new UrlControllerMapper('WebPendingItemsController', '`^/pending(?:/([a-z]+))?/?([a-z]+)?/?([0-9]+)?/?$`', ['field', 'sort', 'page']),
    new UrlControllerMapper('WebMemberItemsController', '`^/member/?([0-9]+)?/?([0-9]+)?/?$`', ['user_id', 'page']),

    new UrlControllerMapper('WebVisitItemController', '`^/visit/([0-9]+)/?$`', ['id']),
    new UrlControllerMapper('WebDeadLinkController', '`^/dead_link/([0-9]+)/?$`', ['id']),
    new UrlControllerMapper('WebCategoryController', '`^(?:/([0-9]+)-([a-z0-9-_]+))?/?([a-z]+)?/?([a-z]+)?/?([0-9]+)?/?([0-9]+)?/?$`', ['id_category', 'rewrited_name', 'field', 'sort', 'page', 'subcategories_page']),
];
DispatchManager::dispatch($url_controller_mappers);
