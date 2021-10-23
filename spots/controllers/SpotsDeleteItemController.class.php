<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 09 16
 * @since       PHPBoost 6.0 - 2021 08 22
*/

class SpotsDeleteItemController extends ModuleController
{
	private $item;

	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_get_protect();

		$item = $this->get_item($request);

		if (!$item->is_authorized_to_delete())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}

		SpotsService::delete($item->get_id());

		SpotsService::clear_cache();

		AppContext::get_response()->redirect(($request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), SpotsUrlBuilder::display($item->get_category()->get_id(), $item->get_category()->get_rewrited_name(), $item->get_id(), $item->get_rewrited_name())->rel()) ? $request->get_url_referrer() : SpotsUrlBuilder::home()), StringVars::replace_vars(LangLoader::get_message('spots.message.success.delete', 'common', 'spots'), array('name' => $item->get_title())));
	}

	private function get_item(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('id', 0);
		if (!empty($id))
		{
			try {
				$this->item = SpotsService::get_item('WHERE spots.id=:id', array('id' => $id));
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}
	}
}
?>
