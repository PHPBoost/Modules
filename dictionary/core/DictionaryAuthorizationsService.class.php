<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 27
 * @since       PHPBoost 4.1 - 2016 02 15
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DictionaryAuthorizationsService
{
	const READ_AUTHORIZATIONS = 1;
	const WRITE_AUTHORIZATIONS = 2;
	const CONTRIBUTION_AUTHORIZATIONS = 4;
	const MODERATION_AUTHORIZATIONS = 8;

	public static function check_authorizations(): mixed
	{
		$instance = new self();
		return $instance;
	}

	public function read(): int
	{
		return $this->is_authorized(self::READ_AUTHORIZATIONS);
	}

	public function write(): int
	{
		return $this->is_authorized(self::WRITE_AUTHORIZATIONS);
	}

	public function contribution(): int
	{
		return $this->is_authorized(self::CONTRIBUTION_AUTHORIZATIONS);
	}

	public function moderation(): int
	{
		return $this->is_authorized(self::MODERATION_AUTHORIZATIONS);
	}

	private function is_authorized(int $bit): int
	{
		$auth = DictionaryConfig::load()->get_authorizations();
		return AppContext::get_current_user()->check_auth($auth, $bit);
	}
}
?>
