<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 06 12
 * @since       PHPBoost 5.0 - 2016 05 01
*/

class HomeLandingAjaxChangeModuleDisplayController extends AbstractController
{
	public function execute(HTTPRequestCustom $request)
	{
		$id = $request->get_int('id', 0);

		$display = -1;
		if ($id !== 0)
		{
			$config = HomeLandingConfig::load();
			$modules = $config->get_modules();
			if ($modules[$id]['displayed'])
				$display = $modules[$id]['displayed'] = 0;
			else
				$display = $modules[$id]['displayed'] = 1;
			$config->set_modules($modules);

			HomeLandingConfig::save();
		}

		return new JSONResponse(array('id' => $id, 'display' => $display));
	}
}
?>
