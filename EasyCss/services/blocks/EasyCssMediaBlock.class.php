<?php

/* #################################################
 *                           EasyCssMediaBlock.class.php
 *                            -------------------
 *   begin                : 2016/05/03
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
 * Description of EasyCssMediaBlock
 *
 * @author PaperToss
 */
class EasyCssMediaBlock extends EasyCssAbstractBlock
{
    public $type;
    public $size;

    
    public $to_display = true;

    public function __construct($id, $parent_id, $value, $css_content)
    {
        $this->id = $id;
        $this->parent_id = $parent_id;
        $this->css_content = $css_content .'}';
        $values = explode(':', $value);
        $this->type = trim($values[0]);
        $this->size = trim($values[1]);
        $this->parse_blocks_content();
    }
    
    protected function parse_blocks_content()
    {
        $css = $this->parse_block($this->css_content);
        $css = $this->parse_title_block($css);
        $css = $this->parse_display_comment_block($css);
        $css = $this->parse_comment_block($css);
        
        $this->parsed_css = str_replace('}', '', $css);
    }
    
    public function get_templates()
    {
        $tpls = parent::get_templates();
        if (!empty($tpls))
        {
            array_unshift($tpls, new StringTemplate('<div class="easycss-block"><h6>' . $this->get_title_block() . '</h6>'));
            array_push($tpls, new StringTemplate('</div>'));
        }
        return $tpls;
    }
    
    private function get_title_block()
    {
        $title = LangLoader::get_message(trim($this->type), 'common', 'EasyCss');
        return $title . ' : ' . $this->size;
    }
    
    public function get_child_name($path_child)
    {
        $path = explode('/', $path_child);
        $child = $path[0];
        array_shift($path);
        $path = implode('/', $path);
        return __CLASS__ . ':' . $this->type . ':' . $this->size . '/' . $this->children[$child]->get_child_name($path);
    }
    
    public function find_id($id)
    {
        $path = explode('/', $id);

        $child = $path[0];
        array_shift($path);
        
        $path = implode('/', $path);
        return $this->children[$child]->find_id($path);
    }

    public function get_css_to_save()
    {
        $css = parent::get_css_to_save();
        $css = "\n@media (" . $this->type . ": " . $this->size . ") {\n" . $css;
        $css = $css . "}\n";
        return $css;
    }
}
