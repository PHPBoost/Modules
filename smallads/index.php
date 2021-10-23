<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 02 19
 * @since       PHPBoost 4.0 - 2013 01 30
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	// Config
	new UrlControllerMapper('AdminSmalladsCategoriesConfigController', '`^/admin(?:/display)?/?$`'),
	new UrlControllerMapper('AdminSmalladsItemsConfigController', '`^/admin/items/?$`'),
	new UrlControllerMapper('AdminSmalladsMiniMenuConfigController', '`^/admin/mini/?$`'),
	new UrlControllerMapper('AdminSmalladsUsageTermsController', '`^/admin/terms/?$`'),

	// Manage categories
	new UrlControllerMapper('DefaultCategoriesManagementController', '`^/categories/?$`'),
	new UrlControllerMapper('DefaultCategoriesFormController', '`^/categories/add/?([0-9]+)?/?$`', array('id_parent')),
	new UrlControllerMapper('DefaultCategoriesFormController', '`^/categories/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('DefaultDeleteCategoryController', '`^/categories/([0-9]+)/delete/?$`', array('id')),

	// Manage items
	new UrlControllerMapper('SmalladsItemsManagerController', '`^/manage/?$`'),
	new UrlControllerMapper('SmalladsItemFormController', '`^/add/?([0-9]+)?/?$`', array('id_category')),
	new UrlControllerMapper('SmalladsItemFormController', '`^(?:/([0-9]+))/edit/?([0-9]+)?/?$`', array('id')),
	new UrlControllerMapper('SmalladsDeleteItemController', '`^/([0-9]+)/delete/?$`', array('id')),

	// Usage Terms Conditions
	new UrlControllerMapper('SmalladsUsageTermsController', '`^/terms/?$`'),

	// Display items
	new UrlControllerMapper('SmalladsMemberItemsController', '`^/member/?([0-9]+)?/?$`', array('user_id')),
	new UrlControllerMapper('SmalladsTagController', '`^/tag/([a-z0-9-_]+)?/?([0-9]+)?/?$`', array('tag')),
	new UrlControllerMapper('SmalladsPendingItemsController', '`^/pending/([0-9]+)?/?$`'),
	new UrlControllerMapper('SmalladsArchivedItemsController', '`^/archives/([0-9]+)?/?$`'),
	new UrlControllerMapper('SmalladsItemController', '`^/([0-9]+)-([a-z0-9-_]+)/([0-9]+)-([a-z0-9-_]+)/?$`', array('id_category', 'rewrited_name_category', 'id', 'rewrited_title')),

	// Display home and categories
	new UrlControllerMapper('SmalladsCategoryController', '`^(?:/([0-9]+)-([a-z0-9-_]+))?/?([0-9]+)?/?$`', array('id_category', 'rewrited_name'))
);

DispatchManager::dispatch($url_controller_mappers);

?>
