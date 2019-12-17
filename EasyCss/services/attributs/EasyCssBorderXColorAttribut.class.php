<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2016 11 14
 * @since       PHPBoost 5.0 - 2016 06 03
 * @contributor mipel <mipel@phpboost.com>
*/

class EasyCssBorderXColorAttribut extends EasyCssAbstractAttribut
{

    protected $name_attribut = '';

    public $to_display = true;

    protected $separator = false;

    /** @staticvar array Regex */
    public static $regex = [
        '`(?<=[^-])border-(top|right|bottom|left)-color\s*:(.*);`isuU',
    ];

    protected $key;

    public function __construct($id, $parent_id, $matches)
    {
        $this->key = $matches[1];
        $value = $matches[2];
        $this->name_attribut = 'border-' . $this->key . '-color';
        parent::__construct($id, $parent_id, $value);
        if (!EasyCssColorsManager::is_color($this->values[0]))
        {
            $this->add_error('Wrong arguments : ' . $this->values[0]);
        }
        else
        {
            foreach ($this->values as $key => &$val)
            {
                $val = EasyCssColorsManager::create_color($key, $this->parent_id . '/' . $this->id, $val);
            }
        }
    }

    public function get_templates()
    {
        AdminEasyCssEditController::add_field_to_hidden_input($this->parent_id . '/' . $this->id);
        $tpls = [];
        foreach ($this->values as $tpl)
        {
            $templates = $tpl->get_templates(AdminEasyCssEditController::get_lang($this->key));
            foreach ($templates as $value_tpl)
            {
                $tpls[] = $value_tpl;
            }
        }

        return parent::get_templates($tpls, AdminEasyCssEditController::get_lang('border_color_description'));
    }


}
