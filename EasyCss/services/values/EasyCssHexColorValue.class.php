<?php

/* #################################################
 *                           EasyCsHexColorValue.class.php
 *                            -------------------
 *   begin                : 2016/05/19
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
 * Description of EasyCssHexColorValue
 *
 * @author PaperToss
 */
class EasyCssHexColorValue extends EasyCssAbstractValue
{
    
    protected $hex_color;
    
    public function __construct($id, $hex_color)
    {
        parent::__construct($id);
        
        $this->set_value($hex_color);
    }
    
    public function get_form($label)
    {
        $tpl = new FileTemplate('EasyCss/fields/EasyCssColorField.tpl');
        $tpl->put_all(array(
            'NAME' =>$this->id,
            'ID' => $this->id,
            'VALUE' => '#' . $this->hex_color,
            'LABEL' => $label,
        ));
        return $tpl;
    }
    
    public function get_color()
    {
        return $this->hex_color;
    }


    public function set_value($color)
    {
        $color=trim($color);
        if (TextHelper::substr($color,0,1) == '#' )
                $color = TextHelper::substr($color,1);
        if (TextHelper::strlen($color) == 3)
        {
            $str = '';
            for ($i=0; $i<= 2; $i++)
            {
                $str .= str_repeat(TextHelper::substr($color, $i, 1),2);
            }
            $color = $str;
        }
        if ($this->hex_color == $color)
        {
            return false;
        }
        $this->hex_color = $color;
        return $this->hex_color;
        
    }
    
}
