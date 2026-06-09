<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 5.0 - 2016 02 18
 * @author      mipel <mipel@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 */

define('PATH_TO_ROOT', '../..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = [
    //Admin
    new UrlControllerMapper('AdminQuotesConfigController', '`^/admin(?:/config)?/?$`'),

    //Categories
    new UrlControllerMapper('DefaultCategoriesManagementController', '`^/categories/?$`'),
    new UrlControllerMapper('DefaultCategoriesFormController', '`^/categories/add/?([0-9]+)?/?$`', ['id_parent']),
    new UrlControllerMapper('DefaultCategoriesFormController', '`^/categories/([0-9]+)/edit/?$`', ['id']),
    new UrlControllerMapper('DefaultDeleteCategoryController', '`^/categories/([0-9]+)/delete/?$`', ['id']),

    //Management
    new UrlControllerMapper('QuotesItemsManagerController', '`^/manage/?$`'),
    new UrlControllerMapper('QuotesItemFormController', '`^/add/?([0-9]+)?/?([a-z0-9-_]+)?/?$`', ['id_category', 'writer']),
    new UrlControllerMapper('QuotesItemFormController', '`^/([0-9]+)/edit/?$`', ['id']),
    new UrlControllerMapper('QuotesDeleteItemController', '`^/([0-9]+)/delete/?$`', ['id']),

    new UrlControllerMapper('AjaxQuotesWriterAutoCompleteController', '`^/ajax_writers/?$`'),
    new UrlControllerMapper('QuotesWriterController', '`^/writer/([a-z0-9-_]+)?/?([0-9]+)?/?$`', ['writer', 'page']),

    new UrlControllerMapper('QuotesPendingItemsController', '`^/pending/?([0-9]+)?/?$`', ['page']),
    new UrlControllerMapper('QuotesMemberItemsController', '`^/member/?([0-9]+)?/?([0-9]+)?/?$`', ['user_id', 'page']),

    new UrlControllerMapper('QuotesCategoryController', '`^(?:/([0-9]+)-([a-z0-9-_]+))?/?([0-9]+)?/?$`', ['id', 'rewrited_name', 'page', 'subcategories_page']),

];
DispatchManager::dispatch($url_controller_mappers);
