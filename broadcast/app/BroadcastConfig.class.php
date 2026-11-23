<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 11
 * @since       PHPBoost 6.0 - 2022 10 25
 */

class BroadcastConfig extends AbstractConfigData
{
	const RADIO_NAME        = 'broadcast_name';
	const RADIO_IMG         = 'broadcast_logo';
	const PLAYER_TYPE       = 'player_type';
	const BROADCAST_URL     = 'broadcast_url';
	const BROADCAST_WIDGET  = 'broadcast_widget';
	const BROADCAST_COMBO   = 'broadcast_combo';
	const PLAYER_WIDTH      = 'player_width';
	const PLAYER_HEIGHT     = 'player_height';

	const AUTHORIZATIONS = 'authorizations';

	const DISPLAY_TYPE   = 'display_type';
	const ACCORDION_VIEW = 'accordion_view';
	const TABLE_VIEW     = 'table_view';
	const CALENDAR_VIEW  = 'calendar_view';

	public function get_broadcast_name(): string
	{
		return $this->get_property(self::RADIO_NAME);
	}

	public function set_broadcast_name(string $broadcast_name): void
	{
		$this->set_property(self::RADIO_NAME, $broadcast_name);
	}

	public function get_player_type(): string
	{
		return $this->get_property(self::PLAYER_TYPE);
	}

	public function set_player_type(string $value): void
	{
		$this->set_property(self::PLAYER_TYPE, $value);
	}

	public function get_broadcast_url(): Url
	{
		return new Url($this->get_property(self::BROADCAST_URL));
	}

	public function set_broadcast_url(Url $broadcast_url): void
	{
		$this->set_property(self::BROADCAST_URL, $broadcast_url);
	}

	public function get_broadcast_widget(): string
	{
		return $this->get_property(self::BROADCAST_WIDGET);
	}

	public function set_broadcast_widget(string $broadcast_widget): void
	{
		$this->set_property(self::BROADCAST_WIDGET, $broadcast_widget);
	}

	public function get_broadcast_combo(): string
	{
		return $this->get_property(self::BROADCAST_COMBO);
	}

	public function set_broadcast_combo(string $broadcast_combo): void
	{
		$this->set_property(self::BROADCAST_COMBO, $broadcast_combo);
	}

	public function get_player_width(): int
	{
		return $this->get_property(self::PLAYER_WIDTH);
	}

	public function set_player_width(int $player_width): void
	{
		$this->set_property(self::PLAYER_WIDTH, $player_width);
	}

	public function get_player_height(): int
	{
		return $this->get_property(self::PLAYER_HEIGHT);
	}

	public function set_player_height(int $player_height): void
	{
		$this->set_property(self::PLAYER_HEIGHT, $player_height);
	}

	public function get_broadcast_logo(): Url
	{
		return new Url($this->get_property(self::RADIO_IMG));
	}

	public function set_broadcast_logo(Url $broadcast_logo): void
	{
		$this->set_property(self::RADIO_IMG, $broadcast_logo);
	}

	public function get_display_type(): string
	{
		return $this->get_property(self::DISPLAY_TYPE);
	}

	public function set_display_type(string $display_type): void
	{
		$this->set_property(self::DISPLAY_TYPE, $display_type);
	}

	public function get_authorizations(): array
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}

	public function set_authorizations(array $authorizations): void
	{
		$this->set_property(self::AUTHORIZATIONS, $authorizations);
	}

	public function get_default_values(): array
	{
		return [
			self::RADIO_NAME 	   => 'RadioBoost',
			self::RADIO_IMG 	   => '/modules/broadcast/templates/images/default_item.webp',
			self::PLAYER_TYPE 	   => BroadcastConfig::BROADCAST_URL,
			self::BROADCAST_URL    => LangLoader::get_message('broadcast.mp3', 'install', 'broadcast'),
			self::BROADCAST_WIDGET => FormatingHelper::second_parse(LangLoader::get_message('broadcast.widget', 'install', 'broadcast')),
			self::PLAYER_WIDTH 	   => 300,
			self::PLAYER_HEIGHT    => 400,
			self::DISPLAY_TYPE 	   => self::ACCORDION_VIEW,
			self::AUTHORIZATIONS   => ['r-1' => 1, 'r0' => 1, 'r1' => 17]
		];
	}

	/**
	 * Returns the configuration.
	 * @return BroadcastConfig
	 */
	public static function load()
	{
		return ConfigManager::load(self::class, 'broadcast', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('broadcast', self::load(), 'config');
	}
}
?>
