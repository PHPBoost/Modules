<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 2.0 - 2008 01 27
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
*/

define('PATH_TO_ROOT', '../..');

//------------------------------------------------------------------- Language
require_once(PATH_TO_ROOT . '/kernel/begin.php');
$lang = LangLoader::get_all_langs('search');

//------------------------------------------------------------- Authorizations
if (!SearchAuthorizationsService::check_authorizations()->read())
{
	$error_controller = PHPBoostErrors::user_not_authorized();
	DispatchManager::redirect($error_controller);
}

//------------------------------------------------------------------- Template
$view = new FileTemplate('search/search_forms.tpl');
$view->add_lang($lang);

//--------------------------------------------------------------------- Params
$request = AppContext::get_request();

$search           = $request->get_value('q', '');
$unsecure_search  = stripslashes($request->get_value('q', ''));
$search_in        = $request->get_postvalue('search_in', 'all');
$selected_modules = $request->get_postvalue('searched_modules', '');
$selected_modules = !empty($selected_modules) ? explode(',', $selected_modules) : [];
$query_mode       = (bool)$request->get_postvalue('query_mode', true);

if ($search_in !== 'all')
{
	$selected_modules = [$search_in];
}
else if (count($selected_modules) == 1)
{
	$module = $selected_modules['0'];
	$search_in = $module;
}

//--------------------------------------------------------------------- Header
define('TITLE', $lang['search.module.title']);

require_once(PATH_TO_ROOT . '/kernel/header.php');
$view->assign_vars([
	'C_SIMPLE_SEARCH' => $search_in == 'all',
	'MODULE_MODE'     => $search_in,
	'TEXT_SEARCHED'   => $unsecure_search,
	'U_FORM_VALID'    => url(TPL_PATH_TO_ROOT . '/search/search.php#results'),
]);

//------------------------------------------------------------- Other includes
require_once(ModulesManager::get_module_path('search') . '/search.inc.php');

//----------------------------------------------------------------------- Main
$config = SearchConfig::load();
$modules_args = [];
$used_modules = [];

// Génération des formulaires précomplétés et passage aux templates
$provider_service = AppContext::get_extension_provider_service();
$search_extensions_point = $provider_service->get_extension_point(SearchableExtensionPoint::EXTENSION_POINT);
$search_extensions_point_modules = array_keys($search_extensions_point);
$providers = $provider_service->get_providers(SearchableExtensionPoint::EXTENSION_POINT);

foreach (ModulesManager::get_installed_modules_map_sorted_by_localized_name() as $id => $module)
{
	if (isset($providers[$module->get_id()]) && $providers[$module->get_id()]->search() !== false && in_array($module->get_id(), $search_extensions_point_modules))
	{
		$module_configuration = $module->get_configuration();
		if (!in_array($module->get_id(), $config->get_all_unauthorized_providers()))
		{
			// Ajout du paramètre search à tous les modules
			$modules_args[$module->get_id()]['search'] = $search;
			if ($search_extensions_point[$module->get_id()] && $search_extensions_point[$module->get_id()]->has_search_options())
			{
				// Récupération de la liste des paramètres
				$form_module_args = $search_extensions_point[$module->get_id()]->get_search_args();
				// Ajout des paramètres optionnels sans les sécuriser.
				// Ils sont sécurisés à l'intérieur de chaque module.
				if ($search_in != 'all')
				{
					foreach ($form_module_args as $arg)
					{
						if ($arg == 'search')
						{   // 'search' non sécurisé
							$modules_args[$module->get_id()]['search'] = $search;
						}
						elseif ($request->has_postparameter($arg))
						{   // Argument non sécurisé (sécurisé par le module en question)
							$modules_args[$module->get_id()][$arg] = $request->get_postvalue($arg);
						}
					}
				}

				$view->assign_block_vars('forms', [
					'C_SEARCH_FORM' => true,
					'C_SELECTED'    => count($selected_modules) == 1 ? in_array($module->get_id(), $selected_modules) : false,
					'MODULE_NAME'   => $module->get_id(),
					'SEARCH_FORM'   => $search_extensions_point[$module->get_id()]->get_search_form($modules_args[$module->get_id()]),
					'L_MODULE_NAME' => TextHelper::ucfirst($module_configuration->get_name()),
				]);
			}
			else
			{
				$view->assign_block_vars('forms', [
					'C_SEARCH_FORM' => false,
					'C_SELECTED'    => count($selected_modules) == 1 ? in_array($module->get_id(), $selected_modules) : false,
					'MODULE_NAME'   => $module->get_id(),
					'SEARCH_FORM'   => $lang['search.no.options'],
					'L_MODULE_NAME' => TextHelper::ucfirst($module_configuration->get_name()),
				]);
			}

			// Récupération de la liste des modules à traiter
			if ( ($selected_modules === []) || ($search_in === $module->get_id()) ||
				(($search_in === 'all') && (in_array($module->get_id(), $selected_modules))) )
			{
				$selected = ' selected="selected"';
				$used_modules[$module->get_id()] = $search_extensions_point[$module->get_id()]; // Ajout du module à traiter
			}
			else
			{
				$selected = '';
			}

			$view->assign_block_vars('searched_modules', [
				'MODULE'        => $module->get_id(),
				'SELECTED'      => $selected,
				'L_MODULE_NAME' => TextHelper::ucfirst($module_configuration->get_name()),
			]);
		}
	}
}

$view->display();

if (!empty($search))
{
	$view = new FileTemplate('search/search_results.tpl');
	$view->add_lang($lang);

	$results = [];
	$idsSearch = [];

	if ( $search_in != 'all' ) // If we are searching in only one module
	{
		if (isset($used_modules[$search_in]) && isset($modules_args[$search_in]))
		{
			$used_modules = [$search_in => $used_modules[$search_in]];
			$modules_args = [$search_in => $modules_args[$search_in]];
		}
		else
		{
			$used_modules = [];
			$modules_args = [];
		}
	}
	else
	{   // We remove modules that we're not searching in
		foreach ($modules_args as $module_id => $module_args)
		{
			if (!$query_mode && (!in_array($module_id, $selected_modules) || !isset($modules_args[$module_id])))
			{
				unset($modules_args[$module_id]);
				unset($used_modules[$module_id]);
			}
		}
	}

	// Génération des résultats et passage aux templates
	$nbResults = get_search_results($search, $used_modules, $modules_args, $results, $idsSearch);

	foreach ($used_modules as $module_id => $extension_point)
	{
		$view->assign_block_vars('results', [
			'MODULE_NAME' => $module_id,
			'ID_SEARCH' => $idsSearch[$module_id],
			'L_MODULE_NAME' => TextHelper::ucfirst(ModulesManager::get_module($module_id)->get_configuration()->get_name()),
		]);
	}

	$all_html_result = '';
	if ( $nbResults > 0 )
		get_html_results($results, $all_html_result, $search_in);

	$view->assign_vars(Array(
		'C_SIMPLE_SEARCH' => ($search_in == 'all'),
		'C_HAS_RESULTS' => $nbResults > 0,
		'C_SEVERAL_RESULTS' => $nbResults > 1,

		'ALL_RESULTS' => $all_html_result,
		'SEARCH_IN' => $search_in,
		'RESULTS_PER_PAGE' => RESULTS_PER_PAGE,
		'RESULTS_NUMBER' => $nbResults,
	));

	$view->display();
}

//--------------------------------------------------------------------- Footer
require_once(PATH_TO_ROOT . '/kernel/footer.php');

?>
