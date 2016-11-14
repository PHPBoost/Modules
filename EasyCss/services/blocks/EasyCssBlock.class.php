<?php

/* #################################################
 *                           EasyCssBlockElement.class.php
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
 * Bloc de type standard (#id { color : ... } )
 *
 * @author PaperToss
 */
class EasyCssBlock extends EasyCssAbstractBlock
{

    protected $tag;
    public $to_display = true;

    public function __construct($id, $parent_id, $tag, $css_content)
    {
        $this->id = $id;
        $this->parent_id = $parent_id;
        $this->css_content = $css_content;
        $this->tag = $tag;
        $this->parse_elements_content();
    }

    protected function parse_elements_content()
    {
        $css = $this->parse_title_block($this->css_content);
        $css = $this->parse_display_comment_block($css);
        $css = $this->parse_comment_block($css);

        // Parsage des éléments gérés
        foreach (EasyCssAbstractAttribut::$attributs as $name)
        {
            /* @var \EasyCssAbstractAttribut $name */
            foreach ($name::$regex as $regex)
            {
                $css = preg_replace_callback($regex, function ($matches) use($name)
                {
                    $this->counter++;
                    $this->children[$this->counter] = new $name($this->counter, $this->parent_id . '/' . $this->id, $matches);
                    return "\n" . '###' . $this->counter . '/###' . "\n";
                }, $css);
            }
        }

        // Parsage des éléments génériques
        $css = preg_replace_callback('`([^#{3}|^\/| ]*)\s*:\s*(.*)\s*;`isuU', function ($matches)
        {
            $this->counter++;
            $this->children[$this->counter] = new EasyCssGenericAttribut($this->counter, $this->parent_id . '/' . $this->id, $matches[1], $matches[2]);
            return "\n" . '###' . $this->counter . '/###' . "\n";
        }, $css);

        $this->parsed_css = $css;
    }

    public function get_templates()
    {
        $tpls = parent::get_templates();
        if (!empty($tpls))
        {
            array_unshift($tpls, new StringTemplate('<div class="easycss-block"><h6>' . $this->tag . '</h6>'));
            array_push($tpls, new StringTemplate('</div>'));
        }
        return $tpls;
    }

    protected function set_value_from_post($path_child, \HTTPRequestCustom $request)
    {
        $path = explode('/', $path_child);
        $child = $path[0];
        array_shift($path);
        return $this->children[$child]->set_value_from_post($request);
    }

    public function get_child_name($path_child)
    {
        $path = explode('/', $path_child);
        $child = $path[0];
        array_shift($path);
        $path = implode('/', $path);
        return __CLASS__ . ':' . $this->tag . '/' . $this->children[$child]->get_child_name();
    }

    public function find_id($id)
    {
        $path = explode('/', $id);

        $child = $path[0];
        array_shift($path);
        $path = implode('/', $path);
        return $this->children[$child];
    }

    public function get_css_to_save()
    {
        $css = parent::get_css_to_save();
        $css = "\n" . $this->get_spaces() . $this->tag . " {\n" . $css;
        $css = $css . $this->get_spaces() . "}\n";
        return $css;
    }

}
