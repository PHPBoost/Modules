<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 5.0 - 2016 04 26
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
*/

class EasyCssDisplayCommentField extends EasyCssAbstractField
{

    protected $title;

    public function __construct($id, $title)
    {
        $this->title = $title;
        $this->id = $id;
    }

    public function get_title()
    {
        return $this->title;
    }

    public function get_form($label)
    {
        $tpl = new FileTemplate('EasyCss/fields/EasyCssDisplayCommentField.tpl');
        $tpl->put_all([
            'VALUE' => $this->title,
        ]);
        return $tpl;
    }

}
