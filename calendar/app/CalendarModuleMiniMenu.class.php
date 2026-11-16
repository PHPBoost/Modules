<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 15
 * @since       PHPBoost 3.0 - 2012 11 22
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CalendarModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block(): int
	{
		return self::BLOCK_POSITION__LEFT;
	}

	public function get_menu_id(): string
	{
		return 'module-mini-calendar';
	}

	public function get_menu_title(): string
	{
		return LangLoader::get_message('calendar.module.title', 'common', 'calendar');
	}

	public function is_displayed(): bool
	{
		return !Url::is_current_url('/calendar/') && CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, 'calendar')->read();
	}

	public function get_menu_content(): string
	{
		$view = new FileTemplate('calendar/CalendarModuleMiniMenu.tpl');
		$view->add_lang(LangLoader::get_all_langs('calendar'));
		MenuService::assign_positions_conditions($view, $this->get_block());

		$view->put('CALENDAR', CalendarAjaxCalendarController::get_view(true));

		return $view->render();
	}
}
?>
