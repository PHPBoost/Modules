<?php

/* #################################################
 *                           AdminEasyCssThemeController.class.php
 *                            -------------------
 *   begin                : 2016/04/22
 *   copyright            : (C) 2016 PaperToss
 *   email                : t0ssp4p3r@gmail.com
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
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
  ################################################### */

/**
 * Recense et affiche les fichiers CSS utilisés par thème installé
 *
 * @author PaperToss
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
