<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 12 24
 * @since   	PHPBoost 5.0 - 2016 02 18
 * @contributor mipel <mipel@phpboost.com>
*/

class QuotesDeleteController extends ModuleController
{
	private $quote;

	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_get_protect();

		$this->get_quote($request);

		$this->check_authorizations();

		QuotesService::delete('WHERE id=:id', array('id' => $this->quote->get_id()));
		PersistenceContext::get_querier()->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => 'quotes', 'id' => $this->quote->get_id()));

		QuotesCategoriesCache::invalidate();

		QuotesCache::invalidate();

		AppContext::get_response()->redirect(($request->get_url_referrer() ? $request->get_url_referrer() : QuotesUrlBuilder::home()), StringVars::replace_vars(LangLoader::get_message('quotes.message.success.delete', 'common', 'quotes'), array('author' => $this->quote->get_author())));
	}

	private function get_quote(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('id', 0);

		if (!empty($id))
		{
			try {
				$this->quote = QuotesService::get_quote('WHERE quotes.id=:id', array('id' => $id));
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}
	}

	private function check_authorizations()
	{
		if (!$this->quote->is_authorized_to_delete())
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
