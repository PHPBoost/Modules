<?php
/*##################################################
 *                           DictionarySearchable.class.php
 *                            -------------------
 *   begin                : November 15, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
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

class DictionarySearchable extends AbstractSearchableExtensionPoint
{
	public function __construct()
	{
		parent::__construct(true, false);
	}
	
	/**
	*  @method  Renvoie le formulaire de recherche du module
	*/
    function get_search_form($args=null)
    {
        require_once(PATH_TO_ROOT . '/kernel/begin.php');
        load_module_lang('dictionary');
        global $LANG;
        
        $tpl = new FileTemplate('dictionary/dictionary_search_form.tpl');
        
        if ( empty($args['DictionaryWhere']) || !in_array($args['DictionaryWhere'], explode(',','word,definition,all')) )
            $args['DictionaryWhere'] = 'all';

        $tpl->assign_vars(Array(
            'L_WHERE' => $LANG['dictionary_search_where'],
            'IS_AUTHOR_SELECTED' => $args['DictionaryWhere'] == 'word'? ' selected="selected"': '',
            'IS_CONTENTS_SELECTED' => $args['DictionaryWhere'] == 'definition'? ' selected="selected"': '',
            'IS_ALL_SELECTED' => $args['DictionaryWhere'] == 'all'? ' selected="selected"': '',
            'L_AUTHOR' => $LANG['dictionary_author'],
            'L_CONTENTS' => $LANG['dictionary_contents']
        ));
        
        return $tpl->render();
    }
	
	/**
	*  @method  Renvoie la liste des arguments de la méthode <GetSearchRequest>
	*/
    function get_search_args()
    {
        return Array('DictionaryWhere');
    }
	
	/**
	*  @method  Renvoie la requête de recherche dans le module
	*/
    function get_search_request($args)
    {
        $weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;
		
        if ( empty($args['DictionaryWhere']) || !in_array($args['DictionaryWhere'], explode(',','title,contents,all')) )
            $args['DictionaryWhere'] = 'all';
        
		switch ($args['DictionaryWhere']) {
			case 'title':
				$req = "SELECT "
						.$args['id_search']." AS `id_search`,
						q.id AS `id_content`,
						q.word AS `title`,
						( MATCH(q.word) AGAINST('".$args['search']."') ) * " . $weight . " AS `relevance`,
						CONCAT('" . PATH_TO_ROOT . "/dictionary/dictionary.php?l=',q.word) AS `link`
						FROM ".PREFIX."dictionary q
						WHERE ( MATCH(q.word) AGAINST('".$args['search']."') )";
				break;
			case 'contents':
				$req = "SELECT "
						.$args['id_search']." AS `id_search`,
						q.id AS `id_content`,
						q.word AS `title`,
						( MATCH(q.description) AGAINST('".$args['search']."') ) * " . $weight . " AS `relevance`,
						CONCAT('" . PATH_TO_ROOT . "/dictionary/dictionary.php?l=',q.word) AS `link`
						FROM ".PREFIX."dictionary q
						WHERE ( MATCH(q.description) AGAINST('".$args['search']."') )";
				break;
			default:
				$req = "SELECT "
						.$args['id_search']." AS `id_search`,
						q.id AS `id_content`,
						q.word AS `title`,
						( MATCH(q.description) AGAINST('".$args['search']."') + MATCH(q.word) AGAINST('".$args['search']."') ) / 2 * " . $weight . " AS `relevance`,
						CONCAT('" . PATH_TO_ROOT . "/dictionary/dictionary.php?l=',q.word) AS `link`
						FROM ".PREFIX."dictionary q
						WHERE ( MATCH(q.word) AGAINST('".$args['search']."')
						OR MATCH(q.description) AGAINST('".$args['search']."') )";
				break;
		}

        return $req;
    }
}
?>