<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2016 02 11
 * @since   	PHPBoost 4.0 - 2013 08 20
*/

class ServerStatusAjaxDeleteServerController extends AbstractController
{
	public function execute(HTTPRequestCustom $request)
	{
		$id = $request->get_int('id', 0);

		$code = -1;
		if (!empty($id))
		{
			$config = ServerStatusConfig::load();
			$servers = $config->get_servers_list();
			if (isset($servers[$id]))
			{
				unset($servers[$id]);
				$new_servers_list = array();

				$position = 0;
				foreach ($servers as $key => $server)
				{
					$position++;
					$new_servers_list[$position] = $server;
				}

				$config->set_servers_list($new_servers_list);

				ServerStatusConfig::save();
				$code = $position;
			}
		}

		return new JSONResponse(array('code' => $code));
	}
}
?>
