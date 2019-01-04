<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Geoffrey ROGUELON <liaght@gmail.com>
 * @version   	PHPBoost 5.2 - last update: 2017 06 15
 * @since   	PHPBoost 3.0 - 2009 07 26
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class LastcomsAuthorizationsService
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
		return AppContext::get_current_user()->check_auth(LastcomsConfig::load()->get_authorizations(), $bit);
	}
}
?>
