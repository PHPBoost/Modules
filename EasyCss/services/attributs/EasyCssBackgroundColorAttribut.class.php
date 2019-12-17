<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2016 11 14
 * @since       PHPBoost 5.0 - 2016 04 22
 * @contributor mipel <mipel@phpboost.com>
*/

class EasyCssBackgroundColorAttribut extends EasyCssColorAttribut
{

    protected $name_attribut = 'background-color';

    /** @staticvar array Regex */
    public static $regex = [
        '`(?<=[^-])background-color\s*:(.*);`isuU',
    ];

    public function get_templates()
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

        return EasyCssAbstractAttribut::get_templates($tpls, AdminEasyCssEditController::get_lang('background_color_description'));
    }



}
