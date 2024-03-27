<?php
/**
 * @copyright   &copy; 2005-2024 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 10 02
 * @since       PHPBoost 6.0 - 2022 10 25
 */

class BroadcastItem
{
	private $id;
	private $id_category;
	private $title;
	private $rewrited_title;
	private $content;

	private $published;
	private $release_days;
	private $start_time;
	private $end_time;
	private $creation_date;
	private $update_date;

	private $author_user;
	private $author_custom_name;

	private $thumbnail_url;

	const NOT_PUBLISHED = 0;
	const PUBLISHED_NOW = 1;

	const MONDAY    = 'monday';
	const TUESDAY   = 'tuesday';
	const WEDNESDAY = 'wednesday';
	const THURSDAY  = 'thursday';
	const FRIDAY    = 'friday';
	const SATURDAY  = 'saturday';
	const SUNDAY    = 'sunday';

	const THUMBNAIL_URL = '/broadcast/templates/images/default_item.webp';

	public function set_id($id)
	{
		$this->id = $id;
	}

	public function get_id()
	{
		return $this->id;
	}

	public function set_id_category($id_category)
	{
		$this->id_category = $id_category;
	}

	public function get_id_category()
	{
		return $this->id_category;
	}

	public function get_category()
	{
		return CategoriesService::get_categories_manager()->get_categories_cache()->get_category($this->id_category);
	}

	public function set_title($title)
	{
		$this->title = $title;
	}

	public function get_title()
	{
		return $this->title;
	}

	public function set_rewrited_title($rewrited_title)
	{
		$this->rewrited_title = $rewrited_title;
	}

	public function get_rewrited_title()
	{
		return $this->rewrited_title;
	}

	public function rewrited_title_is_personalized()
	{
		return $this->rewrited_title != Url::encode_rewrite($this->title);
	}

	public function set_content($content)
	{
		$this->content = $content;
	}

	public function get_content()
	{
		return $this->content;
	}

	public function add_release_day($release_day)
	{
		$this->release_days[] = $release_day;
	}

	public function set_release_days($release_days)
	{
		$this->release_days = $release_days;
	}

	public function get_release_days()
	{
		return $this->release_days;
	}

	public function set_start_time(Date $start_time)
	{
		$this->start_time = $start_time;
	}

	public function get_start_time()
	{
		return $this->start_time;
	}

	public function set_end_time(Date $end_time)
	{
		$this->end_time = $end_time;
	}

	public function get_end_time()
	{
		return $this->end_time;
	}

	public function get_creation_date()
	{
		return $this->creation_date;
	}

	public function set_creation_date(Date $creation_date)
	{
		$this->creation_date = $creation_date;
	}

	public function get_update_date()
	{
		return $this->update_date;
	}

	public function set_update_date(Date $update_date)
	{
		$this->update_date = $update_date;
	}

	public function has_update_date()
	{
		return $this->update_date !== null && $this->update_date > $this->creation_date;
	}

	public function get_publishing_state()
	{
		return $this->published;
	}

	public function set_publishing_state($published)
	{
		$this->published = $published;
	}

	public function set_published($published)
	{
		$this->published = $published;
	}

	public function get_published()
	{
		return $this->published;
	}

	public function is_published()
	{
		$now = new Date();
		return CategoriesAuthorizationsService::check_authorizations($this->id_category)->read() && ($this->get_publishing_state() == self::PUBLISHED_NOW);
	}

	public function get_status()
	{
		switch ($this->published) {
			case self::PUBLISHED_NOW:
				return LangLoader::get_message('common.status.published', 'common-lang');
			break;
			case self::NOT_PUBLISHED:
				return LangLoader::get_message('common.status.draft', 'common-lang');
			break;
		}
	}

	public function set_author_user(User $user)
	{
		$this->author_user = $user;
	}

	public function get_author_user()
	{
		return $this->author_user;
	}

	public function get_author_custom_name()
	{
		return $this->author_custom_name;
	}

	public function set_author_custom_name($author_custom_name)
	{
		$this->author_custom_name = $author_custom_name;
	}

	public function set_thumbnail($thumbnail)
	{
		$this->thumbnail_url = $thumbnail;
	}

	public function get_thumbnail()
	{
		if (!$this->thumbnail_url instanceof Url)
			return new Url($this->thumbnail_url == FormFieldThumbnail::DEFAULT_VALUE ? FormFieldThumbnail::get_default_thumbnail_url(self::THUMBNAIL_URL) : $this->thumbnail_url);

		return $this->thumbnail_url;
	}

	public function has_thumbnail()
	{
		$thumbnail = ($this->thumbnail_url instanceof Url) ? $this->thumbnail_url->rel() : $this->thumbnail_url;
		return !empty($thumbnail);
	}

	public function is_authorized_to_add()
	{
		return CategoriesAuthorizationsService::check_authorizations($this->id_category)->write();
	}

	public function is_authorized_to_edit()
	{
		return CategoriesAuthorizationsService::check_authorizations($this->id_category)->moderation() || (CategoriesAuthorizationsService::check_authorizations($this->get_id_category())->write());
	}

	public function is_authorized_to_delete()
	{
		return CategoriesAuthorizationsService::check_authorizations($this->id_category)->moderation() || (CategoriesAuthorizationsService::check_authorizations($this->get_id_category())->write());
	}

	public function get_item_url()
	{
		$category = $this->get_category();
		return BroadcastUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $this->id, $this->get_rewrited_title())->rel();
	}

	public function get_properties()
	{
		return array(
			'id' => $this->get_id(),
			'id_category' => $this->get_id_category(),
			'title' => $this->get_title(),
			'rewrited_title' => $this->get_rewrited_title(),
			'content' => $this->get_content(),
			'published' => $this->get_published(),
			'start_time' => $this->get_start_time(),
			'end_time' => $this->get_end_time(),
			'release_days' => $this->get_release_days(),
			'creation_date' => $this->get_creation_date()->get_timestamp(),
			'update_date' => $this->get_update_date() !== null ? $this->get_update_date()->get_timestamp() : $this->get_creation_date()->get_timestamp(),
			'start_time' => (int)($this->get_start_time() !== null ? $this->get_start_time()->get_timestamp() : ''),
			'end_time' => (int)($this->get_end_time() !== null ? $this->get_end_time()->get_timestamp() : ''),
			'author_custom_name' => $this->get_author_custom_name(),
			'author_user_id' => $this->get_author_user()->get_id(),
			'thumbnail_url' => $this->get_thumbnail()->relative()
		);
	}

	public function set_properties(array $properties)
	{
		$this->id             = $properties['id'];
		$this->id_category    = $properties['id_category'];
		$this->title          = $properties['title'];
		$this->rewrited_title = $properties['rewrited_title'];
		$this->content        = $properties['content'];
		$this->release_days   = $properties['release_days'];
		$this->start_time     = $properties['start_time'];
		$this->end_time       = $properties['end_time'];
		$this->creation_date  = new Date($properties['creation_date'], Timezone::SERVER_TIMEZONE);
		$this->update_date    = !empty($properties['update_date']) ? new Date($properties['update_date'], Timezone::SERVER_TIMEZONE) : null;
		$this->published      = $properties['published'];
		$this->start_time     = !empty($properties['start_time']) ? new Date($properties['start_time'], Timezone::SERVER_TIMEZONE) : null;
		$this->end_time       = !empty($properties['end_time']) ? new Date($properties['end_time'], Timezone::SERVER_TIMEZONE) : null;
		$this->thumbnail_url  = $properties['thumbnail_url'];

		$user = new User();
		if (!empty($properties['user_id']))
			$user->set_properties($properties);
		else
			$user->init_visitor_user();

		$this->set_author_user($user);

		$this->author_custom_name = !empty($properties['author_custom_name']) ? $properties['author_custom_name'] : $this->author_user->get_display_name();
	}

	public function init_default_properties($id_category = Category::ROOT_CATEGORY)
	{
		$this->id_category        = $id_category;
		$this->published          = self::PUBLISHED_NOW;
		$this->author_user        = AppContext::get_current_user();
		$this->creation_date      = new Date();
		$this->start_time         = 0;
		$this->end_time           = 0;
		$this->start_time         = new Date();
		$this->end_time           = new Date();
		$this->release_days       = TextHelper::serialize(array());
		$this->thumbnail_url      = FormFieldThumbnail::DEFAULT_VALUE;
		$this->author_custom_name = $this->author_user->get_display_name();
	}

	public function get_array_tpl_vars()
	{
		$category = $this->get_category();
		$content = FormatingHelper::second_parse($this->content);
		$user = $this->get_author_user();
		$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);

		return array_merge(
			Date::get_array_tpl_vars($this->creation_date, 'date'),
			Date::get_array_tpl_vars($this->update_date, 'update_date'),
			array(
				// conditions
				'C_CONTROLS'      => $this->is_authorized_to_edit() || $this->is_authorized_to_delete(),
				'C_VISIBLE'       => $this->is_published(),
				'C_EDIT'          => $this->is_authorized_to_edit(),
				'C_DELETE'        => $this->is_authorized_to_delete(),
				'C_HAS_THUMBNAIL' => $this->has_thumbnail(),

				// Item
				'ID'                 => $this->id,
				'TITLE'              => $this->title,
				'CONTENT'            => $content,
				'STATUS'             => $this->get_status(),
				'AUTHOR_CUSTOM_NAME' => $this->author_custom_name,

				// Hourly
				'RELEASE_DAY'   => $this->get_release_days(),
				'START_HOURS'   => $this->start_time->get_hours(),
				'START_MINUTES' => $this->start_time->get_minutes(),
				'END_HOURS'     => $this->end_time->get_hours(),
				'END_MINUTES'   => $this->end_time->get_minutes(),

				// Category
				'C_ROOT_CATEGORY'      => $category->get_id() == Category::ROOT_CATEGORY,
				'CATEGORY_ID'          => $category->get_id(),
				'CATEGORY_NAME'        => $category->get_name(),
				'CATEGORY_DESCRIPTION' => $category->get_description(),
				'CATEGORY_IMAGE'       => $category->get_thumbnail()->rel(),
				'U_EDIT_CATEGORY'      => $category->get_id() == Category::ROOT_CATEGORY ? BroadcastUrlBuilder::configuration()->rel() : CategoriesUrlBuilder::edit($category->get_id())->rel(),

				'U_SYNDICATION' => SyndicationUrlBuilder::rss('broadcast', $this->id_category)->rel(),
				'U_ITEM'        => BroadcastUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $this->id, $this->rewrited_title)->rel(),
				'U_CATEGORY'    => BroadcastUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel(),
				'U_EDIT'        => BroadcastUrlBuilder::edit($this->id)->rel(),
				'U_DELETE'      => BroadcastUrlBuilder::delete($this->id)->rel(),
				'U_THUMBNAIL'   => $this->get_thumbnail()->rel()
			)
		);
	}

	public function get_weekly_planner_vars($label)
	{
		$vars = $days_list = array();
		foreach (TextHelper::unserialize($this->release_days) as $id => $options)
		{
			$days_list[] = $options->get_label();
		}

		$vars = array(
			'C_SEPARATOR' => array_search($label, array_keys($days_list)) < count($days_list) - 1,
			'DAY' => $days_list[$label]
		);
		return $vars;
	}
}
?>
