<?php
/**
 * Parent class of the attributes to be parsed
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 28
 * @since       PHPBoost 5.0 - 2016 05 20
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class EasyCssAbstractAttribut
{
    /** Attribute ID */
    protected string $id;
    /** Full ID of the parent block */
    protected string $parent_id;
    /** Raw CSS content of the attribute */
    protected string $raw_value;
    /** Child elements of the attribute */
    protected array $values = [];
    /** Whether the attribute has the !important property */
    private bool $is_important = false;
    /** Attribute set in error */
    public bool $on_error = false;
    /** Attribute edited on the edit page */
    protected bool $is_modified = false;
    /** Attribute name, used for error display */
    protected string $name_attribut = '';
    /** Template displayed before the elements */
    protected string $begin_block = '<div class="easycss-field">';
    /** Template displayed after the elements */
    protected string $end_block = '</div>';
    /** Attribute to display on the edit page */
    public bool $to_display = true;
    /** Element separator, empty if the attribute must contain only one element */
    protected string $separator = ' ';
    /** ID of the important field on the edit page */
    private string $important_field_id;
    /** The various attribute Regexes */
    public static array $regex = [];
    /** @var \EasyCssAbstractAttribut[] array  Attributes to parse*/
    public static array $attributs = [
        'EasyCssColorAttribut',
        'EasyCssBorderColorAttribut',
        'EasyCssBackgroundColorAttribut',
        'EasyCssBackgroundAttribut',
        'EasyCssBorderXColorAttribut',
        'EasyCssBorderAttribut',
        'EasyCssBorderXAttribut',
    ];

    /**
     * Protected constructor to be called from each attribute
     *
     * @param string $id Element ID
     * @param string $parent_id Parent ID
     * @param string $value Raw value of the attribute content
     */
    protected function __construct(string $id, string $parent_id, string $value)
    {
        $this->id = $id;
        $this->parent_id = $parent_id;
        $this->raw_value = trim($value);
        $this->check_important();
        $this->explode_elements();
        $this->important_field_id = $this->parent_id .'/' . $this->id . '_important';
    }

    /**
     * Adds an error to the attribute
     * Sets this attribute in error and therefore it will not be displayed
     *
     * @param string $msg
     */
    public function add_error(string $msg): void
    {
        $tpl = new StringTemplate('<div>' . $this->name_attribut . ' : ' . $msg . '</div>');
        AdminEasyCssEditController::add_error($tpl);
        $this->on_error = true;
    }

    /**
     * Templates to display
     * Returns the templates of the various fields that make up the element
     *
     * @return \FileTemplate[] Template or array of templates
     */
    public function get_templates(string $label = '', array $tpl = []): array
    {
        if (!is_array($tpl))
        {
            $tpls[] = $tpl;
        }
        else
        {
            $tpls = $tpl;
        }

        $begin_tpl = new StringTemplate($this->begin_block);
        $end_tpl = new StringTemplate($this->end_block);
        if ($label !== '')
        {
            $label_tpl = new StringTemplate('<h6>' . $label . '</h6>');
            array_unshift($tpls, $label_tpl);
        }
        array_push($tpls, $this->get_important_tpl());
        array_unshift($tpls, $begin_tpl);
        array_push($tpls, $end_tpl);
        return $tpls;
    }

    public function set_value_from_post(\HTTPRequestCustom $request): string
    {
        $this->set_autovalues_from_post($request);

        foreach ($this->values as $key => &$val)
        {
            $modified_element = $val->set_value_from_post($request);
            if ($modified_element !== false)
            {
                $this->is_modified = true;
            }
        }
        if ($this->is_modified === true)
        {
            return $this->get_values_text();
        }
        return false;
    }
    /**
     * Retrieves and automatically assigns the common properties
     *
     * @param \HTTPRequestCustom $request
     */
    protected function set_autovalues_from_post(\HTTPRequestCustom $request): void
    {
        // !important property
        $imp = $request->get_poststring($this->important_field_id, false);
        $imp = ($imp !== false) ? true : false;
        if ($imp !== $this->is_important)
        {
            $this->is_modified = true;
        }
        $this->is_important = $imp;
    }


    /**
     * Return text
     * Returns the text that will be written to the CSS file after editing
     *
     * @return string Declaration for saving in the CSS file
     */
    public function get_text_to_file(): string
    {
        if ($this->on_error)
        {
            return $this->name_attribut . ' : ' . trim($this->raw_value) . $this->get_important_text() . ';' ;
        }
        return $this->name_attribut . ' : ' . $this->get_values_text() . ';';
    }

    /**
     * Returns the CSS content of the attribute elements
     *
     * @return string Value of the attribute elements
     */
    protected function get_values_text(): string
    {
        if ($this->separator === false)
        {
            return $this->values[0]->get_text_to_file() . $this->get_important_text();
        }
        $str = '';
        foreach ($this->values as $val)
        {
            $str .= $val->get_text_to_file() . $this->separator;
        }
        return $str . $this->get_important_text();
    }

    /**
     * Returns the class name of the element
     *
     * @return string   Class name of the element
     */
    public function get_child_name(): string
    {
        return get_called_class();
    }

    final protected function get_important_text(): string
    {
        return ($this->is_important === false) ? false : ' !important';
    }

    /**
     * Sets the is_important property in the constructor
     */
    private function check_important(): void
    {
        $pos = TextHelper::strpos($this->raw_value, '!important');
        if ($pos !== false)
        {
            $this->raw_value = str_replace('!important', '', $this->raw_value);
            $this->is_important = true;
        }
    }

    /**
     * Explodes the values according to the separator
     */
    private function explode_elements(): void
    {
        if ($this->separator === false || $this->separator === '')
        {
            $this->values[] = trim($this->raw_value);
            return;
        }
        $this->raw_value = EasyCssColorsManager::sanitise($this->raw_value);
        $this->values = explode($this->separator, trim($this->raw_value));
        foreach ($this->values as &$val)
        {
            if ($val === '0')
            {
                $val = 'none';
            }
        }
        $this->values = array_values(array_filter($this->values));

        if (empty($this->values))
        {
            $this->add_error('Wrong arguments : ' . $this->raw_value);
        }
    }

    /**
     * Creates and returns the template of the !important property
     *
     * @return \FileTemplate Template of the Important property
     */
    private function get_important_tpl(): FileTemplate
    {
        $imp_tpl = new FileTemplate('EasyCss/fields/EasyCssImportantField.tpl');
        $imp_tpl->put_all([
            'NAME' => $this->important_field_id,
            'ID' => $this->important_field_id,
            'HTML_ID' => $this->important_field_id,
            'CHECKED' => ($this->is_important !== false) ? 'checked="checked"' : '',
            'LABEL' => AdminEasyCssEditController::get_lang('important')
        ]);
        return $imp_tpl;
    }
}
?>