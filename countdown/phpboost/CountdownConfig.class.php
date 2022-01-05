<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 01 05
 * @since       PHPBoost 4.1 - 2014 12 12
*/

class CountdownConfig extends AbstractConfigData
{
	const TIMER_DISABLED = 'timer_disabled';
	const EVENT_DATE = 'event_date';

	const NO_JS = 'no_js';
	const NEXT_EVENT = 'next_event';
	const LAST_EVENT = 'last_event';
	const NO_EVENT = 'no_event';
	const STOPPED_EVENT = 'stopped_event';
	const STOP_COUNTER = 'stop_counter';
	const HIDDEN_COUNTER = 'hidden_counter';
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

	public function get_stop_counter()
	{
		return $this->get_property(self::STOP_COUNTER);
	}

	public function set_stop_counter($stop_counter)
	{
		$this->set_property(self::STOP_COUNTER, $stop_counter);
	}

	public function get_hidden_counter()
	{
		return $this->get_property(self::HIDDEN_COUNTER);
	}

	public function set_hidden_counter($hidden_counter)
	{
		$this->set_property(self::HIDDEN_COUNTER, $hidden_counter);
	}

	public function get_stopped_event()
	{
		return $this->get_property(self::STOPPED_EVENT);
	}

	public function set_stopped_event($stopped_event)
	{
		$this->set_property(self::STOPPED_EVENT, $stopped_event);
	}

	public function get_no_js()
	{
		return $this->get_property(self::NO_JS);
	}

	public function set_no_js($no_js)
	{
		$this->set_property(self::NO_JS, $no_js);
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
			self::STOP_COUNTER   => false,
			self::HIDDEN_COUNTER => false,
			self::NO_JS          => LangLoader::get_message('countdown.no.js', 'install', 'countdown'),
			self::EVENT_DATE     => new Date(1672527600, Timezone::SERVER_TIMEZONE), //le 1/1/2023 à 0:00:00
			self::NEXT_EVENT     => LangLoader::get_message('countdown.next.event', 'install', 'countdown'),
			self::LAST_EVENT     => LangLoader::get_message('countdown.last.event', 'install', 'countdown'),
			self::STOPPED_EVENT  => LangLoader::get_message('countdown.stoped.event', 'install', 'countdown'),
			self::NO_EVENT       => LangLoader::get_message('countdown.no.event', 'install', 'countdown'),
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
	 * Saving the configuration in database.
	 */
	public static function save()
	{
		ConfigManager::save('countdown', self::load(), 'config');
	}
}
?>
