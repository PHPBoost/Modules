<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Geoffrey ROGUELON <liaght@gmail.com>
 * @version   	PHPBoost 5.2 - last update: 2017 06 15
 * @since   	PHPBoost 3.0 - 2009 07 26
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class LastcomsUrlBuilder
{
	private static $dispatcher = '/lastcoms';

	public static function config()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config/');
	}
}
?>
