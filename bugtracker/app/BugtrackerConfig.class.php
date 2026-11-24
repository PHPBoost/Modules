<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 24
 * @since       PHPBoost 3.0 - 2012 09 06
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class BugtrackerConfig extends AbstractConfigData
{
	const ITEMS_PER_PAGE            = 'items_per_page';
	const REJECTED_BUG_COLOR        = 'rejected_bug_color';
	const FIXED_BUG_COLOR           = 'fixed_bug_color';
	const ROADMAP_ENABLED           = 'roadmap_enabled';
	const STATS_ENABLED             = 'stats_enabled';
	const STATS_TOP_POSTERS_ENABLED = 'stats_top_posters_enabled';
	const STATS_TOP_POSTERS_NUMBER  = 'stats_top_posters_number';
	const PROGRESS_BAR_ENABLED      = 'progress_bar_enabled';
	const ADMIN_ALERTS_ENABLED      = 'admin_alerts_enabled';
	const ADMIN_ALERTS_LEVELS       = 'admin_alerts_levels';
	const ADMIN_ALERTS_FIX_ACTION   = 'admin_alerts_fix_action';
	const RESTRICT_DISPLAY_TO_OWN_ELEMENTS_ENABLED = 'restrict_display_to_own_elements_enabled';

    const PM_ENABLED             = 'pm_enabled';
	const PM_COMMENT_ENABLED     = 'pm_comment_enabled';
	const PM_IN_PROGRESS_ENABLED = 'pm_in_progress_enabled';
	const PM_FIX_ENABLED         = 'pm_fix_enabled';
	const PM_PENDING_ENABLED     = 'pm_pending_enabled';
	const PM_ASSIGN_ENABLED      = 'pm_assign_enabled';
	const PM_EDIT_ENABLED        = 'pm_edit_enabled';
	const PM_REJECT_ENABLED      = 'pm_reject_enabled';
	const PM_REOPEN_ENABLED      = 'pm_reopen_enabled';
	const PM_DELETE_ENABLED      = 'pm_delete_enabled';

    const CONTENT_VALUE    = 'content_value';
	const TYPES            = 'types';
	const CATEGORIES       = 'categories';
	const SEVERITIES       = 'severities';
	const PRIORITIES       = 'priorities';
	const VERSIONS         = 'versions';
	const DEFAULT_TYPE     = 'default_type';
	const DEFAULT_CATEGORY = 'default_category';
	const DEFAULT_PRIORITY = 'default_priority';
	const DEFAULT_SEVERITY = 'default_severity';
	const DEFAULT_VERSION  = 'default_version';

    const TYPE_MANDATORY                = 'type_mandatory';
	const CATEGORY_MANDATORY            = 'category_mandatory';
	const PRIORITY_MANDATORY            = 'priority_mandatory';
	const SEVERITY_MANDATORY            = 'severity_mandatory';
	const DETECTED_IN_VERSION_MANDATORY = 'detected_in_version_mandatory';

    const DISPLAY_TYPE_COLUMN        = 'display_type_column';
	const DISPLAY_CATEGORY_COLUMN    = 'display_category_column';
	const DISPLAY_PRIORITY_COLUMN    = 'display_priority_column';
	const DISPLAY_DETECTED_IN_COLUMN = 'display_detected_in_column';

    const AUTHORIZATIONS = 'authorizations';
	const STATUS_LIST    = 'status_list';

	const NUMBER_CARACTERS_BEFORE_CUT = 150;

	//Admin alerts fix type
	const FIX    = 'fix';
	const DELETE = 'delete';

	public function get_items_per_page(): int
	{
		return $this->get_property(self::ITEMS_PER_PAGE);
	}

	public function set_items_per_page(int $value): void
	{
		$this->set_property(self::ITEMS_PER_PAGE, $value);
	}

	public function get_rejected_bug_color(): string
	{
		return $this->get_property(self::REJECTED_BUG_COLOR);
	}

	public function set_rejected_bug_color(string $value): void
	{
		$this->set_property(self::REJECTED_BUG_COLOR, $value);
	}

	public function get_fixed_bug_color(): string
	{
		return $this->get_property(self::FIXED_BUG_COLOR);
	}

	public function set_fixed_bug_color(string $value): void
	{
		$this->set_property(self::FIXED_BUG_COLOR, $value);
	}

	public function enable_roadmap(): void
	{
		$this->set_property(self::ROADMAP_ENABLED, true);
	}

	public function disable_roadmap(): void
	{
		$this->set_property(self::ROADMAP_ENABLED, false);
	}

	public function is_roadmap_enabled(): bool
	{
		return $this->get_property(self::ROADMAP_ENABLED);
	}

	public function enable_restrict_display_to_own_elements(): void
	{
		$this->set_property(self::RESTRICT_DISPLAY_TO_OWN_ELEMENTS_ENABLED, true);
	}

	public function disable_restrict_display_to_own_elements(): void
	{
		$this->set_property(self::RESTRICT_DISPLAY_TO_OWN_ELEMENTS_ENABLED, false);
	}

	public function is_restrict_display_to_own_elements_enabled(): bool
	{
		return $this->get_property(self::RESTRICT_DISPLAY_TO_OWN_ELEMENTS_ENABLED);
	}

	public function is_roadmap_displayed(): bool
	{
		return $this->get_property(self::ROADMAP_ENABLED) && $this->get_property(self::VERSIONS);
	}

	public function enable_stats(): void
	{
		$this->set_property(self::STATS_ENABLED, true);
	}

	public function disable_stats(): void
	{
		$this->set_property(self::STATS_ENABLED, false);
	}

	public function are_stats_enabled(): bool
	{
		return $this->get_property(self::STATS_ENABLED);
	}

	public function enable_stats_top_posters(): void
	{
		$this->set_property(self::STATS_TOP_POSTERS_ENABLED, true);
	}

	public function disable_stats_top_posters(): void
	{
		$this->set_property(self::STATS_TOP_POSTERS_ENABLED, false);
	}

	public function are_stats_top_posters_enabled(): bool
	{
		return $this->get_property(self::STATS_TOP_POSTERS_ENABLED);
	}

	public function get_stats_top_posters_number(): int
	{
		return $this->get_property(self::STATS_TOP_POSTERS_NUMBER);
	}

	public function set_stats_top_posters_number(int $value): void
	{
		$this->set_property(self::STATS_TOP_POSTERS_NUMBER, $value);
	}

	public function display_progress_bar(): void
	{
		$this->set_property(self::PROGRESS_BAR_ENABLED, true);
	}

	public function hide_progress_bar(): void
	{
		$this->set_property(self::PROGRESS_BAR_ENABLED, false);
	}

	public function is_progress_bar_displayed(): bool
	{
		return $this->get_property(self::PROGRESS_BAR_ENABLED);
	}

	public function enable_admin_alerts(): void
	{
		$this->set_property(self::ADMIN_ALERTS_ENABLED, true);
	}

	public function disable_admin_alerts(): void
	{
		$this->set_property(self::ADMIN_ALERTS_ENABLED, false);
	}

	public function are_admin_alerts_enabled(): bool
	{
		return $this->get_property(self::ADMIN_ALERTS_ENABLED);
	}

	public function get_admin_alerts_levels(): array
	{
		return $this->get_property(self::ADMIN_ALERTS_LEVELS);
	}

	public function set_admin_alerts_levels(array $array): void
	{
		$this->set_property(self::ADMIN_ALERTS_LEVELS, $array);
	}

	/** when closing the admin alert (delete or fix) */
	public function get_admin_alerts_fix_action(): string
	{
		return $this->get_property(self::ADMIN_ALERTS_FIX_ACTION);
	}

	public function set_admin_alerts_fix_action(string $value): void
	{
		$this->set_property(self::ADMIN_ALERTS_FIX_ACTION, $value);
	}

	public function is_admin_alerts_fix_action_fix(): bool
	{
		return $this->get_property(self::ADMIN_ALERTS_FIX_ACTION) == self::FIX;
	}

	public function enable_pm(): void
	{
		$this->set_property(self::PM_ENABLED, true);
	}

	public function disable_pm(): void
	{
		$this->set_property(self::PM_ENABLED, false);
	}

	public function are_pm_enabled(): bool
	{
		return $this->get_property(self::PM_ENABLED);
	}

	public function enable_pm_comment(): void
	{
		$this->set_property(self::PM_COMMENT_ENABLED, true);
	}

	public function disable_pm_comment(): void
	{
		$this->set_property(self::PM_COMMENT_ENABLED, false);
	}

	public function are_pm_comment_enabled(): bool
	{
		return $this->get_property(self::PM_COMMENT_ENABLED);
	}

	public function enable_pm_fix(): void
	{
		$this->set_property(self::PM_FIX_ENABLED, true);
	}

	public function disable_pm_fix(): void
	{
		$this->set_property(self::PM_FIX_ENABLED, false);
	}

	public function are_pm_fix_enabled(): bool
	{
		return $this->get_property(self::PM_FIX_ENABLED);
	}

	public function enable_pm_in_progress(): void
	{
		$this->set_property(self::PM_IN_PROGRESS_ENABLED, true);
	}

	public function disable_pm_in_progress(): void
	{
		$this->set_property(self::PM_IN_PROGRESS_ENABLED, false);
	}

	public function are_pm_in_progress_enabled(): bool
	{
		return $this->get_property(self::PM_IN_PROGRESS_ENABLED);
	}

	public function enable_pm_pending(): void
	{
		$this->set_property(self::PM_PENDING_ENABLED, true);
	}

	public function disable_pm_pending(): void
	{
		$this->set_property(self::PM_PENDING_ENABLED, false);
	}

	public function are_pm_pending_enabled(): bool
	{
		return $this->get_property(self::PM_PENDING_ENABLED);
	}

	public function enable_pm_assign(): void
	{
		$this->set_property(self::PM_ASSIGN_ENABLED, true);
	}

	public function disable_pm_assign(): void
	{
		$this->set_property(self::PM_ASSIGN_ENABLED, false);
	}

	public function are_pm_assign_enabled(): bool
	{
		return $this->get_property(self::PM_ASSIGN_ENABLED);
	}

	public function enable_pm_edit(): void
	{
		$this->set_property(self::PM_EDIT_ENABLED, true);
	}

	public function disable_pm_edit(): void
	{
		$this->set_property(self::PM_EDIT_ENABLED, false);
	}

	public function are_pm_edit_enabled(): bool
	{
		return $this->get_property(self::PM_EDIT_ENABLED);
	}

	public function enable_pm_reject(): void
	{
		$this->set_property(self::PM_REJECT_ENABLED, true);
	}

	public function disable_pm_reject(): void
	{
		$this->set_property(self::PM_REJECT_ENABLED, false);
	}

	public function are_pm_reject_enabled(): bool
	{
		return $this->get_property(self::PM_REJECT_ENABLED);
	}

	public function enable_pm_reopen(): void
	{
		$this->set_property(self::PM_REOPEN_ENABLED, true);
	}

	public function disable_pm_reopen(): void
	{
		$this->set_property(self::PM_REOPEN_ENABLED, false);
	}

	public function are_pm_reopen_enabled(): bool
	{
		return $this->get_property(self::PM_REOPEN_ENABLED);
	}

	public function enable_pm_delete(): void
	{
		$this->set_property(self::PM_DELETE_ENABLED, true);
	}

	public function disable_pm_delete(): void
	{
		$this->set_property(self::PM_DELETE_ENABLED, false);
	}

	public function are_pm_delete_enabled(): bool
	{
		return $this->get_property(self::PM_DELETE_ENABLED);
	}

	public function get_content_value(): string
	{
		return $this->get_property(self::CONTENT_VALUE);
	}

	public function set_content_value(string $value): void
	{
		$this->set_property(self::CONTENT_VALUE, $value);
	}

	public function get_types(): array
	{
		return $this->get_property(self::TYPES);
	}

	public function set_types(array $array): void
	{
		$this->set_property(self::TYPES, $array);
	}

	public function get_categories(): array
	{
		return $this->get_property(self::CATEGORIES);
	}

	public function set_categories(array $array): void
	{
		$this->set_property(self::CATEGORIES, $array);
	}

	public function get_severities(): array
	{
		return $this->get_property(self::SEVERITIES);
	}

	public function set_severities(array $array): void
	{
		$this->set_property(self::SEVERITIES, $array);
	}

	public function get_priorities(): array
	{
		return $this->get_property(self::PRIORITIES);
	}

	public function set_priorities(array $array): void
	{
		$this->set_property(self::PRIORITIES, $array);
	}

	public function get_versions_detected(): array
	{
		$versions = $this->get_property(self::VERSIONS);
		$versions_detected_in = [];
		foreach ($versions as $key => $version)
		{
			if ($version['detected_in'] == true)
            {
				$versions_detected_in[$key] = $version;
            }
		}
		return $versions_detected_in;
	}

	public function get_versions_fix(): array
	{
		$now = new Date();
		$versions = $this->get_property(self::VERSIONS);
		$versions_fix = [];
		foreach ($versions as $key => $version)
		{
			$release_date = !empty($version['release_date']) && is_numeric($version['release_date']) ? new Date($version['release_date'], Timezone::SERVER_TIMEZONE) : null;

			if (empty($release_date) || $release_date->get_timestamp() >= $now->get_timestamp())
            {
				$versions_fix[$key] = $version;
            }
		}
		return $versions_fix;
	}

	public function get_versions(): array
	{
		return $this->get_property(self::VERSIONS);
	}

	public function set_versions(array $array): void
	{
		$this->set_property(self::VERSIONS, $array);
	}

	public function get_default_type(): int
	{
		return $this->get_property(self::DEFAULT_TYPE);
	}

	public function set_default_type(int $value): void
	{
		$this->set_property(self::DEFAULT_TYPE, $value);
	}

	public function get_default_category(): int
	{
		return $this->get_property(self::DEFAULT_CATEGORY);
	}

	public function set_default_category(int $value): void
	{
		$this->set_property(self::DEFAULT_CATEGORY, $value);
	}

	public function get_default_severity(): int
	{
		return $this->get_property(self::DEFAULT_SEVERITY);
	}

	public function set_default_severity(int $value): void
	{
		$this->set_property(self::DEFAULT_SEVERITY, $value);
	}

	public function get_default_priority(): int
	{
		return $this->get_property(self::DEFAULT_PRIORITY);
	}

	public function set_default_priority(int $value): void
	{
		$this->set_property(self::DEFAULT_PRIORITY, $value);
	}

	public function get_default_version(): int
	{
		return $this->get_property(self::DEFAULT_VERSION);
	}

	public function set_default_version(int $value): void
	{
		$this->set_property(self::DEFAULT_VERSION, $value);
	}

	public function type_mandatory(): void
	{
		$this->set_property(self::TYPE_MANDATORY, true);
	}

	public function type_not_mandatory(): void
	{
		$this->set_property(self::TYPE_MANDATORY, false);
	}

	public function is_type_mandatory(): bool
	{
		return $this->get_property(self::TYPE_MANDATORY);
	}

	public function category_mandatory(): void
	{
		$this->set_property(self::CATEGORY_MANDATORY, true);
	}

	public function category_not_mandatory(): void
	{
		$this->set_property(self::CATEGORY_MANDATORY, false);
	}

	public function is_category_mandatory(): bool
	{
		return $this->get_property(self::CATEGORY_MANDATORY);
	}

	public function severity_mandatory(): void
	{
		$this->set_property(self::SEVERITY_MANDATORY, true);
	}

	public function severity_not_mandatory(): void
	{
		$this->set_property(self::SEVERITY_MANDATORY, false);
	}

	public function is_severity_mandatory(): bool
	{
		return $this->get_property(self::SEVERITY_MANDATORY);
	}

	public function priority_mandatory(): void
	{
		$this->set_property(self::PRIORITY_MANDATORY, true);
	}

	public function priority_not_mandatory(): void
	{
		$this->set_property(self::PRIORITY_MANDATORY, false);
	}

	public function is_priority_mandatory(): bool
	{
		return $this->get_property(self::PRIORITY_MANDATORY);
	}

	public function detected_in_version_mandatory(): void
	{
		$this->set_property(self::DETECTED_IN_VERSION_MANDATORY, true);
	}

	public function detected_in_version_not_mandatory(): void
	{
		$this->set_property(self::DETECTED_IN_VERSION_MANDATORY, false);
	}

	public function is_detected_in_version_mandatory(): bool
	{
		return $this->get_property(self::DETECTED_IN_VERSION_MANDATORY);
	}

	public function display_type_column(): void
	{
		$this->set_property(self::DISPLAY_TYPE_COLUMN, true);
	}

	public function hide_type_column(): void
	{
		$this->set_property(self::DISPLAY_TYPE_COLUMN, false);
	}

	public function is_type_column_displayed(): bool
	{
		return $this->get_property(self::DISPLAY_TYPE_COLUMN);
	}

	public function display_category_column(): void
	{
		$this->set_property(self::DISPLAY_CATEGORY_COLUMN, true);
	}

	public function hide_category_column(): void
	{
		$this->set_property(self::DISPLAY_CATEGORY_COLUMN, false);
	}

	public function is_category_column_displayed(): bool
	{
		return $this->get_property(self::DISPLAY_CATEGORY_COLUMN);
	}

	public function display_priority_column(): void
	{
		$this->set_property(self::DISPLAY_PRIORITY_COLUMN, true);
	}

	public function hide_priority_column(): void
	{
		$this->set_property(self::DISPLAY_PRIORITY_COLUMN, false);
	}

	public function is_priority_column_displayed(): bool
	{
		return $this->get_property(self::DISPLAY_PRIORITY_COLUMN);
	}

	public function display_detected_in_column(): void
	{
		$this->set_property(self::DISPLAY_DETECTED_IN_COLUMN, true);
	}

	public function hide_detected_in_column(): void
	{
		$this->set_property(self::DISPLAY_DETECTED_IN_COLUMN, false);
	}

	public function is_detected_in_column_displayed(): bool
	{
		return $this->get_property(self::DISPLAY_DETECTED_IN_COLUMN);
	}

	public function get_authorizations(): array
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}

	public function set_authorizations(array $array): void
	{
		$this->set_property(self::AUTHORIZATIONS, $array);
	}

	public function get_status_list(): array
	{
		return $this->get_property(self::STATUS_LIST);
	}

	public function set_status_list(array $array): void
	{
		$this->set_property(self::STATUS_LIST, $array);
	}

	public function get_default_values(): array
	{
		$lang = LangLoader::get_all_langs('bugtracker');

		return [
			self::ITEMS_PER_PAGE     => 20,
			self::REJECTED_BUG_COLOR => '#f8465e',
			self::FIXED_BUG_COLOR    => '#afffa2',

            self::ROADMAP_ENABLED           => false,
			self::STATS_ENABLED             => true,
			self::STATS_TOP_POSTERS_ENABLED => true,
			self::STATS_TOP_POSTERS_NUMBER  => 10,
			self::PROGRESS_BAR_ENABLED      => true,
			self::RESTRICT_DISPLAY_TO_OWN_ELEMENTS_ENABLED => false,

            self::ADMIN_ALERTS_ENABLED    => true,
			self::ADMIN_ALERTS_LEVELS     => ['2', '3'],
			self::ADMIN_ALERTS_FIX_ACTION => self::FIX,

            self::PM_ENABLED             => true,
			self::PM_COMMENT_ENABLED     => true,
			self::PM_IN_PROGRESS_ENABLED => true,
			self::PM_FIX_ENABLED         => true,
			self::PM_PENDING_ENABLED     => true,
			self::PM_ASSIGN_ENABLED      => true,
			self::PM_EDIT_ENABLED        => true,
			self::PM_REJECT_ENABLED      => true,
			self::PM_REOPEN_ENABLED      => true,
			self::PM_DELETE_ENABLED      => true,

            self::CONTENT_VALUE => '',

            self::TYPES      => [1 => $lang['types.anomaly'], $lang['types.evolution']],
			self::CATEGORIES => [1 => $lang['categories.kernel'], $lang['categories.module'], $lang['categories.graphism'], $lang['categories.installation']],
			self::SEVERITIES => [1 => ['name' => $lang['severities.minor'], 'color' => '#e8ffa2'], ['name' => $lang['severities.major'], 'color' => '#feebbc'], ['name' => $lang['severities.critical'], 'color' => '#fdbbbb']],
			self::PRIORITIES => [1 => $lang['priorities.very_low'], $lang['priorities.low'], $lang['priorities.normal'], $lang['priorities.high'], $lang['priorities.urgent']],
			self::VERSIONS   => [],

            self::DEFAULT_TYPE     => 1,
			self::DEFAULT_CATEGORY => 0,
			self::DEFAULT_SEVERITY => 1,
			self::DEFAULT_PRIORITY => 3,
			self::DEFAULT_VERSION  => 0,
			self::TYPE_MANDATORY     => false,
			self::CATEGORY_MANDATORY => true,
			self::SEVERITY_MANDATORY => false,
			self::PRIORITY_MANDATORY => true,
			self::DETECTED_IN_VERSION_MANDATORY => false,

            self::DISPLAY_TYPE_COLUMN          => false,
			self::DISPLAY_CATEGORY_COLUMN      => false,
			self::DISPLAY_PRIORITY_COLUMN      => false,
			self::DISPLAY_DETECTED_IN_COLUMN   => false,

            self::AUTHORIZATIONS => ['r0' => 3, 'r1' => 15],

            self::STATUS_LIST => [
                BugtrackerItem::NEW_BUG     => 0,
                BugtrackerItem::PENDING     => 0,
                BugtrackerItem::ASSIGNED    => 20,
                BugtrackerItem::IN_PROGRESS => 50,
                BugtrackerItem::REJECTED    => 0,
                BugtrackerItem::REOPEN      => 30,
                BugtrackerItem::FIXED       => 100
            ]
        ];
	}

	/**
	 * @method Load the bugtracker configuration.
	 * @return BugtrackerConfig
	 */
	public static function load(): BugtrackerConfig
	{
		return ConfigManager::load(self::class, 'bugtracker', 'config');
	}

	/**
	 * @method Saves the bugtracker configuration in the database. It becomes persistent.
	 */
	public static function save(): void
	{
		ConfigManager::save('bugtracker', self::load(), 'config');
	}
}
?>
