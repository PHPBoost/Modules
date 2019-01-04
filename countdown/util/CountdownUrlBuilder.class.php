<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2016 11 11
 * @since   	PHPBoost 4.1 - 2014 12 12
*/

class CountdownUrlBuilder
{
	private static $dispatcher = '/countdown';

	public static function config()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config/');
	}
}
?>
