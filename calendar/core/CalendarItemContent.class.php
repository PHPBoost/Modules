<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 24
 * @since       PHPBoost 4.0 - 2013 10 29
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @author      Mipel <mipel@phpboost.com>
*/

class CalendarItemContent
{
	private int $id = 0;
	private int $id_category;
	private string $title = '';
	private string $rewrited_title;
	private string $content;
	private Url|string $thumbnail_url;

	private mixed $location = null;
	private bool $map_displayed;

	private bool $cancelled;
	private bool $approved;

	private Date $creation_date;
	private Date $update_date;
	private User $author_user;

	private bool $registration_authorized;
	private string $registration_limit;
	private int $max_registered_members;
	private bool $last_registration_date_enabled;
	private ?Date $last_registration_date = null;
	private array $register_authorizations;

	private int $repeat_number;
	private string $repeat_type;

	const NEVER   = 'never';
	const DAILY   = 'daily';
	const WEEKLY  = 'weekly';
	const MONTHLY = 'monthly';
	const YEARLY  = 'yearly';

	const DISPLAY_REGISTERED_USERS_AUTHORIZATION = 1;
	const REGISTER_AUTHORIZATION                 = 2;

	const THUMBNAIL_URL = '/templates/__default__/images/default_item.webp';

	public function get_id(): int
	{
		return $this->id;
	}

	public function set_id(int $id): void
	{
		$this->id = $id;
	}

	public function set_id_category(int $id_category): void
	{
		$this->id_category = $id_category;
	}

	public function get_id_category(): int
	{
		return $this->id_category;
	}

	public function get_category(): mixed
	{
		return CategoriesService::get_categories_manager('calendar')->get_categories_cache()->get_category($this->id_category);
	}

	public function set_title(string $title): void
	{
		$this->title = $title;
	}

	public function get_title(): string
	{
		return $this->title;
	}

	public function set_rewrited_title(string $rewrited_title): void
	{
		$this->rewrited_title = $rewrited_title;
	}

	public function get_rewrited_title(): string
	{
		return $this->rewrited_title;
	}

	public function set_content(string $content): void
	{
		$this->content = $content;
	}

	public function get_content(): string
	{
		return $this->content;
	}

	public function get_summary(): string
	{
		return TextHelper::cut_string(@strip_tags(FormatingHelper::second_parse($this->content), '<br><br/>'), CalendarConfig::load()->get_characters_number_to_cut());
	}

	public function set_thumbnail(Url|string $thumbnail): void
	{
		$this->thumbnail_url = $thumbnail;
	}

	public function get_thumbnail(): Url|string
	{
		if (!$this->thumbnail_url instanceof Url)
        {
			return new Url($this->thumbnail_url == FormFieldThumbnail::DEFAULT_VALUE ? FormFieldThumbnail::get_default_thumbnail_url(self::THUMBNAIL_URL) : $this->thumbnail_url);
        }

		return $this->thumbnail_url;
	}

	public function has_thumbnail(): bool
	{
		$thumbnail = ($this->thumbnail_url instanceof Url) ? $this->thumbnail_url->rel() : $this->thumbnail_url;
		return !empty($thumbnail);
	}

	public function set_location(mixed $location): void
	{
		$this->location = $location;
	}

	public function get_location(): mixed
	{
		return $this->location;
	}

	public function display_map(): void
	{
		$this->map_displayed = true;
	}

	public function hide_map(): void
	{
		$this->map_displayed = false;
	}

	public function is_map_displayed(): bool
	{
		return $this->map_displayed;
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

	public function set_creation_date(Date $creation_date): void
	{
		$this->creation_date = $creation_date;
	}

	public function get_creation_date(): Date
	{
		return $this->creation_date;
	}

	public function set_update_date(Date $update_date): void
	{
		$this->update_date = $update_date;
	}

	public function get_update_date(): Date
	{
		return $this->update_date;
	}

	public function has_update_date(): bool
	{
		return $this->update_date !== null && $this->update_date > $this->creation_date;
	}

	public function set_author_user(User $author): void
	{
		$this->author_user = $author;
	}

	public function get_author_user(): User
	{
		return $this->author_user;
	}

	public function authorize_registration(): void
	{
		$this->registration_authorized = true;
	}

	public function unauthorize_registration(): void
	{
		$this->registration_authorized = false;
	}

	public function is_registration_authorized(): bool
	{
		return $this->registration_authorized;
	}

	public function limit_registration(): void
	{
		$this->registration_limit = true;
	}

	public function unlimit_registration(): void
	{
		$this->registration_limit = false;
	}

	public function is_registration_limited(): bool
	{
		return $this->registration_limit;
	}

	public function set_max_registered_members(int $max_registered_members): void
	{
		$this->max_registered_members = $max_registered_members;
	}

	public function get_max_registered_members(): int
	{
		return $this->max_registered_members;
	}

	public function enable_last_registration_date(): void
	{
		$this->last_registration_date_enabled = true;
	}

	public function disable_last_registration_date(): void
	{
		$this->last_registration_date_enabled = false;
	}

	public function is_last_registration_date_enabled(): bool
	{
		return $this->last_registration_date_enabled;
	}

	public function set_last_registration_date(?Date $last_registration_date): void
	{
		$this->last_registration_date = $last_registration_date;
	}

	public function get_last_registration_date(): ?Date
	{
		return $this->last_registration_date;
	}

	public function set_register_authorizations(array $authorizations): void
	{
		$this->register_authorizations = $authorizations;
	}

	public function get_register_authorizations(): array
	{
		return $this->register_authorizations;
	}

	public function is_authorized_to_display_registered_users(): bool
	{
		return $this->registration_authorized && AppContext::get_current_user()->check_auth($this->register_authorizations, self::DISPLAY_REGISTERED_USERS_AUTHORIZATION);
	}

	public function is_authorized_to_register(): bool
	{
		return $this->registration_authorized && AppContext::get_current_user()->check_auth($this->register_authorizations, self::REGISTER_AUTHORIZATION);
	}

	public function set_repeat_number(int $number): void
	{
		$this->repeat_number = $number;
	}

	public function get_repeat_number(): int
	{
		return $this->repeat_number;
	}

	public function set_repeat_type(string $type): void
	{
		$this->repeat_type = $type;
	}

	public function get_repeat_type(): string
	{
		return $this->repeat_type;
	}

	public function is_repeatable(): bool
	{
		return $this->repeat_type != self::NEVER;
	}

	public function cancel(): void
	{
		$this->cancelled = true;
	}

	public function uncancel(): void
	{
		$this->cancelled = false;
	}

	public function is_cancelled(): bool
	{
		return $this->cancelled;
	}

	public function get_properties(): array
	{
		return [
			'id'                      => $this->get_id(),
			'id_category'             => $this->get_id_category(),
			'title'                   => $this->get_title(),
			'rewrited_title'          => $this->get_rewrited_title(),
			'content'                 => $this->get_content(),
			'thumbnail'               => $this->get_thumbnail()->relative(),
			'location'                => $this->get_location(),
			'cancelled'               => (int)$this->is_cancelled(),
			'approved'                => (int)$this->is_approved(),
			'map_displayed'           => (int)$this->is_map_displayed(),
			'creation_date'           => $this->get_creation_date()->get_timestamp(),
			'update_date'             => $this->get_update_date() !== null ? $this->get_update_date()->get_timestamp() : $this->get_creation_date()->get_timestamp(),
			'author_user_id'          => $this->get_author_user()->get_id(),
			'registration_authorized' => (int)$this->is_registration_authorized(),
			'registration_limit'      => (int)$this->is_registration_limited(),
			'max_registered_members'  => (int)$this->get_max_registered_members(),
			'last_registration_date'  => (int)($this->get_last_registration_date() !== null ? $this->get_last_registration_date()->get_timestamp() : ''),
			'register_authorizations' => TextHelper::serialize($this->get_register_authorizations()),
			'repeat_number'           => $this->get_repeat_number(),
			'repeat_type'             => $this->get_repeat_type()
		];
	}

	public function set_properties(array $properties)
	{
		$this->id             = $properties['id'];
		$this->id_category    = $properties['id_category'];
		$this->title          = $properties['title'];
		$this->rewrited_title = $properties['rewrited_title'];
		$this->content        = $properties['content'];
		$this->thumbnail_url  = $properties['thumbnail'];
		$this->location       = $properties['location'];

		if ($properties['map_displayed'])
        {
			$this->display_map();
        }
		else
        {
			$this->hide_map();
        }

		if ($properties['approved'])
        {
			$this->approve();
        }
		else
        {
			$this->unapprove();
        }

		if ($properties['cancelled'])
        {
			$this->cancel();
        }
		else
        {
			$this->uncancel();
        }

		if ($properties['registration_authorized'])
        {
			$this->authorize_registration();
        }
		else
        {
			$this->unauthorize_registration();
        }

		if ($properties['registration_limit'])
        {
			$this->limit_registration();
        }
		else
        {
			$this->unlimit_registration();
        }

		$this->max_registered_members = $properties['max_registered_members'];
		$this->last_registration_date_enabled = !empty($properties['last_registration_date']);
		$this->last_registration_date = !empty($properties['last_registration_date']) ? new Date($properties['last_registration_date'], Timezone::SERVER_TIMEZONE) : null;
		$this->register_authorizations = !empty($properties['register_authorizations']) ? TextHelper::unserialize($properties['register_authorizations']) : ['r0' => 3, 'r1' => 3];

		$this->creation_date = new Date($properties['creation_date'], Timezone::SERVER_TIMEZONE);
		$this->update_date = new Date($properties['update_date'], Timezone::SERVER_TIMEZONE);

		$this->repeat_number = $properties['repeat_number'];
		$this->repeat_type = $properties['repeat_type'];

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
		$this->id_category   = $id_category;
        $this->content       = CalendarConfig::load()->get_default_content();
		$this->author_user   = AppContext::get_current_user();
		$this->creation_date = new Date();
		$this->thumbnail_url = FormFieldThumbnail::DEFAULT_VALUE;

		$this->registration_authorized        = false;
		$this->registration_limit             = false;
		$this->max_registered_members         = 0;
		$this->last_registration_date_enabled = false;
		$this->register_authorizations        = ['r0' => 3, 'r1' => 3];

		$this->hide_map();

		if (CategoriesAuthorizationsService::check_authorizations()->write())
        {
			$this->approve();
        }
		else
        {
			$this->unapprove();
        }

		$this->repeat_number = 1;
		$this->repeat_type   = self::NEVER;
		$this->cancelled     = false;
	}
}
?>
