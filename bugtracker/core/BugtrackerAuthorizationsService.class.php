<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 24
 * @since       PHPBoost 4.0 - 2013 04 29
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class BugtrackerAuthorizationsService
{
	const READ_AUTHORIZATIONS           = 1;
	const WRITE_AUTHORIZATIONS          = 2;
	const ADVANCED_WRITE_AUTHORIZATIONS = 4;
	const MODERATION_AUTHORIZATIONS     = 8;

	public static function check_authorizations(): mixed
	{
		$instance = new self();
		return $instance;
	}

	public function read(): int
	{
		return $this->get_authorizations(self::READ_AUTHORIZATIONS);
	}

	public function write(): int
	{
		return $this->get_authorizations(self::WRITE_AUTHORIZATIONS);
	}

	public function advanced_write(): int
	{
		return $this->get_authorizations(self::ADVANCED_WRITE_AUTHORIZATIONS);
	}

	public function moderation(): int
	{
		return $this->get_authorizations(self::MODERATION_AUTHORIZATIONS);
	}

	private function get_authorizations(int $bit): bool
	{
		return AppContext::get_current_user()->check_auth(BugtrackerConfig::load()->get_authorizations(), $bit);
	}
}
?>
