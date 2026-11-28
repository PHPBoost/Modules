<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 28
 * @since       PHPBoost 5.0 - 2016 05 26
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class EasyCssAbstractElement
{
    protected string $id;
    protected string $parent_id;
    protected string $raw_value;

    protected function __construct(string $id, string $parent_id, string $value)
    {
        $this->id = $id;
        $this->parent_id = $parent_id;
        $this->raw_value = trim($value);
    }
}
?>
