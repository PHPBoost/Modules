<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 28
 * @since       PHPBoost 5.0 - 2016 04 22
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class EasyCssUrlBuilder
{

    private static string $dispatcher = '/EasyCss';

    public static function theme_choice(): Url
    {
        return DispatchManager::get_url(self::$dispatcher, '/admin/theme/');
    }

    public static function edit(string $theme, string $file): Url
    {
        return DispatchManager::get_url(self::$dispatcher, '/admin/edit/' . $theme .'/' . $file . '/');
    }
}
?>
