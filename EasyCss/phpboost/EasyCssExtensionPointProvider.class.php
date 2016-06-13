<?php

/* #################################################
 *                           EasyCssExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : 2016/00/22
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
 * Provide phpboost services
 *
 * @author PaperToss
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
