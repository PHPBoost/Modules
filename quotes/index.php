<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 02 18
 * @since       PHPBoost 5.0 - 2016 02 18
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	//Admin
	new UrlControllerMapper('AdminQuotesConfigController', '`^/admin(?:/config)?/?$`'),

	//Categories
	new UrlControllerMapper('DefaultCategoriesManagementController', '`^/categories/?$`'),
	new UrlControllerMapper('DefaultCategoriesFormController', '`^/categories/add/?([0-9]+)?/?$`', array('id_parent')),
	new UrlControllerMapper('DefaultCategoriesFormController', '`^/categories/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('DefaultDeleteCategoryController', '`^/categories/([0-9]+)/delete/?$`', array('id')),

	//Management
	new UrlControllerMapper('QuotesItemsManagerController', '`^/manage/?$`'),
	new UrlControllerMapper('QuotesItemFormController', '`^/add/?([0-9]+)?/?([a-z0-9-_]+)?/?$`', array('id_category', 'writer')),
	new UrlControllerMapper('QuotesItemFormController', '`^/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('QuotesDeleteItemController', '`^/([0-9]+)/delete/?$`', array('id')),

	new UrlControllerMapper('AjaxQuotesWriterAutoCompleteController','`^/ajax_writers/?$`'),
	new UrlControllerMapper('QuotesWriterController', '`^/writer/([a-z0-9-_]+)?/?([0-9]+)?/?$`', array('writer', 'page')),

	new UrlControllerMapper('QuotesPendingItemsController', '`^/pending/?([0-9]+)?/?$`', array('page')),
	new UrlControllerMapper('QuotesMemberItemsController', '`^/member/?([0-9]+)?/?([0-9]+)?/?$`', array('user_id', 'page')),

	new UrlControllerMapper('QuotesCategoryController', '`^(?:/([0-9]+)-([a-z0-9-_]+))?/?([0-9]+)?/?$`', array('id', 'rewrited_name', 'page', 'subcategories_page')),

);
DispatchManager::dispatch($url_controller_mappers);
?>
