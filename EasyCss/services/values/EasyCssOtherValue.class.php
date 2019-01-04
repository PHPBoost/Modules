<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version   	PHPBoost 5.2 - last update: 2016 06 16
 * @since   	PHPBoost 5.0 - 2016 05 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
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
