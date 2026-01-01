<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 6.1 - last update: 2016 06 13
 * @since       PHPBoost 5.0 - 2016 05 26
*/

class EasyCssGenericElement extends EasyCssAbstractElement
{
    public function __construct($id, $parent_id, $value)
    {
        parent::__construct($id, $parent_id, $value);
    }

    public function get_templates()
    {
        return [];
    }

    public function set_value_from_post(\HTTPRequestCustom $request)
    {
        return false;
    }

    public function get_text_to_file()
    {
        return $this->raw_value;
    }
}
