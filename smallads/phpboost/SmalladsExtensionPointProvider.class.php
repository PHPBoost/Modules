<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 08 09
 * @since   	PHPBoost 4.0 - 2013 01 29
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
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
