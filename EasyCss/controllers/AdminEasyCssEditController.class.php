<?php

/* #################################################
 *                           AdminEasyCssEditController.class.php
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
 * Cette page permet la modification des fichiers CSS contenus dans le dossier 'theme' des thèmes installés
 *
 * @author PaperToss
 */
class AdminEasyCssEditController extends ModuleController
{

    /** @var \FileTemplate  Vue */
    private $view;

    /** @staticvar array          Array de langue */
    private static $lang;

    /** @var \File          Fichier CSS chargé */
    private $file;

    /** @var string         Nom du thème */
    private $theme_id;

    /** @var string         Nom du fichier css */
    private $css_id;

    /** @staticvar int      Compteur de remplacement */
    public static $counter;

    /** @staticvar array    Elements remplacés */
    public static $vars;

    /** @staticvar string   Contenu du champ hidden (ID des éléments à modifier) */
    private static $hidden_input_content = '';

    /** @staticvar array    Erreurs à afficher */
    private static $errors = [];

    /** @staticvar \EasyCssMainBlock  Bloc Principal du CSS */
    private static $main_block;

    /**
     * Exécution de la page
     * 
     * @param \HTTPRequestCustom $request
     * @return \AdminDisplayResponse
     */
    public function execute(\HTTPRequestCustom $request)
    {
        $this->get_file($request);
        $this->init();

        $this->create_objects_elements();

        if ($request->is_post_method())
        {
            $this->post_process($request);
            $this->create_objects_elements();
        }

        $this->put_templates();

        return $this->build_response($this->view);
    }

    /**
     * Création de la langue et la vue
     */
    private function init()
    {
        self::$lang = LangLoader::get('common', 'EasyCss');
        $this->view = new FileTemplate('EasyCss/AdminEditController.tpl');
        $this->view->add_lang(self::$lang);
    }

    public static function get_lang($title)
    {
        return isset(self::$lang[$title]) ? self::$lang[$title] : '';
    }
    /**
     * Récupération du fichier CSS à modifier
     * 
     * @param \HTTPRequestCustom $request
     */
    private function get_file(\HTTPRequestCustom $request)
    {
        $this->theme_id = $request->get_getstring('theme', false);
        $this->theme_id = trim($this->theme_id, '/');

        $this->css_id = $request->get_getstring('file', false);
        $this->css_id = trim($this->css_id, '/');

        $file_path = PATH_TO_ROOT . '/templates/' . $this->theme_id . '/theme/' . $this->css_id . '.css';
        $this->file = new File($file_path);

        if (!$this->file->exists())
            DispatchManager::redirect(PHPBoostErrors::unexisting_page());
    }

    /**
     * Création de l'objet MainBlock et de tous ses enfants
     * Parsage du CSS
     */
    private function create_objects_elements()
    {
        $css = $this->file->read();
        self::$main_block = new EasyCssMainBlock($css);
    }

    /**
     * Envoi des templates et du contenu du hidden à la vue
     */
    private function put_templates()
    {
        // Templates des attributs
        $forms_tpl = self::$main_block->get_templates();
        foreach ($forms_tpl as $tpl)
        {
            $tpls[] = array('SUBTEMPLATE' => $tpl);
        }

        // Templates des erreurs
        $error_tpls = [];
        foreach (self::$errors as $error_tpl)
        {
            $error_tpls[] = array('SUBTEMPLATE' => $error_tpl);
        }

        // Pousse à la vue
        $this->view->put_all([
            'errors' => $error_tpls,
            'elements' => $tpls,
            'ELEMENTS_FIELDS' => self::$hidden_input_content,
            'FIELDSET_LEGEND' => self::$lang['file_edit'] . $this->theme_id . ' / ' . $this->css_id,
        ]);
    }

    /**
     * Exécution des éléments POST
     * Récupération du champ hidden, remplacement des objets avec les nouvelles valeurs
     * Ecriture du CSS et suppression du cache CSS
     * 
     * @param \HTTPRequestCustom $request
     */
    private function post_process(\HTTPRequestCustom $request)
    {
        $post_elements = $request->get_poststring(__CLASS__ . '_elements_fields', false);
        self::$main_block->replace_with_post($post_elements, $request);
        $this->write_to_file();
        $this->clear_css_cache();
        $this->view->put('MSG', MessageHelper::display(self::$lang['file_edit_success'], MessageHelper::SUCCESS, 5));
    }

    /**
     * Création et retour de la reponse
     * 
     * @param View $view
     * @return \AdminDisplayResponse
     */
    private function build_response(View $view)
    {
        $response = new AdminDisplayResponse($view);
        $response->get_graphical_environment()->set_page_title(self::$lang['module_title']);
        return $response;
    }

    /**
     * Ecriture du fichier CSS
     */
    private function write_to_file()
    {
        $this->file->write(trim(self::$main_block->get_css_to_save()));
        $this->clear_css_cache();
    }

    /**
     * Suppression du cache CSS si activé
     */
    private function clear_css_cache()
    {
        $css_cache_config = CSSCacheConfig::load();
        if ($css_cache_config->is_enabled())
        {
            AppContext::get_cache_service()->clear_css_cache();
        }
    }

    /**
     * Ajoute un élément au champ hidden
     * 
     * @static
     * @param string    ID complet du champ à ajouter
     */
    public static function add_field_to_hidden_input($id)
    {
        self::$hidden_input_content .= $id . ';';
    }

    /**
     * Ajoute une erreur de contenu
     * 
     * @static
     * @param string    Erreur à afficher
     */
    public static function add_error(\Template $tpl)
    {
        self::$errors[] = $tpl;
    }

    /**
     * Retourne un attribut depuis son ID
     * 
     * @static
     * @param string    ID complet de l'élément
     * @return \EasyCssAbstractAttribut
     */
    public static function get_element($id)
    {
        return self::$main_block->find_id($id);
    }

}
