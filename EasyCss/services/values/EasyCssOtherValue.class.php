<?php

/* #################################################
 *                           EasyCssOtherValue.class.php
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
 * Description of EasyCssOtherValue
 *
 * @author PaperToss
 */
class EasyCssOtherValue extends EasyCssAbstractValue
{
    protected $value;
    
    public function __construct($id, $value)
    {
        parent::__construct($id);
        $this->set_value($value);
    }
    
    public function set_value($value)
    {
        if ($this->value === $value)
        {
            return false;
        }
        $this->value = $value;
        return $this->value;
    }
    
    public function get_form($label)
    {
        $tpl = new FileTemplate('EasyCss/fields/EasyCssOtherField.tpl');
        $tpl->put_all(array(
            'NAME' => $this->id,
            'ID' => $this->id,
            'HTML_ID' => $this->id,
            'VALUE' => $this->value,
            'LABEL' => $label
        ));
        return $tpl;
    }
    
    public function get_value()
    {
        return $this->value;
    }
}
