<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 24
 * @since       PHPBoost 4.0 - 2013 02 25
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class BugtrackerItem
{
	private int $id;
	private string $title;
	private string $rewrited_title;
	private string $content;

	private User $author_user;

	private Date $submit_date;
	private ?Date $fix_date;

	private string $status;
	private string $type;
	private string $category;
	private string $severity;
	private string $priority;

	private bool $reproductible;
	private string $reproduction_method;

	private string $detected_in;
	private string $fixed_in;

	private int $assigned_to_id;
	private string $assigned_user;

	const NEW_BUG     = 'new';
	const PENDING     = 'pending';
	const ASSIGNED    = 'assigned';
	const IN_PROGRESS = 'in_progress';
	const REJECTED    = 'rejected';
	const REOPEN      = 'reopen';
	const FIXED       = 'fixed';

	public function get_id(): int
	{
		return $this->id;
	}

	public function set_id(int $id): void
	{
		$this->id = $id;
	}

	public function get_title(): string
	{
		return $this->title;
	}

	public function set_title(string $title): void
	{
		$this->title = $title;
	}

	public function get_rewrited_title(): string
	{
		return $this->rewrited_title;
	}

	public function set_rewrited_title(string $rewrited_title): void
	{
		$this->rewrited_title = $rewrited_title;
	}

	public function get_content(): string
	{
		return $this->content;
	}

	public function set_content(string $content): void
	{
		$this->content = $content;
	}

	public function get_real_short_content(): string
	{
		return TextHelper::cut_string(@strip_tags(FormatingHelper::second_parse($this->content), '<br><br/>'), (int)BugtrackerConfig::NUMBER_CARACTERS_BEFORE_CUT);
	}

	public function get_author_user(): User
	{
		return $this->author_user;
	}

	public function set_author_user(User $user): void
	{
		$this->author_user = $user;
	}

	public function get_submit_date(): Date
	{
		return $this->submit_date;
	}

	public function set_submit_date(Date $submit_date): void
	{
		$this->submit_date = $submit_date;
	}

	public function get_fix_date(): Date
	{
		return $this->fix_date;
	}

	public function set_fix_date(?Date $fix_date): void
	{
		$this->fix_date = $fix_date;
	}

	public function get_status(): string
	{
		return $this->status;
	}

	public function set_status(string $status): void
	{
		$this->status = $status;
	}

	public function is_new(): bool
	{
		return $this->status == BugtrackerItem::NEW_BUG;
	}

	public function is_pending(): bool
	{
		return $this->status == BugtrackerItem::PENDING;
	}

	public function is_assigned(): bool
	{
		return $this->status == BugtrackerItem::ASSIGNED;
	}

	public function is_in_progress(): bool
	{
		return $this->status == BugtrackerItem::IN_PROGRESS;
	}

	public function is_rejected(): bool
	{
		return $this->status == BugtrackerItem::REJECTED;
	}

	public function is_reopen(): bool
	{
		return $this->status == BugtrackerItem::REOPEN;
	}

	public function is_fixed(): bool
	{
		return $this->status == BugtrackerItem::FIXED;
	}

	public function get_type(): string
	{
		return $this->type;
	}

	public function set_type(string $type): void
	{
		$this->type = $type;
	}

	public function get_category(): string
	{
		return $this->category;
	}

	public function set_category(string $category): void
	{
		$this->category = $category;
	}

	public function get_severity(): string
	{
		return $this->severity;
	}

	public function set_severity(string $severity): void
	{
		$this->severity = $severity;
	}

	public function get_priority(): string
	{
		return $this->priority;
	}

	public function set_priority(string $priority): void
	{
		$this->priority = $priority;
	}

	public function get_reproductible(): bool
	{
		return $this->reproductible;
	}

	public function set_reproductible(bool $reproductible): void
	{
		$this->reproductible = $reproductible;
	}

	public function is_reproductible(): bool
	{
		return $this->reproductible;
	}

	public function get_reproduction_method(): string
	{
		return $this->reproduction_method;
	}

	public function set_reproduction_method(string $reproduction_method): void
	{
		$this->reproduction_method = $reproduction_method;
	}

	public function get_detected_in(): string
	{
		return $this->detected_in;
	}

	public function set_detected_in(string $detected_in): void
	{
		$this->detected_in = $detected_in;
	}

	public function get_fixed_in(): string
	{
		return $this->fixed_in;
	}

	public function set_fixed_in(string $fixed_in): void
	{
		$this->fixed_in = $fixed_in;
	}

	public function get_assigned_to_id(): int
	{
		return $this->assigned_to_id;
	}

	public function set_assigned_to_id(int $assigned_to_id): void
	{
		$this->assigned_to_id = $assigned_to_id;
	}

	public function get_assigned_user(): string
	{
		return $this->assigned_user;
	}

	public function is_authorized_to_add(): bool
	{
		return BugtrackerAuthorizationsService::check_authorizations()->write();
	}

	public function is_authorized_to_edit(): bool
	{
		return BugtrackerAuthorizationsService::check_authorizations()->moderation() || (BugtrackerAuthorizationsService::check_authorizations()->write() && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id());
	}

	public function get_properties(): array
	{
		return [
			'id'                  => $this->get_id(),
			'title'               => $this->get_title(),
			'content'             => $this->get_content(),
			'author_id'           => $this->get_author_user()->get_id(),
			'submit_date'         => $this->get_submit_date() ? $this->get_submit_date()->get_timestamp() : 0,
			'fix_date'            => $this->get_fix_date() ? $this->get_fix_date()->get_timestamp() : 0,
			'status'              => $this->get_status(),
			'type'                => $this->get_type(),
			'category'            => $this->get_category(),
			'severity'            => $this->get_severity(),
			'priority'            => $this->get_priority(),
			'reproductible'       => $this->get_reproductible(),
			'reproduction_method' => $this->get_reproduction_method(),
			'detected_in'         => $this->get_detected_in(),
			'fixed_in'            => $this->get_fixed_in(),
			'assigned_to_id'      => $this->get_assigned_to_id(),
		];
	}

	public function set_properties(array $properties)
	{
		$this->id                  = $properties['id'];
		$this->title               = $properties['title'];
		$this->rewrited_title      = Url::encode_rewrite($properties['title']);
		$this->content             = $properties['content'];
		$this->submit_date         = !empty($properties['submit_date']) ? new Date( $properties['submit_date'], Timezone::SERVER_TIMEZONE) : null;
		$this->fix_date            = !empty($properties['fix_date']) ? new Date($properties['fix_date'], Timezone::SERVER_TIMEZONE) : null;
		$this->status              = $properties['status'];
		$this->type                = $properties['type'];
		$this->category            = $properties['category'];
		$this->severity            = $properties['severity'];
		$this->priority            = $properties['priority'];
		$this->reproductible       = $properties['reproductible'];
		$this->reproduction_method = $properties['reproduction_method'];
		$this->detected_in         = $properties['detected_in'];
		$this->fixed_in            = $properties['fixed_in'];
		$this->assigned_to_id      = $properties['assigned_to_id'];

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

	public function init_default_properties(): void
	{
		$config = BugtrackerConfig::load();

		$this->submit_date    = new Date();
		$this->fix_date       = new Date();
		$this->status         = BugtrackerItem::NEW_BUG;
		$this->author_user    = AppContext::get_current_user();
		$this->content        = $config->get_content_value();
		$this->type           = $config->get_default_type();
		$this->category       = $config->get_default_category();
		$this->severity       = $config->get_default_severity();
		$this->priority       = $config->get_default_priority();
		$this->detected_in    = $config->get_default_version();
		$this->fixed_in       = 0;
		$this->assigned_to_id = 0;
		$this->reproductible  = true;
	}

	public function clean_fix_date(): void
	{
		$this->fix_date = null;
	}

	public function get_item_url(): string
	{
		return BugtrackerUrlBuilder::detail($this->id . '-' . $this->rewrited_title)->rel();
	}

	public function get_template_vars(): array
	{
		$config      = BugtrackerConfig::load();
		$types       = $config->get_types();
		$categories  = $config->get_categories();
		$severities  = $config->get_severities();
		$priorities  = $config->get_priorities();
		$versions    = $config->get_versions();
		$status_list = $config->get_status_list();

		$lang = LangLoader::get_all_langs('bugtracker');

		$user = $this->get_author_user();
		$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
		$comments_number = CommentsService::get_comments_number('bugtracker', $this->id);

		return array_merge(
			Date::get_array_tpl_vars($this->submit_date, 'submit_date'),
			Date::get_array_tpl_vars($this->fix_date, 'fix_date'),
			[
			'C_PROGRESS'              => $config->is_progress_bar_displayed(),
			'C_FIX_DATE'              => $this->fix_date != null,
			'C_FIXED_IN'              => $this->detected_in,
			'C_FIXED'                 => $this->is_fixed(),
			'C_PENDING'               => $this->is_pending(),
			'C_REPRODUCTIBLE'         => $this->is_reproductible(),
			'C_REPRODUCTION_METHOD'   => $this->reproduction_method,
			'C_AUTHOR_GROUP_COLOR'    => !empty($user_group_color),
			'C_AUTHOR_EXISTS'         => $user->get_id() !== User::VISITOR_LEVEL,
			'C_MORE_THAN_ONE_COMMENT' => $comments_number > 1,

			//Bug
			'ID'                  => $this->id,
			'TITLE'               => $this->title,
			'CONTENT'             => FormatingHelper::second_parse($this->content),
			'TYPE'                => (isset($types[$this->type])) ? stripslashes($types[$this->type]) : $lang['notice.none'],
			'CATEGORY'            => (isset($categories[$this->category])) ? stripslashes($categories[$this->category]) : $lang['notice.none_e'],
			'SEVERITY'            => (isset($severities[$this->severity])) ? stripslashes($severities[$this->severity]['name']) : $lang['notice.none'],
			'PRIORITY'            => (isset($priorities[$this->priority])) ? stripslashes($priorities[$this->priority]) : $lang['notice.none_e'],
			'DETECTED_IN'         => (isset($versions[$this->detected_in])) ? stripslashes($versions[$this->detected_in]['name']) : $lang['notice.not_defined'],
			'FIXED_IN'            => (isset($versions[$this->fixed_in])) ? stripslashes($versions[$this->fixed_in]['name']) : $lang['notice.not_defined'],
			'PROGRESS'            => $status_list[$this->status],
			'STATUS'              => LangLoader::get_message('status.' . $this->status, 'common', 'bugtracker'),
			'REPRODUCTION_METHOD' => FormatingHelper::second_parse($this->reproduction_method),
			'AUTHOR'              => $user->get_display_name(),
			'AUTHOR_LEVEL_CLASS'  => UserService::get_level_class($user->get_level()),
			'AUTHOR_GROUP_COLOR'  => $user_group_color,
			'COMMENTS_NUMBER'     => $comments_number,

			'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($user->get_id())->rel(),
			'U_LINK'           => $this->get_item_url(),
			'U_HISTORY'        => BugtrackerUrlBuilder::history($this->id)->rel(),
			'U_COMMENTS'       => BugtrackerUrlBuilder::detail($this->id . '-' . $this->rewrited_title . '#comments-list')->rel()
		]);
	}
}
?>
