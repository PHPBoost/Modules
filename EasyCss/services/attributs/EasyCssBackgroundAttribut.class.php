<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 14
 * @since       PHPBoost 5.0 - 2016 06 03
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

class EasyCssBackgroundAttribut extends EasyCssAbstractAttribut
{
    protected $name_attribut = 'background';

    public $to_display = true;

    /** @staticvar array Regex */
    public static $regex = [
        '`(?<=[^-])background\s*:(.*);`isuU',
    ];

    protected $separator = ' ';

    public function __construct($id, $parent_id, $matches)
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

    public function get_templates($label = 'background', $tpl = array())
    {
        AdminEasyCssEditController::add_field_to_hidden_input($this->parent_id . '/' . $this->id);
        $tpls = [];
        foreach ($this->values as $key => $tpl)
        {
            $templates = $tpl->get_templates();
            foreach ($templates as $value_tpl)
            {
                $tpls[] = $value_tpl;
            }
            if (count($templates) > 1)
                $tpls[] = new StringTemplate("<br/>");
        }

        return parent::get_templates(AdminEasyCssEditController::get_lang('background_description'), $tpls);
    }
}
