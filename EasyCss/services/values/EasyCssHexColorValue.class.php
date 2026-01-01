<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 28
 * @since       PHPBoost 5.0 - 2016 05 19
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
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
