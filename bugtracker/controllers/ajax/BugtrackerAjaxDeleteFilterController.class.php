<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 4.0 - 2014 05 08
*/

class BugtrackerAjaxDeleteFilterController extends AbstractController
{
	public function execute(HTTPRequestCustom $request)
	{
		$id = $request->get_int('id', 0);

		$code = -1;
		if (!empty($id))
		{
			//Delete filter
			BugtrackerService::delete_filter("WHERE id=:id", ['id' => $id]);
			$code = $id;
		}

		return new JSONResponse(['code' => $code]);
	}
}
?>
