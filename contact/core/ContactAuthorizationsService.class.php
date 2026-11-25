<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 25
 * @since       PHPBoost 4.0 - 2013 08 06
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ContactAuthorizationsService
{
	const READ_AUTHORIZATIONS = 1;

	public static function check_authorizations(): mixed
	{
		$instance = new self();
		return $instance;
	}

	public function read(): int
	{
		return $this->get_authorizations(self::READ_AUTHORIZATIONS);
	}

	private function get_authorizations(int $bit): int
	{
		return AppContext::get_current_user()->check_auth(ContactConfig::load()->get_authorizations(), $bit);
	}
}
?>
