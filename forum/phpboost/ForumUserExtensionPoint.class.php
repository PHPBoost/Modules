<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2010 10 16
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ForumUserExtensionPoint implements UserExtensionPoint
{
	/**
	 * {@inheritDoc}
	 */
	public function get_publications_module_view($user_id)
	{
		return Url::to_rel('/forum/membermsg.php?id=' . $user_id);
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_publications_module_name()
	{
		return LangLoader::get_message('forum.module.title', 'common', 'forum');
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_publications_module_id()
	{
		return 'forum';
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_publications_module_icon()
	{
		return 'fa fa-globe';
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_publications_number($user_id)
	{
		return PersistenceContext::get_querier()->count(PREFIX . 'forum_msg', 'WHERE user_id = :user_id', ['user_id' => $user_id]);
	}
}
?>
