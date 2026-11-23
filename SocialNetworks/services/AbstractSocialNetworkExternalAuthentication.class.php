<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 23
 * @since       PHPBoost 5.1 - 2018 04 16
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class AbstractSocialNetworkExternalAuthentication implements ExternalAuthentication
{
	abstract public function get_authentication_id(): string;

	public function get_authentication_name(): string
	{
		return StringVars::replace_vars(LangLoader::get_message('sn.sign.in.label', 'common', 'SocialNetworks'), ['name' => $this->get_social_network()->get_name()]);
	}

	abstract protected function get_social_network(): SocialNetwork;

	public function authentication_actived(): bool
	{
		return SocialNetworksConfig::load()->is_authentication_available($this->get_authentication_id());
	}

	public function get_image_renderer_html(): string
	{
		$view = new FileTemplate('SocialNetworks/auth_image_render.tpl');
		$view->put_all([
			'C_DISPLAY_NAME' => strpos(REWRITED_SCRIPT, 'user') !== false && strpos(REWRITED_SCRIPT, 'login') !== false,
			'ICON_NAME'      => $this->get_social_network()->get_icon_name(),
			'NAME'           => $this->get_authentication_name()
		]);
		return $view->render();
	}

	public function get_css_class(): string
	{
		return 'social-connect ' . $this->get_social_network()->get_css_class();
	}

	abstract public function get_authentication(): AuthenticationMethod;

	/**
	 * Delete the Social Network session token
	 */
	public function delete_session_token(): void
	{
		if (isset($_SESSION[$this->get_authentication_id() . '_token']))
			unset($_SESSION[$this->get_authentication_id() . '_token']);
	}
}
?>
