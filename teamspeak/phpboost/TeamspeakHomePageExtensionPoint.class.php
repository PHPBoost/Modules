<?php

class WebHomePageExtensionPoint implements HomePageExtensionPoint
{
	public function get_home_page()
	{
		return new DefaultHomePage($this->get_title(), TeamspeakHomeController::get_view());
	}
	
	private function get_title()
	{
		return LangLoader::get_message('ts_title', 'common', 'teamspeak');
	}
}
?>
