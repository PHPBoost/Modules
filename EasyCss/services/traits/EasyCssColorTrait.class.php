<?php

/* #################################################
 *                           EasyCssColorTrait.class.php
 *                            -------------------
 *   begin                : 2016/00/01
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
 * Description of EasyCssColorTrait
 *
 * @author PaperToss
 */
trait EasyCssColorTrait
{
    protected static function rgb_to_hex($n)
    {
        $n = intval($n);
        if (!$n)
            return '00';

        $n = max(0, min($n, 255)); // s'assurer que 0 <= $n <= 255
        $index1 = (int) ($n - ($n % 16)) / 16;
        $index2 = (int) $n % 16;

        return TextHelper::substr("0123456789ABCDEF", $index1, 1)
                . TextHelper::substr("0123456789ABCDEF", $index2, 1);
    }

    protected static function hex_to_rgb($hex)
    {
        $hex = str_replace("#", "", $hex);

        if (TextHelper::strlen($hex) == 3)
        {
            $r = hexdec(TextHelper::substr($hex, 0, 1) . TextHelper::substr($hex, 0, 1));
            $g = hexdec(TextHelper::substr($hex, 1, 1) . TextHelper::substr($hex, 1, 1));
            $b = hexdec(TextHelper::substr($hex, 2, 1) . TextHelper::substr($hex, 2, 1));
        } else
        {
            $r = hexdec(TextHelper::substr($hex, 0, 2));
            $g = hexdec(TextHelper::substr($hex, 2, 2));
            $b = hexdec(TextHelper::substr($hex, 4, 2));
        }
        $rgb = array($r, $g, $b);
        return implode(",", $rgb); 
    }
    
    protected static function get_hex_value_from_str($value)
    {
        preg_match('`^\s*#([0-9a-fA-F]{6}|[0-9a-fA-F]{3})\s*$`iu', $value, $matches);
        return $matches[1];
    }
    
    protected static function get_rgba_value_from_str($value)
    {
        preg_match('`\s*rgba\s*\((.*)\)\s*`iu', $value, $matches);
        return $matches[1];
    }
    
    protected static function get_rgb_value_from_str($value)
    {
        preg_match('`\s*rgb\s*\((.*)\)\s*`iu', $value, $matches);
        return $matches[1];
    }
}
