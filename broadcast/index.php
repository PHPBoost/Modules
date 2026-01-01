<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2022 10 25
 * @since       PHPBoost 6.0 - 2022 10 25
 */

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	// Admin
	new UrlControllerMapper('AdminBroadcastConfigController', '`^/admin(?:/config)?/?$`'),

	// Categories
	new UrlControllerMapper('DefaultCategoriesManagementController', '`^/categories/?$`'),
	new UrlControllerMapper('DefaultCategoriesFormController', '`^/categories/add/?([0-9]+)?/?$`', array('id_parent')),
	new UrlControllerMapper('DefaultCategoriesFormController', '`^/categories/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('DefaultDeleteCategoryController', '`^/categories/([0-9]+)/delete/?$`', array('id')),

	// Manage Broadcast
	new UrlControllerMapper('BroadcastItemsManagerController', '`^/manage/?$`'),
	new UrlControllerMapper('BroadcastItemFormController', '`^/add/?([0-9]+)?/?$`', array('id_category')),
	new UrlControllerMapper('BroadcastItemFormController', '`^/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('BroadcastDeleteItemController', '`^/([0-9]+)/delete/?$`', array('id')),

	new UrlControllerMapper('BroadcastItemController', '`^/([0-9]+)-([a-z0-9-_]+)/([0-9]+)-([a-z0-9-_]+)/?$`', array('id_category', 'rewrited_name_category', 'id', 'rewrited_name')),

	new UrlControllerMapper('BroadcastCategoryController', '`^(?:/([0-9]+)-([a-z0-9-_]+))?/?([0-9]+)?/?$`', array('id_category', 'rewrited_name', 'page')),
);
DispatchManager::dispatch($url_controller_mappers);
?>
