<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2016 06 13
 * @since       PHPBoost 5.0 - 2016 06 01
*/

class EasyCssOtherElement extends EasyCssAbstractElement
{
    /** @var \EasyCssOtherValue */
    protected $value;
    protected $value_id;

    public function __construct($id, $parent_id, $value)
    {
        parent::__construct($id, $parent_id, $value);
        $this->value_id = $this->parent_id . '/' . $this->id . '_other';
        $this->value = new EasyCssOtherValue($this->value_id, $this->raw_value);
    }

    public function get_templates($label = '')
    {
        return [$this->value->get_form($label)];
    }

    public function set_value_from_post(\HTTPRequestCustom $request)
    {
        $value = $request->get_poststring($this->value_id, false);
        $value_modif = $this->value->set_value($value);

        if ($value_modif)
            return $this->get_text_to_file();
        return false;
    }

    public function get_text_to_file()
    {
        return $this->value->get_value();
    }
}
