<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 5.1 - 2018 04 10
*/

class SocialNetworksList
{
	/**
	 * @return string[] List of the classes implementing interface
	 */
	public function get_implementing_classes(string $interface_name): array
	{
		$folder = new Folder(ModulesManager::get_module_path('SocialNetworks') . '/social_networks');
		$classes = [];

		foreach ($folder->get_files() as $class)
		{
			$name = str_replace('.class.php', '', $class->get_name());
			if (ClassLoader::is_class_registered_and_valid($name) && in_array($interface_name, class_implements($name)))
				$classes[] = $name;
		}

		$additional_social_networks = SocialNetworksConfig::load()->get_additional_social_networks();
		if (is_array($additional_social_networks))
		{
			foreach (SocialNetworksConfig::load()->get_additional_social_networks() as $class)
			{
				if (ClassLoader::is_class_registered_and_valid($class) && in_array($interface_name, class_implements($class)))
					$classes[] = $class;
			}
		}

		return $classes;
	}

	/**
	 * @return string[] List of social networks
	 */
	public function get_social_networks_list(): array
	{
		$social_networks = [];

		foreach ($this->get_implementing_classes('SocialNetwork') as $social_network)
		{
			$social_networks[$social_network::SOCIAL_NETWORK_ID] = $social_network;
		}

		return $social_networks;
	}

	/**
	 * @return string[] List of social networks ids
	 */
	public function get_social_networks_ids(): array
	{
		return array_keys($this->get_social_networks_list());
	}

	/**
	 * @return string[] Sorted list of social networks
	 */
	public function get_sorted_social_networks_list(): array
	{
		$social_networks = $this->get_social_networks_list();
		$sorted_social_networks = [];

		foreach (SocialNetworksConfig::load()->get_social_networks_order() as $social_network_id)
		{
			if (isset($social_networks[$social_network_id]))
			{
				$sorted_social_networks[$social_network_id] = $social_networks[$social_network_id];
				unset($social_networks[$social_network_id]);
			}
		}

		return array_merge($sorted_social_networks, $social_networks);
	}

	/**
	 * @return string[] List of social networks authentifications
	 */
	public function get_external_authentications_list(): array
	{
		$get_enabled_authentications = SocialNetworksConfig::load()->get_enabled_authentications();
		$external_authentications = [];

		foreach ($this->get_sorted_social_networks_list() as $id => $social_network)
		{
			$sn = new $social_network();
			if ($sn->has_authentication() && in_array($id, $get_enabled_authentications))
				$external_authentications[] = $sn->get_external_authentication();
		}

		return $external_authentications;
	}

	/**
	 * @return string[] List of social networks sharing links
	 */
	public function get_sharing_links_list(): array
	{
		$request = AppContext::get_request();
		$enabled_content_sharing = SocialNetworksConfig::load()->get_enabled_content_sharing();
		$sharing_links = [];

		foreach ($this->get_sorted_social_networks_list() as $id => $social_network)
		{
			if (in_array($id, $enabled_content_sharing))
			{
				$sn = new $social_network();

				$display = false;
				if ($sn->is_desktop_only() && !$request->is_mobile_device())
				{
					$content_sharing_url = $sn->get_content_sharing_url();
					$display = true;
				}
				else if ($sn->is_mobile_only() && $request->is_mobile_device())
				{
					$content_sharing_url = $sn->has_mobile_content_sharing_url() ? $sn->get_mobile_content_sharing_url() : $sn->get_content_sharing_url();
					$display = true;
				}
				else if (!$sn->is_desktop_only() && !$sn->is_mobile_only())
				{
					if ($request->is_mobile_device() && $sn->has_mobile_content_sharing_url())
						$content_sharing_url = $sn->get_mobile_content_sharing_url();
					else
						$content_sharing_url = $sn->get_content_sharing_url();

					$display = true;
				}

				if ($display && $content_sharing_url)
					$sharing_links[] = new ContentSharingActionsMenuLink($sn->get_css_class(), $sn->get_name(), new Url($content_sharing_url), $sn->get_share_image_renderer_html());
			}
		}

		return $sharing_links;
	}
}
?>
