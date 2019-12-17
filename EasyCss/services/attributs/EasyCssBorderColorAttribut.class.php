<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2016 11 14
 * @since       PHPBoost 5.0 - 2016 05 26
 * @contributor mipel <mipel@phpboost.com>
*/

class EasyCssBorderColorAttribut extends EasyCssAbstractAttribut
{
    protected $name_attribut = 'border-color';

    public $to_display = true;

    protected $separator = ' ';


    /** @staticvar array Regex */
    public static $regex = [
        '`(?<=[^-])border-color\s*:(.*);`isuU',
    ];

    public function __construct($id, $parent_id, $matches)
    {
        $value = $matches[1];
        parent::__construct($id, $parent_id, $value);

        if ($this->on_error)
            return ;
        foreach ($this->values as $value)
        {
            if (!EasyCssColorsManager::is_color($value))
            {
                $this->add_error('Wrong arguments : ' . $this->raw_value);
                break;
            }
        }
        if (!$this->on_error)
            $this->build_borders_array();
    }

    public function get_templates()
    {
        AdminEasyCssEditController::add_field_to_hidden_input($this->parent_id . '/' . $this->id);
        $tpls = [];
        foreach ($this->values as $key => $tpl)
        {
            $templates = $tpl->get_templates(AdminEasyCssEditController::get_lang($key));
            foreach ($templates as $value_tpl)
            {
                $tpls[] = $value_tpl;
            }
            $tpls[] = new StringTemplate("<br/>");
        }

        return parent::get_templates($tpls, AdminEasyCssEditController::get_lang('border_color_description'));
    }

    private function build_borders_array()
    {
        $borders = [];
        $borders['top'] = EasyCssColorsManager::create_color('top', $this->parent_id . '/' . $this->id, $this->values[0]);
        switch (count($this->values))
        {
            case 1 :
                $right = $bottom = $left = 0;
                break;
            case 2 :
                $bottom = 0;
                $right = $left = 1;
                break;
            case 3 :
                $right = $left = 1;
                $bottom = 2;
                break;
            case 4 :
                $right = 1;
                $bottom = 2;
                $left = 3;
                break;
            default :
                $this->add_error('Wrong arguments : ' . $this->raw_value);
                return;
        }
        $borders['right'] = EasyCssColorsManager::create_color('right', $this->parent_id . '/' . $this->id, $this->values[$right]);
        $borders['bottom'] = EasyCssColorsManager::create_color('bottom', $this->parent_id . '/' . $this->id, $this->values[$bottom]);
        $borders['left'] = EasyCssColorsManager::create_color('left', $this->parent_id . '/' . $this->id, $this->values[$left]);
        $this->values = $borders;
    }
}
