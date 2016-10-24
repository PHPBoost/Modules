<?php
/*##################################################
 *                              admin_dictionary_list.php
 *                            -------------------
 *   begin                : March  3, 2009 
 *   copyright            : (C) 2009 Nicolas Maurel
 *   email                :  crunchfamily@free.fr
 *
 *  
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../admin/admin_begin.php');
load_module_lang('dictionary'); //Chargement de la langue du module.
define('TITLE', $LANG['dictionary']);
require_once('../admin/admin_header.php');

$config = DictionaryConfig::load();

$Template = new FileTemplate('dictionary/admin_dictionary_list.tpl');
$nbr_l = PersistenceContext::get_querier()->count(DictionarySetup::$dictionary_table);

//On crée une pagination si le nombre de web est trop important.
$page = AppContext::get_request()->get_getint('p', 1);
$pagination = new ModulePagination($page, $nbr_l, $config->get_items_number_per_page());
$pagination->set_url(new Url('/dictionary/admin_dictionary_list.php?p=%d'));

if ($pagination->current_page_is_empty() && $page > 1)
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

$Template->put_all(array(
	'C_PAGINATION' => $pagination->has_several_pages(),
	'PAGINATION' => $pagination->display(),
	'L_DICTIONARY_ADD' => $LANG['create_dictionary'],
	'L_SUBMIT' => $LANG['submit'],
	'L_RESET' => $LANG['reset'],
	'L_DICTIONARY_MANAGEMENT' => $LANG['dictionary_management'],
	'L_DICTIONARY_CATS' => $LANG['dictionary_cats'],
	'L_DICTIONARY_CATS_ADD' => $LANG['dictionary_cats_add'],
	'L_LIST_DEF' =>$LANG['list_def'],
	'L_CATEGORY' => $LANG['category'],
	'L_DICTIONARY_WORD' => $LANG['dictionary_word'],
	'L_DELETE_DICTIONARY_CONF' => $LANG['delete_dictionary_conf'],
	'L_LIST' => $LANG['list'],
	'L_DATE' => $LANG['date'],
	'L_APPROBATION' => $LANG['approbation'],
	'TITLE' => $LANG['dictionary']
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
	$aprob = ($row['dictionary_approved'] == 1) ? $LANG['yes'] : $LANG['no'];
	//On reccourci le lien si il est trop long pour éviter de déformer l'administration.s
	$title = $row['word'];
	$title = mb_strlen($title) > 45 ? mb_substr($title, 0, 45) . '...' : $title;
	$img = empty($row['images']) ? '<i class="fa fa-folder"></i>' : '<img src="' . $row['images'] . '" alt="' . $row['images'] . '" />';
	$date_created = !empty($row['timestamp']) ? new Date($row['timestamp'], Timezone::SERVER_TIMEZONE) : null;
	
	$Template->assign_block_vars('dictionary_list', array(
		'ID' => $row['dictionary_id'],
		'NAME' => Texthelper::uppercase_first(mb_strtolower(stripslashes($title))),
		'IDCAT' => $row['cat'],
		'CAT' => mb_strtoupper($row['name']),
		'DATE' => (!empty($date_created)) ? $date_created->format(Date::FORMAT_DAY_MONTH_YEAR) : '',
		'APROBATION' => $aprob,
		'IMG' => $img,
	));
}
$result->dispose();

$Template->display(); 


require_once('../admin/admin_footer.php');

?>
