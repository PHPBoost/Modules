<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 19
 * @since       PHPBoost 5.0 - 2016 02 18
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class QuotesDeleteItemController extends ModuleController
{
	private $item;

	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_get_protect();

		$this->get_item($request);

		$this->check_authorizations();

		QuotesService::delete($this->item->get_id());

		QuotesService::clear_cache();

		AppContext::get_response()->redirect(($request->get_url_referrer() ? $request->get_url_referrer() : QuotesUrlBuilder::home()), StringVars::replace_vars(LangLoader::get_message('quotes.message.success.delete', 'common', 'quotes'), array('writer' => $this->item->get_writer())));
	}

	private function get_item(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('id', 0);

		if (!empty($id))
		{
			try {
				$this->item = QuotesService::get_item('WHERE quotes.id=:id', array('id' => $id));
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}
	}

	private function check_authorizations()
	{
		if (!$this->item->is_authorized_to_delete())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		if (AppContext::get_current_user()->is_readonly())
		{
			$error_controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
