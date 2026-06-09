<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      LamPDL
 * @version     PHPBoost 6.0 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2026 03 01
 *
 * Installs a module already present on disk.
 */
class DevToolsAjaxLocalInstallController extends AbstractController
{
    public function execute(HTTPRequestCustom $request)
    {
        if (!DevToolsAuthorizationsService::check_authorizations()->moderation())
            return new JSONResponse(['success' => false, 'error' => 'Unauthorized'], 403);

        $module_id = preg_replace('/[^a-zA-Z0-9_\-]/', '', $request->get_string('id', ''));

        if (empty($module_id))
            return new JSONResponse(['success' => false, 'error' => 'Missing module identifier']);

        if (ModulesManager::is_module_installed($module_id))
            return new JSONResponse(['success' => false, 'error' => 'Module already installed']);

        $module_dir = PATH_TO_ROOT . '/modules/' . $module_id;
        if (!is_dir($module_dir))
            return new JSONResponse(['success' => false, 'error' => 'Module folder not found']);

        $result = ModulesManager::install_module($module_id);

        switch ($result)
        {
            case ModulesManager::MODULE_INSTALLED:
                $module = ModulesManager::get_module($module_id);
                HooksService::execute_hook_typed_action('install', 'module', $module_id, array_merge(
                    ['title' => $module->get_configuration()->get_name(), 'url' => ''],
                    $module->get_configuration()->get_properties()
                ));
                return new JSONResponse(['success' => true]);

            case ModulesManager::MODULE_ALREADY_INSTALLED:
                return new JSONResponse(['success' => false, 'error' => 'Module already installed']);

            case ModulesManager::CONFIG_CONFLICT:
                return new JSONResponse(['success' => false, 'error' => 'Configuration conflict']);

            case ModulesManager::PHP_VERSION_CONFLICT:
                return new JSONResponse(['success' => false, 'error' => 'Incompatible PHP version']);

            case ModulesManager::PHPBOOST_VERSION_CONFLICT:
                return new JSONResponse(['success' => false, 'error' => 'Incompatible PHPBoost version']);

            case ModulesManager::UNEXISTING_MODULE:
            default:
                return new JSONResponse(['success' => false, 'error' => 'Module introuvable ou erreur d\'installation']);
        }
    }
}
?>
