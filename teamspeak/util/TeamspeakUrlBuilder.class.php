<?php
class TeamspeakUrlBuilder
{
	private static $dispatcher = '/teamspeak';
	
	/**
	 * @return Url
	 */
	public static function configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config/');
	}
	
	/**
	 * @return Url
	 */
	public static function refresh_viewer()
	{
		return DispatchManager::get_url(self::$dispatcher, '/ajax_refresh_viewer/');
	}
	
	/**
	 * @return Url
	 */
    public static function home()
	{
		return DispatchManager::get_url(self::$dispatcher, '/');
	}
}
?>
