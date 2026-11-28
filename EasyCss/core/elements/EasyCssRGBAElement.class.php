<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 28
 * @since       PHPBoost 5.0 - 2016 05 22
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class EasyCssRGBAElement extends EasyCssAbstractElement
{

    use EasyCssColorTrait;

    protected EasyCssRGBColorValue $color;
    protected string $color_id;
    protected EasyCssTransparencyValue $transparency;
    protected string $transparency_id;

    public function __construct(string $id, string $parent_id, string $value)
    {
        parent::__construct($id, $parent_id, $value);
        $this->color_id = $this->parent_id . '/' . $this->id . '_color';
        $this->transparency_id = $this->parent_id . '/' . $this->id . '_transparency';
        $color = self::get_rgba_value_from_str($value);

        $values = explode(',', $color);

        $rgbcolor = $values[0] . ',' . $values[1] . ',' . $values[2];
        $this->color = new EasyCssRGBColorValue($this->color_id, $rgbcolor);
        $this->transparency = new EasyCssTransparencyValue($this->transparency_id, $values[3]);
    }

    public function get_templates(string $label = ''): array
    {
        if ($label === '')
        {
            $label = AdminEasyCssEditController::get_lang('color_description');
        }

        return [$this->color->get_form($label), $this->transparency->get_form(AdminEasyCssEditController::get_lang('transparency_description'))];
    }

    public function set_value_from_post(\HTTPRequestCustom $request): string
    {
        $color_value = $request->get_poststring($this->color_id, false);
        $color_modif = $this->color->set_value($color_value);
        $transparency_value = $request->get_poststring($this->transparency_id, false);
        $transp_modif = $this->transparency->set_value($transparency_value);

        if ($color_modif || $transp_modif)
        {
            return $this->get_text_to_file();
        }
        return false;
    }

    public function get_text_to_file(): string
    {
        return 'rgba(' . $this->color->get_color() . ',' . $this->transparency->get_transparency() . ')';
    }
}
?>