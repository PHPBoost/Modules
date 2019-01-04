<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version   	PHPBoost 5.2 - last update: 2016 11 14
 * @since   	PHPBoost 5.0 - 2016 04 26
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
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
