<?php
/*##################################################
 *                          AjaxQuoteAuthorAutoCompleteController.class.php
 *                            -------------------
 *   begin                : February 18, 2016
 *   copyright            : (C) 2016 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */
class AjaxQuoteAuthorAutoCompleteController extends AbstractController
{
	public function execute(HTTPRequestCustom $request)
	{
		$suggestions = array();
 
		try {
			$result = PersistenceContext::get_querier()->select("SELECT author FROM " . QuotesSetup::$quotes_table . " WHERE author LIKE '" . $request->get_value('value', '') . "%' GROUP BY author");
			
			while($row = $result->fetch())
			{
				$suggestions[] = $row['author'];
			}
			$result->dispose();
		} catch (Exception $e) {
		}
		
		return new JSONResponse(array('suggestions' => $suggestions));
	}
}
?>
