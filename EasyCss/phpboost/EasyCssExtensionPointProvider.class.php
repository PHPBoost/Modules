<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2016 06 13
 * @since       PHPBoost 5.0 - 2016 04 22
*/

class EasyCssExtensionPointProvider extends ExtensionPointProvider
{

    public function __construct()
    {
        parent::__construct('EasyCss');
    }

    public function url_mappings()
    {
        return new UrlMappings(array(new DispatcherUrlMapping('/EasyCss/index.php', '([\w/_-]*)$')));
    }

    public function css_files()
    {
        $module_css_files = new ModuleCssFiles();
        $module_css_files->adding_running_module_displayed_file('easycss.css');
        return $module_css_files;
    }

}
