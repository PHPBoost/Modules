<?php
/**
 * Commentaire affiché ( /** commentaire * / )
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 28
 * @since       PHPBoost 5.0 - 2016 05 04
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class EasyCssDisplayCommentBlock extends EasyCssAbstractBlock
{
    public string $id;
    public $to_display = true;
    protected EasyCssDisplayCommentField $title;

    public function __construct(string $id, string $parent_id, string $value)
    {
        $this->title = new EasyCssDisplayCommentField($id, $value);
        $this->id = $id;
        $this->parent_id = $parent_id;
    }

    public function get_templates(): array
    {
        $title_tpl = $this->title->get_form(false);
        return [$title_tpl];
    }

    public function get_css_to_save(): string
    {

        if ($this->parent_id === '' || $this->parent_id === '/main')
        {
            return "\n" . '/**' . $this->title->get_title() . '*/' . "\n";
        }
        return '/**' . $this->title->get_title() . '*/' . "\n";
    }
}
?>
