<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2025 01 12
 * @since       PHPBoost 5.0 - 2016 02 02
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SmalladsUrlBuilder
{

	private static $dispatcher = '/smallads';

    // Administration

	/**
	 * @return Url
	 */
	public static function categories_configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/display/');
	}

	/**
	 * @return Url
	 */
	public static function items_configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/items/');
	}

	/**
	 * @return Url
	 */
	public static function mini_configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/mini/');
	}

	/**
	 * @return Url
	 */
	public static function usage_terms_configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/terms/');
	}

	/**
	 * @return Url
	 */
	public static function display_category($id, $rewrited_name)
	{
		$category = $id > 0 ? $id . '-' . $rewrited_name .'/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/' . $category);
	}

    // Items

	/**
	 * @return Url
	 */
	public static function manage_items()
	{
		return DispatchManager::get_url(self::$dispatcher, '/manage/');
	}

	/**
	 * @return Url
	 */
	public static function print_item($id_smallad, $rewrited_title)
	{
		return DispatchManager::get_url(self::$dispatcher, '/print/' . $id_smallad . '-' . $rewrited_title . '/');
	}

	/**
	 * @return Url
	 */
	public static function add_item($id_category = null)
	{
		$id_category = !empty($id_category) ? $id_category . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/add/' . $id_category);
	}

	/**
	 * @return Url
	 */
	public static function edit_item($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/edit/');
	}

	/**
	 * @return Url
	 */
	public static function delete_item($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/delete/?' . 'token=' . AppContext::get_session()->get_token());
	}

	/**
	 * @return Url
	 */
	public static function display($id_category, $rewrited_name_category, $id_smallad, $rewrited_title)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id_category . '-' . $rewrited_name_category . '/' . $id_smallad . '-' .$rewrited_title . '/');
	}

	/**
	 * @return Url
	 */
	public static function display_items_comments($id_category, $rewrited_name_category, $id_smallad, $rewrited_title)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id_category . '-' . $rewrited_name_category . '/' . $id_smallad . '-' . $rewrited_title . '/#comments-list');
	}

	/**
	 * @return Url
	 */
	public static function display_pending_items()
	{
		return DispatchManager::get_url(self::$dispatcher, '/pending/');
	}

	/**
	 * @return Url
	 */
	public static function archived_items()
	{
		return DispatchManager::get_url(self::$dispatcher, '/archives/');
	}

	/**
	 * @return Url
	 */
	public static function display_tag($rewrited_name)
	{
		return DispatchManager::get_url(self::$dispatcher, '/tag/'. $rewrited_name);
	}

	/**
	 * @return Url
	 */
	public static function display_member_items($user_id = null, $page = 1)
	{
		$page = $page !== 1 ? $page . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/member/' . $user_id. '/'  . $page);
	}

	/**
	 * @return Url
	 */
	public static function usage_terms()
	{
		return DispatchManager::get_url(self::$dispatcher, '/terms/');
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
