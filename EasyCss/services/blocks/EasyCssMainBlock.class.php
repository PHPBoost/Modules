<?php
/**
 * Bloc principal (CSS complet), qui contient tous les autres blocs
 * @copyright   &copy; 2005-2024 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 24
 * @since       PHPBoost 5.0 - 2016 05 03
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class EasyCssMainBlock extends EasyCssAbstractBlock
{

    public function __construct($css_content)
    {
        $this->id = 'main';
        $this->parent_id = '';
        $this->css_content = $css_content;
        $this->parse_blocks_content();
    }

    protected function parse_blocks_content()
    {
        $css = $this->parse_media_block($this->css_content);
        $css = $this->parse_block($css);
        $css = $this->parse_title_block($css);
        $css = $this->parse_display_comment_block($css);
        $css = $this->parse_comment_block($css);


        $this->parsed_css = str_replace('}', '', $css);
    }

    public function replace_with_post($post_elements, \HTTPRequestCustom $request)
    {
        $elements = explode(';', $post_elements);
        foreach ($elements as $element)
        {
            if ($element == '')
                break 1;
            $path = explode('/', $element);

            array_shift($path);
            array_shift($path);
            $path = implode('/', $path);
            $modif = $this->set_value_from_post($path, $request);
            if ($modif !== false)
            {
                //echo $this->get_child_full_name($element) . ' est modifié ' . $modif . "<br/>";
            }
        }
    }

    protected function get_child_full_name($path)
    {
        $path = explode('/', $path);

        array_shift($path);
        array_shift($path);
        $path = implode('/', $path);
        return $this->get_child_name($path);
    }

    protected function get_child_name($path_child)
    {
        $path = explode('/', $path_child);
        $child = $path[0];
        array_shift($path);
        $path = implode('/', $path);
        return '/main/' . $this->children[$child]->get_child_name($path);
    }

    public function find_id($id)
    {
        $path = explode('/', $id);

        array_shift($path);
        array_shift($path);
        $child = $path[0];
        array_shift($path);
        $path = implode('/', $path);
        return $this->children[$child]->find_id($path);
    }

}
