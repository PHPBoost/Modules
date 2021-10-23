<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 24
 * @since       PHPBoost 3.0 - 2012 12 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class GoogleAnalyticsExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('GoogleAnalytics');
	}

	public function menus()
	{
		return new ModuleMenus(array(new GoogleAnalyticsModuleMiniMenu()));
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/GoogleAnalytics/index.php')));
	}
}
?>
