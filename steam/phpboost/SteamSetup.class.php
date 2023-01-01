<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 25
 * @since       PHPBoost 5.1 - 2018 04 22
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

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
			$controller = new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'), LangLoader::get_message('module_social_networks_not_installed', 'common', 'steam'), UserErrorController::FATAL);
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
