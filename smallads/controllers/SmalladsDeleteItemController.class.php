<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 12 18
 * @since       PHPBoost 5.1 - 2018 03 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class SmalladsDeleteItemController extends ModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_get_protect();

		$smallad = $this->get_smallad($request);

		if (!$smallad->is_authorized_to_delete())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}

		SmalladsService::delete($smallad->get_id());

		SmalladsService::clear_cache();

		AppContext::get_response()->redirect(($request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), SmalladsUrlBuilder::display_item($smallad->get_category()->get_id(), $smallad->get_category()->get_rewrited_name(), $smallad->get_id(), $smallad->get_rewrited_title())->rel()) ? $request->get_url_referrer() : SmalladsUrlBuilder::home()), StringVars::replace_vars(LangLoader::get_message('smallads.message.success.delete', 'common', 'smallads'), array('title' => $smallad->get_title())));
	}

	private function get_smallad(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('id', 0);
		if (!empty($id))
		{
			try {
				return SmalladsService::get_smallad('WHERE smallads.id=:id', array('id' => $id));
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}
	}
}
?>
