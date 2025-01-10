<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2025 01 10
 * @since       PHPBoost 6.0 - 2022 10 25
 */

class BroadcastConfig extends AbstractConfigData
{
	const RADIO_NAME    = 'broadcast_name';
	const RADIO_IMG     = 'broadcast_logo';
	const PLAYER_TYPE   = 'player_type';
	const BROADCAST_URL     = 'broadcast_url';
	const BROADCAST_WIDGET  = 'broadcast_widget';
	const BROADCAST_COMBO   = 'broadcast_combo';
	const PLAYER_WIDTH  = 'player_width';
	const PLAYER_HEIGHT = 'player_height';

	const AUTHORIZATIONS = 'authorizations';

	const DISPLAY_TYPE   = 'display_type';
	const ACCORDION_VIEW = 'accordion_view';
	const TABLE_VIEW     = 'table_view';
	const CALENDAR_VIEW  = 'calendar_view';

	public function get_broadcast_name()
	{
		return $this->get_property(self::RADIO_NAME);
	}

	public function set_broadcast_name($broadcast_name)
	{
		$this->set_property(self::RADIO_NAME, $broadcast_name);
	}

	public function get_player_type()
	{
		return $this->get_property(self::PLAYER_TYPE);
	}

	public function set_player_type($value)
	{
		$this->set_property(self::PLAYER_TYPE, $value);
	}

	public function get_broadcast_url()
	{
		return new Url($this->get_property(self::BROADCAST_URL));
	}

	public function set_broadcast_url($broadcast_url)
	{
		$this->set_property(self::BROADCAST_URL, $broadcast_url);
	}

	public function get_broadcast_widget()
	{
		return $this->get_property(self::BROADCAST_WIDGET);
	}

	public function set_broadcast_widget($broadcast_widget)
	{
		$this->set_property(self::BROADCAST_WIDGET, $broadcast_widget);
	}

	public function get_broadcast_combo()
	{
		return $this->get_property(self::BROADCAST_COMBO);
	}

	public function set_broadcast_combo($broadcast_combo)
	{
		$this->set_property(self::BROADCAST_COMBO, $broadcast_combo);
	}

	public function get_player_width()
	{
		return $this->get_property(self::PLAYER_WIDTH);
	}

	public function set_player_width($player_width)
	{
		$this->set_property(self::PLAYER_WIDTH, $player_width);
	}

	public function get_player_height()
	{
		return $this->get_property(self::PLAYER_HEIGHT);
	}

	public function set_player_height($player_height)
	{
		$this->set_property(self::PLAYER_HEIGHT, $player_height);
	}

	public function get_broadcast_logo()
	{
		return new Url($this->get_property(self::RADIO_IMG));
	}

	public function set_broadcast_logo($broadcast_logo)
	{
		$this->set_property(self::RADIO_IMG, $broadcast_logo);
	}

	public function get_display_type()
	{
		return $this->get_property(self::DISPLAY_TYPE);
	}

	public function set_display_type($display_type)
	{
		$this->set_property(self::DISPLAY_TYPE, $display_type);
	}

	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}

	public function set_authorizations(Array $authorizations)
	{
		$this->set_property(self::AUTHORIZATIONS, $authorizations);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return [
			self::RADIO_NAME 	 => 'RadioBoost',
			self::RADIO_IMG 	 => '/broadcast/templates/images/default_item.webp',
			self::PLAYER_TYPE 	 => BroadcastConfig::BROADCAST_URL,
			self::BROADCAST_URL 	 => LangLoader::get_message('broadcast.mp3', 'install', 'broadcast'),
			self::BROADCAST_WIDGET 	 => FormatingHelper::second_parse(LangLoader::get_message('broadcast.widget', 'install', 'broadcast')),
			self::PLAYER_WIDTH 	 => 300,
			self::PLAYER_HEIGHT 	 => 400,
			self::DISPLAY_TYPE 	 => self::ACCORDION_VIEW,
			self::AUTHORIZATIONS => ['r-1' => 1, 'r0' => 1, 'r1' => 17]
		];
	}

	/**
	 * Returns the configuration.
	 * @return BroadcastConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'broadcast', 'config');
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
