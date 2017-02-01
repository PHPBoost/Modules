<?php
/*##################################################
 *                               WikiStatusModuleMiniMenu.class.php
 *                            -------------------
 *   begin                : January 30, 2017
 *   copyright            : (C) 2016 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

class WikiStatusExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('WikiStatus');
	}
	
	public function menus()
	{
		return new ModuleMenus(array(new WikiStatusModuleMiniMenu()));
	}
	
	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_always_displayed_file('wikistatus.css');
		return $module_css_files;
	}
}
?>