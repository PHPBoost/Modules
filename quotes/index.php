<?php
/*##################################################
 *                               index.php
 *                            -------------------
 *   begin                : February 18, 2016
 *   copyright            : (C) 2016 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	//Categories
	new UrlControllerMapper('QuotesCategoriesManageController', '`^/admin/categories/?$`'),
	new UrlControllerMapper('QuotesCategoriesFormController', '`^/admin/categories/add/?([0-9]+)?/?$`', array('id_parent')),
	new UrlControllerMapper('QuotesCategoriesFormController', '`^/admin/categories/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('QuotesDeleteCategoryController', '`^/admin/categories/([0-9]+)/delete/?$`', array('id')),
	
	//Admin
	new UrlControllerMapper('AdminQuotesManageController', '`^/admin/manage/?$`'),
	new UrlControllerMapper('AdminQuotesConfigController', '`^/admin(?:/config)?/?$`'),
	
	//Management
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
