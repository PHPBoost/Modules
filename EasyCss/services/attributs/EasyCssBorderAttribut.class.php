<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 15
 * @since       PHPBoost 5.0 - 2016 04 22
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class EasyCssBorderAttribut extends EasyCssAbstractAttribut
{

    protected $name_attribut = 'border';

    public $to_display = true;

    protected $separator = ' ';

    /** @staticvar array Regex */
    public static $regex = [
        '`(?<=[^-])border\s*:(.*);`isuU',
    ];

    public function __construct($id, $parent_id, $matches)
    {
        $value = $matches[1];
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
        foreach ($this->values as $tpl)
        {
            $templates = $tpl->get_templates();
            foreach ($templates as $value_tpl)
            {
                $tpls[] = $value_tpl;
            }
        }

        return parent::get_templates(AdminEasyCssEditController::get_lang('border_description'), $tpls);
    }


}
