<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 15
 * @since       PHPBoost 5.0 - 2016 02 18
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class QuotesItem
{
	private $id;
	private $id_category;
	private $creation_date;
	private $approved;
	private $author_user;
	private $writer;
	private $rewrited_writer;
	private $content;

	public function get_id()
	{
		return $this->id;
	}

	public function set_id($id)
	{
		$this->id = $id;
	}

	public function get_id_category()
	{
		return $this->id_category;
	}

	public function set_id_category($id_category)
	{
		$this->id_category = $id_category;
	}

	public function get_category()
	{
		return CategoriesService::get_categories_manager()->get_categories_cache()->get_category($this->id_category);
	}

	public function get_creation_date()
	{
		return $this->creation_date;
	}

	public function set_creation_date(Date $creation_date)
	{
		$this->creation_date = $creation_date;
	}

	public function set_author_user(User $user)
	{
		$this->author_user = $user;
	}

	public function get_author_user()
	{
		return $this->author_user;
	}

	public function approve()
	{
		$this->approved = true;
	}

	public function unapprove()
	{
		$this->approved = false;
	}

	public function is_approved()
	{
		return $this->approved;
	}

	public function get_writer()
	{
		return $this->writer;
	}

	public function set_writer($writer)
	{
		$this->writer = $writer;
	}

	public function set_rewrited_writer($rewrited_writer)
	{
		$this->rewrited_writer = $rewrited_writer;
	}

	public function get_rewrited_writer()
	{
		return Url::encode_rewrite($this->writer);
	}

	public function get_content()
	{
		return $this->content;
	}

	public function set_content($content)
	{
		$this->content = $content;
	}

	public function is_authorized_to_add()
	{
		return CategoriesAuthorizationsService::check_authorizations($this->id_category)->write() || CategoriesAuthorizationsService::check_authorizations($this->id_category)->contribution();
	}

	public function is_authorized_to_edit()
	{
		return CategoriesAuthorizationsService::check_authorizations($this->id_category)->moderation() || ((CategoriesAuthorizationsService::check_authorizations($this->id_category)->write() || (CategoriesAuthorizationsService::check_authorizations($this->id_category)->contribution())) && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}

	public function is_authorized_to_delete()
	{
		return CategoriesAuthorizationsService::check_authorizations($this->id_category)->moderation() || ((CategoriesAuthorizationsService::check_authorizations($this->id_category)->write() || (CategoriesAuthorizationsService::check_authorizations($this->id_category)->contribution() && !$this->is_approved())) && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}

	public function get_properties()
	{
		return array(
			'id' => $this->get_id(),
			'id_category' => $this->get_id_category(),
			'creation_date' => $this->get_creation_date()->get_timestamp(),
			'approved' => (int)$this->is_approved(),
			'author_user_id' => $this->get_author_user()->get_id(),
			'writer' => $this->get_writer(),
			'rewrited_writer' => $this->get_rewrited_writer(),
			'content' => $this->get_content()
		);
	}

	public function set_properties(array $properties)
	{
		$this->set_id($properties['id']);
		$this->set_id_category($properties['id_category']);
		$this->set_creation_date(new Date($properties['creation_date'], Timezone::SERVER_TIMEZONE));
		if ($properties['approved'])
			$this->approve();
		else
			$this->unapprove();
		$this->set_writer($properties['writer']);
		$this->set_rewrited_writer($properties['rewrited_writer']);
		$this->set_content($properties['content']);

		$user = new User();
		if (!empty($properties['user_id']))
			$user->set_properties($properties);
		else
			$user->init_visitor_user();

		$this->set_author_user($user);
	}

	public function init_default_properties($id_category = Category::ROOT_CATEGORY)
	{
		$this->id_category = $id_category;
		$this->author_user = AppContext::get_current_user();
		$this->creation_date = new Date();
		if (CategoriesAuthorizationsService::check_authorizations()->write())
			$this->approve();
		else
			$this->unapprove();
	}

	public function get_array_tpl_vars()
	{
		$category = $this->get_category();
		$content = FormatingHelper::second_parse($this->content);
		$user = $this->get_author_user();
		$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);

		return array_merge(
			Date::get_array_tpl_vars($this->creation_date,'date'),
			array(
				// Conditions
				'C_APPROVED' => $this->is_approved(),
				'C_CONTROLS' => $this->is_authorized_to_edit() || $this->is_authorized_to_delete(),
				'C_EDIT' => $this->is_authorized_to_edit(),
				'C_DELETE' => $this->is_authorized_to_delete(),
				'C_USER_GROUP_COLOR' => !empty($user_group_color),

				// Item
				'ID' => $this->id,
				'WRITER_NAME' => $this->writer,
				'CONTENT' => $content,
				'C_AUTHOR_EXISTS' => $user->get_id() !== User::VISITOR_LEVEL,
				'AUTHOR_DISPLAY_NAME' => $user->get_display_name(),
				'USER_LEVEL_CLASS' => UserService::get_level_class($user->get_level()),
				'USER_GROUP_COLOR' => $user_group_color,

				// Category
				'C_ROOT_CATEGORY' => $category->get_id() == Category::ROOT_CATEGORY,
				'CATEGORY_ID' => $category->get_id(),
				'CATEGORY_NAME' => $category->get_name(),
				'CATEGORY_DESCRIPTION' => $category->get_description(),
				'U_CATEGORY_THUMBNAIL' => $category->get_thumbnail()->rel(),
				'U_CATEGORY' => QuotesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel(),
				'U_EDIT_CATEGORY' => $category->get_id() == Category::ROOT_CATEGORY ? QuotesUrlBuilder::configuration()->rel() : CategoriesUrlBuilder::edit($category->get_id())->rel(),

				// Item links
				'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($this->get_author_user()->get_id())->rel(),
				'U_WRITER' => QuotesUrlBuilder::display_writer_items($this->rewrited_writer)->rel(),
				'U_EDIT' => QuotesUrlBuilder::edit($this->id)->rel(),
				'U_DELETE' => QuotesUrlBuilder::delete($this->id)->rel(),
			)
		);
	}
}
?>
