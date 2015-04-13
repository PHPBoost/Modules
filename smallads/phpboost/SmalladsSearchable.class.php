<?php
/*##################################################
 *                           SmalladsSearchable.class.php
 *                            -------------------
 *   begin                : January 29, 2013
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

class SmalladsSearchable extends AbstractSearchableExtensionPoint
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
		require_once(PATH_TO_ROOT . '/smallads/smallads.class.php');
        global $CONFIG, $LANG;

        load_module_lang('smallads');
		
		//Creation of the template
		$tpl = new FileTemplate('smallads/smallads_search_form.tpl');
		
		$smallads = new Smallads();

        if ( empty($args['SmalladsWhere']) || !in_array($args['SmalladsWhere'], explode(',','title,contents,all')) )
            $args['SmalladsWhere'] = 'all';
		
		$tpl->put_all(Array(
			'L_WHERE' 				=> $LANG['sa_search_where'],
            'IS_TITLE_SELECTED' 	=> $smallads->selected($args['SmalladsWhere'], 'title'),
            'IS_CONTENTS_SELECTED' 	=> $smallads->selected($args['SmalladsWhere'], 'contents'),
            'IS_ALL_SELECTED' 		=> $smallads->selected($args['SmalladsWhere'], 'all'),
            'L_TITLE' 				=> $LANG['sa_db_title'],
            'L_CONTENTS' 			=> $LANG['sa_db_contents']
		));
		
		return $tpl->render();
	}
	
	 /**
	 *  @method Get the args list of the <get_search_args> method
	 */
    function get_search_args()
    {
        return Array('SmalladsWhere');
    }
	
	 /**
	 * @method Return the search request.
	 * @param string[] $args Search arguments
	 */
	public function get_search_request($args)
	{
		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;

        if ( empty($args['SmalladsWhere']) || !in_array($args['SmalladsWhere'], explode(',','title,contents,all')) )
            $args['SmalladsWhere'] = 'all';

		$target = PATH_TO_ROOT . '/smallads/smallads.php?id=';

		$smallads 	= new Smallads();
		$mask	 	= 0x08;					// SMALLADS_LIST_ACCESS but can't include constants defined in smallads_begin.php (namespace conflicts)

		$access_nok = '';
		if (!$smallads->access_ok($mask))
		{
			$access_nok = ' 0 AND ';
		}

		$req = "SELECT "
				.$args['id_search']." AS `id_search`,
				q.id AS `id_content`,
				q.title AS `title`,
				1 AS `relevance`,
				CONCAT('" . $target . "',q.id) AS `link`
				FROM ".PREFIX."smallads q ";

		switch ($args['SmalladsWhere']) {
			case 'title':
				$req .= " WHERE ".$access_nok.
						" (q.title LIKE '%".addslashes($args['search'])."%')";
				break;

			case 'contents':
				$req .= " WHERE ".$access_nok.
						" (q.contents LIKE '%".addslashes($args['search'])."%')";
				break;

			case 'all':
			default:
				$req .= " WHERE ".$access_nok.
						" ((q.title LIKE '%".addslashes($args['search'])."%')
							OR (q.contents LIKE '%".addslashes($args['search'])."%'))";
		}
        return $req;
	}
}
?>