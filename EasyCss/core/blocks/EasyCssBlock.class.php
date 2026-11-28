<?php
/**
 * Bloc de type standard (#id { color : ... } )
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 28
 * @since       PHPBoost 5.0 - 2016 05 03
 * @author      mipel <mipel@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class EasyCssBlock extends EasyCssAbstractBlock
{

    protected string $tag;
    public bool $to_display = true;

    public function __construct(string $id, string $parent_id, string $tag, string $css_content)
    {
        $this->id = $id;
        $this->parent_id = $parent_id;
        $this->css_content = $css_content;
        $this->tag = $tag;
        $this->parse_elements_content();
    }

    protected function parse_elements_content()
    {
        $css = $this->parse_title_block($this->css_content);
        $css = $this->parse_display_comment_block($css);
        $css = $this->parse_comment_block($css);

        // Parses items
        foreach (EasyCssAbstractAttribut::$attributs as $name)
        {
            foreach ($name::$regex as $regex)
            {
                $css = preg_replace_callback($regex, function ($matches) use($name)
                {
                    $this->counter++;
                    $this->children[$this->counter] = new $name($this->counter, $this->parent_id . '/' . $this->id, $matches);
                    return "\n" . '###' . $this->counter . '/###' . "\n";
                }, $css);
            }
        }

        // Parses generic items
        $css = preg_replace_callback('`([^#{3}|^\/| ]*)\s*:\s*(.*)\s*;`isuU', function ($matches)
        {
            $this->counter++;
            $this->children[$this->counter] = new EasyCssGenericAttribut($this->counter, $this->parent_id . '/' . $this->id, $matches[1], $matches[2]);
            return "\n" . '###' . $this->counter . '/###' . "\n";
        }, $css);

        $this->parsed_css = $css;
    }

    public function get_templates(): array
    {
        $tpls = parent::get_templates();
        if (!empty($tpls))
        {
            array_unshift($tpls, new StringTemplate('<div class="easycss-block"><h6>' . $this->tag . '</h6>'));
            array_push($tpls, new StringTemplate('</div>'));
        }
        return $tpls;
    }

    protected function set_value_from_post(string $path_child, \HTTPRequestCustom $request): string
    {
        $path = explode('/', $path_child);
        $child = $path[0];
        array_shift($path);
        return $this->children[$child]->set_value_from_post($request);
    }

    public function get_child_name(string $path_child)
    {
        $path = explode('/', $path_child);
        $child = $path[0];
        array_shift($path);
        $path = implode('/', $path);
        return self::class . ':' . $this->tag . '/' . $this->children[$child]->get_child_name();
    }

    public function find_id(string $id)
    {
        $path = explode('/', $id);

        $child = $path[0];
        array_shift($path);
        $path = implode('/', $path);
        return $this->children[$child];
    }

    public function get_css_to_save(): string
    {
        $css = parent::get_css_to_save();
        $css = "\n" . $this->get_spaces() . $this->tag . " {\n" . $css;
        $css = $css . $this->get_spaces() . "}\n";
        return $css;
    }
}
?>
