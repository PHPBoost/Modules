<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 28
 * @since       PHPBoost 5.0 - 2016 05 04
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class EasyCssGenericAttribut extends EasyCssAbstractAttribut
{
    public string $id;
    public string $attribut;

    public string $value;

    public bool $to_display = false;

    public function __construct(string $id, string $parent_id, string $attribut, string $value)
    {
        $this->id = $id;
        $this->attribut = trim($attribut);
        $this->value = trim($value);
        $this->parent_id = $parent_id;
    }

    public function get_text_to_file(): string
    {
        return $this->attribut . ' : ' . $this->value . ';';
    }

    public function get_templates(string $label = '', array $tpl = []): array
    {
        return [];
    }
}
?>