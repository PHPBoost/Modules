<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 5.1 - 2018 01 08
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
*/

class GoogleExternalAuthentication extends AbstractSocialNetworkExternalAuthentication
{
	public function get_authentication_id()
	{
		return GoogleSocialNetwork::SOCIAL_NETWORK_ID;
	}

	protected function get_social_network()
	{
		return new GoogleSocialNetwork();
	}

	public function get_authentication()
	{
		return new GoogleAuthenticationMethod();
	}
}
?>
