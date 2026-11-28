<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 28
 * @since       PHPBoost 5.0 - 2016 05 19
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class EasyCssTransparencyValue extends EasyCssAbstractValue
{
    protected string $transparency;

    public function __construct(string $id, string $transparency)
    {
        parent::__construct($id);
        $this->set_value($transparency);
    }

    public function set_value(string $transparency): string
    {
        $transparency = floatval($transparency);
        if ($transparency < 0 || $transparency > 1)
        {
            $transparency = 1;
        }
        if ($this->transparency == $transparency)
        {
            return false;
        }
        $this->transparency = $transparency;
        return $this->transparency;
    }

    public function get_form(string $label): FileTemplate
    {
        $tpl = new FileTemplate('EasyCss/fields/EasyCssTransparencyField.tpl');
        $tpl->put_all([
            'NAME' => $this->id,
            'ID' => $this->id,
            'HTML_ID' => $this->id,
            'VALUE' => $this->transparency,
            'LABEL' => $label
        ]);
        return $tpl;
    }

    public function get_transparency(): string
    {
        return $this->transparency;
    }
}
?>