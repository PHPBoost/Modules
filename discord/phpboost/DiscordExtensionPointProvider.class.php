<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 03 06
 * @since       PHPBoost 6.0 - 2023 03 05
*/

class DiscordExtensionPointProvider extends ExtensionPointProvider
{
    function __construct()
    {
        parent::__construct('discord');
    }

    public function menus()
    {
        return new ModuleMenus(array(new DiscordModuleMiniMenu()));
    }

    public function css_files()
    {
        $module_css_files = new ModuleCssFiles();
        $module_css_files->adding_always_displayed_file('discord_mini.css');
        return $module_css_files;
    }

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/discord/index.php')));
	}
}
?>
