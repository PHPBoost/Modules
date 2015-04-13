<?php
/**
 * admin_smallads.php
 *
 * @author     alain91
 * @copyright  (C) 2009 Alain Gandon
 * @email      alain091@gmail.com
 * @license    GPLv2
 */

defined('PATH_TO_ROOT') or define('PATH_TO_ROOT','..');

require_once(PATH_TO_ROOT.'/admin/admin_begin.php');
require_once(PATH_TO_ROOT.'/smallads/smallads_begin.php');
require_once(PATH_TO_ROOT.'/smallads/smallads.class.php');
require_once(PATH_TO_ROOT.'/admin/admin_header.php');

$fields = array(
	'items_per_page' 	=> array('value' => null, 'default' => SMALLADS_ITEMS_PER_PAGE, 'type' => TINTEGER, 'min' => 1, 'max' => 50),
	'max_links'			=> array('value' => null, 'default' => SMALLADS_MAX_LINKS, 'type' => TINTEGER, 'min' => 1, 'max' => 10),
	'list_size'			=> array('value' => null, 'default' => MAX_MINIMENU, 'type' => TINTEGER, 'min' => 1, 'max' => 10),
	'maxlen_contents'	=> array('value' => null, 'default' => MAXLEN_CONTENTS, 'type' => TINTEGER, 'min' => 255, 'max' => 65535),
	'max_weeks'			=> array('value' => null, 'default' => MAX_WEEKS, 'type' => TINTEGER, 'min' => 0, 'max' => 520),
	);
	
$checkboxes = array(
	'view_mail'		 	=> array('value' => null, 'default' => 0, 'type' => TINTEGER),
	'view_pm'			=> array('value' => null, 'default' => 0, 'type' => TINTEGER),
	'return_to_list'	=> array('value' => null, 'default' => 0, 'type' => TINTEGER),
	'usage_terms'		=> array('value' => null, 'default' => 0, 'type' => TINTEGER),
	);

$smallads = new Smallads();

if( !empty($_POST['valid'])  )
{
	$config_smallads = array();
	foreach($fields as $k => $v)
	{
		$config_smallads[$k]  = retrieve(POST, $k, $v['default'], $v['type']);
		if ($config_smallads[$k] < $v['min'])
			$config_smallads[$k] = $v['min'];
		elseif ($config_smallads[$k] > $v['max'])
			$config_smallads[$k] = $v['max'];
	}
	foreach($checkboxes as $k => $v)
	{
		$config_smallads[$k]  = retrieve(POST, $k, $v['default'], $v['type']);
	}
	
	$cgu_contents 	= retrieve(POST, 'cgu_contents', '', TSTRING_UNCHANGE);
	$cgu_contents	= FormatingHelper::strparse($cgu_contents, $forbidden_tags, FALSE); // TSTRING_PARSE ne permet pas les parametres

	if ( !empty($config_smallads['usage_terms']) )
	{
		if (empty($cgu_contents))
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), $LANG['sa_e_cgu_invalid']);
			DispatchManager::redirect($controller);
		}
		else
			$smallads->save_cgu($cgu_contents);
	}
	
	//Génération du tableau des droits.
	$array_auth_all = Authorizations::build_auth_array_from_form(SMALLADS_OWN_CRUD_ACCESS, SMALLADS_UPDATE_ACCESS, SMALLADS_DELETE_ACCESS, SMALLADS_LIST_ACCESS, SMALLADS_CONTRIB_ACCESS);
	// On restreint les droits possibles des visiteurs
	$array_auth_all['r-1'] &= SMALLADS_LIST_ACCESS;
	
	$config_smallads['auth'] = $array_auth_all;
		
	$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . "
						SET value = '" . addslashes(serialize($config_smallads)) . "'
						WHERE name = 'smallads'",
						__LINE__, __FILE__);
	
	$Cache->generate_module_file('smallads'); // regeneration du cache
	
	AppContext::get_response()->redirect(HOST . SCRIPT);
}
//Sinon on rempli le formulaire
else	
{
	$tpl = new FileTemplate('smallads/admin_smallads_config.tpl');
	
	$Cache->load('smallads');

	foreach($fields as $k => $v)
	{
		$fields[$k]['value'] = $smallads->config_get($k, $v['default']); // foreach creates and works on a copy of $fields
	}
	foreach($checkboxes as $k => $v)
	{
		$checkboxes[$k]['value'] = $smallads->config_get($k, $v['default']); // foreach creates and works on a copy of $fields
	}
	
	$usage_terms = $smallads->config_get('usage_terms',0);
	$cgu_contents = $smallads->get_cgu();
	
	if ( !empty($usage_terms) && empty($cgu_contents) )
	{
		$Errorh->handler('sa_e_cgu_invalid', E_USER_REDIRECT);
		exit;
	}
	
	$editor = AppContext::get_content_formatting_service()->get_default_editor();
	$editor->set_identifier('cgu_contents');
	$editor->set_forbidden_tags($forbidden_tags);
	
	$tpl->put_all(array(
		'L_REQUIRE'       	=> $LANG['require'],
		'L_SMALLADS'        => $LANG['sa_title'],
		'L_SMALLADS_CONFIG' => $LANG['sa_config'],
		'L_AUTH_MESSAGE'	=> $LANG['sa_auth_message'],
		'L_CGU_CONTENTS'	=> $LANG['sa_cgu_contents'],

		'KERNEL_EDITOR'		=> $editor->display(),
		'CGU_CONTENTS'		=> FormatingHelper::unparse($cgu_contents),
		'L_CGU_INVALID'		=> $LANG['sa_e_cgu_invalid'],
		
		'L_UPDATE'        	=> $LANG['update'],
		'L_RESET'         	=> $LANG['reset'],
		'L_PREVIEW'			=> $LANG['preview']
	));
	
	foreach ($fields as $k => $v) {
		$tpl->assign_block_vars('config', array(
			'L_LABEL'	=> $LANG['sa_'.$k],
			'NAME'		=> $k,
			'VALUE'		=> $v['value'],
			'MIN'		=> $v['min'],
			'MAX'		=> $v['max']
		));
	}
	
	foreach ($checkboxes as $k => $v) {
		$tpl->assign_block_vars('config_checkbox', array(
			'L_LABEL' => $LANG['sa_'.$k],
			'NAME'    => $k,
			'CHECKED' => (!empty($v['value'])) ? 'checked="checked"' : '',
			'VALUE'   => 1
		));
	}

	$currents = $smallads->config_get('auth', array());
	$auths = array(
		SMALLADS_OWN_CRUD_ACCESS => 'sa_own_crud',
		SMALLADS_UPDATE_ACCESS => 'sa_update',
		SMALLADS_DELETE_ACCESS => 'sa_delete',
		SMALLADS_LIST_ACCESS   => 'sa_list',
		SMALLADS_CONTRIB_ACCESS => 'sa_contrib',
		);
	foreach ($auths as $key => $value) {
		$tpl->assign_block_vars('auth', array(
			'SELECT'   => Authorizations::generate_select($key, $currents),
			'L_SELECT' => $LANG[$value]
			));
	}
	
	$tpl->display();
}

require_once(PATH_TO_ROOT.'/admin/admin_footer.php');

?>