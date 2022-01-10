<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 12
 * @since       PHPBoost 5.1 - 2018 03 15
 * @contributor Mipel <mipel@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>

*/

class SmalladsItem
{
	private $id;
	private $id_category;
	private $title;
	private $rewrited_title;
	private $summary;
	private $content;
	private $thumbnail_url;
	private $views_number;
	private $price;
	private $smallad_type;
	private $brand;
	private $max_weeks;
	private $completed;
	private $archived;

	private $author_user;
	private $location;
	private $other_location;
	private $contact_level;
	private $displayed_author_email;
	private $enabled_author_email_customization;
	private $custom_author_email;
	private $displayed_author_pm;
	private $displayed_author_name;
	private $enabled_author_name_customization;
	private $custom_author_name;
	private $displayed_author_phone;
	private $author_phone;

	private $published;
	private $publishing_start_date;
	private $publishing_end_date;
	private $creation_date;
	private $enabled_end_date;
	private $update_date;

	private $sources;
	private $carousel;
	private $keywords;

	const SORT_ALPHABETIC = 'title';
	const SORT_DATE = 'creation_date';
	const SORT_AUTHOR = 'display_name';
	const SORT_NUMBER_VIEWS = 'views_number';
	const SORT_COMMENTS_NUMBER = 'comments_number';
	const SORT_PRICE = 'price';

	const SORT_FIELDS_URL_VALUES = array(
		self::SORT_ALPHABETIC => 'title',
		self::SORT_DATE => 'date',
		self::SORT_AUTHOR => 'author',
		self::SORT_NUMBER_VIEWS => 'views',
		self::SORT_COMMENTS_NUMBER => 'comments',
		self::SORT_PRICE => 'price'
	);

	const ASC = 'ASC';
	const DESC = 'DESC';

	const NOT_PUBLISHED = 0;
	const PUBLISHED_NOW = 1;
	const PUBLICATION_DATE = 2;

	const NOT_COMPLETED = 0;
	const COMPLETED = 1;

	const NOT_ARCHIVED = 0;
	const ARCHIVED = 1;

	const NOT_DISPLAYED_AUTHOR_NAME = 0;
	const DISPLAYED_AUTHOR_NAME = 1;

	const NOT_DISPLAYED_AUTHOR_EMAIL = 0;
	const DISPLAYED_AUTHOR_EMAIL = 1;

	const NOT_DISPLAYED_AUTHOR_PM = 0;
	const DISPLAYED_AUTHOR_PM = 1;

	const NOT_DISPLAYED_AUTHOR_PHONE = 0;
	const DISPLAYED_AUTHOR_PHONE = 1;

	const THUMBNAIL_URL = '/templates/__default__/images/default_item_thumbnail.png';

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

	public function set_summary($summary)
	{
		$this->summary = $summary;
	}

	public function get_summary()
	{
		return $this->summary;
	}

	public function get_summary_enabled()
	{
		return !empty($this->summary);
	}

	public function get_real_summary()
	{
		if ($this->get_summary_enabled())
		{
			return FormatingHelper::second_parse($this->summary);
		}
		return TextHelper::cut_string(@strip_tags(FormatingHelper::second_parse($this->content), '<br><br/>'), (int)SmalladsConfig::load()->get_characters_number_to_cut());
	}

	public function set_content($content)
	{
		$this->content = $content;
	}

	public function get_content()
	{
		return $this->content;
	}

	public function set_price($price)
	{
		$this->price = $price;
	}

	public function get_price()
	{
		return $this->price;
	}

	public function set_max_weeks($max_weeks)
	{
		$this->max_weeks = $max_weeks;
	}

	public function get_max_weeks()
	{
		return $this->max_weeks;
	}

	public function set_smallad_type($smallad_type)
	{
		$this->smallad_type = $smallad_type;
	}

	public function get_smallad_type()
	{
		return $this->smallad_type;
	}

	public function set_brand($brand)
	{
		$this->brand = $brand;
	}

	public function get_brand()
	{
		return $this->brand;
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

	public function set_views_number($views_number)
	{
		$this->views_number = $views_number;
	}

	public function get_views_number()
	{
		return $this->views_number;
	}

	public function get_completed()
	{
		return $this->completed;
	}

	public function set_completed($completed)
	{
		$this->completed= $completed;
	}

	public function is_completed()
	{
		return $this->completed;
	}

	public function get_archived()
	{
		return $this->archived;
	}

	public function set_archived($archived)
	{
		$this->archived= $archived;
	}

	public function is_archived()
	{
		return $this->archived;
	}

	public function set_author_user(User $user)
	{
		$this->author_user = $user;
	}

	public function get_author_user()
	{
	    return $this->author_user;
	}

	public function set_location($location)
	{
		$this->location = $location;
	}

	public function get_location()
	{
		return $this->location;
	}

	public function set_other_location($other_location)
	{
		$this->other_location = $other_location;
	}

	public function get_other_location()
	{
		return $this->other_location;
	}

	public function is_other_location()
	{
		return $this->other_location;
	}

	public function get_displayed_author_email()
	{
		return $this->displayed_author_email;
	}

	public function set_displayed_author_email($displayed)
	{
		$this->displayed_author_email = $displayed;
	}

	public function is_displayed_author_email()
	{
		return $this->displayed_author_email;
	}

	public function get_enabled_author_email_customization()
	{
		return $this->enabled_author_email_customization;
	}

	public function set_enabled_author_email_customization($enabled)
	{
		$this->enabled_author_email_customization = $enabled;
	}

	public function is_enabled_author_email_customization()
	{
		return $this->enabled_author_email_customization;
	}

	public function get_custom_author_email()
	{
		return $this->custom_author_email;
	}

	public function set_custom_author_email($custom_author_email)
	{
		$this->custom_author_email = $custom_author_email;
	}

	public function get_displayed_author_pm()
	{
		return $this->displayed_author_pm;
	}

	public function set_displayed_author_pm($displayed)
	{
		$this->displayed_author_pm = $displayed;
	}

	public function is_displayed_author_pm()
	{
		return $this->displayed_author_pm;
	}

	public function get_displayed_author_name()
	{
		return $this->displayed_author_name;
	}

	public function set_displayed_author_name($displayed)
	{
		$this->displayed_author_name = $displayed;
	}

	public function is_displayed_author_name()
	{
		return $this->displayed_author_name;
	}

	public function is_enabled_author_name_customization()
	{
		return $this->enabled_author_name_customization;
	}

	public function get_custom_author_name()
	{
		return $this->custom_author_name;
	}

	public function set_custom_author_name($custom_author_name)
	{
		$this->custom_author_name = $custom_author_name;
	}

	public function get_displayed_author_phone()
	{
		return $this->displayed_author_phone;
	}

	public function set_displayed_author_phone($displayed)
	{
		$this->displayed_author_phone = $displayed;
	}

	public function is_displayed_author_phone()
	{
		return $this->displayed_author_phone;
	}

	public function get_author_phone()
	{
		return $this->author_phone;
	}

	public function set_author_phone($author_phone)
	{
		$this->author_phone = $author_phone;
	}

	public function set_publication_state($published)
	{
		$this->published = $published;
	}

	public function get_publication_state()
	{
		return $this->published;
	}

	public function is_published()
	{
		$now = new Date();
		return CategoriesAuthorizationsService::check_authorizations($this->id_category)->read() && ($this->get_publication_state() == self::PUBLISHED_NOW || ($this->get_publication_state() == self::PUBLICATION_DATE && $this->get_publishing_start_date()->is_anterior_to($now) && ($this->enabled_end_date ? $this->get_publishing_end_date()->is_posterior_to($now) : true)));
	}

	public function get_status()
	{
		switch ($this->published) {
			case self::PUBLISHED_NOW:
				return LangLoader::get_message('common.status.published.alt', 'common-lang');
			break;
			case self::PUBLICATION_DATE:
				return LangLoader::get_message('common.status.deffered.date', 'common-lang');
			break;
			case self::NOT_PUBLISHED:
				return LangLoader::get_message('common.status.draft', 'common-lang');
			break;
		}
	}

	public function set_publishing_start_date(Date $publishing_start_date)
	{
		$this->publishing_start_date = $publishing_start_date;
	}

	public function get_publishing_start_date()
	{
		return $this->publishing_start_date;
	}

	public function set_publishing_end_date(Date $publishing_end_date)
	{
		$this->publishing_end_date = $publishing_end_date;
		$this->enabled_end_date = true;
	}

	public function get_publishing_end_date()
	{
		return $this->publishing_end_date;
	}

	public function enabled_end_date()
	{
		return $this->enabled_end_date;
	}

	public function set_creation_date(Date $creation_date)
	{
		$this->creation_date = $creation_date;
	}

	public function get_creation_date()
	{
		return $this->creation_date;
	}

	public function get_update_date()
	{
		return $this->update_date;
	}

	public function set_update_date(Date $update_date)
	{
	    $this->update_date = $update_date;
	}

	public function add_source($source)
	{
		$this->sources[] = $source;
	}

	public function set_sources($sources)
	{
		$this->sources = $sources;
	}

	public function get_sources()
	{
		return $this->sources;
	}

	public function add_picture($picture)
	{
		$this->carousel[] = $picture;
	}

	public function set_carousel($carousel)
	{
		$this->carousel = $carousel;
	}

	public function get_carousel()
	{
		return $this->carousel;
	}

	public function get_keywords()
	{
		if ($this->keywords === null)
		{
			$this->keywords = KeywordsService::get_keywords_manager()->get_keywords($this->id);
		}
		return $this->keywords;
	}

	public function get_keywords_name()
	{
		return array_keys($this->get_keywords());
	}

	public function is_authorized_to_add()
	{
		return CategoriesAuthorizationsService::check_authorizations($this->id_category)->write() || CategoriesAuthorizationsService::check_authorizations($this->id_category)->contribution();
	}

	public function is_authorized_to_edit()
	{
		return CategoriesAuthorizationsService::check_authorizations($this->id_category)->moderation() || ((CategoriesAuthorizationsService::check_authorizations($this->get_id_category())->write() || (CategoriesAuthorizationsService::check_authorizations($this->get_id_category())->contribution() && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL))));
	}

	public function is_authorized_to_delete()
	{
		return CategoriesAuthorizationsService::check_authorizations($this->id_category)->moderation() || ((CategoriesAuthorizationsService::check_authorizations($this->get_id_category())->write() || (CategoriesAuthorizationsService::check_authorizations($this->get_id_category())->contribution() && !$this->is_published())) && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}

	public function get_properties()
	{
		return array(
			'id'                     => $this->get_id(),
			'id_category'            => $this->get_id_category(),
			'title'                  => $this->get_title(),
			'rewrited_title'         => $this->get_rewrited_title(),
			'summary'                => $this->get_summary(),
			'content'                => $this->get_content(),
			'price'               	 => $this->get_price(),
			'max_weeks'              => $this->get_max_weeks(),
			'smallad_type'           => $this->get_smallad_type(),
			'brand'               	 => $this->get_brand(),
			'thumbnail_url'          => $this->get_thumbnail()->relative(),
			'views_number'           => $this->get_views_number(),
			'completed' 			 => $this->get_completed(),
			'archived' 			 	 => $this->get_archived(),
			'author_user_id'         => $this->get_author_user()->get_id(),
			'location' 				 => $this->get_location(),
			'other_location' 		 => $this->get_other_location(),
			'displayed_author_email' => $this->get_displayed_author_email(),
			'custom_author_email' 	 => $this->get_custom_author_email(),
			'displayed_author_pm' 	 => $this->get_displayed_author_pm(),
			'displayed_author_name'  => $this->get_displayed_author_name(),
			'custom_author_name' 	 => $this->get_custom_author_name(),
			'displayed_author_phone' => $this->get_displayed_author_phone(),
			'author_phone' 			 => $this->get_author_phone(),
			'published'              => $this->get_publication_state(),
			'publishing_start_date'  => $this->get_publishing_start_date() !== null ? $this->get_publishing_start_date()->get_timestamp() : 0,
			'publishing_end_date'    => $this->get_publishing_end_date() !== null ? $this->get_publishing_end_date()->get_timestamp() : 0,
			'creation_date'          => $this->get_creation_date()->get_timestamp(),
			'update_date'            => $this->get_update_date() !== null ? $this->get_update_date()->get_timestamp() : 0,
			'sources'                => TextHelper::serialize($this->get_sources()),
			'carousel'               => TextHelper::serialize($this->get_carousel())
		);
	}

	public function set_properties(array $properties)
	{
		$this->set_id($properties['id']);
		$this->set_id_category($properties['id_category']);
		$this->set_title($properties['title']);
		$this->set_rewrited_title($properties['rewrited_title']);
		$this->set_summary($properties['summary']);
		$this->set_content($properties['content']);
		$this->set_price($properties['price']);
		$this->set_max_weeks($properties['max_weeks']);
		$this->set_smallad_type($properties['smallad_type']);
		$this->set_brand($properties['brand']);
		$this->set_thumbnail($properties['thumbnail_url']);
		$this->set_views_number($properties['views_number']);
		$this->set_completed($properties['completed']);
		$this->set_archived($properties['archived']);
		$this->location = $properties['location'];
		$this->other_location = $properties['other_location'];
		$this->set_displayed_author_email($properties['displayed_author_email']);
		$this->set_custom_author_email($properties['custom_author_email']);
		$this->set_displayed_author_pm($properties['displayed_author_pm']);
		$this->set_displayed_author_name($properties['displayed_author_name']);
		$this->set_displayed_author_phone($properties['displayed_author_phone']);
		$this->set_author_phone($properties['author_phone']);
		$this->set_publication_state($properties['published']);
		$this->publishing_start_date = !empty($properties['publishing_start_date']) ? new Date($properties['publishing_start_date'], Timezone::SERVER_TIMEZONE) : null;
		$this->publishing_end_date = !empty($properties['publishing_end_date']) ? new Date($properties['publishing_end_date'], Timezone::SERVER_TIMEZONE) : null;
		$this->enabled_end_date = !empty($properties['publishing_end_date']);
		$this->set_creation_date(new Date($properties['creation_date'], Timezone::SERVER_TIMEZONE));
		$this->update_date = !empty($properties['update_date']) ? new Date($properties['update_date'], Timezone::SERVER_TIMEZONE) : null;
		$this->set_sources(!empty($properties['sources']) ? TextHelper::unserialize($properties['sources']) : array());
		$this->set_carousel(!empty($properties['carousel']) ? TextHelper::unserialize($properties['carousel']) : array());

		$user = new User();
		if (!empty($properties['user_id']))
			$user->set_properties($properties);
		else
			$user->init_visitor_user();

		$this->set_author_user($user);

		$this->custom_author_email = !empty($properties['custom_author_email']) ? $properties['custom_author_email'] : $this->author_user->get_email();
		$this->enabled_author_email_customization = !empty($properties['custom_author_email']);

		$this->custom_author_name = !empty($properties['custom_author_name']) ? $properties['custom_author_name'] : $this->author_user->get_display_name();
		$this->enabled_author_name_customization = !empty($properties['custom_author_name']);
	}

	public function init_default_properties($id_category = Category::ROOT_CATEGORY)
	{
		if(SmalladsConfig::load()->is_max_weeks_number_displayed())
			$max_weeks_config_number = SmalladsConfig::load()->get_max_weeks_number();
		else
			$max_weeks_config_number = null;

		$this->id_category = $id_category;
        $this->content = SmalladsConfig::load()->get_default_content();
		$this->completed = self::NOT_COMPLETED;
		$this->archived = self::NOT_ARCHIVED;
		$this->displayed_author_name = self::DISPLAYED_AUTHOR_NAME;
		$this->author_user = AppContext::get_current_user();
		$this->published = self::PUBLISHED_NOW;
		$this->publishing_start_date = new Date();
		$this->publishing_end_date = new Date();
		$this->creation_date = new Date();
		$this->sources = array();
		$this->carousel = array();
		$this->thumbnail_url = FormFieldThumbnail::DEFAULT_VALUE;
		$this->views_number = 0;
		$this->price = 0;
		$this->max_weeks = $max_weeks_config_number;
		$this->custom_author_email = $this->author_user->get_email();
		$this->custom_author_name = $this->author_user->get_display_name();
		$this->enabled_author_email_customization = false;
		$this->enabled_author_name_customization = false;
		$this->displayed_author_pm = true;
	}

	public function clean_publication_start_and_end_date()
	{
		$this->publishing_start_date = null;
		$this->publishing_end_date = null;
		$this->enabled_end_date = false;
	}

	public function clean_publishing_end_date()
	{
		$this->publishing_end_date = null;
		$this->enabled_end_date = false;
	}

	public function get_item_url()
	{
		$category = $this->get_category();
		return SmalladsUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $this->id, $this->get_rewrited_title())->rel();
	}

	public function get_template_vars()
	{
		$this->config = SmalladsConfig::load();

		$category         = $this->get_category();
		$content	 	  = FormatingHelper::second_parse($this->content);
		$rich_content 	  = HooksService::execute_hook_display_action('smallads', $content, $this->get_properties());
		$summary 	      = $this->get_real_summary();
		$user             = $this->get_author_user();
		$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
		$sources          = $this->get_sources();
		$nbr_sources      = count($sources);
		$carousel         = $this->get_carousel();
		$nbr_pictures	  = count($carousel);

		if($this->config->is_googlemaps_available())
		{
			$location_value = TextHelper::deserialize($this->get_location());
			$location = '';
			if (is_array($location_value) && isset($location_value['address']))
				$location = $location_value['address'];
			else if (!is_array($location_value))
				$location = $location_value;
		}
		else
		{
			if(is_numeric($this->get_location()))
				$location = LangLoader::get_message('county.' . $this->get_location(), 'counties', 'smallads');
			else
				$location = $this->get_location();
		}

		if($this->config->is_user_allowed())
			$contact_level = AppContext::get_current_user()->check_level(User::VISITOR_LEVEL);
		else
			$contact_level = AppContext::get_current_user()->check_level(User::MEMBER_LEVEL);

		return array_merge(
			Date::get_array_tpl_vars($this->creation_date, 'date'),
			Date::get_array_tpl_vars($this->update_date, 'update_date'),
			Date::get_array_tpl_vars($this->publishing_start_date, 'publishing_start_date'),
			Date::get_array_tpl_vars($this->publishing_end_date, 'publishing_end_date'),
			array(
			// Conditions
			'C_CONTROLS'					   => $this->is_authorized_to_edit() || $this->is_authorized_to_delete(),
			'C_EDIT'                           => $this->is_authorized_to_edit(),
			'C_DELETE'                         => $this->is_authorized_to_delete(),
			'C_PRICE'                  		   => $this->get_price() != 0,
			'C_HAS_THUMBNAIL'                  => $this->has_thumbnail(),
			'C_AUTHOR_GROUP_COLOR'               => !empty($user_group_color),
			'C_VISIBLE'                        => $this->is_published(),
			'C_PUBLISHING_START_AND_END_DATE'  => $this->publishing_start_date != null && $this->publishing_end_date != null,
			'C_PUBLISHING_START_DATE'          => $this->publishing_start_date != null,
			'C_PUBLISHING_END_DATE'            => $this->publishing_end_date != null,
			'C_HAS_UPDATE_DATE'                => $this->update_date != null,
			'C_CONTACT'						   => $this->is_displayed_author_email() || $this->is_displayed_author_pm() || $this->is_displayed_author_phone(),
			'C_CONTACT_LEVEL'				   => $contact_level,
			'C_COMPLETED'         			   => $this->is_completed(),
			'C_ARCHIVED'         			   => $this->is_archived(),
			'C_DISPLAYED_AUTHOR_EMAIL'         => $this->is_displayed_author_email(),
			'C_CUSTOM_AUTHOR_EMAIL'            => $this->is_enabled_author_email_customization(),
			'C_DISPLAYED_AUTHOR_PM'            => $this->is_displayed_author_pm(),
			'C_DISPLAYED_AUTHOR_PHONE'         => $this->is_displayed_author_phone() && !empty($this->get_author_phone()),
			'C_DISPLAYED_AUTHOR'               => $this->is_displayed_author_name(),
			'C_CUSTOM_AUTHOR_NAME' 			   => $this->is_enabled_author_name_customization(),
			'C_READ_MORE'                      => !$this->get_summary_enabled() && TextHelper::strlen($content) > SmalladsConfig::load()->get_characters_number_to_cut() && $summary != @strip_tags($content, '<br><br/>'),
			'C_SOURCES'                        => $nbr_sources > 0,
			'C_CAROUSEL'                       => $nbr_pictures > 0,
			'C_DIFFERED'                       => $this->published == self::PUBLICATION_DATE,
			'C_NEW_CONTENT'                    => ContentManagementConfig::load()->module_new_content_is_enabled_and_check_date('smallads', $this->publishing_start_date != null ? $this->publishing_start_date->get_timestamp() : $this->get_creation_date()->get_timestamp()) && $this->is_published(),
			'C_USAGE_TERMS'					   => $this->config->are_usage_terms_displayed(),
			'IS_LOCATED'					   => !empty($this->get_location()),
			'C_OTHER_LOCATION'				   => $this->get_location() === 'other',
			'C_GMAP'					   	   => $this->config->is_googlemaps_available(),

			// Smallads
			'ID'                 	=> $this->get_id(),
			'TITLE'              	=> $this->get_title(),
			'STATUS'             	=> $this->get_status(),
			'L_COMMENTS'         	=> CommentsService::get_number_and_lang_comments('smallads', $this->get_id()),
			'COMMENTS_NUMBER'    	=> CommentsService::get_comments_number('smallads', $this->get_id()),
			'VIEWS_NUMBER'       	=> $this->get_views_number(),
			'C_AUTHOR_EXISTS'     	=> $user->get_id() !== User::VISITOR_LEVEL,
			'AUTHOR_EMAIL'       	=> $user->get_email(),
			'CUSTOM_AUTHOR_EMAIL'	=> $this->custom_author_email,
			'AUTHOR_DISPLAY_NAME'   => $user->get_display_name(),
			'CUSTOM_AUTHOR_NAME' 	=> $this->custom_author_name,
			'AUTHOR_PHONE'       	=> $this->get_author_phone(),
			'SUMMARY'        		=> $summary,
			'PRICE'          	 	=> $this->get_price(),
			'CURRENCY'          	=> $this->config->get_currency(),
			'SMALLAD_TYPE'   		=> str_replace('-',' ', $this->get_smallad_type()),
			'SMALLAD_TYPE_FILTER'   => Url::encode_rewrite(TextHelper::strtolower($this->get_smallad_type())),
			'BRAND'          	 	=> $this->get_brand(),
			'AUTHOR_LEVEL_CLASS'   	=> UserService::get_level_class($user->get_level()),
			'AUTHOR_GROUP_COLOR'   	=> $user_group_color,
			'LOCATION'				=> $location,
			'OTHER_LOCATION'		=> $this->get_other_location(),
			'CONTENT'           	=> $rich_content,

			// Category
			'C_ROOT_CATEGORY'      => $category->get_id() == Category::ROOT_CATEGORY,
			'ID_CATEGORY'          => $category->get_id(),
			'CATEGORY_NAME'        => $category->get_name(),
			'CATEGORY_DESCRIPTION' => $category->get_description(),
			'U_CATEGORY_THUMBNAIL' => $category->get_thumbnail()->rel(),
			'U_EDIT_CATEGORY'      => $category->get_id() == Category::ROOT_CATEGORY ? SmalladsUrlBuilder::categories_configuration()->rel() : CategoriesUrlBuilder::edit($category->get_id())->rel(),

			// Links
			'U_COMMENTS'       => SmalladsUrlBuilder::display_items_comments($category->get_id(), $category->get_rewrited_name(), $this->get_id(), $this->get_rewrited_title())->rel(),
			'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($this->get_author_user()->get_id())->rel(),
			'U_AUTHOR_PM'      => UserUrlBuilder::personnal_message($this->get_author_user()->get_id())->rel(),
			'U_CATEGORY'       => SmalladsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel(),
			'U_ITEM'           => $this->get_item_url(),
			'U_THUMBNAIL' 	   => $this->get_thumbnail()->rel(),
			'U_EDIT'   		   => SmalladsUrlBuilder::edit_item($this->id)->rel(),
			'U_DELETE' 		   => SmalladsUrlBuilder::delete_item($this->id)->rel(),
			'U_SYNDICATION'    => SyndicationUrlBuilder::rss('smallads', $category->get_id())->rel(),
			'U_PRINT_ITEM'     => SmalladsUrlBuilder::print_item($this->get_id(), $this->get_rewrited_title())->rel(),
			'U_USAGE_TERMS'    => SmalladsUrlBuilder::usage_terms()->rel()
			)
		);
	}
}
?>
