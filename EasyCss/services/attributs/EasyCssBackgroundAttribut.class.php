<?php

/* #################################################
 *                           EasyCssBackgroundAttribut.class.php
 *                            -------------------
 *   begin                : 2016/06/03
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
 * Description of EasyCssBackgroundAttribut
 *
 * @author PaperToss
 */
class EasyCssBackgroundAttribut extends EasyCssAbstractAttribut
{
    protected $name_attribut = 'background';

    public $to_display = true;
    
    /** @staticvar array Regex */
    public static $regex = [
        '`(?<=[^-])background\s*:(.*);`isuU',
    ];
    
    protected $separator = ' ';
    
    public function __construct($id, $parent_id, $matches)
    {
        $value = $matches[1];
        parent::__construct($id, $parent_id, $value);

        if ($this->on_error)
            return ;
        
        if (EasyCssColorsManager::is_color($this->values[0]))
        {
            $this->values[0] = EasyCssColorsManager::create_color('0', $this->parent_id . '/' . $this->id, $this->values[0]);
        }
        else
        {
            $this->values[0] = new EasyCssGenericElement('0', $this->parent_id . '/' . $this->id, $this->values[0]);
        }
        foreach ($this->values as $key => &$val)
        {
            if ($key === 0)                continue;
            $val = new EasyCssGenericElement($key, $this->parent_id . '/' . $this->id, $val);
        }
    }
    
    public function get_templates()
    {
        AdminEasyCssEditController::add_field_to_hidden_input($this->parent_id . '/' . $this->id);
        $tpls = [];
        foreach ($this->values as $key => $tpl)
        {
            $templates = $tpl->get_templates();
            foreach ($templates as $value_tpl)
            {
                $tpls[] = $value_tpl;
            }
            if (count($tpl) > 1)
                $tpls[] = new StringTemplate("<br/>");
        }

        return parent::get_templates($tpls, AdminEasyCssEditController::get_lang('background_description'));
    }
}
