<?php

/* #################################################
 *                           EasyCssAbstractBlock.class.php
 *                            -------------------
 *   begin                : 2016/05/06
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
 * Classe parente des blocs
 *
 * @author PaperToss
 */
abstract class EasyCssAbstractBlock
{
    /** @var string         ID de l'élément */
    public $id;
    
    /** @var string         ID du parent direct */
    protected $parent_id;
    
    /** @var string         Contenu CSS original de l'élément */
    protected $css_content;
    
    /** @var string         Contenu du fichier parsé */
    protected $parsed_css;
    
    /** @var array          Blocks ou éléments enfants (si EasyCssBlock) */
    protected $children = [];
    
    /** @var integer        Compteur de remplacement */
    protected $counter = 0;
    
    /** @var boolean        Si le contenu du bloc doit être affiché lors de l'édition */
    public $to_display;
    
    public $on_error = false;


    /**
     * Récupération des templates des blocs enfants
     * 
     * @return \FileTemplate array      Tableau de templates des enfants du bloc
     */
    public function get_templates()
    {
        if ($this->to_display === false) return false;
        $tpls = [];
        $lines = explode("\n", $this->parsed_css);
        foreach ($lines as $line)
        {
            if (preg_match('`###(\d+)\/###`isuU', $line, $matches))
            {
                /* @var $block \EasyCssAbstractBlock */       
                $block = $this->children[$matches[1]];
                if ($block->to_display && $block->on_error === false)
                {
                    // Si le bloc doit être affiché
                    $templates = $block->get_templates();
                    if (!is_array($templates))
                        $templates = [$templates];
                    foreach ($templates as $template)
                    {
                        $tpls[] = $template;
                    }
                }
            }
        }
        return $tpls;
    }
    
    /**
     * Récupération du code CSS du bloc et ses enfants
     * 
     * @return string   Contenu CSS des éléments
     */
    public function get_css_to_save()
    {
        $css = '';
        $lines = explode("\n", $this->parsed_css);
        foreach ($lines as $line)
        {
            if (preg_match('`###(\d+)\/###`isuU', $line, $matches))
            {
                /** @var \EasyCssAbstractBlock $block */       
                $block = $this->children[$matches[1]];
                if (get_parent_class($block) === __CLASS__)
                {
                    // Enfant de type EasyCssAbstractBlock
                    $css.= $this->get_spaces() . '    ' . $block->get_css_to_save();
                }
                else
                {
                    // Enfant de type EasyCssAbstractElement
                    $css.= $this->get_spaces() . '    ' .$block->get_text_to_file() . "\n";
                }   
            }
        }
        return $css;
    }
    
    /**
     * Indique à l'enfant qu'il doit aller récupérer sa valeur depuis le POST
     * 
     * @param string                Chemin de l'enfant
     * @param \HTTPRequestCustom    $request
     * @return mixed                Nouvelle valeur si l'élément a été modifié, sinon false
     */
    protected function set_value_from_post($path_child, \HTTPRequestCustom $request)
    {
        $path = explode('/', $path_child);
        $child = $path[0];
        array_shift($path);
        $path = implode('/', $path);
        return $this->children[$child]->set_value_from_post($path, $request);
    }
    
    /**
     * Parse les éléments de type EasyCssTitleBlock
     * 
     * @param   string  Contenu CSS du bloc
     * @return  string  Contenu CSS parsé
     */
    protected function parse_title_block($css)
    {
        return preg_replace_callback('`\/\*\*\s*-{3}(.+)-{3}\s*\*\/`isuU', array($this, 'replace_parse_title_block'), $css );
    }
    
    /**
     * Parse les éléments de type EasyCssDisplayCommentBlock
     * 
     * @param   string  Contenu CSS du bloc
     * @return  string  Contenu CSS parsé
     */
    protected function parse_display_comment_block($css)
    {
        return preg_replace_callback('`\/\*\*(.+)\*\/`isuU', array($this, 'replace_parse_display_comment_block'), $css );
    }
    
    /**
     * Parse les éléments de type EasyCssCommentBlock
     * 
     * @param   string  Contenu CSS du bloc
     * @return  string  Contenu CSS parsé
     */
    protected function parse_comment_block($css)
    {
        return preg_replace_callback('`\/\*(.+)\*\/`isuU', array($this, 'replace_parse_comment_block'), $css );
    }
    
    /**
     * Parse les éléments de type EasyCssMediaBlock
     * 
     * @param   string  Contenu CSS du bloc
     * @return  string  Contenu CSS parsé
     */
    protected function parse_media_block($css)
    {
        return preg_replace_callback('`@media\s*\((.+)\)\s*\{(.*)\}\s*\}`isuU', array($this, 'replace_parse_media_block'), $css );
    }
    
    /**
     * Parse les éléments de type EasyCssBlock
     * 
     * @param   string  Contenu CSS du bloc
     * @return  string  Contenu CSS parsé
     */
    protected function parse_block($css)
    {
        return preg_replace_callback('`\s*((?:(?!#\s|\/).)*)\{(.*)\}`isuU', array($this, 'replace_parse_block'), $css );
    }
    
    /**
     * Remplace le code CSS complet du bloc EasyCssTitleBlock
     * Créé un nouvel objet EasyCssTitleBlock et le stocke dans le tableau $children du bloc parent
     * Retourne la chaine de remplacement
     * 
     * @param   array   Elements de la recherche du preg_replace
     * @return  string  Chaine de remplacement
     */
    protected function replace_parse_title_block($matches)
    {
        $this->counter++;
        
        $this->children[$this->counter] = new EasyCssTitleBlock($this->counter, $this->parent_id .'/' . $this->id, $matches[1]);
        return "\n" . '###' . $this->counter . '/###' . "\n";
    }
    
    /**
     * Remplace le code CSS complet du bloc EasyCssDisplayCommentBlock
     * Créé un nouvel objet EasyCssDisplayCommentBlock et le stocke dans le tableau $children du bloc parent
     * Retourne la chaine de remplacement
     * 
     * @param   array   Elements de la recherche du preg_replace
     * @return  string  Chaine de remplacement
     */
    protected function replace_parse_display_comment_block($matches)
    {
        $this->counter++;
        
        $this->children[$this->counter] = new EasyCssDisplayCommentBlock($this->counter, $this->parent_id .'/' . $this->id,$matches[1]);
        return "\n" . '###' . $this->counter . '/###' . "\n";
    }
    
    /**
     * Remplace le code CSS complet du bloc EasyCssCommentBlock
     * Créé un nouvel objet EasyCssCommentBlock et le stocke dans le tableau $children du bloc parent
     * Retourne la chaine de remplacement
     * 
     * @param   array   Elements de la recherche du preg_replace
     * @return  string  Chaine de remplacement
     */
    protected function replace_parse_comment_block($matches)
    {
        $this->counter++;
        
        $this->children[$this->counter] = new EasyCssCommentBlock($this->counter, $this->parent_id .'/' . $this->id,$matches[1]);
        return "\n" . '###' . $this->counter . '/###' . "\n";
    }
    
    /**
     * Remplace le code CSS complet du bloc EasyCssMediaBlock
     * Créé un nouvel objet EasyCssMediaBlock et le stocke dans le tableau $children du bloc parent
     * Retourne la chaine de remplacement
     * 
     * @param   array   Elements de la recherche du preg_replace
     * @return  string  Chaine de remplacement
     */
    protected function replace_parse_media_block($matches)
    {
        $this->counter++;

        $this->children[$this->counter] = new EasyCssMediaBlock($this->counter, $this->parent_id .'/' . $this->id,preg_replace('/\s{2,}/u', ' ', trim(str_replace("\n\n", "\n", $matches[1]))), $matches[2]);
        return "\n" . '###' . $this->counter . '/###' . "\n";
    }
    
    /**
     * Remplace le code CSS complet du bloc EasyCssBlock
     * Créé un nouvel objet EasyCssBlock et le stocke dans le tableau $children du bloc parent
     * Retourne la chaine de remplacement
     * 
     * @param   array   Elements de la recherche du preg_replace
     * @return  string  Chaine de remplacement
     */
    protected function replace_parse_block($matches)
    {
        if (!isset($matches[1])) return '';
        
        $this->counter++;
        $this->children[$this->counter] = new EasyCssBlock($this->counter, $this->parent_id .'/' . $this->id, preg_replace('/\s{2,}/u', ' ', trim(str_replace("\n\n", "\n", $matches[1]))), $matches[2]);
        return "\n" . '###' . $this->counter . '/###' . "\n";
    }
    
    /**
     * Créé l'indentation
     * 
     * Retourne un certain nombre d'espaces en fonction du parent du bloc
     * 
     * @return string   Chaine d'espaces
     */
    protected function get_spaces()
    {
        if ($this->parent_id === '' || $this->parent_id === '/main') return '';
        return '    ';
    }
    
}
