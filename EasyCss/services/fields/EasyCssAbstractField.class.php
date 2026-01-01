<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 6.1 - last update: 2016 10 24
 * @since       PHPBoost 5.0 - 2016 05 15
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

abstract class EasyCssAbstractField
{
    /** @var    string  ID du champ */
    protected $id;

    /**
     * Récupération du template du champ
     *
     * @param   string   Label à afficher
     */
    abstract public function get_form($label);
}
