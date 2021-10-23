<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 02 11
 * @since       PHPBoost 4.1 - 2015 10 08
*/

class ServerStatusAjaxChangeServerDisplayController extends AbstractController
{
	public function execute(HTTPRequestCustom $request)
	{
		$id = $request->get_int('id', 0);

		$display = -1;
		if ($id !== 0)
		{
			$config = ServerStatusConfig::load();
			$servers_list = $config->get_servers_list();
			if ($servers_list[$id]->is_displayed())
			{
				$display = 0;
				$servers_list[$id]->not_displayed();
			}
			else
			{
				$display = 1;
				$servers_list[$id]->displayed();
			}
			$config->set_servers_list($servers_list);

			ServerStatusConfig::save();
		}

		return new JSONResponse(array('id' => $id, 'display' => $display));
	}
}
?>
