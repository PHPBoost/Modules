<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 16
 * @since       PHPBoost 5.0 - 2016 02 18
 * @author      mipel <mipel@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class QuotesItem
{
	private int $id = 0;
	private int $id_category = 0;
	private ?Date $creation_date = null;
	private bool $approved = false;
	private ?User $author_user = null;
	private string $writer = '';
	private string $rewrited_writer = '';
	private string $content = '';

	public function get_id(): int
	{
		return $this->id;
	}

	public function set_id(int $id): void
	{
		$this->id = $id;
	}

	public function get_id_category(): int
	{
		return $this->id_category;
	}

	public function set_id_category(int $id_category): void
	{
		$this->id_category = $id_category;
	}

	public function get_category()
	{
		return CategoriesService::get_categories_manager('quotes')->get_categories_cache()->get_category($this->id_category);
	}

	public function get_creation_date(): Date
	{
		return $this->creation_date;
	}

	public function set_creation_date(Date $creation_date): void
	{
		$this->creation_date = $creation_date;
	}

	public function set_author_user(User $user): void
	{
		$this->author_user = $user;
	}

	public function get_author_user(): User
	{
		return $this->author_user;
	}

	public function approve(): void
	{
		$this->approved = true;
	}

	public function unapprove(): void
	{
		$this->approved = false;
	}

	public function is_approved(): bool
	{
		return $this->approved;
	}

	public function get_writer(): string
	{
		return $this->writer;
	}

	public function set_writer(string $writer): void
	{
		$this->writer = $writer;
	}

	public function set_rewrited_writer(string $rewrited_writer): void
	{
		$this->rewrited_writer = $rewrited_writer;
	}

	public function get_rewrited_writer(): string
	{
		return Url::encode_rewrite($this->writer);
	}

	public function get_content(): string
	{
		return $this->content;
	}

	public function set_content(string $content): void
	{
		$this->content = $content;
	}

	public function is_authorized_to_add(): bool
	{
		return CategoriesAuthorizationsService::check_authorizations($this->id_category)->write() || CategoriesAuthorizationsService::check_authorizations($this->id_category)->contribution();
	}

	public function is_authorized_to_edit(): bool
	{
		return CategoriesAuthorizationsService::check_authorizations($this->id_category)->moderation() || ((CategoriesAuthorizationsService::check_authorizations($this->id_category)->write() || (CategoriesAuthorizationsService::check_authorizations($this->id_category)->contribution())) && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}

	public function is_authorized_to_delete(): bool
	{
		return CategoriesAuthorizationsService::check_authorizations($this->id_category)->moderation() || ((CategoriesAuthorizationsService::check_authorizations($this->id_category)->write() || (CategoriesAuthorizationsService::check_authorizations($this->id_category)->contribution() && !$this->is_approved())) && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}

	public function get_properties(): array
	{
		return [
			'id' => $this->get_id(),
			'id_category' => $this->get_id_category(),
			'creation_date' => $this->get_creation_date()->get_timestamp(),
			'approved' => (int)$this->is_approved(),
			'author_user_id' => $this->get_author_user()->get_id(),
			'writer' => $this->get_writer(),
			'rewrited_writer' => $this->get_rewrited_writer(),
			'content' => $this->get_content()
		];
	}

	public function set_properties(array $properties): void
	{
		$this->set_id($properties['id']);
		$this->set_id_category($properties['id_category']);
		$this->set_creation_date(new Date($properties['creation_date'], Timezone::SERVER_TIMEZONE));
		if ($properties['approved'])
        {
			$this->approve();
        }
		else
        {
			$this->unapprove();
        }
		$this->set_writer($properties['writer']);
		$this->set_rewrited_writer($properties['rewrited_writer']);
		$this->set_content($properties['content']);

		$user = new User();
		if (!empty($properties['user_id']))
        {
			$user->set_properties($properties);
        }
		else
        {
			$user->init_visitor_user();
        }

		$this->set_author_user($user);
	}

	public function init_default_properties(int $id_category = Category::ROOT_CATEGORY): void
	{
		$this->id_category = $id_category;
		$this->author_user = AppContext::get_current_user();
		$this->creation_date = new Date();
		if (CategoriesAuthorizationsService::check_authorizations()->write())
        {
			$this->approve();
        }
		else
        {
			$this->unapprove();
        }
	}

	public function get_item_url(): string
	{
		return QuotesUrlBuilder::display_writer_items($this->rewrited_writer)->rel() . '#quotes-item-' . $this->id;
	}

	public function get_template_vars(): array
	{
		$category = $this->get_category();
		$content = FormatingHelper::second_parse($this->content);
		$rich_content = HooksService::execute_hook_display_action('faq', $content, $this->get_properties());
		$user = $this->get_author_user();
		$user_group_color = User::get_group_color($user->get_groups(), $user->get_level());

		return array_merge(
			Date::get_array_tpl_vars($this->creation_date,'date'),
			[
				// Conditions
				'C_APPROVED'         => $this->is_approved(),
				'C_CONTROLS'         => $this->is_authorized_to_edit() || $this->is_authorized_to_delete(),
				'C_EDIT'             => $this->is_authorized_to_edit(),
				'C_DELETE'           => $this->is_authorized_to_delete(),
				'C_USER_GROUP_COLOR' => !empty($user_group_color),

				// Item
				'ID'                  => $this->id,
				'WRITER_NAME'         => $this->writer,
				'CONTENT'             => $rich_content,
				'C_AUTHOR_EXISTS'     => $user->get_id() !== User::VISITOR_LEVEL,
				'AUTHOR_DISPLAY_NAME' => $user->get_display_name(),
				'USER_LEVEL_CLASS'    => UserService::get_level_class($user->get_level()),
				'USER_GROUP_COLOR'    => $user_group_color,

				// Category
				'C_ROOT_CATEGORY'      => $category->get_id() == Category::ROOT_CATEGORY,
				'CATEGORY_ID'          => $category->get_id(),
				'CATEGORY_NAME'        => $category->get_name(),
				'CATEGORY_DESCRIPTION' => $category->get_description(),
				'U_CATEGORY_THUMBNAIL' => $category->get_thumbnail()->rel(),
				'U_CATEGORY'           => QuotesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel(),
				'U_EDIT_CATEGORY'      => $category->get_id() == Category::ROOT_CATEGORY ? QuotesUrlBuilder::configuration()->rel() : CategoriesUrlBuilder::edit($category->get_id(), 'quotes')->rel(),

				// Item links
				'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($this->get_author_user()->get_id())->rel(),
				'U_AUTHOR_CONTRIB' => QuotesUrlBuilder::display_member_items($this->get_author_user()->get_id())->rel(),
				'U_WRITER'         => QuotesUrlBuilder::display_writer_items($this->rewrited_writer)->rel(),
				'U_ITEM'           => $this->get_item_url(),
				'U_EDIT'           => QuotesUrlBuilder::edit($this->id)->rel(),
				'U_DELETE'         => QuotesUrlBuilder::delete($this->id)->rel(),
			]
		);
	}
}
?>
