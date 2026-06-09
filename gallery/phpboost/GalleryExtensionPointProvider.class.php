<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 2.0 - 2008 07 07
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      xela <xela@phpboost.com>
*/

if (defined('PHPBOOST') !== true) exit;

class GalleryExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('gallery');
	}

	public function comments()
	{
		return new CommentsTopics([new GalleryCommentsTopic()]);
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('gallery.css');
		$module_css_files->adding_always_displayed_file('gallery_mini.css');
		return $module_css_files;
	}

	public function feeds()
	{
		return new GalleryFeedProvider();
	}

	public function home_page()
	{
		return new DefaultHomePageDisplay($this->get_id(), GalleryDisplayCategoryController::get_view());
	}

	public function menus()
	{
		return new ModuleMenus([new GalleryModuleMiniMenu()]);
	}

	public function sitemap()
	{
		return new DefaultSitemapCategoriesModule('gallery');
	}

	public function tree_links()
	{
		return new GalleryTreeLinks();
	}

	public function url_mappings()
	{
		return new UrlMappings([new DispatcherUrlMapping('/gallery/index.php')]);
	}

	public function lobby(): array
	{
		return [new GalleryLobbyProvider()];
	}
}
?>
