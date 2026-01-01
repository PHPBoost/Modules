<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 15
 * @since       PHPBoost 5.0 - 2016 04 22
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class EasyCssBackgroundColorAttribut extends EasyCssColorAttribut
{

    protected $name_attribut = 'background-color';

    /** @staticvar array Regex */
    public static $regex = [
        '`(?<=[^-])background-color\s*:(.*);`isuU',
    ];

    public function get_templates($label = '', $tpl = array())
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

        return EasyCssAbstractAttribut::get_templates(AdminEasyCssEditController::get_lang('background_color_description'), $tpls);
    }



}
