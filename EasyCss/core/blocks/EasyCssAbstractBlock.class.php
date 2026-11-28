<?php
/**
 * Parent class of the blocks
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 28
 * @since       PHPBoost 5.0 - 2016 05 06
 * @author      mipel <mipel@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class EasyCssAbstractBlock
{
    /** Element ID */
    public string $id;
    /** ID of the direct parent */
    protected string $parent_id;
    /** Original CSS content of the element */
    protected string $css_content;
    /** Content of the parsed file */
    protected string $parsed_css;
    /**Child blocks or elements (if EasyCssBlock) */
    protected array $children = [];
    /** Replacement counter */
    protected int $counter = 0;
    /** Whether the block content should be displayed when editing */
    public bool $to_display = true;
    public bool $on_error = false;


    /**
     * Retrieves the templates of the child blocks
     *
        * @return Template[]|false Array of the block's children templates, or false if the block is hidden
     */
    public function get_templates(): array
    {
        if ($this->to_display === false)
        {
            return [];
        }
        $tpls = [];
        $lines = explode("\n", $this->parsed_css);
        foreach ($lines as $line)
        {
            if (preg_match('`###(\d+)\/###`isuU', $line, $matches))
            {
                $block = $this->children[$matches[1]];
                if ($block->to_display && $block->on_error === false)
                {
                    // If the block should be displayed
                    $templates = $block->get_templates();
                    if (!is_array($templates))
                    {
                        $templates = [$templates];
                    }
                    foreach ($templates as $template)
                    {
                        $tpls[] = $template;
                    }
                }
            }
        }
        return $tpls;
    }

    /**
     * Retrieves the CSS code of the block and its children
     *
     * @return string   CSS content of the elements
     */
    public function get_css_to_save(): string
    {
        $css = '';
        $lines = explode("\n", $this->parsed_css);
        foreach ($lines as $line)
        {
            if (preg_match('`###(\d+)\/###`isuU', $line, $matches))
            {
                /** @var \EasyCssAbstractBlock|\EasyCssAbstractAttribut $block */
                $block = $this->children[$matches[1]];
                if ($block instanceof EasyCssAbstractBlock)
                {
                    // Child of type EasyCssAbstractBlock
                    $css.= $this->get_spaces() . '    ' . $block->get_css_to_save();
                }
                else
                {
                    // Child of type EasyCssAbstractAttribut
                    $css.= $this->get_spaces() . '    ' .$block->get_text_to_file() . "\n";
                }
            }
        }
        return $css;
    }

    /**
     * Tells the child to go fetch its value from the POST
     *
     * @param string                $path_child Path of the child
     * @param \HTTPRequestCustom    $request
     * @return mixed                New value if the element was modified, otherwise false
     */
    protected function set_value_from_post(string $path_child, \HTTPRequestCustom $request): mixed
    {
        $path = explode('/', $path_child);
        $child = $path[0];
        array_shift($path);
        $path = implode('/', $path);
        return $this->children[$child]->set_value_from_post($path, $request);
    }

    /**
     * Parses the elements of type EasyCssTitleBlock
     *
     * @param   string  $css CSS content of the block
     * @return  string  Parsed CSS content
     */
    protected function parse_title_block(string $css): string
    {
        return preg_replace_callback('`\/\*\*\s*-{3}(.+)-{3}\s*\*\/`isuU', [$this, 'replace_parse_title_block'], $css );
    }

    /**
     * Parses the elements of type EasyCssDisplayCommentBlock
     *
     * @param   string $css CSS content of the block
     * @return  string  Parsed CSS content
     */
    protected function parse_display_comment_block(string $css): string
    {
        return preg_replace_callback('`\/\*\*(.+)\*\/`isuU', [$this, 'replace_parse_display_comment_block'], $css );
    }

    /**
     * Parses the elements of type EasyCssCommentBlock
     *
     * @param   string $css CSS content of the block
     * @return  string  Parsed CSS content
     */
    protected function parse_comment_block(string $css): string
    {
        return preg_replace_callback('`\/\*(.+)\*\/`isuU', [$this, 'replace_parse_comment_block'], $css );
    }

    /**
     * Parses the elements of type EasyCssMediaBlock
     *
     * @param   string $css CSS content of the block
     * @return  string  Parsed CSS content
     */
    protected function parse_media_block(string $css): string
    {
        return preg_replace_callback('`@media\s*\((.+)\)\s*\{(.*)\}\s*\}`isuU', [$this, 'replace_parse_media_block'], $css );
    }

    /**
     * Parses the elements of type EasyCssBlock
     *
     * @param   string $css CSS content of the block
     * @return  string  Parsed CSS content
     */
    protected function parse_block(string $css): string
    {
        return preg_replace_callback('`\s*((?:(?!#\s|\/).)*)\{(.*)\}`isuU', [$this, 'replace_parse_block'], $css );
    }

    /**
     * Replaces the full CSS code of the EasyCssTitleBlock block
     * Creates a new EasyCssTitleBlock object and stores it in the $children array of the parent block
     * Returns the replacement string
     *
     * @param   array   $matches Elements found by the preg_replace search
     * @return  string  Replacement string
     */
    protected function replace_parse_title_block(array $matches): string
    {
        $this->counter++;

        $this->children[$this->counter] = new EasyCssTitleBlock($this->counter, $this->parent_id .'/' . $this->id, $matches[1]);
        return "\n" . '###' . $this->counter . '/###' . "\n";
    }

    /**
     * Replaces the full CSS code of the EasyCssDisplayCommentBlock block
     * Creates a new EasyCssDisplayCommentBlock object and stores it in the $children array of the parent block
     * Returns the replacement string
     *
     * @param   array  $matches Elements found by the preg_replace search
     * @return  string  Replacement string
     */
    protected function replace_parse_display_comment_block(array $matches): string
    {
        $this->counter++;

        $this->children[$this->counter] = new EasyCssDisplayCommentBlock($this->counter, $this->parent_id .'/' . $this->id,$matches[1]);
        return "\n" . '###' . $this->counter . '/###' . "\n";
    }

    /**
     * Replaces the full CSS code of the EasyCssCommentBlock block
     * Creates a new EasyCssCommentBlock object and stores it in the $children array of the parent block
     * Returns the replacement string
     *
     * @param   array  $matches Elements found by the preg_replace search
     * @return  string  Replacement string
     */
    protected function replace_parse_comment_block(array $matches): string
    {
        $this->counter++;

        $this->children[$this->counter] = new EasyCssCommentBlock($this->counter, $this->parent_id .'/' . $this->id,$matches[1]);
        return "\n" . '###' . $this->counter . '/###' . "\n";
    }

    /**
     * Replaces the full CSS code of the EasyCssMediaBlock block
     * Creates a new EasyCssMediaBlock object and stores it in the $children array of the parent block
     * Returns the replacement string
     *
     * @param   array  $matches Elements found by the preg_replace search
     * @return  string  Replacement string
     */
    protected function replace_parse_media_block(array $matches): string
    {
        $this->counter++;

        $this->children[$this->counter] = new EasyCssMediaBlock($this->counter, $this->parent_id .'/' . $this->id,preg_replace('/\s{2,}/u', ' ', trim(str_replace("\n\n", "\n", $matches[1]))), $matches[2]);
        return "\n" . '###' . $this->counter . '/###' . "\n";
    }

    /**
     * Replaces the full CSS code of the EasyCssBlock block
     * Creates a new EasyCssBlock object and stores it in the $children array of the parent block
     * Returns the replacement string
     *
     * @param   array  $matches Elements found by the preg_replace search
     * @return  string  Replacement string
     */
    protected function replace_parse_block(array $matches): string
    {
        if (!isset($matches[1])) return '';

        $this->counter++;
        $this->children[$this->counter] = new EasyCssBlock($this->counter, $this->parent_id .'/' . $this->id, preg_replace('/\s{2,}/u', ' ', trim(str_replace("\n\n", "\n", $matches[1]))), $matches[2]);
        return "\n" . '###' . $this->counter . '/###' . "\n";
    }

    /**
     * Creates the indentation
     *
     * Returns a number of spaces depending on the parent of the block
     *
     * @return string   String of spaces
     */
    protected function get_spaces(): string
    {
        if ($this->parent_id === '' || $this->parent_id === '/main') return '';
        return '    ';
    }
}
?>