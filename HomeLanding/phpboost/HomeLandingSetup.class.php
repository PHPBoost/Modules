<?php
/*##################################################
 *		             HomeLandingSetup.class.php
 *                            -------------------
 *   begin                : January 2, 2016
 *   copyright            : (C) 2016 Sebastien Lartigue
 *   email                : babso@web33.fr
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Comments Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Comments Public License for more details.
 *
 * You should have received a copy of the GNU Comments Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

class HomeLandingSetup extends DefaultModuleSetup
{
	public function uninstall()
	{
		$this->delete_configuration();
	}
	
	public function upgrade($installed_version)
	{
		$file = new File(Url::to_rel('/HomeLanding/templates/pagecontent/HomeLandingFormFieldSelectCarousel.tpl'));
		$file->delete();
		
		return '5.0.1';
	}
	
	private function delete_configuration()
	{
		ConfigManager::delete('homelanding', 'config');
	}
}
?>
