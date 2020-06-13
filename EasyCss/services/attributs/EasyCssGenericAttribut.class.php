<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2016 06 13
 * @since       PHPBoost 5.0 - 2016 05 04
*/

class EasyCssGenericAttribut extends EasyCssAbstractAttribut
{
    public $id;
    public $attribut;

    public $value;

    public $to_display = false;

    public function __construct($id, $parent_id, $attribut, $value)
    {
        $this->id = $id;
        $this->attribut = trim($attribut);
        $this->value = trim($value);
        $this->parent_id = $parent_id;
    }

    public function get_text_to_file()
    {
        return $this->attribut . ' : ' . $this->value . ';';
    }

    public function get_templates()
    {
        return;
    }

}
