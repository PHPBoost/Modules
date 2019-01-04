<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 12 24
 * @since   	PHPBoost 5.0 - 2016 02 18
 * @contributor mipel <mipel@phpboost.com>
*/

class QuotesAuthorizationsService
{
	public $id_category;

	public static function check_authorizations($id_category = Category::ROOT_CATEGORY)
	{
		$instance = new self();
		$instance->id_category = $id_category;
		return $instance;
	}

	public function read()
	{
		return $this->is_authorized(Category::READ_AUTHORIZATIONS, Authorizations::AUTH_PARENT_PRIORITY);
	}

	public function write()
	{
		return $this->is_authorized(Category::WRITE_AUTHORIZATIONS);
	}

	public function contribution()
	{
		return $this->is_authorized(Category::CONTRIBUTION_AUTHORIZATIONS);
	}

	public function moderation()
	{
		return $this->is_authorized(Category::MODERATION_AUTHORIZATIONS);
	}

	public function manage_categories()
	{
		return $this->is_authorized(Category::CATEGORIES_MANAGEMENT_AUTHORIZATIONS);
	}

	private function is_authorized($bit, $mode = Authorizations::AUTH_CHILD_PRIORITY)
	{
		return QuotesService::get_categories_manager()->get_categories_cache()->get_category($this->id_category)->check_auth($bit);
	}
}
?>
