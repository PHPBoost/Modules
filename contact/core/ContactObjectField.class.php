<?php
/**
 * This class represents the contact object field
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 25
 * @since       PHPBoost 4.0 - 2013 09 24
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ContactObjectField extends ContactField
{
	private array $recipients;

	public function set_recipients(array $recipients): void
	{
		$this->recipients = $recipients;
	}

	public function get_recipients(): array
	{
		return $this->recipients;
	}
}
?>
