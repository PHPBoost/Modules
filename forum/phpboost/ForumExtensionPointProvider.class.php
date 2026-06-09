<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 2.0 - 2008 02 24
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      xela <xela@phpboost.com>
*/

define('FORUM_MAX_SEARCH_RESULTS', 50);

class ForumExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('forum');
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('forum.css');
		return $module_css_files;
	}

	public function feeds()
	{
		return new ForumFeedProvider();
	}

	public function home_page()
	{
		return new DefaultHomePageDisplay($this->get_id(), ForumHomeController::get_view());
	}

	public function lobby(): array
	{
		return [new ForumLobbyProvider()];
	}

	public function scheduled_jobs()
	{
		return new ForumScheduledJobs();
	}

	public function search()
	{
		return new ForumSearchable();
	}

	public function sitemap()
	{
		return new DefaultSitemapCategoriesModule('forum');
	}

	public function tree_links()
	{
		return new ForumTreeLinks();
	}

    public function url_mappings()
    {
        return new UrlMappings([
            new DispatcherUrlMapping('/forum/index.php'),
            new UrlMapping('^forum/forum-([0-9]+)-([a-z0-9][a-z0-9_-]*)\.php$', '/forum/forum.php?id=$1'),
            new UrlMapping('^forum/topic-([0-9]+)-([a-z0-9][a-z0-9_-]*)\.php$', '/forum/topic.php?id=$1'),
            new UrlMapping('^forum/cat-([0-9]+)-([a-z0-9][a-z0-9_-]*)\.php$',   '/forum/index.php?id=$1'),
            new UrlMapping('^forum$', '/modules/forum/index.php?url=/', 'L,NC,QSA,R=301'),
        ]);
    }

	public function user()
	{
		return new ForumUserExtensionPoint();
	}
}
?>
