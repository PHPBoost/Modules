<?php

/* #################################################
 *                           EasyCssTransparencyValue.class.php
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
 * Description of EasyCssTransparencyValue
 *
 * @author PaperToss
 */
class EasyCssTransparencyValue extends EasyCssAbstractValue
{
    protected $transparency;
    
    public function __construct($id, $transparency)
    {
        parent::__construct($id);
        $this->set_value($transparency);
    }
    
    public function set_value($transparency)
    {
        $transparency = floatval($transparency);
        if ($transparency < 0 || $transparency > 1)
            $transparency = 1;
        if ($this->transparency == $transparency)
        {
            return false;
        }
        $this->transparency = $transparency;
        return $this->transparency;
    }
    
    public function get_form($label)
    {
        $tpl = new FileTemplate('EasyCss/fields/EasyCssTransparencyField.tpl');
        $tpl->put_all(array(
            'NAME' => $this->id,
            'ID' => $this->id,
            'HTML_ID' => $this->id,
            'VALUE' => $this->transparency,
            'LABEL' => $label
        ));
        return $tpl;
    }
    
    public function get_transparency()
    {
        return $this->transparency;
    }
}
