<?php
/*##################################################
 *                           index.php
 *                            -------------------
 *   begin                : March 15, 2018
 *   copyright            : (C) 2018 Sebastien LARTIGUE
 *   email                : babsolune@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
 */

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	//Config
	new UrlControllerMapper('AdminSmalladsCategoriesConfigController', '`^/admin/display/?$`'),
	new UrlControllerMapper('AdminSmalladsItemsConfigController', '`^/admin/items/?$`'),
	new UrlControllerMapper('AdminSmalladsMiniMenuConfigController', '`^/admin/mini/?$`'),
	new UrlControllerMapper('AdminSmalladsUsageTermsController', '`^/admin/terms/?$`'),

	//Manage categories
	new UrlControllerMapper('SmalladsCategoriesManagerController', '`^/categories/?$`'),
	new UrlControllerMapper('SmalladsCategoriesFormController', '`^/categories/add/?([0-9]+)?/?$`', array('id_parent')),
	new UrlControllerMapper('SmalladsCategoriesFormController', '`^/categories/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('SmalladsDeleteCategoryController', '`^/categories/([0-9]+)/delete/?$`', array('id')),

	//Manage items
	new UrlControllerMapper('SmalladsItemsManagerController', '`^/manage/?$`'),
	new UrlControllerMapper('SmalladsItemFormController', '`^/add/?([0-9]+)?/?$`', array('id_category')),
	new UrlControllerMapper('SmalladsItemFormController', '`^(?:/([0-9]+))/edit/?([0-9]+)?/?$`', array('id', 'page')),
	new UrlControllerMapper('SmalladsDeleteItemController', '`^/([0-9]+)/delete/?$`', array('id')),

	// Usage Terms Conditions
	new UrlControllerMapper('SmalladsDisplayUsageTermsController', '`^/terms/?$`'),

	//Display items
	new UrlControllerMapper('SmalladsDisplayMemberItemsController', '`^/member(?:/([a-z0-9-_]+))?/?([a-z]+)?/?([a-z]+)?/?([0-9]+)?/?$`', array('member', 'field', 'sort', 'page')),
	new UrlControllerMapper('SmalladsDisplayTagController', '`^/tag(?:/([a-z0-9-_]+))?/?([a-z]+)?/?([a-z]+)?/?([0-9]+)?/?$`', array('tag', 'field', 'sort', 'page')),
	new UrlControllerMapper('SmalladsDisplayPendingItemsController', '`^/pending(?:/([a-z]+))?/?([a-z]+)?/?([0-9]+)?/?$`', array('field', 'sort', 'page')),
	new UrlControllerMapper('SmalladsDisplayItemController', '`^(?:/([0-9]+)-([a-z0-9-_]+)/([0-9]+)-([a-z0-9-_]+))/?([0-9]+)?/?$`', array('id_category', 'rewrited_name_category', 'id', 'rewrited_title', 'page')),

	//Display home and categories
	new UrlControllerMapper('SmalladsDisplayCategoryController', '`^(?:/([0-9]+)-([a-z0-9-_]+))?/?([a-z]+)?/?([a-z]+)?/?([0-9]+)?/?([0-9]+)?/?$`', array('id_category', 'rewrited_name', 'field', 'sort', 'page', 'subcategories_page'))
);

DispatchManager::dispatch($url_controller_mappers);

?>
