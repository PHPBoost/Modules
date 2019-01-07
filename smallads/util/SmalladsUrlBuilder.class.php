<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 12 10
 * @since   	PHPBoost 5.0 - 2016 02 02
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

    // Categories

	/**
	 * @return Url
	 */
	public static function manage_categories()
	{
		return DispatchManager::get_url(self::$dispatcher, '/categories/');
	}

	/**
	 * @return Url
	 */
	public static function category_syndication($id)
	{
		return SyndicationUrlBuilder::rss('smallads', $id);
	}

	/**
	 * @return Url
	 */
	public static function add_category($id_parent = null)
	{
		$id_parent = !empty($id_parent) ? $id_parent . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/categories/add/' . $id_parent);
	}

	/**
	 * @return Url
	 */
	public static function edit_category($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/categories/'. $id .'/edit/');
	}

	/**
	 * @return Url
	 */
	public static function delete_category($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/categories/'. $id .'/delete/');
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
	public static function display_item($id_category, $rewrited_name_category, $id_smallad, $rewrited_title)
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
	public static function display_tag($rewrited_name)
	{
		return DispatchManager::get_url(self::$dispatcher, '/tag/'. $rewrited_name);
	}

	/**
	 * @return Url
	 */
	public static function display_member_items()
	{
		return DispatchManager::get_url(self::$dispatcher, '/member/');
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
