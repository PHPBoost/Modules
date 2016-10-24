<?php

/* #################################################
 *                           EasycssUrlBuilder.class.php
 *                            -------------------
 *   begin                : 2016/04/22
 *   copyright            : (C) 2016 PaperToss
 *   email                : t0ssp4p3r@gmail.com
 *
 *
  ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
  ################################################### */

/**
 * EasyCss URL Builder
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
