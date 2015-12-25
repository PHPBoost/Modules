<?php
/*##################################################
 *                         GoogleAnalyticsSetup.class.php
 *                            -------------------
 *   begin                : September 24, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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

class GoogleAnalyticsSetup extends DefaultModuleSetup
{
	public function upgrade($installed_version)
	{
		return '5.0.0';
	}
	
	public function uninstall()
	{
		$this->delete_configuration();
	}
	
	private function delete_configuration()
	{
		ConfigManager::delete('GoogleAnalytics', 'config');
	}
}
?>
