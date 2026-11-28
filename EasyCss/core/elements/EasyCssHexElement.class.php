<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 28
 * @since       PHPBoost 5.0 - 2016 05 22
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class EasyCssHexElement extends EasyCssAbstractElement
{
    use EasyCssColorTrait;

    protected EasyCssHexColorValue $color;
    protected string $color_id;

    public function __construct(string $id, string $parent_id, string $value)
    {
        parent::__construct($id, $parent_id, $value);
        $this->color_id = $this->parent_id . '/' . $this->id . '_color';
        $color = self::get_hex_value_from_str($value);

        $this->color = new EasyCssHexColorValue($this->color_id, $color);
    }

    public function get_templates(string $label = ''): array
    {
        if ($label === '')
        {
            $label = AdminEasyCssEditController::get_lang('color_description');
        }

        return [$this->color->get_form($label)];
    }

    public function set_value_from_post(\HTTPRequestCustom $request): string
    {
        $color_value = $request->get_poststring($this->color_id, false);
        $color_modif = $this->color->set_value($color_value);

        if ($color_modif)
        {
            return $this->get_text_to_file();
        }
        return false;
    }

    public function get_text_to_file(): string
    {
        return '#' . $this->color->get_color();
    }
}
?>