<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 12 19
 * @since   	PHPBoost 4.0 - 2013 08 09
*/

abstract class AbstractServerStatusServer
{
	const DISPLAY_SERVER_AUTHORIZATIONS = 1;

	const ONLINE = true;
	const OFFLINE = false;

	const DNS = 'dns';
	const IP = 'ip';

	const TCP = 'tcp';
	const UDP = 'udp';

	protected $name;
	protected $rewrited_name;
	protected $description;
	protected $address_type = self::IP;
	protected $address;
	protected $port;
	protected $status = self::OFFLINE;
	protected $displayed = true;
	protected $authorizations = array('r-1' => 1, 'r0' => 1, 'r1' => 1);
	protected $last_check_time = 0;

	protected $id;
	protected $protocol = self::TCP;
	protected $default_port = 0;

	protected $Socket;

	public function __construct($name, $default_port)
	{
		$this->set_name($name);
		$this->set_rewrited_name($name);
		$this->set_id($this->rewrited_name);
		$this->set_default_port($default_port);
	}

	public function set_name($name)
	{
		$this->name = $name;
	}

	public function get_name()
	{
		return $this->name;
	}

	public function set_rewrited_name($name)
	{
		$this->rewrited_name = Url::encode_rewrite($name);
	}

	public function get_rewrited_name()
	{
		return $this->rewrited_name;
	}

	public function set_description($description)
	{
		$this->description = $description;
	}

	public function get_description()
	{
		return $this->description;
	}

	public function set_address_type($value)
	{
		$this->address_type = $value;
	}

	public function get_address_type()
	{
		return $this->address_type;
	}

	public function address_type_is_dns()
	{
		return $this->address_type == self::DNS;
	}

	public function address_type_is_ip()
	{
		return $this->address_type == self::IP;
	}

	public function set_address($value)
	{
		$this->address = $value;
	}

	public function get_address()
	{
		return $this->address;
	}

	public function set_port($value)
	{
		$this->port = $value;
	}

	public function get_port()
	{
		return $this->port;
	}

	public function online()
	{
		$this->status = self::ONLINE;
	}

	public function offline()
	{
		$this->status = self::OFFLINE;
	}

	public function is_online()
	{
		return $this->status;
	}

	public function displayed()
	{
		$this->displayed = true;
	}

	public function not_displayed()
	{
		$this->displayed = false;
	}

	public function is_displayed()
	{
		return $this->displayed;
	}

	public function get_authorizations()
	{
		return $this->authorizations;
	}

	public function set_authorizations(Array $array)
	{
		$this->authorizations = $array;
	}

	public function is_authorized()
	{
		return AppContext::get_current_user()->check_auth($this->authorizations, self::DISPLAY_SERVER_AUTHORIZATIONS);
	}

	public function get_last_check_time()
	{
		return $this->last_check_time;
	}

	public function set_last_check_time($value)
	{
		$this->last_check_time = $value;
	}

	public function get_type()
	{
		return get_class($this);
	}

	public function get_id()
	{
		return $this->id;
	}

	public function set_id($value)
	{
		$this->id = $value;
	}

	public function get_protocol()
	{
		return $this->protocol == 'udp' ? 'udp' : 'tcp';
	}

	protected function protocol_tcp()
	{
		$this->protocol = self::TCP;
	}

	protected function protocol_udp()
	{
		$this->protocol = self::UDP;
	}

	public function get_default_port()
	{
		return $this->default_port;
	}

	protected function set_default_port($value)
	{
		$this->default_port = $value;
	}

	public function get_medium_icon()
	{
		return Url::to_rel('/ServerStatus/templates/images/icons/32/' . $this->id . '.png');
	}

	public function has_medium_icon()
	{
		$file = new File(PATH_TO_ROOT . '/ServerStatus/templates/images/icons/32/' . $this->id . '.png');
		return $file->exists();
	}

	public function get_large_icon()
	{
		return Url::to_rel('/ServerStatus/templates/images/icons/64/' . $this->id . '.png');
	}

	public function has_large_icon()
	{
		$file = new File(PATH_TO_ROOT . '/ServerStatus/templates/images/icons/64/' . $this->id . '.png');
		return $file->exists();
	}

	public function check_status($force_check = false)
	{
		$config = ServerStatusConfig::load();
		$timeout = $config->get_timeout() / 1000;

		if ((($this->get_last_check_time() + ($config->get_refresh_delay() * 60)) < time()) || $force_check)
		{
			$this->Socket = @fsockopen($this->get_protocol() . '://' . $this->get_address(), (int)$this->get_port(), $ErrNo, $ErrStr, $timeout);

			@stream_set_timeout($this->Socket, $timeout);

			if ($this->Socket)
				$this->online();
			else
				$this->offline();

			$this->check_parameters();

			@fclose($this->Socket);

			$this->set_last_check_time(time());
		}
	}

	public function check_parameters() {}

	public function get_view()
	{
		$lang = LangLoader::get('common', 'ServerStatus');
		$config = ServerStatusConfig::load();

		$tpl = new FileTemplate('ServerStatus/ServerStatusServer.tpl');
		$tpl->add_lang($lang);

		$tpl->put_all(array(
			'C_ICON' => $this->has_large_icon(),
			'C_ADDRESS_DISPLAYED' => $config->is_address_displayed(),
			'C_ONLINE' => $this->is_online(),
			'C_DESCRIPTION' => !empty($this->description),
			'ANCHOR' => $this->rewrited_name,
			'ICON' => $this->get_large_icon(),
			'NAME' => $this->name,
			'DESCRIPTION' => FormatingHelper::second_parse($this->description),
			'ADDRESS' => $this->address,
			'PORT' => $this->port
		));

		return $tpl->render();
	}
}
?>
