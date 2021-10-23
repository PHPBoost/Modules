<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 05 20
 * @since       PHPBoost 5.1 - 2018 04 22
*/

class SteamExternalAuthentication extends AbstractSocialNetworkExternalAuthentication
{
	public function get_authentication_id()
	{
		return SteamSocialNetwork::SOCIAL_NETWORK_ID;
	}

	protected function get_social_network()
	{
		return new SteamSocialNetwork();
	}

	public function get_authentication()
	{
		return new SteamAuthenticationMethod();
	}
}
?>
