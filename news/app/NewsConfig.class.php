<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 4.0 - 2013 02 13
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Mipel <mipel@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class NewsConfig extends DefaultRichModuleConfig
{
	const ITEMS_SUGGESTIONS_ENABLED = 'items_suggestions_enabled';
	const ITEMS_NAVIGATION_ENABLED = 'items_navigation_enabled';

	/**
	 * {@inheritdoc}
	 */
	public function get_additional_default_values()
	{
		return [
			self::ITEMS_PER_PAGE            => 10,
			self::ITEMS_PER_ROW             => 1,
			self::FULL_ITEM_DISPLAY         => true,
			self::VIEWS_NUMBER_ENABLED      => true,
			self::DISPLAY_TYPE              => self::LIST_VIEW,
			self::ROOT_CATEGORY_DESCRIPTION => '',
			self::ITEMS_SUGGESTIONS_ENABLED => true,
			self::ITEMS_NAVIGATION_ENABLED  => true
		];
	}
}
?>
