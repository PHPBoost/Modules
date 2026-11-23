<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 23
 * @since       PHPBoost 5.1 - 2018 01 08
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FacebookExternalAuthentication extends AbstractSocialNetworkExternalAuthentication
{
	public function get_authentication_id(): string
	{
		return FacebookSocialNetwork::SOCIAL_NETWORK_ID;
	}

	protected function get_social_network(): SocialNetwork
	{
		return new FacebookSocialNetwork();
	}

	public function get_authentication(): AuthenticationMethod
	{
		return new FacebookAuthenticationMethod();
	}
}
?>
