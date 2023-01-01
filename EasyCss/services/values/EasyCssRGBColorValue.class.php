<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 28
 * @since       PHPBoost 5.0 - 2016 05 19
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
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
