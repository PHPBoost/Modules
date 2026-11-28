<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 28
 * @since       PHPBoost 5.0 - 2016 05 22
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class EasyCssAbstractValue
{
    protected string $id;

    protected function __construct(string $id)
    {
        $this->id = $id;
    }

    abstract public function get_form(string $label): FileTemplate;
}
