<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 26
 * @since       PHPBoost 3.0 - 2011 07 07
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CustomizationExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('customization');
	}

	public function tree_links(): mixed
	{
		return new CustomizationTreeLinks();
	}

	public function url_mappings(): mixed
	{
		return new UrlMappings([new DispatcherUrlMapping('/customization/index.php', '([\w/_-]*(?:\.css)?)$')]);
	}
}
?>
