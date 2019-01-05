<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 05 20
 * @since   	PHPBoost 5.1 - 2018 04 22
*/

class SteamSocialNetwork extends AbstractSocialNetwork
{
	const SOCIAL_NETWORK_ID = 'steam';

	public function get_name()
	{
		return 'Steam';
	}

	public function get_icon_name()
	{
		return self::SOCIAL_NETWORK_ID . '-symbol';
	}

	public function get_external_authentication()
	{
		return new SteamExternalAuthentication();
	}

	public function authentication_client_secret_needed()
	{
		return false;
	}

	public function get_identifiers_creation_url()
	{
		return 'http://steamcommunity.com/dev/apikey';
	}

	public function callback_url_needed()
	{
		return false;
	}
}
?>
