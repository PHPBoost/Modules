<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 1.2 - 2005 10 25
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 */

define('PATH_TO_ROOT', '../..');

require_once PATH_TO_ROOT . '/kernel/init.php';

// Ensure the 'url' parameter is set for the dispatcher
// When URL rewriting is enabled, .htaccess sets this; when disabled or not matched, set it from query parameters
if (empty($_GET['url']))
{
	$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
	if ($id > 0)
	{
		$_GET['url'] = '/' . $id;
	}
	else
	{
		$_GET['url'] = '/';
	}
}

$url_controller_mappers = [
    //Config
    new UrlControllerMapper('AdminForumConfigController', '`^/admin(?:/config)?/?$`'),

    //Categories
    new UrlControllerMapper('ForumCategoriesManagementController', '`^/categories/?$`'),
    new UrlControllerMapper('ForumCategoriesFormController', '`^/categories/add/?$`'),
    new UrlControllerMapper('ForumCategoriesFormController', '`^/categories/([0-9]+)/edit/?$`', ['id']),
    new UrlControllerMapper('DefaultDeleteCategoryController', '`^/categories/([0-9]+)/delete/?$`', ['id']),

    //Home - with slug for URL rewriting enabled
    new UrlControllerMapper('ForumHomeController', '`^/([0-9]+)(?:-([a-z0-9-_]+))?/?$`', ['id', 'rewrited_name']),
    new UrlControllerMapper('ForumHomeController', '`^/?$`'),
];
DispatchManager::dispatch($url_controller_mappers);
