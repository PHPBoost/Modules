<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 02 11
 * @since       PHPBoost 4.0 - 2013 08 04
*/

class ServerStatusService
{
	public static function check_servers_status($force_check = false)
	{
		$config = ServerStatusConfig::load();
		$servers_list = $config->get_servers_list();

		foreach ($servers_list as $id => &$server)
		{
			if ($server->is_displayed())
				$server->check_status($force_check);
		}

		$config->set_servers_list($servers_list);
		ServerStatusConfig::save();
	}

	public static function get_types()
	{
		$types = array();

		$folder = new Folder(PATH_TO_ROOT . '/ServerStatus/services/types');
		if ($folder->exists())
		{
			foreach ($folder->get_folders() as $f)
			{
				$type = $names = array();
				foreach ($f->get_files() as $file)
				{
					$name_class = str_replace('.class', '', $file->get_name_without_extension());

					$instance_class = new $name_class();
					$type[$name_class] = array('name' => $instance_class->get_name(), 'default_port' => $instance_class->get_default_port(), 'icon' => $instance_class->get_medium_icon());
					$names[$name_class] = $instance_class->get_name();
				}

				if (!empty($type))
				{
					array_multisort($names, SORT_ASC, $type);
					$types[$f->get_name()] = $type;
				}
			}
		}

		return $types;
	}
}
?>
