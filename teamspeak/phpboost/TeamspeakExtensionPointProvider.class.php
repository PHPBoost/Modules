<?php

class TeamspeakExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('teamspeak');
	}
	
	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_always_displayed_file('teamspeak.css');
		return $module_css_files;
	}
	
	public function home_page()
	{
		return new TeamspeakHomePageExtensionPoint();
	}
	
	public function menus()
	{
		return new ModuleMenus(array(new TeamspeakModuleMiniMenu()));
	}
	
	public function tree_links()
	{
		return new TeamspeakTreeLinks();
	}
	
	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/teamspeak/index.php')));
	}
}
?>
