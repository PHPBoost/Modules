<?php
/*##################################################
 *                           AdminServerStatusDisplayResponse.class.php
 *                            -------------------
 *   begin                : August 4, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

class AdminServerStatusDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);
		
		$lang = LangLoader::get('common', 'ServerStatus');
		$picture = '/ServerStatus/ServerStatus.png';
		$this->set_title($lang['module_title']);
		$this->add_link($lang['admin.config.servers.management'], ServerStatusUrlBuilder::servers_management(), $picture);
		$this->add_link($lang['admin.config.servers.action.add_server'], ServerStatusUrlBuilder::add_server(), $picture);
		$this->add_link(LangLoader::get_message('configuration', 'admin'), ServerStatusUrlBuilder::configuration(), $picture);
		
		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page);
	}
}
?>
