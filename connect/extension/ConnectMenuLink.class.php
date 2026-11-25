<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 24
 * @since       PHPBoost 6.0 - 2022 02 09
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

interface ConnectMenuLink extends ExtensionPoint
{
	const EXTENSION_POINT = 'connect_menu_link';

	/**
	 * @return string Menu name, will create a css class connect-"name"
	 */
	function get_menu_name(): string;

	/**
	 * @return string Menu fa icon, return empty string to disable icon display
	 */
	function get_icon(): string;

	/**
	 * @return string Menu label
	 */
	function get_label(): string;

	/**
	 * @return int Level from which display the menu (User::MEMBER_LEVEL, User::MODERATOR_LEVEL or User::ADMINISTRATOR_LEVEL)
	 */
	function get_display_level(): int;

	/**
	 * @return int Number of unread elements to display. Return 0 to disable it.
	 */
	function get_unread_elements_number(): int;

	/**
	 * @return Url Menu link
	 */
	function get_url(): Url;
}
?>
