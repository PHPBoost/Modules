<?php
/*##################################################
 *                               SmalladsAuthorizationsService.class.php
 *                            -------------------
 *   begin                : February 2, 2016
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

class SmalladsAuthorizationsService
{
	const READ_AUTHORIZATIONS = 1;
	const OWN_CRUD_AUTHORIZATIONS = 2;
	const CONTRIBUTION_AUTHORIZATIONS = 4;
	const MODERATION_AUTHORIZATIONS = 8;
	
	public static function check_authorizations()
	{
		$instance = new self();
		return $instance;
	}
	
	public function read()
	{
		return $this->is_authorized(self::READ_AUTHORIZATIONS);
	}
	
	public function own_crud()
	{
		return $this->is_authorized(self::OWN_CRUD_AUTHORIZATIONS);
	}
	
	public function contribution()
	{
		return $this->is_authorized(self::CONTRIBUTION_AUTHORIZATIONS);
	}
	
	public function moderation()
	{
		return $this->is_authorized(self::MODERATION_AUTHORIZATIONS);
	}
	
	private function is_authorized($bit)
	{
		$auth = SmalladsConfig::load()->get_authorizations();
		return AppContext::get_current_user()->check_auth($auth, $bit);
	}
}
?>
