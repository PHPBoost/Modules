<?php
/**
 * The AuthenticationMethod interface could be implemented in different ways to enable specifics
 * authentication mecanisms.
 * PHPBoost comes with a PHPBoostAuthenticationMethod which will be performed on the internal member
 * list. But it is possible to implement external authentication mecanism by providing others
 * implementations of this class to support LDAP authentication, OpenID, Facebook connect and more...
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 05 20
 * @since       PHPBoost 5.1 - 2018 04 22
*/

session_start();

require_once PATH_TO_ROOT . '/steam/lib/steam/openid.php';

class SteamAuthenticationMethod extends AbstractSocialNetworkAuthenticationMethod
{
	protected function get_external_authentication()
	{
		return new SteamExternalAuthentication();
	}

	public function authenticate()
	{
		$querier = PersistenceContext::get_querier();
		$user_id = 0;
		$data = $this->get_user_data();

		if ($data)
		{
			try {
				$user_id = $querier->get_column_value(DB_TABLE_AUTHENTICATION_METHOD, 'user_id', 'WHERE method=:method AND identifier=:identifier',  array('method' => $this->get_external_authentication()->get_authentication_id(), 'identifier' => $data['id']));
			} catch (RowNotFoundException $e) {

				if (!empty($data['id']) && !empty($data['name']))
				{
					$user = new User();

					$user->set_display_name($data['name']);
					$user->set_level(User::MEMBER_LEVEL);
					$user->set_email($data['email']);

					$auth_method = new static();
					$fields_data = !empty($data['picture_url']) ? array('user_avatar' => $data['picture_url']) : array();

					return UserService::create($user, $auth_method, $fields_data, $data);
				}
				else
					$this->error_msg = LangLoader::get_message('external-auth.user-data-not-found', 'user-common');
			}
		}
		else
			$this->error_msg = LangLoader::get_message('external-auth.user-data-not-found', 'user-common');

		$this->check_user_bannishment($user_id);

		if (!$this->error_msg)
		{
			$this->update_user_last_connection_date($user_id);
			return $user_id;
		}
	}

	protected function get_user_data()
	{
		if (!isset($_SESSION['steam_token']) || empty($_SESSION['steam_token']))
		{
			try {
				$openid = new LightOpenID($_SERVER['SERVER_NAME']);

				if (!$openid->mode)
				{
					$openid->identity = 'https://steamcommunity.com/openid';
					AppContext::get_response()->redirect($openid->authUrl());
				}
				else if ($openid->validate())
				{
					$id = $openid->identity;
					$ptn = "/^https?:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/";
					preg_match($ptn, $id, $matches);

					$_SESSION['steam_token'] = $matches[1];
				}
				else
				{
					if ($authenticate_type && $authenticate_type != PHPBoostAuthenticationMethod::AUTHENTICATION_METHOD && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL))
						AppContext::get_response()->redirect(UserUrlBuilder::edit_profile(AppContext::get_current_user()->get_id())->rel());
					else
						AppContext::get_response()->redirect(Environment::get_home_page());
				}
			} catch(ErrorException $e) {
				AppContext::get_response()->redirect(Environment::get_home_page());
			}
		}

		$url = file_get_contents("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=" . SocialNetworksConfig::load()->get_client_id(SteamSocialNetwork::SOCIAL_NETWORK_ID) . "&steamids=" . $_SESSION['steam_token']);
		$content = json_decode($url, true);

		return array(
			'id' => $content['response']['players'][0]['steamid'],
			'email' => '',
			'name' => $content['response']['players'][0]['personaname'],
			'picture_url' => $content['response']['players'][0]['avatarfull']
		);
	}
}
?>
