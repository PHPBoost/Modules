<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 2.0 - 2008 07 07
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      xela <xela@phpboost.com>
*/

class ShoutboxExtensionPointProvider extends ModuleExtensionPointProvider
{
	public function home_page()
	{
		return new DefaultHomePageDisplay($this->get_id(), ShoutboxHomeController::get_view());
	}
}
?>
