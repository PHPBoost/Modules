<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 26
 * @since       PHPBoost 3.0 - 2011 09 19
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminCustomizeUrlBuilder
{
    private static $dispatcher = '/customization';

    public static function customize_interface(string $theme = ''): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/interface/'. $theme);
	}

    public static function customize_favicon(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/favicon');
	}

    public static function editor_css_file(string $theme = '', string $file = ''): Url
	{
		$url = !empty($file) ? $theme . '/' . $file : $theme;
		return DispatchManager::get_url(self::$dispatcher, '/admin/editor/css/'. $url);
	}

    public static function editor_tpl_file(string $theme = '', string $file = ''): Url
	{
		$url = !empty($file) ? $theme . '/' . $file : $theme;
		return DispatchManager::get_url(self::$dispatcher, '/admin/editor/tpl/'. $url);
	}
}
?>
