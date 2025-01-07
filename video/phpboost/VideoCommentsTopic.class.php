<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 01 16
 * @since       PHPBoost 6.0 - 2022 10 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 */

class VideoCommentsTopic extends DefaultCommentsTopic
{
	public function __construct(VideoItem $item = null)
	{
		parent::__construct('video');
		$this->item = $item;
	}

	protected function get_item_from_manager()
	{
		return VideoService::get_item($this->get_id_in_module());
	}
}
?>
