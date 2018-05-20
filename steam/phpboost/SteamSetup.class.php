<?php
/*##################################################
 *                          SteamSetup.class.php
 *                            -------------------
 *   begin                : April 22, 2018
 *   copyright            : (C) 2018 Julien BRISWALTER
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

class SteamSetup extends DefaultModuleSetup
{
	public function install()
	{
		if (ModulesManager::is_module_installed('SocialNetworks'))
		{
			$config = SocialNetworksConfig::load();
			$config->add_additional_social_networks('SteamSocialNetwork');
			SocialNetworksConfig::save();
		}
		else
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), LangLoader::get_message('module_social_networks_not_installed', 'common', 'steam'), UserErrorController::FATAL);
			DispatchManager::redirect($controller);
		}
	}
	
	public function uninstall()
	{
		if (ModulesManager::is_module_installed('SocialNetworks'))
		{
			$config = SocialNetworksConfig::load();
			$config->remove_additional_social_networks('SteamSocialNetwork');
			SocialNetworksConfig::save();
		}
	}
}
?>
