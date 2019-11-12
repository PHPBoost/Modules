<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2019 11 12
 * @since   	PHPBoost 5.1 - 2018 03 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
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
		$authorizations->set_authorized_access_module(CategoriesAuthorizationsService::check_authorizations($this->get_smallad()->get_id_category(), 'smallads')->read());
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
