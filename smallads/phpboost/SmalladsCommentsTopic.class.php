<?php
/*##################################################
 *                    SmalladsCommentsTopic.class.php
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

class SmalladsCommentsTopic extends CommentsTopic
{
	private $smallad;

	public function __construct(Smallad $smallad = null)
	{
		parent::__construct('smallads');
		$this->smallad = $smallad;
	}

	public function get_authorizations()
	{
		$authorizations = new CommentsAuthorizations();
		$authorizations->set_authorized_access_module(SmalladsAuthorizationsService::check_authorizations($this->get_smallad()->get_id_category())->read());
		return $authorizations;
	}

	public function is_display()
	{
		return $this->get_smallad()->is_published();
	}

	private function get_smallad()
	{
		if ($this->smallad === null)
		{
			$this->smallad = SmalladsService::get_smallad('WHERE smallads.id=:id', array('id' => $this->get_id_in_module()));
		}
		return $this->smallad;
	}
}
?>
