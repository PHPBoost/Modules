<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 10 17
 * @since       PHPBoost 6.0 - 2022 10 17
 */

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	// Configuration
	new UrlControllerMapper('AdminVideoConfigController', '`^/admin(?:/config)?/?$`'),

	//Categories
	new UrlControllerMapper('DefaultCategoriesManagementController', '`^/categories/?$`'),
	new UrlControllerMapper('DefaultCategoriesFormController', '`^/categories/add/?([0-9]+)?/?$`', array('id_parent')),
	new UrlControllerMapper('DefaultCategoriesFormController', '`^/categories/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('DefaultDeleteCategoryController', '`^/categories/([0-9]+)/delete/?$`', array('id')),

	// Items Management
	new UrlControllerMapper('VideoItemsManagerController', '`^/manage/?$`'),
	new UrlControllerMapper('VideoItemFormController', '`^/add/?([0-9]+)?/?$`', array('id_category')),
	new UrlControllerMapper('VideoItemFormController', '`^/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('VideoDeleteItemController', '`^/([0-9]+)/delete/?$`', array('id')),
	new UrlControllerMapper('VideoItemController', '`^/([0-9]+)-([a-z0-9-_]+)/([0-9]+)-([a-z0-9-_]+)?/?$`', array('id_category', 'rewrited_name_category', 'id', 'rewrited_name')),

	// Keywords
	new UrlControllerMapper('VideoTagController', '`^/tag/([a-z0-9-_]+)?/?([a-z_]+)?/?([a-z]+)?/?([0-9]+)?/?$`', array('tag', 'field', 'sort', 'page')),

	new UrlControllerMapper('VideoPendingItemsController', '`^/pending(?:/([a-z_]+))?/?([a-z]+)?/?([0-9]+)?/?$`', array('field', 'sort', 'page')),
	new UrlControllerMapper('VideoMemberItemsController', '`^/member/([0-9]+)?/?([0-9]+)?/?$`', array('user_id', 'page')),

	new UrlControllerMapper('VideoDeadLinkController', '`^/dead_link/([0-9]+)/?$`', array('id')),
	new UrlControllerMapper('VideoCategoryController', '`^(?:/([0-9]+)-([a-z0-9-_]+))?/?([a-z_]+)?/?([a-z]+)?/?([0-9]+)?/?([0-9]+)?/?$`', array('id_category', 'rewrited_name', 'field', 'sort', 'page', 'subcategories_page'))
);
DispatchManager::dispatch($url_controller_mappers);
?>
