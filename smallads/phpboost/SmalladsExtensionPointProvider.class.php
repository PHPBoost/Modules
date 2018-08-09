<?php
/*##################################################
 *                        SmalladsExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : March 15, 2018
 *   copyright            : (C) 2018 Sebastien LARTIGUE
 *   email                : babsolune@phpboost.com
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

/**
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class SmalladsExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('smallads');
	}

	public function comments()
	{
		return new CommentsTopics(array(new SmalladsCommentsTopic()));
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('smallads.css');
		$module_css_files->adding_always_displayed_file('smallads_mini.css');
		return $module_css_files;
	}

	public function menus()
	{
		return new ModuleMenus(array(new SmalladsLastItemsMiniMenu()));
	}

	public function feeds()
	{
		return new SmalladsFeedProvider();
	}

	public function home_page()
	{
		return new SmalladsHomePageExtensionPoint();
	}

	public function scheduled_jobs()
	{
		return new SmalladsScheduledJobs();
	}

	public function search()
	{
		return new SmalladsSearchable();
	}

	public function sitemap()
	{
		return new SmalladsSitemapExtensionPoint();
	}

	public function tree_links()
	{
		return new SmalladsTreeLinks();
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/smallads/index.php')));
	}
}
?>
