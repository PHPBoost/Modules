<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 07 04
 * @since       PHPBoost 2.0 - 2012 11 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../admin/admin_begin.php');

$lang = LangLoader::get('common', 'dictionary');
$common_lang = LangLoader::get('common-lang');
define('TITLE', $lang['dictionary.module.title']);
require_once('../admin/admin_header.php');

$config = DictionaryConfig::load();

$view = new FileTemplate('dictionary/admin_dictionary_list.tpl');
$view->add_lang(array_merge(
	$lang,
	$common_lang,
	LangLoader::get('category-lang'),
	LangLoader::get('form-lang')
));
$words_number = PersistenceContext::get_querier()->count(DictionarySetup::$dictionary_table);

//On crée une pagination si le nombre de web est trop important.
$page = AppContext::get_request()->get_getint('p', 1);
$pagination = new ModulePagination($page, $words_number, $config->get_items_per_page());
$pagination->set_url(new Url('/dictionary/admin_dictionary_list.php?p=%d'));

if ($pagination->current_page_is_empty() && $page > 1)
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

$view->put_all(array(
	'C_PAGINATION' => $pagination->has_several_pages(),

	'PAGINATION' => $pagination->display(),
));

$result = PersistenceContext::get_querier()->select("SELECT l.word ,l.id AS dictionary_id,l.cat,l.approved AS dictionary_approved,l.timestamp,cat.id,cat.name,cat.images
FROM " . PREFIX . "dictionary AS l
LEFT JOIN " . PREFIX . "dictionary_cat cat ON cat.id = l.cat
ORDER BY timestamp DESC
LIMIT :number_items_per_page OFFSET :display_from", array(
	'number_items_per_page' => $pagination->get_number_items_per_page(),
	'display_from' => $pagination->get_display_from()
));
while ($row = $result->fetch())
{
	$aprob = ($row['dictionary_approved'] == 1) ? $common_lang['common.yes'] : $common_lang['common.no'];
	//On reccourci le lien si il est trop long pour éviter de déformer l'administration.s
	$title = $row['word'];
	$title = TextHelper::strlen($title) > 45 ? TextHelper::substr($title, 0, 45) . '...' : $title;
	$img = empty($row['images']) ? '<i class="fa fa-folder"></i>' : '<img src="' . $row['images'] . '" alt="' . $row['images'] . '" />';
	$date_created = !empty($row['timestamp']) ? new Date($row['timestamp'], Timezone::SERVER_TIMEZONE) : null;

	$view->assign_block_vars('dictionary_list', array(
		'ITEM_ID'        => $row['dictionary_id'],
		'NAME'           => Texthelper::ucfirst(TextHelper::strtolower(stripslashes($title))),
		'CATEGORY_ID'    => $row['cat'],
		'CATEGORY_NAME'  => TextHelper::strtoupper($row['name']),
		'DATE'           => (!empty($date_created)) ? $date_created->format(Date::FORMAT_DAY_MONTH_YEAR) : '',
		'APPROBATION'    => $aprob,
		'CATEGORY_IMAGE' => $img,
	));
}
$result->dispose();

$view->display();


require_once('../admin/admin_footer.php');

?>
