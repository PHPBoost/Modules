<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 23
 * @since       PHPBoost 5.1 - 2018 04 22
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SteamExternalAuthentication extends AbstractSocialNetworkExternalAuthentication
{
	public function get_authentication_id(): string
	{
		return SteamSocialNetwork::SOCIAL_NETWORK_ID;
	}

	protected function get_social_network(): SocialNetwork
	{
		return new SteamSocialNetwork();
	}

	public function get_authentication(): AuthenticationMethod
	{
		return new SteamAuthenticationMethod();
	}
}
?>
