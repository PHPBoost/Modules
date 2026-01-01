<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 6.1 - last update: 2016 11 14
 * @since       PHPBoost 5.0 - 2016 05 22
*/

abstract class EasyCssAbstractValue
{
    protected $id;

    protected function __construct($id)
    {
        $this->id = $id;
    }

    abstract public function get_form($label);
}
