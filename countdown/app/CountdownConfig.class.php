<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 11
 * @since       PHPBoost 4.1 - 2014 12 12
*/

class CountdownConfig extends AbstractConfigData
{
	const TIMER_DISABLED = 'timer_disabled';
	const EVENT_DATE     = 'event_date';

	const NO_JS          = 'no_js';
	const NEXT_EVENT     = 'next_event';
	const LAST_EVENT     = 'last_event';
	const NO_EVENT       = 'no_event';
	const STOPPED_EVENT  = 'stopped_event';
	const STOP_COUNTER   = 'stop_counter';
	const HIDDEN_COUNTER = 'hidden_counter';
	const AUTHORIZATIONS = 'authorizations';

	public function get_timer_disabled(): bool
	{
		return $this->get_property(self::TIMER_DISABLED);
	}

	public function set_timer_disabled(bool $timer_disabled): void
	{
		$this->set_property(self::TIMER_DISABLED, $timer_disabled);
	}

	public function get_event_date(): Date
	{
		return $this->get_property(self::EVENT_DATE);
	}

	public function set_event_date(Date $date): void
	{
		$this->set_property(self::EVENT_DATE, $date);
	}

	public function get_next_event(): string
	{
		return $this->get_property(self::NEXT_EVENT);
	}

	public function set_next_event(string $next_event): void
	{
		$this->set_property(self::NEXT_EVENT, $next_event);
	}

	public function get_last_event(): string
	{
		return $this->get_property(self::LAST_EVENT);
	}

	public function set_last_event(string $last_event): void
	{
		$this->set_property(self::LAST_EVENT, $last_event);
	}

	public function get_no_event(): string
	{
		return $this->get_property(self::NO_EVENT);
	}

	public function set_no_event(string $no_event): void
	{
		$this->set_property(self::NO_EVENT, $no_event);
	}

	public function get_stop_counter(): bool
	{
		return $this->get_property(self::STOP_COUNTER);
	}

	public function set_stop_counter(bool $stop_counter): void
	{
		$this->set_property(self::STOP_COUNTER, $stop_counter);
	}

	public function get_hidden_counter(): bool
	{
		return $this->get_property(self::HIDDEN_COUNTER);
	}

	public function set_hidden_counter(bool $hidden_counter): void
	{
		$this->set_property(self::HIDDEN_COUNTER, $hidden_counter);
	}

	public function get_stopped_event(): string
	{
		return $this->get_property(self::STOPPED_EVENT);
	}

	public function set_stopped_event(string $stopped_event): void
	{
		$this->set_property(self::STOPPED_EVENT, $stopped_event);
	}

	public function get_no_js(): string
	{
		return $this->get_property(self::NO_JS);
	}

	public function set_no_js(string $no_js): void
	{
		$this->set_property(self::NO_JS, $no_js);
	}

	public function get_authorizations(): array
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}

	public function set_authorizations(array $array): void
	{
		$this->set_property(self::AUTHORIZATIONS, $array);
	}

	public function get_default_values(): array
	{
		$now = new Date();
		$new_year = $now->get_year() + 1 . '-01-01';
		return [
			self::TIMER_DISABLED => false,
			self::STOP_COUNTER   => false,
			self::HIDDEN_COUNTER => false,
			self::NO_JS          => LangLoader::get_message('countdown.no.js', 'install', 'countdown'),
			self::EVENT_DATE     => new Date($new_year, Timezone::SERVER_TIMEZONE), // next new year date
			self::NEXT_EVENT     => LangLoader::get_message('countdown.next.event', 'install', 'countdown'),
			self::LAST_EVENT     => LangLoader::get_message('countdown.last.event', 'install', 'countdown'),
			self::STOPPED_EVENT  => LangLoader::get_message('countdown.stoped.event', 'install', 'countdown'),
			self::NO_EVENT       => LangLoader::get_message('countdown.no.event', 'install', 'countdown'),
			self::AUTHORIZATIONS => ['r-1' => 3, 'r0' => 3, 'r1' => 7]
		];
	}

	/**
	 * Returns the configuration.
	 * @return CountdownConfig
	 */
	public static function load(): CountdownConfig
	{
		return ConfigManager::load(self::class, 'countdown', 'config');
	}

	/**
	 * Saving the configuration in database.
	 */
	public static function save(): void
	{
		ConfigManager::save('countdown', self::load(), 'config');
	}
}
?>
