<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2016 06 13
 * @since       PHPBoost 5.0 - 2016 05 26
*/

abstract class EasyCssAbstractElement
{
    protected $id;
    protected $parent_id;
    protected $raw_value;

    protected function __construct($id, $parent_id, $value)
    {
        $this->id = $id;
        $this->parent_id = $parent_id;
        $this->raw_value = trim($value);
    }
}
