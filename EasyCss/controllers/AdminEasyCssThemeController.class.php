<?php
/**
 * Recense et affiche les fichiers CSS utilisés par thème installé
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2018 11 14
 * @since       PHPBoost 5.0 - 2016 04 22
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class AdminEasyCssThemeController extends ModuleController
{
    /** @var \FileTemplate */
    private $view;

    private $lang;

    public function execute(\HTTPRequestCustom $request)
    {
        $this->init();

        $this->get_themes_folders();

        return $this->build_response($this->view);
    }

    private function init()
    {
        $this->lang = LangLoader::get('common', 'EasyCss');
        $this->view = new FileTemplate('EasyCss/AdminThemeController.tpl');
        $this->view->add_lang($this->lang);
    }

    private function build_response(View $view)
    {
        $response = new AdminDisplayResponse($view);
        $response->get_graphical_environment()->set_page_title($this->lang['module_title']);
        return $response;
    }

    /**
     * Retourne les dossiers 'theme' des différents thèmes installés
     *
     * @return \Folder
     */
    private function get_themes_folders()
    {

        /** @var \Theme */
        $obj_themes = ThemesManager::get_installed_themes_map();

        foreach ($obj_themes as $theme)
        {
            $this->view->assign_block_vars('themes', array(
                'NAME' => $theme->get_id(),
                'DEFAULT' => ($theme->get_id() === ThemesManager::get_default_theme()) ? true : false,
            ));

            $theme_folder = new Folder(PATH_TO_ROOT . '/templates/' . $theme->get_id() . '/theme');

            foreach ($theme_folder->get_files('`\.css$`iu') as $file)
            {
                $this->view->assign_block_vars('themes.css', array(
                    'NAME'  => $file->get_name(),
                    'URL'   => EasyCssUrlBuilder::edit($theme->get_id(), $file->get_name_without_extension())->rel(),
                ));
            }
        }
    }
}
