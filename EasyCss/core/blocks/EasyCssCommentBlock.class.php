<?php
/**
 * Bloc de commentaire (/* commentaire * / )
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 28
 * @since       PHPBoost 5.0 - 2016 05 04
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class EasyCssCommentBlock extends EasyCssAbstractBlock
{
    public string $id;
    public string $title;
    public bool $to_display = false;

    public function __construct(string $id, string $parent_id, string $title)
    {
        $this->id = $id;
        $this->title = $title;
        $this->parent_id = $parent_id;
    }

    public function get_css_to_save(): string
    {
        if ($this->parent_id === '' || $this->parent_id === '/main')
        {
            return "\n" . '/*' . $this->title . '*/' . "\n";
        }
        return '/*' . $this->title . '*/' . "\n";
    }
}
?>
