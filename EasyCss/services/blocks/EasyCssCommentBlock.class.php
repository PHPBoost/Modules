<?php
/**
 * Bloc de commentaire (/* commentaire * / )
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version   	PHPBoost 5.2 - last update: 2016 06 13
 * @since   	PHPBoost 5.0 - 2016 05 04
*/

class EasyCssCommentBlock extends EasyCssAbstractBlock
{
    public $id;

    public $title;

    public $to_display = false;

    public function __construct($id, $parent_id, $title)
    {
        $this->id = $id;
        $this->title = $title;
        $this->parent_id = $parent_id;
    }

    public function get_css_to_save()
    {
        if ($this->parent_id === '' || $this->parent_id === '/main')
        {
            return "\n" . '/*' . $this->title . '*/' . "\n";
        }
        return '/*' . $this->title . '*/' . "\n";
    }
}
