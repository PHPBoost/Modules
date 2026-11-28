<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 28
 * @since       PHPBoost 5.0 - 2016 06 03
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      mipel <mipel@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class EasyCssBackgroundAttribut extends EasyCssAbstractAttribut
{
    protected string $name_attribut = 'background';
    public bool $to_display = true;
    public static array $regex = [
        '`(?<=[^-])background\s*:(.*);`isuU',
    ];
    protected string $separator = ' ';

    public function __construct(string $id, string $parent_id, array $matches)
    {
        $value = $matches[1];
        parent::__construct($id, $parent_id, $value);

        if ($this->on_error)
            return ;

        if (EasyCssColorsManager::is_color($this->values[0]))
        {
            $this->values[0] = EasyCssColorsManager::create_color('0', $this->parent_id . '/' . $this->id, $this->values[0]);
        }
        else
        {
            $this->values[0] = new EasyCssGenericElement('0', $this->parent_id . '/' . $this->id, $this->values[0]);
        }
        foreach ($this->values as $key => &$val)
        {
            if ($key === 0)                continue;
            $val = new EasyCssGenericElement($key, $this->parent_id . '/' . $this->id, $val);
        }
    }

    public function get_templates(string $label = 'background', array $tpl = []): array
    {
        AdminEasyCssEditController::add_field_to_hidden_input($this->parent_id . '/' . $this->id);
        $tpls = [];
        foreach ($this->values as $key => $value)
        {
            $templates = $value->get_templates();
            foreach ($templates as $value_tpl)
            {
                $tpls[] = $value_tpl;
            }
            if (count($templates) > 1)
            {
                $tpls[] = new StringTemplate("<br/>");
            }
        }

        return parent::get_templates(AdminEasyCssEditController::get_lang('background_description'), $tpls);
    }
}
?>
