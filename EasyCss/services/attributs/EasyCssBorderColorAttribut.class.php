<?php

/* #################################################
 *                           EasyCssBorderColorAttribut.class.php
 *                            -------------------
 *   begin                : 2016/05/26
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
 * Description of EasyCssBorderColorAttribut
 *
 * @author PaperToss
 */
class EasyCssBorderColorAttribut extends EasyCssAbstractAttribut
{
    protected $name_attribut = 'border-color';
    
    public $to_display = true;
    
    protected $separator = ' ';


    /** @staticvar array Regex */
    public static $regex = [
        '`(?<=[^-])border-color\s*:(.*);`isuU',
    ];
    
    public function __construct($id, $parent_id, $matches)
    {
        $value = $matches[1];
        parent::__construct($id, $parent_id, $value);

        if ($this->on_error)
            return ;
        foreach ($this->values as $value)
        {
            if (!EasyCssColorsManager::is_color($value))
            {
                $this->add_error('Wrong arguments : ' . $this->raw_value);
                break;
            }
        }
        if (!$this->on_error)
            $this->build_borders_array();
    }
    
    public function get_templates()
    {
        AdminEasyCssEditController::add_field_to_hidden_input($this->parent_id . '/' . $this->id);
        $tpls = [];
        foreach ($this->values as $key => $tpl)
        {
            $templates = $tpl->get_templates(AdminEasyCssEditController::get_lang($key));
            foreach ($templates as $value_tpl)
            {
                $tpls[] = $value_tpl;
            }
            $tpls[] = new StringTemplate("<br/>");
        }

        return parent::get_templates($tpls, AdminEasyCssEditController::get_lang('border_color_description'));
    }
    
    private function build_borders_array()
    {
        $borders = [];
        $borders['top'] = EasyCssColorsManager::create_color('top', $this->parent_id . '/' . $this->id, $this->values[0]);
        switch (count($this->values))
        {
            case 1 :
                $right = $bottom = $left = 0;
                break;
            case 2 :
                $bottom = 0;
                $right = $left = 1;
                break;
            case 3 :
                $right = $left = 1;
                $bottom = 2;
                break;
            case 4 :
                $right = 1;
                $bottom = 2;
                $left = 3;
                break;
            default :
                $this->add_error('Wrong arguments : ' . $this->raw_value);
                return;
        }
        $borders['right'] = EasyCssColorsManager::create_color('right', $this->parent_id . '/' . $this->id, $this->values[$right]);
        $borders['bottom'] = EasyCssColorsManager::create_color('bottom', $this->parent_id . '/' . $this->id, $this->values[$bottom]);
        $borders['left'] = EasyCssColorsManager::create_color('left', $this->parent_id . '/' . $this->id, $this->values[$left]);
        $this->values = $borders;
    }
}
