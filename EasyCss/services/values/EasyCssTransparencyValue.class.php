<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2016 06 16
 * @since       PHPBoost 5.0 - 2016 05 19
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
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
