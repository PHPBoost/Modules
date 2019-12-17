<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 08
 * @since       PHPBoost 4.1 - 2014 12 12
*/

class CountdownModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__RIGHT;
	}

	public function admin_display()
	{
		return '';
	}

	public function get_menu_id()
	{
		return 'module-mini-countdown';
	}

	public function get_menu_title()
	{
		return LangLoader::get_message('title', 'common', 'countdown');
	}

	public function is_displayed()
	{
		return CountdownAuthorizationsService::check_authorizations()->read();
	}

	public function get_menu_content()
	{
		$lang = LangLoader::get('common', 'countdown');
		$tpl = new FileTemplate('countdown/CountdownModuleMiniMenu.tpl');
		$tpl->add_lang($lang);
		MenuService::assign_positions_conditions($tpl, $this->get_block());
		Menu::assign_common_template_variables($tpl);

		$countdown_config = CountdownConfig::load();
		$event_date = $countdown_config->get_event_date();

		$tpl->put_all(array(
			'C_DISABLED'        => $countdown_config->get_timer_disabled(),
			'C_STOP_COUNTER'    => $countdown_config->get_stop_counter(),
			'C_RELEASE_COUNTER' => !$countdown_config->get_hidden_counter(),

			'TIMER_YEAR'        => $event_date->get_year(),
			'TIMER_MONTH'       => $event_date->get_month(),
			'TIMER_DAY'         => $event_date->get_day(),
			'TIMER_HOUR'        => $event_date->get_hours(),
			'TIMER_MINUTE'      => $event_date->get_minutes(),

			'NO_JAVAS'          => FormatingHelper::second_parse($countdown_config->get_no_javas()),
			'NEXT_EVENT'        => FormatingHelper::second_parse($countdown_config->get_next_event()),
			'LAST_EVENT'        => FormatingHelper::second_parse($countdown_config->get_last_event()),
			'STOPPED_EVENT'     => FormatingHelper::second_parse($countdown_config->get_stopped_event()),
			'NO_EVENT'          => FormatingHelper::second_parse($countdown_config->get_no_event()),

			'L_DAY'             => $lang['day'],
			'L_HOUR'            => $lang['hour'],
			'L_MINI_HOUR'       => $lang['mini.hour'],
			'L_MINUTE'          => $lang['minute'],
			'L_MINI_MINUTE'     => $lang['mini.minute'],
			'L_SECOND'          => $lang['seconde'],
			'L_MINI_SECOND'     => $lang['mini.seconde'],
		));

		return $tpl->render();
	}

	public function display()
	{
		if ($this->is_displayed())
		{
			return $this->get_menu_content();
		}
		return '';
	}
}
?>
