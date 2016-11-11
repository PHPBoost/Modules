<?php
/*##################################################
 *                        CountdownModuleMiniMenu.class.php
 *                            -------------------
 *   begin                	: December 12, 2014
 *   copyright            	: (C) 2014 Sebastien LARTIGUE
 *   email                	: babsolune@phpboost.com
 *   credits 			 	: Edson Hilios @ http://hilios.github.io/jQuery.countdown/
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class CountdownModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__TOP_CENTRAL;
	}

	public function display($tpl = false)
	{
    	if (CountdownAuthorizationsService::check_authorizations()->read())
		{
			$lang = LangLoader::get('common', 'countdown');
			$tpl = new FileTemplate('countdown/CountdownModuleMiniMenu.tpl');
			$tpl->add_lang($lang);
			MenuService::assign_positions_conditions($tpl, $this->get_block());
			
			$countdown_config = CountdownConfig::load();
			$event_date = $countdown_config->get_event_date();
			
			$tpl->put_all(array(
			'C_DISABLED' => $countdown_config->get_timer_disabled(),
			
			'TIMER_YEAR' => $event_date->get_year(),
			'TIMER_MONTH' => $event_date->get_month(),
			'TIMER_DAY' => $event_date->get_day(),
			'TIMER_HOUR' => $event_date->get_hours(),
			'TIMER_MINUTE' => $event_date->get_minutes(),
			
			'NO_JAVAS' => $countdown_config->get_no_javas(),
			'NEXT_EVENT' => $countdown_config->get_next_event(),
			'LAST_EVENT' => $countdown_config->get_last_event(),
			'NO_EVENT' => $countdown_config->get_no_event(),
			
			'L_DAY' => $lang['day'],
			'L_HOUR' => $lang['hour'],
			'L_MINI_HOUR' => $lang['mini.hour'],
			'L_MINUTE' => $lang['minute'],
			'L_MINI_MINUTE' => $lang['mini.minute'],
			'L_SECOND' => $lang['seconde'],
			'L_MINI_SECOND' => $lang['mini.seconde'],
			));
			
			return $tpl->render();
		}
	}
}
?>