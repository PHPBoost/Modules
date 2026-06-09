<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      LamPDL
 * @version     PHPBoost 6.0 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2026 03 01
 *
 * Main page: local modules table + remote repo panel.
 */

class DevToolsHomeController extends DefaultModuleController
{
    public function execute(HTTPRequestCustom $request)
    {
        $this->check_authorizations();
        $this->build_view();
        return $this->generate_response();
    }

    private function build_view()
    {
        $this->view = new FileTemplate('devtools/DevToolsHomeController.tpl');
        $this->view->add_lang(LangLoader::get_all_langs('devtools'));

        $config  = DevToolsConfig::load();
        $modules = DevToolsLocalService::get_local_modules();
        $repos   = $config->get_repos() ?: DevToolsConfig::DEFAULT_REPOS;

        // --- Local modules table rows ---
        $module_rows = '';
        foreach ((array)$modules as $mod)
        {
            $status_label = $this->lang['devtools.status.not.installed'];
            $status_class = 'pbtm-status-none';

            if ($mod['installed'] && $mod['activated'])
            {
                $status_label = $this->lang['devtools.status.active'];
                $status_class = 'pbtm-status-active';
            }
            elseif ($mod['installed'])
            {
                $status_label = $this->lang['devtools.status.inactive'];
                $status_class = 'pbtm-status-inactive';
            }

            $actions = '';
            $token   = AppContext::get_session()->get_token();

            if ($mod['installed'] && $mod['activated'])
            {
                $actions .= '<button class="pbtm-btn pbtm-btn-ok pbtm-action-activate" data-id="' . htmlspecialchars($mod['id']) . '" data-token="' . $token . '" title="' . htmlspecialchars($this->lang['devtools.action.activate.title']) . '" style="display:none">'
                    . $this->lang['devtools.action.activate'] . '</button> ';
                $actions .= '<button class="pbtm-btn pbtm-btn-warn pbtm-action-deactivate" data-id="' . htmlspecialchars($mod['id']) . '" data-token="' . $token . '" title="' . htmlspecialchars($this->lang['devtools.action.deactivate.title']) . '">'
                    . $this->lang['devtools.action.deactivate'] . '</button> ';
                $actions .= '<button class="pbtm-btn pbtm-btn-danger pbtm-action-uninstall" data-id="' . htmlspecialchars($mod['id']) . '" data-drop="0" data-token="' . $token . '" title="' . htmlspecialchars($this->lang['devtools.uninstall.soft.title']) . '">'
                    . $this->lang['devtools.action.uninstall.soft'] . '</button> ';
                $actions .= '<button class="pbtm-btn pbtm-btn-danger pbtm-action-uninstall" data-id="' . htmlspecialchars($mod['id']) . '" data-drop="1" data-token="' . $token . '" title="' . htmlspecialchars($this->lang['devtools.uninstall.hard.title']) . '">'
                    . $this->lang['devtools.action.uninstall.hard'] . '</button>';
            }
            elseif ($mod['installed'])
            {
                $actions .= '<button class="pbtm-btn pbtm-btn-ok pbtm-action-activate" data-id="' . htmlspecialchars($mod['id']) . '" data-token="' . $token . '" title="' . htmlspecialchars($this->lang['devtools.action.activate.title']) . '">'
                    . $this->lang['devtools.action.activate'] . '</button> ';
                $actions .= '<button class="pbtm-btn pbtm-btn-danger pbtm-action-uninstall" data-id="' . htmlspecialchars($mod['id']) . '" data-drop="0" data-token="' . $token . '" title="' . htmlspecialchars($this->lang['devtools.uninstall.soft.title']) . '">'
                    . $this->lang['devtools.action.uninstall.soft'] . '</button> ';
                $actions .= '<button class="pbtm-btn pbtm-btn-danger pbtm-action-uninstall" data-id="' . htmlspecialchars($mod['id']) . '" data-drop="1" data-token="' . $token . '" title="' . htmlspecialchars($this->lang['devtools.uninstall.hard.title']) . '">'
                    . $this->lang['devtools.action.uninstall.hard'] . '</button>';
            }
            else
            {
                $actions .= '<button class="pbtm-btn pbtm-btn-ok pbtm-action-local-install" data-id="' . htmlspecialchars($mod['id']) . '" data-token="' . $token . '">'
                    . $this->lang['devtools.action.local.install'] . '</button>';
            }

            $module_rows .= '<tr>'
                . '<td>' . htmlspecialchars($mod['name']) . '<br/><small class="pbtm-id">' . htmlspecialchars($mod['id']) . '</small></td>'
                . '<td>' . htmlspecialchars($mod['version'] ?? '—') . '</td>'
                . '<td><span class="pbtm-status ' . $status_class . '">' . $status_label . '</span></td>'
                . '<td class="pbtm-remote-version" data-id="' . htmlspecialchars($mod['id']) . '">—</td>'
                . '<td class="pbtm-actions">' . $actions . '</td>'
                . '</tr>';
        }

        // --- Repos select options ---
        $repo_options = '';
        foreach ($repos as $idx => $repo)
        {
            $label = htmlspecialchars($repo['label'] ?? $repo['owner'] . '/' . $repo['repo']);
            $repo_options .= '<option value="' . $idx . '" data-repo="' . htmlspecialchars(json_encode($repo)) . '">' . $label . '</option>';
        }

        $this->view->put_all([
            'C_IS_ADMIN'              => DevToolsAuthorizationsService::check_authorizations()->admin(),
            'U_CONFIG'                => ModulesUrlBuilder::configuration()->rel(),
            'MODULE_ROWS'             => $module_rows,
            'REPO_OPTIONS'            => $repo_options,
            'URL_AJAX_BRANCHES'       => DevToolsUrlBuilder::ajax_branches()->rel(),
            'URL_AJAX_FOLDERS'        => DevToolsUrlBuilder::ajax_folders()->rel(),
            'URL_AJAX_INSTALL'        => DevToolsUrlBuilder::ajax_install()->rel(),
            'URL_AJAX_ACTIVATE'       => DevToolsUrlBuilder::ajax_activate()->rel(),
            'URL_AJAX_DEACTIVATE'     => DevToolsUrlBuilder::ajax_deactivate()->rel(),
            'URL_AJAX_UNINSTALL'      => DevToolsUrlBuilder::ajax_uninstall()->rel(),
            'URL_AJAX_REPOS'          => DevToolsUrlBuilder::ajax_repos()->rel(),
            'URL_AJAX_SAVE_REPOS'     => DevToolsUrlBuilder::ajax_save_repo()->rel(),
            'URL_AJAX_LOCAL_INSTALL'  => DevToolsUrlBuilder::ajax_local_install()->rel(),
            'URL_AJAX_RESTORE'        => DevToolsUrlBuilder::ajax_restore()->rel(),
            'URL_AJAX_BACKUP'         => DevToolsUrlBuilder::ajax_backup()->rel(),
            'URL_AJAX_IMPORT_BDD'     => DevToolsUrlBuilder::ajax_import_bdd()->rel(),
            'URL_AJAX_REVIEW'         => DevToolsUrlBuilder::ajax_review()->rel(),
            'URL_AJAX_LANG'           => DevToolsUrlBuilder::ajax_lang()->rel(),
            'CSRF_TOKEN'              => AppContext::get_session()->get_token(),
        ]);
    }

    protected function get_template_string_content()
    {
        return '
            # INCLUDE MESSAGE_HELPER #
            # INCLUDE CONTENT #
        ';
    }

    private function check_authorizations()
    {
        $current_user = AppContext::get_current_user();
        if ($current_user->get_level() < User::MODERATOR_LEVEL)
        {
            $error_controller = PHPBoostErrors::user_not_authorized();
            DispatchManager::redirect($error_controller);
        }
    }

    private function generate_response()
    {
        $response              = new SiteDisplayResponse($this->view);
        $graphical_environment = $response->get_graphical_environment();
        $graphical_environment->set_page_title($this->lang['devtools.module.title']);
        $graphical_environment->get_seo_meta_data()->set_canonical_url(DevToolsUrlBuilder::home());

        $breadcrumb = $graphical_environment->get_breadcrumb();
        $breadcrumb->add($this->lang['devtools.module.title'], DevToolsUrlBuilder::home());

        return $response;
    }
}
?>
