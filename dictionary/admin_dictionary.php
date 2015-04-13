<?php
/*##################################################
 *                            admin_dictionary.php
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
require_once('../dictionary/dictionary_begin.php');
require_once('../admin/admin_header.php');


if (retrieve(POST,'valid',false))
{
	$Cache->load('dictionary');
	$config_dictionary = array();
	//Génération du tableau des droits.
	$config_dictionary['dictionary_forbidden_tags'] = isset($_POST['dictionary_forbidden_tags']) ? $_POST['dictionary_forbidden_tags'] : array();
	$config_dictionary['pagination'] = retrieve(POST, 'pagination_nb', 15, TINTEGER);
	$config_dictionary['auth'] = Authorizations::build_auth_array_from_form(DICTIONARY_CREATE_ACCESS, DICTIONARY_UPDATE_ACCESS, DICTIONARY_DELETE_ACCESS, DICTIONARY_LIST_ACCESS, DICTIONARY_CONTRIB_ACCESS);
	
	$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($config_dictionary)) . "' WHERE name = 'dictionary'", __LINE__, __FILE__);

	$Cache->generate_module_file('dictionary'); // regeneration du cache
	
	AppContext::get_response()->redirect(HOST . SCRIPT);
}
elseif (retrieve(POST,'reset',false))
{	
	$config_dictionary = array();
	$config_dictionary['dictionary_forbidden_tags']   = array('swf','movie','sound');
	$config_dictionary['pagination'] = 15;
	$config_dictionary['auth'] = array('r24' => 24, 'r1' => 31);
	
	$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($config_dictionary)) . "' WHERE name = 'dictionary'", __LINE__, __FILE__);
			
	$Cache->generate_module_file('dictionary'); // regeneration du cache
	AppContext::get_response()->redirect(HOST . SCRIPT);
}
else
{
	$Template = new FileTemplate('dictionary/admin_dictionary.tpl');

	
	$Cache->load('dictionary');
	$Cache->generate_module_file('dictionary');
	$array_auth_all = !empty($CONFIG_DICTIONARY['auth']) ? $CONFIG_DICTIONARY['auth'] : array();

	//Balises interdites
	$i = 0;
	$tags = '';
	$CONFIG_DICTIONARY['dictionary_forbidden_tags'] = isset($CONFIG_DICTIONARY['dictionary_forbidden_tags']) ? $CONFIG_DICTIONARY['dictionary_forbidden_tags'] : $array_tags;
	$CONFIG_DICTIONARY['pagination'] = isset($CONFIG_DICTIONARY['pagination']) ? $CONFIG_DICTIONARY['pagination'] : 15;

	foreach (AppContext::get_content_formatting_service()->get_available_tags() as $value => $name)
	{
		if (in_array($value, $CONFIG_DICTIONARY['dictionary_forbidden_tags']))
		{		
			$Template->assign_block_vars('tag', array(
				'I'   => $i++,
				'NAME' => $name,
				'VALUE' => $value,
				'SELECTED' => 'selected="selected"',
				));
		}
		else
		{
			$Template->assign_block_vars('tag', array(
				'I'   => $i++,
				'NAME' => $name,
				'VALUE' => $value,
				'SELECTED' => "",
				));
		}
	}
	$Template->assign_vars(array(
		'TITLE' => $LANG['dictionary'],
		'L_DICTIONARY_ADD' => $LANG['create_dictionary'],
		'L_DICTIONARY_CONFIG' => $LANG['dictionary_config'],
		'L_SUBMIT' => $LANG['submit'],
		'L_RESET' => $LANG['reset'],
		'L_CONFIGURATION' => $LANG['configuration'],
		'L_AUTH' => $LANG['auth'],
		'L_PAGINATION_NB' => $LANG['pagination_nb'],
		'L_DICTIONARY_CATS' => $LANG['dictionary_cats'],
		'L_DICTIONARY_CATS_ADD' => $LANG['dictionary_cats_add'],
		'L_LIST_DEF' => $LANG['list_def'],
		'L_FORBIDDEN_TAGS' => $LANG['dictionary_forbidden_tags'],
		'L_EXPLAIN_SELECT_MULTIPLE' => $LANG['explain_select_multiple'],
		'L_SELECT_ALL' => $LANG['select_all'],
		'L_SELECT_NONE' => $LANG['select_none'],
		'L_MAX_LINK' => $LANG['max_link'],
		'L_MAX_LINK_EXPLAIN' => $LANG['max_link_explain'],
		'L_VAL_INC' => $LANG['value_incorrect'],
		'PAGINATION_NB' => $CONFIG_DICTIONARY['pagination'],
		'NBR_TAGS' => $i,
		'MAX_LINK' => isset($CONFIG_DICTIONARY['dictionary_max_link']) ? $CONFIG_DICTIONARY['dictionary_max_link'] : '-1',
	));
	
	$auths = array(
		DICTIONARY_CREATE_ACCESS => 'create_dictionary',
		DICTIONARY_UPDATE_ACCESS => 'update_dictionary',
		DICTIONARY_DELETE_ACCESS => 'delete_dictionary',
		DICTIONARY_LIST_ACCESS   => 'list_dictionary',
		DICTIONARY_CONTRIB_ACCESS => 'contrib_dictionary'
		);
	foreach ($auths as $key => $value) {
		$Template->assign_block_vars('auth', array(
			'SELECT'   => Authorizations::generate_select($key, $array_auth_all),
			'L_SELECT' => $LANG[$value],
			));
	}
	$Template->display();
}

require_once('../admin/admin_footer.php');

?>