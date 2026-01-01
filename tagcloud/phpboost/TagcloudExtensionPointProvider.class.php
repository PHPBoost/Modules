<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2024 07 17
 * @since       PHPBoost 6.0 - 2023 01 17
*/

class TagcloudExtensionPointProvider extends ExtensionPointProvider
{
    function __construct()
    {
        parent::__construct('tagcloud');
    }

    public function menus()
    {
        return new ModuleMenus([new TagcloudModuleMiniMenu()]);
    }

	public function js_files()
	{
		$js_file = new ModuleJsFiles();
		$js_file->adding_always_displayed_file('tagcloud.js');
		return $js_file;
	}
}
?>
