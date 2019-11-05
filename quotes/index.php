<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2019 11 03
 * @since   	PHPBoost 5.0 - 2016 02 18
 * @contributor mipel <mipel@phpboost.com>
*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	//Admin
	new UrlControllerMapper('AdminQuotesConfigController', '`^/admin(?:/config)?/?$`'),

	//Categories
	new UrlControllerMapper('DefaultCategoriesManageController', '`^/categories/?$`'),
	new UrlControllerMapper('DefaultRichCategoriesFormController', '`^/categories/add/?([0-9]+)?/?$`', array('id_parent')),
	new UrlControllerMapper('DefaultRichCategoriesFormController', '`^/categories/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('DefaultDeleteCategoryController', '`^/categories/([0-9]+)/delete/?$`', array('id')),

	//Management
	new UrlControllerMapper('QuotesManageController', '`^/manage/?$`'),
	new UrlControllerMapper('QuotesFormController', '`^/add/?([0-9]+)?/?([a-z0-9-_]+)?/?$`', array('id_category', 'author')),
	new UrlControllerMapper('QuotesFormController', '`^/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('QuotesDeleteController', '`^/([0-9]+)/delete/?$`', array('id')),

	new UrlControllerMapper('AjaxQuoteAuthorAutoCompleteController','`^/ajax_authors/?$`'),
	new UrlControllerMapper('QuotesDisplayAuthorQuotesController', '`^/author/([a-z0-9-_]+)?/?([0-9]+)?/?$`', array('author', 'page')),

	new UrlControllerMapper('QuotesDisplayPendingQuotesController', '`^/pending/?([0-9]+)?/?$`', array('page')),

	new UrlControllerMapper('QuotesDisplayCategoryController', '`^(?:/([0-9]+)-([a-z0-9-_]+))?/?([0-9]+)?/?$`', array('id', 'rewrited_name', 'page', 'subcategories_page')),

);
DispatchManager::dispatch($url_controller_mappers);
?>
