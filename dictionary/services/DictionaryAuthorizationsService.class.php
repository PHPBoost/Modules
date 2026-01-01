<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 02 17
 * @since       PHPBoost 4.1 - 2016 02 15
*/

class DictionaryAuthorizationsService
{
	const READ_AUTHORIZATIONS = 1;
	const WRITE_AUTHORIZATIONS = 2;
	const CONTRIBUTION_AUTHORIZATIONS = 4;
	const MODERATION_AUTHORIZATIONS = 8;

	public static function check_authorizations()
	{
		$instance = new self();
		return $instance;
	}

	public function read()
	{
		return $this->is_authorized(self::READ_AUTHORIZATIONS);
	}

	public function write()
	{
		return $this->is_authorized(self::WRITE_AUTHORIZATIONS);
	}

	public function contribution()
	{
		return $this->is_authorized(self::CONTRIBUTION_AUTHORIZATIONS);
	}

	public function moderation()
	{
		return $this->is_authorized(self::MODERATION_AUTHORIZATIONS);
	}

	private function is_authorized($bit)
	{
		$auth = DictionaryConfig::load()->get_authorizations();
		return AppContext::get_current_user()->check_auth($auth, $bit);
	}
}
?>
