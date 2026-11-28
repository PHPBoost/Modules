<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 28
 * @since       PHPBoost 5.0 - 2016 02 18
 * @author      mipel <mipel@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class QuotesUrlBuilder
{
	private static $dispatcher = '/quotes';

	public static function configuration(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config');
	}

	public static function manage(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/manage/');
	}

	public static function display_category(int $id, string $rewrited_name, int|string $page = 1, int|string $subcategories_page = 1): Url
	{
		$category = $id > 0 ? $id . '-' . $rewrited_name . '/' : '';
		$page = $page !== 1 || $subcategories_page !== 1 ? $page . '/' : '';
		$subcategories_page = $subcategories_page !== 1 ? $subcategories_page . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/' . $category . $page . $subcategories_page);
	}

	public static function display_writer_items(string $rewrited_writer, int|string $page = 1): Url
	{
		$page = $page !== 1 ? $page . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/writer/' . $rewrited_writer . $page);
	}

	public static function display_pending(int|string $page = 1): Url
	{
		$page = $page !== 1 ? $page . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/pending/' . $page);
	}

	public static function display_member_items(?int $user_id = null, int|string $page = 1): Url
	{
		$page = $page !== 1 ? $page . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/member/' . $user_id . '/' . $page);
	}

	public static function add(?int $id_category = null, ?string $rewrited_writer = null): Url
	{
		$id_category = !empty($id_category) || !empty($rewrited_writer) ? $id_category . '/': '';
		$rewrited_writer = !empty($rewrited_writer) ? $rewrited_writer . '/': '';
		return DispatchManager::get_url(self::$dispatcher, '/add/' . $id_category . $rewrited_writer);
	}

	public static function edit(int $id): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/edit/');
	}

	public static function delete(int $id): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/delete/?token=' . AppContext::get_session()->get_token());
	}

	public static function ajax_writers(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/ajax_writers/');
	}

	public static function home(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/');
	}
}
?>
