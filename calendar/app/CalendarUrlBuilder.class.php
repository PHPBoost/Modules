<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 24
 * @since       PHPBoost 3.0 - 2012 11 20
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CalendarUrlBuilder
{
	const DEFAULT_SORT_FIELD = 'date';
	const DEFAULT_SORT_MODE  = 'desc';

	private static string $dispatcher = '/calendar';

	public static function configuration(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config');
	}

	public static function manage_items(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/manage/');
	}

	public static function display_category(int $id, string $rewrited_name, ?int $year = null, ?int $month = null, ?int $day = null): Url
	{
		$category = $id > 0 ? $id . '-' . $rewrited_name . '/' : '';
		$year = $year !== null ? $year . '/' : '';
		$month = $month !== null ? $month . '/' : '';
		$day = $day !== null ? $day . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/' . $category . ($id > 0 ? $year . $month . $day : ''));
	}

	public static function display(int $id_category, string $rewrited_name_category, int $item_id, string $rewrited_title): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id_category . '-' . $rewrited_name_category . '/' . $item_id . '-' . $rewrited_title . '/');
	}

	public static function display_item_comments(int $id_category, string $rewrited_name_category, int $item_id, string $rewrited_title): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id_category . '-' . $rewrited_name_category . '/' . $item_id . '-' . $rewrited_title . '/#comments-list');
	}

	public static function display_pending_items(int $page = 1): Url
	{
		$page = $page !== 1 ? $page . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/pending/' . $page);
	}

	public static function display_member_items(?int $user_id = null, int|string $page = 1): Url
	{
		$page = $page !== 1 ? $page . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/member/' . $user_id . '/'  . $page);
	}

	public static function add_item(?int $year = null, ?int $month = null, ?int $day = null): Url
	{
		$year = $year !== null ? $year . '/' : '';
		$month = $month !== null ? $month . '/' : '';
		$day = $day !== null ? $day . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/add/' . $year . $month . $day);
	}

	public static function edit_item(int $id): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/edit/');
	}

	public static function duplicate_item(int $id): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/duplicate/');
	}

	public static function delete_item(int $id): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/delete/?' . 'token=' . AppContext::get_session()->get_token());
	}

	public static function suscribe_item(int $id): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/suscribe/');
	}

	public static function unsuscribe_item(int $id): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/unsuscribe/');
	}

	public static function ajax_month_calendar(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/ajax_month_calendar/');
	}

	public static function ajax_month_events(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/ajax_month_events/');
	}

	public static function display_items_list(?int $year = null, ?int $month = null, ?int $day = null): Url
	{
		$now = new Date();
		$date = ($year !== null && $month !== null && $day !== null ? (sprintf("%04d", $year) . '-' . sprintf("%02d", $month) . '-' . sprintf("%02d", $day)) : ($now->get_year() . '-' . sprintf("%02d", $now->get_month()) . '-' . sprintf("%02d", $now->get_day())));
		return DispatchManager::get_url(self::$dispatcher, '/items_list/?items-list=,filters:{filter1:' . $date . '}');
	}

	public static function home(?int $year = null, ?int $month = null, ?int $day = null, bool $calendar_anchor = false): Url
	{
		$year = $year !== null ? $year . '/' : '';
		$month = $month !== null ? $month . '/' : '';
		$day = $day !== null ? $day . '/' : '';
		$calendar_anchor = $calendar_anchor !== false ? '#calendar' : '';
		return DispatchManager::get_url(self::$dispatcher, '/' . $year . $month . $day . $calendar_anchor);
	}
}
?>
