<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 11
 * @since       PHPBoost 3.0 - 2010 10 16
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ForumUserExtensionPoint implements UserExtensionPoint
{
	public function get_publications_module_view(int $user_id): string
	{
		return Url::to_rel('/forum/membermsg.php?id=' . $user_id);
	}

	public function get_publications_module_name(): string
	{
		return LangLoader::get_message('forum.module.title', 'common', 'forum');
	}

	public function get_publications_module_id(): string
	{
		return 'forum';
	}

	public function get_publications_module_icon(): string
	{
		return 'fa fa-globe';
	}

	public function get_publications_number(int $user_id): int
	{
		return PersistenceContext::get_querier()->count(PREFIX . 'forum_msg', 'WHERE user_id = :user_id', ['user_id' => $user_id]);
	}
}
?>
