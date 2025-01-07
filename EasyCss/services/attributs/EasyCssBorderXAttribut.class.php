<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 15
 * @since       PHPBoost 5.0 - 2016 06 05
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class EasyCssBorderXAttribut extends EasyCssAbstractAttribut
{
    protected $name_attribut = '';

    public $to_display = true;

    protected $separator = ' ';

    /** @staticvar array Regex */
    public static $regex = [
        '`(?<=[^-])border-(top|right|bottom|left)\s*:(.*);`isuU',
    ];

    protected $key;

    public function __construct($id, $parent_id, $matches)
    {
        $this->key = $matches[1];
        $value = $matches[2];
        $this->name_attribut = 'border-' . $this->key;
        parent::__construct($id, $parent_id, $value);
        foreach ($this->values as $key => &$val)
        {
            if (EasyCssColorsManager::is_color($val))
            {
                $val = EasyCssColorsManager::create_color($key, $this->parent_id . '/' . $this->id, $val);
            }
            else
            {
                $val = new EasyCssGenericElement($key, $this->parent_id . '/' . $this->id, $val);
            }

        }
    }

    public function get_templates($label = '', $tpl = [])
    {
        AdminEasyCssEditController::add_field_to_hidden_input($this->parent_id . '/' . $this->id);
        $tpls = [];
        foreach ($this->values as $tpl)
        {
            $templates = $tpl->get_templates();
            foreach ($templates as $value_tpl)
            {
                $tpls[] = $value_tpl;
            }
        }

        return parent::get_templates(AdminEasyCssEditController::get_lang('border_' . $this->key . '_description'), $tpls);
    }
}
