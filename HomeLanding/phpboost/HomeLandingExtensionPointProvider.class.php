<?php
/*##################################################
 *                              HomeLandingExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : January 2, 2016
 *   copyright            : (C) 2016 Sebastien Lartigue
 *   email                : babso@web33.fr
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
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

class HomeLandingExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('HomeLanding');
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('HomeLanding.css');
		$module_css_files->adding_running_module_displayed_file('HomeSlider.css');
		return $module_css_files;
	}

	public function home_page()
	{
		return new HomeLandingHomePageExtensionPoint();
	}

	public function tree_links()
	{
		return new HomeLandingTreeLinks();
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/HomeLanding/index.php')));
	}
}
?>
