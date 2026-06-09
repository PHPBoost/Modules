<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      LamPDL
 * @version     PHPBoost 6.0 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2026 03 01
 */

define('PATH_TO_ROOT', is_dir(__DIR__ . '/../../modules') ? '../..' : '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = [
    // Admin
    new UrlControllerMapper('AdminDevToolsConfigController', '`^/admin(?:/config)?/?$`'),

    // Ajax endpoints
    new UrlControllerMapper('DevToolsAjaxBranchesController',   '`^/ajax/branches/?$`'),
    new UrlControllerMapper('DevToolsAjaxFoldersController',    '`^/ajax/folders/?$`'),
    new UrlControllerMapper('DevToolsAjaxInstallController',    '`^/ajax/install/?$`'),
    new UrlControllerMapper('DevToolsAjaxActivateController',   '`^/ajax/activate/?$`'),
    new UrlControllerMapper('DevToolsAjaxDeactivateController', '`^/ajax/deactivate/?$`'),
    new UrlControllerMapper('DevToolsAjaxUninstallController',  '`^/ajax/uninstall/?$`'),
    new UrlControllerMapper('DevToolsAjaxReposController',      '`^/ajax/repos/?$`'),
    new UrlControllerMapper('DevToolsAjaxSaveRepoController',   '`^/ajax/save-repo/?$`'),
    new UrlControllerMapper('DevToolsAjaxLocalInstallController', '`^/ajax/local-install/?$`'),
    new UrlControllerMapper('DevToolsAjaxRestoreController',      '`^/ajax/restore/?$`'),
    new UrlControllerMapper('DevToolsAjaxBackupController',       '`^/ajax/backup/?$`'),
    new UrlControllerMapper('DevToolsAjaxImportBddController',    '`^/ajax/import-bdd/?$`'),
    new UrlControllerMapper('DevToolsAjaxReviewController',       '`^/ajax/review/?$`'),
    new UrlControllerMapper('DevToolsAjaxLangController',         '`^/ajax/lang/?$`'),

    // Main page (modules tab)
    new UrlControllerMapper('DevToolsHomeController', '`^(?:/modules)?/?$`'),
];

DispatchManager::dispatch($url_controller_mappers);
?>
