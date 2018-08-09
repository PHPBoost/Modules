<?php
/*##################################################
 *		       SmalladsDeleteItemController.class.php
 *                            -------------------
 *   begin                : March 15, 2018
 *   copyright            : (C) 2018 Sebastien LARTIGUE
 *   email                : babsolune@phpboost.com
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
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
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

		if (AppContext::get_current_user()->is_readonly())
		{
			$controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($controller);
		}

		SmalladsService::delete('WHERE id=:id', array('id' => $smallad->get_id()));
		SmalladsService::get_keywords_manager()->delete_relations($smallad->get_id());

		PersistenceContext::get_querier()->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => 'smallads', 'id' => $smallad->get_id()));

		CommentsService::delete_comments_topic_module('smallads', $smallad->get_id());

		Feed::clear_cache('smallads');
		SmalladsCache::invalidate();
		SmalladsCategoriesCache::invalidate();

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
