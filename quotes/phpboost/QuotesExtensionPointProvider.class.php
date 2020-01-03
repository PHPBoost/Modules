<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 02
 * @since       PHPBoost 5.0 - 2016 02 18
 * @contributor mipel <mipel@phpboost.com>
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class QuotesExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('quotes');
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('quotes.css');
		$module_css_files->adding_always_displayed_file('quotes_mini.css');
		return $module_css_files;
	}

	public function home_page()
	{
		return new DefaultHomePageDisplay($this->get_id(), QuotesDisplayCategoryController::get_view());
	}

	public function menus()
	{
		return new ModuleMenus(array(new QuotesModuleMiniMenu()));
	}

	public function search()
	{
		return new QuotesSearchable();
	}

	public function sitemap()
	{
		return new DefaultSitemapCategoriesModule('quotes');
	}

	public function tree_links()
	{
		return new QuotesTreeLinks();
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/quotes/index.php')));
	}
}
?>
