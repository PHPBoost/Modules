<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 12 30
 * @since       PHPBoost 5.0 - 2016 02 18
 * @contributor mipel <mipel@phpboost.com>
*/

class Quote
{
	private $id;
	private $id_category;
	private $creation_date;
	private $approved;
	private $author_user;
	private $author;
	private $rewrited_author;
	private $quote;

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

	public function get_author()
	{
		return $this->author;
	}

	public function set_author($author)
	{
		$this->author = $author;
	}

	public function set_rewrited_author($rewrited_author)
	{
		$this->rewrited_author = $rewrited_author;
	}

	public function get_rewrited_author()
	{
		return Url::encode_rewrite($this->author);
	}

	public function get_quote()
	{
		return $this->quote;
	}

	public function set_quote($quote)
	{
		$this->quote = $quote;
	}

	public function is_authorized_to_add()
	{
		return CategoriesAuthorizationsService::check_authorizations($this->id_category)->write() || CategoriesAuthorizationsService::check_authorizations($this->id_category)->contribution();
	}

	public function is_authorized_to_edit()
	{
		return CategoriesAuthorizationsService::check_authorizations($this->id_category)->moderation() || ((CategoriesAuthorizationsService::check_authorizations($this->id_category)->write() || (CategoriesAuthorizationsService::check_authorizations($this->id_category)->contribution() && !$this->is_approved())) && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
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
			'author' => $this->get_author(),
			'rewrited_author' => $this->get_rewrited_author(),
			'quote' => $this->get_quote()
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
		$this->set_author($properties['author']);
		$this->set_rewrited_author($properties['rewrited_author']);
		$this->set_quote($properties['quote']);

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
		$quote = FormatingHelper::second_parse($this->quote);
		$user = $this->get_author_user();
		$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);

		return array(
			'C_APPROVED' => $this->is_approved(),
			'C_MODERATION' => CategoriesAuthorizationsService::check_authorizations($this->id_category)->moderation(),
			'C_EDIT' => $this->is_authorized_to_edit(),
			'C_DELETE' => $this->is_authorized_to_delete(),
			'C_USER_GROUP_COLOR' => !empty($user_group_color),

			//quotes
			'ID' => $this->id,
			'AUTHOR' => $this->author,
			'QUOTE' => $quote,
			'DATE' => $this->creation_date->format(Date::FORMAT_DAY_MONTH_YEAR),
			'DATE_DAY' => $this->creation_date->get_day(),
			'DATE_MONTH' => $this->creation_date->get_month(),
			'DATE_YEAR' => $this->creation_date->get_year(),
			'DATE_DAY_MONTH' => $this->creation_date->format(Date::FORMAT_DAY_MONTH),
			'DATE_ISO8601' => $this->creation_date->format(Date::FORMAT_ISO8601),
			'C_AUTHOR_EXIST' => $user->get_id() !== User::VISITOR_LEVEL,
			'PSEUDO' => $user->get_display_name(),
			'USER_LEVEL_CLASS' => UserService::get_level_class($user->get_level()),
			'USER_GROUP_COLOR' => $user_group_color,

			//Category
			'C_ROOT_CATEGORY' => $category->get_id() == Category::ROOT_CATEGORY,
			'CATEGORY_ID' => $category->get_id(),
			'CATEGORY_NAME' => $category->get_name(),
			'CATEGORY_DESCRIPTION' => $category->get_description(),
			'U_CATEGORY_THUMBNAIL' => $category->get_thumbnail()->rel(),
			'U_CATEGORY' => QuotesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel(),
			'U_EDIT_CATEGORY' => $category->get_id() == Category::ROOT_CATEGORY ? QuotesUrlBuilder::configuration()->rel() : CategoriesUrlBuilder::edit_category($category->get_id())->rel(),

			'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($this->get_author_user()->get_id())->rel(),
			'U_AUTHOR_LINK' => QuotesUrlBuilder::display_author_quotes($this->rewrited_author)->rel(),
			'U_EDIT' => QuotesUrlBuilder::edit($this->id)->rel(),
			'U_DELETE' => QuotesUrlBuilder::delete($this->id)->rel(),
		);
	}
}
?>
