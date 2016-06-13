<?php

/* #################################################
 *                           EasyCssColorAttribut.class.php
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
 * Description of EasyCssColorAttribut
 *
 * @author PaperToss
 */
class EasyCssColorAttribut extends EasyCssAbstractAttribut
{

    protected $name_attribut = 'color';
    
    public $to_display = true;
    
    protected $separator = false;

    /** @staticvar array Regex */
    public static $regex = [
        '`(?<=[^-])color\s*:(.*);`isU',
    ];

    public function __construct($id, $parent_id, $matches)
    {
        $value = $matches[1];
        parent::__construct($id, $parent_id, $value);
        if (!EasyCssColorsManager::is_color($this->values[0]))
        {
            $this->add_error('Wrong arguments : ' . $this->values[0]);
        } 
        else
        {
            foreach ($this->values as $key => &$val)
            {
                $val = EasyCssColorsManager::create_color($key, $this->parent_id . '/' . $this->id, $val);
            }
        }
    }
    
    public function get_templates()
    {
        AdminEasyCssEditController::add_field_to_hidden_input($this->parent_id . '/' . $this->id);
        $tpls = [];
        foreach ($this->values as $tpl)
        {
            $templates = $tpl->get_templates();
            foreach ($templates as $value_tpl)
            {
                $tpls[] = $value_tpl;
            }
        }

        return parent::get_templates($tpls, AdminEasyCssEditController::get_lang('color_description'));
    }
       

}
