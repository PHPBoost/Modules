<?php

/* #################################################
 *                           EasyCssRGBAElement.class.php
 *                            -------------------
 *   begin                : 2016/05/22
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
 * Description of EasyCssRGBAElement
 *
 * @author PaperToss
 */
class EasyCssRGBAElement extends EasyCssAbstractElement
{

    use EasyCssColorTrait;
    
    protected $color;
    protected $color_id;
    protected $transparency;
    protected $transparency_id;

    public function __construct($id, $parent_id, $value)
    {
        parent::__construct($id, $parent_id, $value);
        $this->color_id = $this->parent_id . '/' . $this->id . '_color';
        $this->transparency_id = $this->parent_id . '/' . $this->id . '_transparency';
        $color = self::get_rgba_value_from_str($value);

        $values = explode(',', $color);

        $rgbcolor = $values[0] . ',' . $values[1] . ',' . $values[2];
        $this->color = new EasyCssRGBColorValue($this->color_id, $rgbcolor);
        $this->transparency = new EasyCssTransparencyValue($this->transparency_id, $values[3]);
    }

    public function get_templates($label = false)
    {
        if ($label === false)
        {
            $label = AdminEasyCssEditController::get_lang('color_description');
        }
        
        return [$this->color->get_form($label),
            $this->transparency->get_form(AdminEasyCssEditController::get_lang('transparency_description'))];
    }

    public function set_value_from_post(\HTTPRequestCustom $request)
    {
        $color_value = $request->get_poststring($this->color_id, false);
        $color_modif = $this->color->set_value($color_value);
        $transparency_value = $request->get_poststring($this->transparency_id, false);
        $transp_modif = $this->transparency->set_value($transparency_value);

        if ($color_modif || $transp_modif)
            return $this->get_text_to_file();
        return false;
    }

    public function get_text_to_file()
    {
        return 'rgba(' . $this->color->get_color() . ',' . $this->transparency->get_transparency() . ')';
    }

}
