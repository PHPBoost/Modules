<?php
/**
 * Commentaire affiché ( /** commentaire * / )
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 24
 * @since       PHPBoost 5.0 - 2016 05 04
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class EasyCssDisplayCommentBlock extends EasyCssAbstractBlock
{
    public $id;

    public $to_display = true;

    /** @var \EasyCssDisplayCommentField */
    protected $title;

    public function __construct($id, $parent_id, $title)
    {
        $this->title = new EasyCssDisplayCommentField($id, $title);
        $this->id = $id;
        $this->parent_id = $parent_id;
    }

    public function get_templates()
    {
        $title_tpl = $this->title->get_form(false);
        return array($title_tpl);
    }

    public function get_css_to_save()
    {

        if ($this->parent_id === '' || $this->parent_id === '/main')
        {
            return "\n" . '/**' . $this->title->get_title() . '*/' . "\n";
        }
        return '/**' . $this->title->get_title() . '*/' . "\n";
    }

}
