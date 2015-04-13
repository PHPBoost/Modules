<?php
/*##################################################
 *                           QuotesSearchable.class.php
 *                            -------------------
 *   begin                : February 4, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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

class QuotesSearchable extends AbstractSearchableExtensionPoint
{
	private $sql_querier;
	
	public function __construct()
	{
		$this->sql_querier = PersistenceContext::get_sql();
		parent::__construct(true, false);
	}
	
	 /**
	 * @method Return the search form.
	 * @param string[] $args (optional) Search arguments
	 */
	public function get_search_form($args = null)
	{
		require_once(PATH_TO_ROOT . '/kernel/begin.php');
		
		
        global $CONFIG, $LANG, $QUOTES_LANG;
		
		load_module_lang('quotes');
		
		//Creation of the template
		$tpl = new FileTemplate('quotes/quotes_search_form.tpl');
		
		 if ( empty($args['QuotesWhere']) || !in_array($args['QuotesWhere'], explode(',','author,contents,all')) )
            $args['QuotesWhere'] = 'all';
		
		$tpl->put_all(Array(
			'L_WHERE' => $QUOTES_LANG['q_search_where'],
            'IS_AUTHOR_SELECTED' => $args['QuotesWhere'] == 'author'? ' selected="selected"': '',
            'IS_CONTENTS_SELECTED' => $args['QuotesWhere'] == 'contents'? ' selected="selected"': '',
            'IS_ALL_SELECTED' => $args['QuotesWhere'] == 'all'? ' selected="selected"': '',
            'L_AUTHOR' => $QUOTES_LANG['q_author'],
            'L_CONTENTS' => $QUOTES_LANG['q_contents']
		));
		
		return $tpl->render();
	}
	
	 /**
	 *  @method Get the args list of the <get_search_args> method
	 */
    function get_search_args()
    {
        return Array('QuotesWhere');
    }
	
	 /**
	 * @method Return the search request.
	 * @param string[] $args Search arguments
	 */
	public function get_search_request($args)
	{
		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;
		
        if ( empty($args['QuotesWhere']) || !in_array($args['QuotesWhere'], explode(',','author,contents,all')) )
            $args['QuotesWhere'] = 'all';
        
		switch ($args['QuotesWhere']) {
			case 'author':
				$req = "SELECT "
						.$args['id_search']." AS `id_search`,
						q.id AS `id_content`,
						q.author AS `title`,
						( MATCH(q.author) AGAINST('".$args['search']."') ) * " . $weight . " AS `relevance`,
						CONCAT('" . PATH_TO_ROOT . "/quotes/quotes.php?id=',q.id) AS `link`
						FROM ".PREFIX."quotes q
						WHERE ( MATCH(q.author) AGAINST('".$args['search']."') )";
				break;
				
			case 'contents':
				$req = "SELECT "
						.$args['id_search']." AS `id_search`,
						q.id AS `id_content`,
						q.author AS `title`,
						( MATCH(q.contents) AGAINST('".$args['search']."') ) * " . $weight . " AS `relevance`,
						CONCAT('" . PATH_TO_ROOT . "/quotes/quotes.php?id=',q.id) AS `link`
						FROM ".PREFIX."quotes q
						WHERE ( MATCH(q.contents) AGAINST('".$args['search']."') )";
				break;
				
			case 'all':
			default:
				$req = "SELECT "
						.$args['id_search']." AS `id_search`,
						q.id AS `id_content`,
						q.author AS `title`,
						( MATCH(q.contents) AGAINST('".$args['search']."') + MATCH(q.author) AGAINST('".$args['search']."') ) / 2 * " . $weight . " AS `relevance`,
						CONCAT('" . PATH_TO_ROOT . "/quotes/quotes.php?id=',q.id) AS `link`
						FROM ".PREFIX."quotes q
						WHERE ( MATCH(q.author) AGAINST('".$args['search']."')
						OR MATCH(q.contents) AGAINST('".$args['search']."') )";
		}
        return $req;
	}
}
?>