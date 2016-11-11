<?php
/*##################################################
 *		                   CountdownConfig.class.php
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

class CountdownConfig extends AbstractConfigData
{
	const TIMER_DISABLED = 'timer_disabled';
	const EVENT_DATE = 'event_date';
	
	const NO_JAVAS = 'no_javas';
	const NEXT_EVENT = 'next_event';
	const LAST_EVENT = 'last_event';
	const NO_EVENT = 'no_event';
	const AUTHORIZATIONS = 'authorizations';
	
	public function get_timer_disabled()
	{
		return $this->get_property(self::TIMER_DISABLED);
	}

	public function set_timer_disabled($timer_disabled)
	{
		$this->set_property(self::TIMER_DISABLED, $timer_disabled);
	}
	
	public function get_event_date()
	{
		return $this->get_property(self::EVENT_DATE);
	}

	public function set_event_date(Date $date)
	{
		$this->set_property(self::EVENT_DATE, $date);
	}
	
	public function get_next_event()
	{
		return $this->get_property(self::NEXT_EVENT);
	}

	public function set_next_event($next_event)
	{
		$this->set_property(self::NEXT_EVENT, $next_event);
	}
	
	public function get_last_event()
	{
		return $this->get_property(self::LAST_EVENT);
	}

	public function set_last_event($last_event)
	{
		$this->set_property(self::LAST_EVENT, $last_event);
	}
	
	public function get_no_event()
	{
		return $this->get_property(self::NO_EVENT);
	}

	public function set_no_event($no_event)
	{
		$this->set_property(self::NO_EVENT, $no_event);
	}
	
	public function get_no_javas()
	{
		return $this->get_property(self::NO_JAVAS);
	}

	public function set_no_javas($no_javas)
	{
		$this->set_property(self::NO_JAVAS, $no_javas);
	}
	
	 /**
	 * @method Get authorizations
	 */
	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}
	
	 /**
	 * @method Set authorizations
	 * @params string[] $array Array of authorizations
	 */
	public function set_authorizations(Array $array)
	{
		$this->set_property(self::AUTHORIZATIONS, $array);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::TIMER_DISABLED => false,
			self::NO_JAVAS => LangLoader::get_message('countdown.no.javas', 'install', 'countdown'),
			self::EVENT_DATE => new Date(1483225200, Timezone::SERVER_TIMEZONE), //le 1/1/2017 à 0:00:00
			self::NEXT_EVENT => LangLoader::get_message('countdown.next.event', 'install', 'countdown'),
			self::LAST_EVENT => LangLoader::get_message('countdown.last.event', 'install', 'countdown'),
			self::NO_EVENT => LangLoader::get_message('countdown.no.event', 'install', 'countdown'),
			self::AUTHORIZATIONS => array('r-1' => 3, 'r0' => 3, 'r1' => 7)
		);
	}

	/**
	 * Returns the configuration.
	 * @return CountdownConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'countdown', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('countdown', self::load(), 'config');
	}
}
?>