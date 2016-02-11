<?php
/*##################################################
 *                        ServerStatusService.class.php
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

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
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
