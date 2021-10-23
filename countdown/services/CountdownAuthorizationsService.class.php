<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 11 11
 * @since       PHPBoost 4.1 - 2014 12 12
*/

class CountdownAuthorizationsService
{
	const READ_AUTHORIZATIONS = 1;

	public static function check_authorizations()
	{
		$instance = new self();
		return $instance;
	}

	public function read()
	{
		return $this->get_authorizations(self::READ_AUTHORIZATIONS);
	}

	private function get_authorizations($bit)
	{
		return AppContext::get_current_user()->check_auth(CountdownConfig::load()->get_authorizations(), $bit);
	}
}
?>
