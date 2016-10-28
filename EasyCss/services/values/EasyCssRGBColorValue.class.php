<?php

/* #################################################
 *                           EasyCsRGBColorValue.class.php
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
 * Description of EasyCssRGBColorValue
 *
 * @author PaperToss
 */
class EasyCssRGBColorValue extends EasyCssAbstractValue
{
    use EasyCssColorTrait;
    
    protected $rgb_color;
    
    protected $hex_color;
    
    public function __construct($id, $rgbcolor)
    {
        parent::__construct($id);
        
        $values = explode(',', $rgbcolor);
        $this->rgb_color = $values[0] . ',' . $values[1] . ',' . $values[2];
        $r = self::rgb_to_hex($values[0]);
        $g = self::rgb_to_hex($values[1]);
        $b = self::rgb_to_hex($values[2]);
        $this->hex_color = $r . $g . $b;
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
        return $this->rgb_color;
    }


    public function set_value($hexcolor)
    {
        $rgbcolor = self::hex_to_rgb($hexcolor);
        if ($this->rgb_color == $rgbcolor)
        {
            return false;
        }
        $this->rgb_color = $rgbcolor;
        if (TextHelper::substr($hexcolor,0,1) == '#' )
                $hexcolor = TextHelper::substr($hexcolor,1);
        $this->hex_color = $hexcolor;
        return $this->rgb_color;
    }
    
    
}
