<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 31
 * @since       PHPBoost 5.0 - 2017 04 03
*/

class GoogleMapsMarker
{
	private string $name = '';
	private string $address = '';
	private string $latitude = '';
	private string $longitude = '';
	private int $zoom = 0;
	private bool $address_displayed_on_label = true;

	/**
	 * Constructs a GoogleMapsMarker.
	 * @param string $address Marker address
	 * @param string $latitude Marker latitude
	 * @param string $longitude Marker longitude
	 * @param string $name Marker name
	 * @param int $zoom Map zoom
	 */
	public function __construct(string $address = '', string $latitude = '', string $longitude = '', string $name = '', int $zoom = 0, bool $address_displayed_on_label = true)
	{
		$this->address = $address;
		$this->latitude = $latitude;
		$this->longitude = $longitude;
		$this->name  = $name;
		$this->zoom = $zoom;
		$this->address_displayed_on_label = $address_displayed_on_label;
	}

	public function set_name(string $name): void
	{
		$this->name = $name;
	}

	public function get_name(): string
	{
		return $this->name;
	}

	public function set_address(string $address)
	{
		$this->address = $address;
	}

	public function get_address(): string
	{
		return $this->address;
	}

	public function set_latitude(string $latitude)
	{
		$this->latitude = $latitude;
	}

	public function get_latitude(): string
	{
		return $this->latitude;
	}

	public function set_longitude(string $longitude)
	{
		$this->longitude = $longitude;
	}

	public function get_longitude(): string
	{
		return $this->longitude;
	}

	public function set_zoom(int $zoom)
	{
		$this->zoom = $zoom;
	}

	public function get_zoom(): int
	{
		return $this->zoom;
	}

	public function display_address_on_label()
	{
		$this->address_displayed_on_label = true;
	}

	public function hide_address_on_label()
	{
		$this->address_displayed_on_label = false;
	}

	public function is_address_displayed_on_label()
	{
		return $this->address_displayed_on_label;
	}

	public function get_properties()
	{
		return [
			'name' => $this->get_name(),
			'address' => $this->get_address(),
			'latitude' => $this->get_latitude(),
			'longitude' => $this->get_longitude(),
			'zoom' => $this->get_zoom(),
			'address_displayed_on_label' => (int)$this->is_address_displayed_on_label()
		];
	}

	public function set_properties(array $properties)
	{
		if (isset($properties['address']))
			$this->address = $properties['address'];

		if (isset($properties['latitude']))
			$this->latitude = $properties['latitude'];

		if (isset($properties['longitude']))
			$this->longitude = $properties['longitude'];

		if (isset($properties['name']))
			$this->name = $properties['name'];

		if (isset($properties['zoom']))
			$this->zoom = $properties['zoom'];

		if (isset($properties['address_displayed_on_label']))
			$this->address_displayed_on_label = (bool)$properties['address_displayed_on_label'];
	}

	public function get_array_tpl_vars()
	{
		return [
			'C_ADDRESS' => $this->get_address(),
			'C_COORDONATES' => $this->get_latitude() && $this->get_longitude(),
			'C_ZOOM' => $this->get_zoom() > 0,
			'MARKER_NAME' => $this->get_name(),
			'LABEL' => ($this->get_name() ? '<strong>' . $this->get_name() . '</strong><br />' : '') . ($this->is_address_displayed_on_label() ? $this->get_address() : ''),
			'ADDRESS' => $this->get_address(),
			'LATITUDE' => $this->get_latitude(),
			'LONGITUDE' => $this->get_longitude(),
			'ZOOM' => $this->get_zoom()
		];
	}
}
?>
