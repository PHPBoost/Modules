<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 28
 * @since       PHPBoost 5.0 - 2016 06 03
 * @author      mipel <mipel@phpboost.com>
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class EasyCssBorderXColorAttribut extends EasyCssAbstractAttribut
{

    protected string $name_attribut = '';
    public bool $to_display = true;
    protected string $separator = '';
    public static array $regex = [
        '`(?<=[^-])border-(top|right|bottom|left)-color\s*:(.*);`isuU',
    ];
    protected string $key;

    public function __construct(string $id, string $parent_id, array $matches)
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

    public function get_templates(string $label = '', array $tpl = []): array
    {
        AdminEasyCssEditController::add_field_to_hidden_input($this->parent_id . '/' . $this->id);
        $tpls = [];
        foreach ($this->values as $value)
        {
            $templates = $value->get_templates(AdminEasyCssEditController::get_lang($this->key));
            foreach ($templates as $value_tpl)
            {
                $tpls[] = $value_tpl;
            }
        }

        return parent::get_templates(AdminEasyCssEditController::get_lang('border_color_description'), $tpls);
    }
}
?>
