<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2016 06 16
 * @since       PHPBoost 5.0 - 2016 05 03
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class EasyCssMediaBlock extends EasyCssAbstractBlock
{
    public $type;
    public $size;


    public $to_display = true;

    public function __construct($id, $parent_id, $value, $css_content)
    {
        $this->id = $id;
        $this->parent_id = $parent_id;
        $this->css_content = $css_content .'}';
        $values = explode(':', $value);
        $this->type = trim($values[0]);
        $this->size = trim($values[1]);
        $this->parse_blocks_content();
    }

    protected function parse_blocks_content()
    {
        $css = $this->parse_block($this->css_content);
        $css = $this->parse_title_block($css);
        $css = $this->parse_display_comment_block($css);
        $css = $this->parse_comment_block($css);

        $this->parsed_css = str_replace('}', '', $css);
    }

    public function get_templates()
    {
        $tpls = parent::get_templates();
        if (!empty($tpls))
        {
            array_unshift($tpls, new StringTemplate('<div class="easycss-block"><h6>' . $this->get_title_block() . '</h6>'));
            array_push($tpls, new StringTemplate('</div>'));
        }
        return $tpls;
    }

    private function get_title_block()
    {
        $title = LangLoader::get_message(trim($this->type), 'common', 'EasyCss');
        return $title . ' : ' . $this->size;
    }

    public function get_child_name($path_child)
    {
        $path = explode('/', $path_child);
        $child = $path[0];
        array_shift($path);
        $path = implode('/', $path);
        return __CLASS__ . ':' . $this->type . ':' . $this->size . '/' . $this->children[$child]->get_child_name($path);
    }

    public function find_id($id)
    {
        $path = explode('/', $id);

        $child = $path[0];
        array_shift($path);

        $path = implode('/', $path);
        return $this->children[$child]->find_id($path);
    }

    public function get_css_to_save()
    {
        $css = parent::get_css_to_save();
        $css = "\n@media (" . $this->type . ": " . $this->size . ") {\n" . $css;
        $css = $css . "}\n";
        return $css;
    }
}
