<?php
/*##################################################
 *                            QuotesCats.class.php
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

define('DO_NOT_GENERATE_CACHE', false);

class QuotesCats extends DeprecatedCategoriesManager
{
	## Public methods ##

	//Constructor
	public function QuotesCats()
	{
		global $Cache, $QUOTES_CAT;
		if (!isset($QUOTES_CAT))
		$Cache->load('quotes');
		parent::__construct('quotes_cats', 'quotes', $QUOTES_CAT);
	}

	//Method which removes all subcategories and their content
	public function delete_category_recursively($id)
	{
		global $Cache;
		//We delete the category
		$this->_delete_category_with_content($id);
		//Then its content
		foreach ($this->cache_var as $id_cat => $properties)
		{
			if ($id_cat != 0 && $properties['id_parent'] == $id)
			$this->delete_category_recursively($id_cat);
		}

		$Cache->Generate_module_file('quotes', RELOAD_CACHE);
	}

	//Method which deletes a category and move its content in another category
	public function delete_category_and_move_content($id_cat, $new_id_cat)
	{
		global $Sql;

		if ($id_cat == 0 || !array_key_exists($id_cat, $this->cache_var))
		{
			parent::add_error(NEW_PARENT_CATEGORY_DOES_NOT_EXIST);
			return false;
		}

		parent::delete($id_cat);
		
		foreach ($this->cache_var as $id => $properties)
		{
			if ($id != 0 && $properties['id_parent'] == $id_cat)
			parent::move_into_another($id, $new_id_cat);
		}

		$Sql->query_inject("UPDATE " . PREFIX . "quotes
								SET idcat = " . intval($new_id_cat) . "
								WHERE idcat = " . intval($id_cat)
								,__LINE__, __FILE__);

		return true;
	}

	//Function which adds a category
	public function add_category($id_parent, $name, $description, $image, $auth, $visible)
	{
		global $Sql;
		if ($id_parent == 0 || array_key_exists($id_parent, $this->cache_var))
		{
			$new_id_cat = parent::add($id_parent, $name, intval($visible));
			$Sql->query_inject("UPDATE " . PREFIX . "quotes_cats
									SET description = '" . addslashes($description) . "',
										image = '" . addslashes($image) . "',
										auth = '" . addslashes($auth) . "'
									WHERE id = " . intval($new_id_cat),
									__LINE__, __FILE__);
			return 'e_success';
		}
		else
		return 'e_unexisting_cat';
	}

	//Function which updates a category
	public function update_category($id_cat, $id_parent, $name, $description, $image, $auth, $visible)
	{
		global $Sql, $Cache;
		if ($id_cat == 0 || array_key_exists($id_cat, $this->cache_var))
		{
			if ($id_parent != $this->cache_var[$id_cat]['id_parent'])
			{
				if ( ! parent::move_into_another($id_cat, $id_parent))
				{
					if ($this->check_error(NEW_PARENT_CATEGORY_DOES_NOT_EXIST))
						return 'e_new_cat_does_not_exist';
					if ($this->check_error(NEW_CATEGORY_IS_IN_ITS_CHILDRENS))
						return 'e_infinite_loop';
				}
				else
				{
					$Cache->load('quotes', RELOAD_CACHE);
				}
			}
			$Sql->query_inject("UPDATE " . PREFIX . "quotes_cats
								SET name = '" . addslashes($name) . "',
									image = '" . addslashes($image) . "',
									description = '" . addslashes($description) . "',
									auth = '" . addslashes($auth) . "',
									visible = " . intval($visible) . "
								WHERE id = " . intval($id_cat),
								__LINE__, __FILE__);
			$Cache->Generate_module_file('quotes');
				
			return 'e_success';
		}
		else
		return 'e_unexisting_category';
	}

	//Function which moves a category
	public function move_into_another($id, $new_id_cat, $position = 0)
	{
		$result = parent::move_into_another($id, $new_id_cat, $position);

		return $result;
	}
	
	//function which changes the visibility of one category
	public function change_visibility($category_id, $visibility, $generate_cache = LOAD_CACHE)
	{
		$result = parent::change_visibility($category_id, $visibility, $generate_cache);
		
		return $result;
	}

	// Genrerate the bread crumb from a category.
	public function bread_crumb($id = 0)
	{
		global $Bread_crumb, $QUOTES_LANG, $QUOTES_CAT;

		$Bread_crumb->add($QUOTES_LANG['q_title'], url('quotes.php'));
		$Bread_crumb->reverse();
	}

	## Private methods ##

	//method which deletes a category and its content (not recursive)
	public function _delete_category_with_content($id)
	{
		global $Sql;
		
		if ($test = parent::delete($id))
		{
			$Sql->query_inject("DELETE FROM " . PREFIX . "quotes
									WHERE idcat = " . intval($id)
									, __LINE__, __FILE__);
			return TRUE;
		}
		else
			return FALSE;
	}
	
	/**
	*
	* Determine if a current user may access to category
	*
	*/
	public function access_ok($id_cat, $mask, $halt = FALSE)
	{
		return $this->_check_auth($id_cat, $mask, $halt);
	}
	
	/**
	*
	* Determines if current user have access to a category
	*
	*/
	function _check_auth($id_cat, $mask, $halt = FALSE)
	{
		global $User, $Errorh, $CONFIG_QUOTES, $QUOTES_CATS;
		
		if ($User->is_admin())
		{
			return TRUE;
		}
		
		$auth = false;
		if (!empty($QUOTES_CATS[$id_cat]['auth']))
		{
			$auth = $User->check_auth($QUOTES_CATS[$id_cat]['auth'], $mask);
		}
		else
		{
			$auth = $User->check_auth($CONFIG_QUOTES['auth'], $mask);
		}
		
		if ( !$auth && $halt )
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'errors-common'), LangLoader::get_message('e_auth', 'errors'));
			DispatchManager::redirect($controller);
		}
		return $auth;
	}
}
?>