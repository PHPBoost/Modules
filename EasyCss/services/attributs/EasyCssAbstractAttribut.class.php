<?php

/* #################################################
 *                           EasyCssAbstractAttribut.class.php
 *                            -------------------
 *   begin                : 2016/05/20
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
 * Classe mère des attributs à parser
 *
 * @abstract
 * @author PaperToss
 */
abstract class EasyCssAbstractAttribut
{
    /** @var string     ID de l'attribut */
    protected $id;
    
    /** @var string     ID complet du bloc parent */
    protected $parent_id;
    
    /** @var string     Contenu CSS brut de l'attribut */
    protected $raw_value;
    
    /** @var array      Eléments enfants de l'attribut */
    protected $values = [];

    /** @var bool     Si l'attribut à la propriété !important */
    private $is_important = false;
    
    /** @var bool       Attribut mis en erreur */
    public $on_error = false;
    
    /** @var bool       Attribut modifié sur la page d'édition */
    protected $is_modified = false;

    /** @var string     Nom de l'attribut, sert pour l'affichage des erreurs */
    protected $name_attribut = '';
    
    /** @var string     Template à afficher avant les éléments */
    protected $begin_block = '<div class="easycss-field">';
    
    /** @var string     Template à afficher après les éléments */
    protected $end_block = '</div>';
    
    /** @var bool       Attribut à afficher sur la page d'édition */
    public $to_display = true;
    
    /** @var string     Separateur d'éléments, false si l'attribut ne doit comporter qu'un élément */
    protected $separator = ' ';
    
    /** @var string     ID du champ important sur la page d'édition */
    private $important_field_id;
    
    /** @var \EasyCssAbstractAttribut array  Attributs à parser*/
    public static $attributs = [
        'EasyCssColorAttribut',
        'EasyCssBorderColorAttribut',
        'EasyCssBackgroundColorAttribut',
        'EasyCssBackgroundAttribut',
        'EasyCssBorderXColorAttribut',
        'EasyCssBorderAttribut',
        'EasyCssBorderXAttribut',
    ];
    
    /** @staticvar array Différents Regex de l'attribut */
    public static $regex = [];

    /**
     * Constructeur protégé à appeler depuis chaque attribut
     * 
     * @param string ID de l'élément
     * @param string ID du parent
     * @param string Valeur brute du contenu de l'attribut
     */
    protected function __construct($id, $parent_id, $value)
    {
        $this->id = $id;
        $this->parent_id = $parent_id;
        $this->raw_value = trim($value);
        $this->check_important();
        $this->explode_elements();
        $this->important_field_id = $this->parent_id .'/' . $this->id . '_important';
    }
    
    /**
     * Ajoute une erreur à l'attribut
     * Met cet attribut en erreur et donc ne sera pas affiché
     * 
     * @param type $msg
     */
    public function add_error($msg)
    {
        $tpl = new StringTemplate('<div>' . $this->name_attribut . ' : ' . $msg . '</div>');
        AdminEasyCssEditController::add_error($tpl);
        $this->on_error = true;
    }
    
    /**
     * Templates à afficher
     * Retourne les templates des différents fields qui composent l'élément
     * 
     * @return \FileTemplate Template ou tableau de templates
     */
    public function get_templates($tpl, $label = false)
    {
        if (!is_array($tpl))
            $tpls[] = $tpl;
        else
            $tpls = $tpl;
        
        $begin_tpl = new StringTemplate($this->begin_block);
        $end_tpl = new StringTemplate($this->end_block);
        if ($label !== false)
        {
            $label_tpl = new StringTemplate('<h6>' . $label . '</h6>');
            array_unshift($tpls, $label_tpl);
        }
        array_push($tpls, $this->get_important_tpl());
        array_unshift($tpls, $begin_tpl);
        array_push($tpls, $end_tpl);
        return $tpls;
    }
    
    public function set_value_from_post(\HTTPRequestCustom $request)
    {
        $this->set_autovalues_from_post($request);
        
        foreach ($this->values as $key => &$val)
        {
            $modified_element = $val->set_value_from_post($request);
            if ($modified_element !== false)
            {
                $this->is_modified = true;
            }
        }
        if ($this->is_modified === true)
        {
            return $this->get_values_text();
        }
        return false;
    }
    /**
     * Récupération et assignation auto des propriétés communes
     * 
     * @param \HTTPRequestCustom $request
     */
    protected function set_autovalues_from_post(\HTTPRequestCustom $request)
    {
        // Propriété !important
        $imp = $request->get_poststring($this->important_field_id, false);
        $imp = ($imp !== false) ? true : false;
        if ($imp !== $this->is_important)
            $this->is_modified = true;
        $this->is_important = $imp;
    }


    /**
     * Texte de retour
     * Retourne le texte qui sera écrit dans le fichier CSS après modification
     * 
     * @return string Déclaration pour enregistrement dans le fichier CSS
     */
    public function get_text_to_file()
    {
        if ($this->on_error)
        {
            return $this->name_attribut . ' : ' . trim($this->raw_value) . $this->get_important_text() . ';' ;
        }
        return $this->name_attribut . ' : ' . $this->get_values_text() . ';';
    }
    
    /**
     * Retourne le contenu CSS des éléments de l'attribut
     * 
     * @return string Valeur des éléments de l'attribut
     */
    protected function get_values_text()
    {
        if ($this->separator === false)
        {
            return $this->values[0]->get_text_to_file() . $this->get_important_text();
        }
        $str = '';
        foreach ($this->values as $val)
        {
            $str .= $val->get_text_to_file() . $this->separator;
        }
        return $str . $this->get_important_text();
    }
    
    /**
     * Retourne le nom de la classe de l'élément
     * 
     * @return string   Nom de la classe de l'élément
     */
    public function get_child_name()
    {
        return get_called_class();
    }
    
    final protected function get_important_text()
    {
        return ($this->is_important === false) ? false : ' !important';
    }

    /**
     * Définition de la propriété is_important au constructeur
     */
    private function check_important()
    {
        $pos = TextHelper::strpos($this->raw_value, '!important');
        if ($pos !== false)
        {
            $this->raw_value = str_replace('!important', '', $this->raw_value);
            $this->is_important = true;
        }
    }
    
    /**
     * Explosion des valeurs selon le séparateur
     * 
     * @return array    Valeurs
     */
    private function explode_elements()
    {
        if ($this->separator === false)
        {
            $this->values[] = trim($this->raw_value);
            return;
        }
        $this->raw_value = EasyCssColorsManager::sanitise($this->raw_value);
        $this->values = explode($this->separator, trim($this->raw_value));
        foreach ($this->values as &$val)
        {
            if ($val === '0')
                $val = 'none';
        }
        $this->values = array_values(array_filter($this->values));
        
        if (empty($this->values))
            $this->add_error('Wrong arguments : ' . $this->raw_value);
    }
    
    /**
     * Création et récupération du template de la propriété !important
     * 
     * @return \FileTemplate Template de la propriété Important
     */
    private function get_important_tpl()
    {
        $imp_tpl = new FileTemplate('EasyCss/fields/EasyCssImportantField.tpl');
        $imp_tpl->put_all(array(
            'NAME' => $this->important_field_id,
            'ID' => $this->important_field_id,
            'HTML_ID' => $this->important_field_id,
            'CHECKED' => ($this->is_important !== false) ? 'checked="checked"' : '',
            'LABEL' => AdminEasyCssEditController::get_lang('important')
        ));
        return $imp_tpl;
    }
    
}
