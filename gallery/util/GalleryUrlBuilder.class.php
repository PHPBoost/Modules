<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Alain091 <alain091@gmail.com>
 * @version     PHPBoost 6.1 - last update: 2026 07 27
 * @since       PHPBoost 3.0 - 2011 08 13
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      xela <xela@phpboost.com>
*/

class GalleryUrlBuilder
{
	private static $dispatcher = '/gallery';

	public static function configuration(): Url
	{
		return new Url('/gallery/admin_gallery_config.php');
	}

	public static function manage(): Url
	{
		return new Url('/gallery/admin_gallery.php');
	}

	public static function display_category($id, $rewrited_name, $page = 1): Url
	{
		return new Url('/gallery/gallery' . url('.php?cat=' . $id . ($page !== 1 ? '&p=' . $page : ''), '-' . $id . '-' . $rewrited_name . ($page !== 1 ? '-' . $page : '') . '.php'));
	}

	public static function admin_add($id_category = null): Url
	{
		return new Url('/gallery/admin_gallery_add.php' . (!empty($id_category) ? '?cat=' . $id_category : ''));
	}

	public static function add($id_category = null): Url
	{
		return new Url('/gallery/gallery.php?add=1' . (!empty($id_category) ? '&cat=' . $id_category : ''));
	}

	public static function home(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/');
	}


	// TODO : supprimer ce qui est en dessous si possible
	public static function get_link_item($id_category, $id, $com = null, $sort = null): string
	{
		return Url::to_rel('/gallery/gallery' . url('.php?cat=' . $id_category . '&id=' . $id . (!empty($com) ? '&com=' . $com : '') . (!empty($sort) ? '&sort=' . $sort : ''),
			'-' . $id_category . '-' . $id . '.php' . (!empty($com) ? '?com=' . $com : '') . (!empty($sort) ? '&sort=' . $sort : '')));
	}

	public static function get_link_cat($id, $name = null): string
	{
		if (!empty($name))
			$name = '-' . Url::encode_rewrite($name);

		return Url::to_rel('/gallery/gallery'.url('.php?cat='.$id, '-'.$id.$name.'.php'));
	}

	public static function get_link_cat_add($id, $error = null, $token = null): string
	{
		if (!empty($error))
			$error = '&error='. $error;
		if (!empty($token))
			$token = '&token='. $token;
		return Url::to_rel('/gallery/gallery'.url(
			'.php?add=1&cat='. $id . $error . $token,
			'-'. $id .'.php?add=1'. $error . $token,
			'&'));
	}

	public static function documentation(): Url
	{
		return new Url (ModulesManager::get_module('gallery')->get_configuration()->get_documentation());
	}
}
?>
