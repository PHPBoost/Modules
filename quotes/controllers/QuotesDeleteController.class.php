<?php
/*##################################################
 *                               QuotesDeleteController.class.php
 *                            -------------------
 *   begin                : February 18, 2016
 *   copyright            : (C) 2016 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
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
		
		AppContext::get_response()->redirect(($request->get_url_referrer() ? $request->get_url_referrer() : QuotesUrlBuilder::home()), StringVars::replace_vars(LangLoader::get_message('quotes.message.success.delete', 'common', 'quotes'), array('name' => $this->quote->get_author())));
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
