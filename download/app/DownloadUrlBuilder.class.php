<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 28
 * @since       PHPBoost 4.0 - 2014 08 24
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DownloadUrlBuilder
{
	private static string $dispatcher = '/download';

	public static function configuration(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config');
	}

	public static function manage(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/manage/');
	}

	public static function display_category(int $id, string $rewrited_name, string $sort_field = '', string $sort_mode = '', int|string $page = 1, int|string $subcategories_page = 1): Url
	{
		$config = DownloadConfig::load();
		$category = $id > 0 ? $id . '-' . $rewrited_name . '/' : '';
		$page = $page !== 1 || $subcategories_page !== 1 ? $page . '/' : '';
		$subcategories_page = $subcategories_page !== 1 ? $subcategories_page . '/' : '';
		$sort_field = ($sort_field && $sort_field !== $config->get_items_default_sort_field()) ? $sort_field . '/' : '';
		$sort_mode = ($sort_mode && $sort_mode !== $config->get_items_default_sort_mode()) ? $sort_mode . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/' . $category . $sort_field . $sort_mode . $page . $subcategories_page);
	}

	public static function display_tag(string $rewrited_name, string $sort_field = '', string $sort_mode = '', int|string $page = 1): Url
	{
		$config = DownloadConfig::load();
		$page = $page !== 1 ? $page . '/' : '';
		$sort_field = ($sort_field && $sort_field !== $config->get_items_default_sort_field()) ? $sort_field . '/' : '';
		$sort_mode = ($sort_mode && $sort_mode !== $config->get_items_default_sort_mode()) ? $sort_mode . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/tag/' . $rewrited_name . '/' . $sort_field . $sort_mode . $page);
	}

	public static function display_pending(string $sort_field = '', string $sort_mode = '', int|string $page = 1): Url
	{
		$config = DownloadConfig::load();
		$page = $page !== 1 ? $page . '/' : '';
		$sort_field = ($sort_field && $sort_field !== $config->get_items_default_sort_field()) ? $sort_field . '/' : '';
		$sort_mode = ($sort_mode && $sort_mode !== $config->get_items_default_sort_mode()) ? $sort_mode . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/pending/' . $sort_field . $sort_mode . $page);
	}

	public static function display_member_items(?int $user_id = null, int|string $page = 1): Url
	{
		$page = $page !== 1 ? $page . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/member/' . $user_id . '/' . $page);
	}

	public static function add(?int $id_category = null): Url
	{
		$id_category = !empty($id_category) ? $id_category . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/add/' . $id_category);
	}

	public static function edit(int $id): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/edit/');
	}

	public static function duplicate(int $id): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/duplicate/');
	}

	public static function delete(int $id): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/delete/?' . 'token=' . AppContext::get_session()->get_token());
	}

	public static function display(int $id_category, string $rewrited_name_category, int $id, string $rewrited_title): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id_category . '-' . $rewrited_name_category . '/' . $id . '-' . $rewrited_title . '/');
	}

	public static function display_comments(int $id_category, string $rewrited_name_category, int $id, string $rewrited_title): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id_category . '-' . $rewrited_name_category . '/' . $id . '-' . $rewrited_title . '/#comments-list');
	}

	public static function download(int $id): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/file/' . $id);
	}

	public static function dead_link(int $id): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/dead_link/' . $id);
	}

	public static function home(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/');
	}
}
?>
