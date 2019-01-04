<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 12 24
 * @since   	PHPBoost 5.0 - 2016 02 18
 * @contributor mipel <mipel@phpboost.com>
*/

class QuotesUrlBuilder
{
	private static $dispatcher = '/quotes';

	/**
	 * @return Url
	 */
	public static function configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config');
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
	public static function manage_categories()
	{
		return DispatchManager::get_url(self::$dispatcher, '/categories/');
	}

	/**
	 * @return Url
	 */
	public static function manage()
	{
		return DispatchManager::get_url(self::$dispatcher, '/manage/');
	}

	/**
	 * @return Url
	 */
	public static function display_category($id, $rewrited_name, $page = 1, $subcategories_page = 1)
	{
		$category = $id > 0 ? $id . '-' . $rewrited_name . '/' : '';
		$page = $page !== 1 || $subcategories_page !== 1 ? $page . '/' : '';
		$subcategories_page = $subcategories_page !== 1 ? $subcategories_page . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/' . $category . $page . $subcategories_page);
	}

	/**
	 * @return Url
	 */
	public static function display_author_quotes($rewrited_author, $page = 1)
	{
		$page = $page !== 1 ? $page . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/author/' . $rewrited_author . $page);
	}

	/**
	 * @return Url
	 */
	public static function display_pending($page = 1)
	{
		$page = $page !== 1 ? $page . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/pending/' . $page);
	}

	/**
	 * @return Url
	 */
	public static function add($id_category = null, $rewrited_author = null)
	{
		$id_category = !empty($id_category) || !empty($rewrited_author) ? $id_category . '/': '';
		$rewrited_author = !empty($rewrited_author) ? $rewrited_author . '/': '';
		return DispatchManager::get_url(self::$dispatcher, '/add/' . $id_category . $rewrited_author);
	}

	/**
	 * @return Url
	 */
	public static function edit($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/edit/');
	}

	/**
	 * @return Url
	 */
	public static function delete($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/delete/?token=' . AppContext::get_session()->get_token());
	}

	/**
	 * @return Url
	 */
	public static function ajax_authors()
	{
		return DispatchManager::get_url(self::$dispatcher, '/ajax_authors/');
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
