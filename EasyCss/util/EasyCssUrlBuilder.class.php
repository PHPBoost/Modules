<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      PaperToss <t0ssp4p3r@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 24
 * @since       PHPBoost 5.0 - 2016 04 22
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class EasyCssUrlBuilder
{

    private static $dispatcher = '/EasyCss';

    /**
     * URL du choix du thème
     *
     * @return Url
     */
    public static function theme_choice()
    {
        return DispatchManager::get_url(self::$dispatcher, '/theme/');
    }

    /**
     * URL d'édition d'un fichier css
     *
     * @return Url
     */
    public static function edit($theme, $file)
    {
        return DispatchManager::get_url(self::$dispatcher, '/edit/' . $theme .'/' . $file . '/');
    }


}
