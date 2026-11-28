<?php
/**
 * This page allows editing CSS files contained in the 'theme' folder of installed themes
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 5.0 - 2016 04 22
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminEasyCssEditController extends ModuleController
{

    /** View */
    private FileTemplate $view;
    /** Language array */
    private static array $lang;
    /** Loaded CSS file */
    private File $file;
    /** Theme name */
    private string $theme_id;
    /** CSS file name */
    private string $css_id;
    /** Replacement counter */
    public static int $counter;
    /** Replaced elements */
    public static array $vars;
    /** Hidden field content (IDs of the elements to edit) */
    private static string $hidden_input_content = '';
    /** Errors to display */
    private static array $errors = [];
    /** Main CSS block */
    private static EasyCssMainBlock $main_block;

    /**
     * Page execution
     *
     * @param \HTTPRequestCustom $request
     * @return \AdminDisplayResponse
     */
    public function execute(\HTTPRequestCustom $request): AdminDisplayResponse
    {
        $this->get_file($request);
        $this->init();

        $this->create_objects_elements();

        if ($request->is_post_method())
        {
            $this->post_process($request);
            $this->create_objects_elements();
        }

        $this->put_templates();

        return $this->build_response($this->view);
    }

    /**
     * Creates the language and the view
     */
    private function init(): void
    {
        self::$lang = LangLoader::get('common', 'EasyCss');
        $this->view = new FileTemplate('EasyCss/AdminEditController.tpl');
        $this->view->add_lang(self::$lang);
    }

    public static function get_lang(string $title): string
    {
        return isset(self::$lang[$title]) ? self::$lang[$title] : '';
    }
    /**
     * Retrieves the CSS file to edit
     *
     * @param \HTTPRequestCustom $request
     */
    private function get_file(\HTTPRequestCustom $request): void
    {
        $this->theme_id = $request->get_getstring('theme', false);
        $this->theme_id = trim($this->theme_id, '/');

        $this->css_id = $request->get_getstring('file', false);
        $this->css_id = trim($this->css_id, '/');

        $file_path = PATH_TO_ROOT . '/templates/' . $this->theme_id . '/theme/' . $this->css_id . '.css';
        $this->file = new File($file_path);

        if (!$this->file->exists())
        {
            DispatchManager::redirect(PHPBoostErrors::unexisting_page());
        }
    }

    /**
     * Creates the MainBlock object and all its children
     * CSS parsing
     */
    private function create_objects_elements(): void
    {
        $css = $this->file->read();
        self::$main_block = new EasyCssMainBlock($css);
    }

    /**
     * Sends the templates and the hidden field content to the view
     */
    private function put_templates(): void
    {
        // Element templates
        $forms_tpl = self::$main_block->get_templates();
        $tpls = [];
        foreach ($forms_tpl as $tpl)
        {
            $tpls[] = ['SUBTEMPLATE' => $tpl];
        }

        // Error templates
        $error_tpls = [];
        foreach (self::$errors as $error_tpl)
        {
            $error_tpls[] = ['SUBTEMPLATE' => $error_tpl];
        }

        // Push to the view
        $this->view->put_all([
            'errors' => $error_tpls,
            'elements' => $tpls,
            'ELEMENTS_FIELDS' => self::$hidden_input_content,
            'FIELDSET_LEGEND' => self::$lang['file_edit'] . $this->theme_id . ' / ' . $this->css_id,
        ]);
    }

    /**
     * Processes the POST elements
     * Retrieves the hidden field, replaces the objects with the new values
     * Writes the CSS and clears the CSS cache
     *
     * @param \HTTPRequestCustom $request
     */
    private function post_process(\HTTPRequestCustom $request): void
    {
        $post_elements = $request->get_poststring(self::class . '_elements_fields', false);
        self::$main_block->replace_with_post($post_elements, $request);
        $this->write_to_file();
        $this->clear_css_cache();
        $this->view->put('MSG', MessageHelper::display(self::$lang['file_edit_success'], MessageHelper::SUCCESS, 5));
    }

    /**
     * Creates and returns the response
     *
     * @param View $view
     * @return \AdminDisplayResponse
     */
    private function build_response(View $view): AdminDisplayResponse
    {
        $response = new AdminDisplayResponse($view);
        $response->get_graphical_environment()->set_page_title(self::$lang['module_title']);
        return $response;
    }

    /**
     * Writes the CSS file
     */
    private function write_to_file(): void
    {
        $this->file->write(trim(self::$main_block->get_css_to_save()));
        $this->clear_css_cache();
    }

    /**
     * Clears the CSS cache if enabled
     */
    private function clear_css_cache()
    {
        $css_cache_config = CSSCacheConfig::load();
        if ($css_cache_config->is_enabled())
        {
            AppContext::get_cache_service()->clear_css_cache();
        }
    }

    /**
     * Adds an element to the hidden field
     *
     * @static
     * @param string  $id  Full ID of the field to add
     */
    public static function add_field_to_hidden_input(string $id): void
    {
        self::$hidden_input_content .= $id . ';';
    }

    /**
     * Adds a content error
     *
     * @static
     * @param Template $tpl Error to display
     */
    public static function add_error(\Template $tpl): void
    {
        self::$errors[] = $tpl;
    }

    /**
     * Returns an attribute from its ID
     *
     * @static
     * @param string $id Full ID of the element
     * @return \EasyCssAbstractAttribut
     */
    public static function get_element(string $id): EasyCssAbstractAttribut
    {
        return self::$main_block->find_id($id);
    }
}
?>