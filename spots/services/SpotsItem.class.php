<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 04 15
 * @since       PHPBoost 6.0 - 2021 08 22
*/

class SpotsItem
{
	private $id;
	private $id_category;
	private $title;
	private $rewrited_title;
	private $website_url;
    private $location;
    private $location_latitude;
    private $location_longitude;
    private $route;
    private $travel_type;
    private $phone;
    private $spot_email;
	private $content;

	private $published;

	private $creation_date;
	private $update_date;
	private $author_user;
	private $views_number;
	private $visits_number;
	private $thumbnail_url;

    private $facebook;
    private $twitter;
    private $instagram;
    private $youtube;

	const THUMBNAIL_URL = '/templates/__default__/images/default_item.webp';

	const NOT_PUBLISHED = 0;
	const PUBLISHED = 1;

	const TRAVEL_TYPE = 'travel_type';
	const TRAVEL_TYPE_DRIVING = 'DRIVING';
	const TRAVEL_TYPE_WALKING = 'WALKING';
	const TRAVEL_TYPE_BICYCLING = 'BICYCLING';
	const TRAVEL_TYPE_TRANSIT = 'TRANSIT';

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
		return CategoriesService::get_categories_manager('spots')->get_categories_cache()->get_category($this->id_category);
	}

	public function get_title()
	{
		return $this->title;
	}

	public function set_title($title)
	{
		$this->title = $title;
	}

	public function get_rewrited_title()
	{
		return $this->rewrited_title;
	}

	public function set_rewrited_title($rewrited_title)
	{
		$this->rewrited_title = $rewrited_title;
	}

	public function get_website_url()
	{
		if (!$this->website_url instanceof Url)
			return new Url('');

		return $this->website_url;
	}

	public function set_website_url(Url $website_url)
	{
		$this->website_url = $website_url;
	}

	public function get_location()
	{
		return $this->location;
	}

	public function set_location($location)
	{
		$this->location = $location;
	}

	public function is_route_enabled()
	{
		return $this->route;
	}

	public function set_route($route)
	{
		$this->route = $route;
	}

	public function get_travel_type()
	{
		return $this->travel_type;
	}

	public function set_travel_type($travel_type)
	{
		$this->travel_type = $travel_type;
	}

	public function get_location_latitude()
	{
		return $this->location_latitude;
	}

	public function set_location_latitude($location_latitude)
	{
		$this->location_latitude = $location_latitude;
	}

	public function get_location_longitude()
	{
		return $this->location_longitude;
	}

	public function set_location_longitude($location_longitude)
	{
		$this->location_longitude = $location_longitude;
	}

	public function get_phone()
	{
		return $this->phone;
	}

	public function set_phone($phone)
	{
		$this->phone = $phone;
	}

	public function get_spot_email()
	{
		return $this->spot_email;
	}

	public function set_spot_email($spot_email)
	{
		$this->spot_email = $spot_email;
	}

	public function get_content()
	{
		return $this->content;
	}

	public function set_content($content)
	{
		$this->content = $content;
	}

	public function get_published()
	{
		return $this->published;
	}

	public function set_published($published)
	{
		$this->published = $published;
	}

	public function is_published()
	{
		$now = new Date();
		return CategoriesAuthorizationsService::check_authorizations($this->id_category)->read() && ($this->get_published() == self::PUBLISHED );
	}

	public function get_status()
	{
		switch ($this->published) {
			case self::PUBLISHED:
				return LangLoader::get_message('common.status.approved', 'common-lang');
			break;
			case self::NOT_PUBLISHED:
				return LangLoader::get_message('common.status.draft', 'common-lang');
			break;
		}
	}

	public function get_creation_date()
	{
		return $this->creation_date;
	}

	public function set_creation_date(Date $creation_date)
	{
		$this->creation_date = $creation_date;
	}

	public function set_update_date(Date $update_date)
	{
		$this->update_date = $update_date;
	}

	public function get_update_date()
	{
		return $this->update_date;
	}

	public function has_update_date()
	{
		return ($this->update_date !== null) && ($this->update_date > $this->creation_date);
	}

	public function get_author_user()
	{
		return $this->author_user;
	}

	public function set_author_user(User $user)
	{
		$this->author_user = $user;
	}

	public function get_views_number()
	{
		return $this->views_number;
	}

	public function set_views_number($views_number)
	{
		$this->views_number = $views_number;
	}

	public function get_visits_number()
	{
		return $this->visits_number;
	}

	public function set_visits_number($visits_number)
	{
		$this->visits_number = $visits_number;
	}

	public function get_thumbnail()
	{
		if (!$this->thumbnail_url instanceof Url)
			return new Url($this->thumbnail_url == FormFieldThumbnail::DEFAULT_VALUE ? FormFieldThumbnail::get_default_thumbnail_url(self::THUMBNAIL_URL) : $this->thumbnail_url);

		return $this->thumbnail_url;
	}

	public function set_thumbnail($thumbnail)
	{
		$this->thumbnail_url = $thumbnail;
	}

	public function has_thumbnail()
	{
		$thumbnail = ($this->thumbnail_url instanceof Url) ? $this->thumbnail_url->rel() : $this->thumbnail_url;
		return !empty($thumbnail);
	}

	public function get_facebook()
	{
		if (!$this->facebook instanceof Url)
			return new Url('');

		return $this->facebook;
	}

	public function set_facebook(Url $facebook)
	{
		$this->facebook = $facebook;
	}

	public function get_twitter()
	{
		if (!$this->twitter instanceof Url)
			return new Url('');

		return $this->twitter;
	}

	public function set_twitter(Url $twitter)
	{
		$this->twitter = $twitter;
	}

	public function get_instagram()
	{
		if (!$this->instagram instanceof Url)
			return new Url('');

		return $this->instagram;
	}

	public function set_instagram(Url $instagram)
	{
		$this->instagram = $instagram;
	}

	public function get_youtube()
	{
		if (!$this->youtube instanceof Url)
			return new Url('');

		return $this->youtube;
	}

	public function set_youtube(Url $youtube)
	{
		$this->youtube = $youtube;
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
		return CategoriesAuthorizationsService::check_authorizations($this->id_category)->moderation() || ((CategoriesAuthorizationsService::check_authorizations($this->id_category)->write() || (CategoriesAuthorizationsService::check_authorizations($this->id_category)->contribution() && !$this->is_published())) && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}

	public function get_properties()
	{
		return array(
			'id' => $this->get_id(),
			'id_category' => $this->get_id_category(),
			'title' => $this->get_title(),
			'rewrited_title' => $this->get_rewrited_title(),
			'website_url' => $this->get_website_url()->absolute(),
			'phone' => $this->get_phone(),
			'spot_email' => $this->get_spot_email(),
			'location' => $this->get_location(),
			'route_enabled' => $this->is_route_enabled(),
			'travel_type' => $this->get_travel_type(),
			'latitude' => $this->get_location_latitude(),
			'longitude' => $this->get_location_longitude(),
			'facebook' => $this->get_facebook()->absolute(),
			'twitter' => $this->get_twitter()->absolute(),
			'instagram' => $this->get_instagram()->absolute(),
			'youtube' => $this->get_youtube()->absolute(),
			'content' => $this->get_content(),
			'published' => $this->get_published(),
			'creation_date' => $this->get_creation_date()->get_timestamp(),
			'update_date' => $this->get_update_date() !== null ? $this->get_update_date()->get_timestamp() : $this->get_creation_date()->get_timestamp(),
			'author_user_id' => $this->get_author_user()->get_id(),
			'views_number' => $this->get_views_number(),
			'visits_number' => $this->get_visits_number(),
			'thumbnail' => $this->get_thumbnail()->relative(),
		);
	}

	public function set_properties(array $properties)
	{
		$this->id = $properties['id'];
		$this->id_category = $properties['id_category'];
		$this->title = $properties['title'];
		$this->rewrited_title = $properties['rewrited_title'];
		$this->website_url = new Url($properties['website_url']);
		$this->phone = $properties['phone'];
		$this->spot_email = $properties['spot_email'];
        $this->location = $properties['location'];
        $this->route = $properties['route_enabled'];
        $this->travel_type = $properties['travel_type'];
        $this->location_latitude = $properties['latitude'];
        $this->location_longitude = $properties['longitude'];
        $this->facebook = new Url($properties['facebook']);
        $this->twitter = new Url($properties['twitter']);
        $this->instagram = new Url($properties['instagram']);
        $this->youtube = new Url($properties['youtube']);
		$this->content = $properties['content'];
		$this->published = $properties['published'];
		$this->creation_date = new Date($properties['creation_date'], Timezone::SERVER_TIMEZONE);
		$this->update_date = !empty($properties['update_date']) ? new Date($properties['update_date'], Timezone::SERVER_TIMEZONE) : null;
		$this->views_number = $properties['views_number'];
		$this->visits_number = $properties['visits_number'];
		$this->thumbnail_url = $properties['thumbnail'];

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
		$this->published = self::PUBLISHED;
		$this->author_user = AppContext::get_current_user();
		$this->creation_date = new Date();
		$this->views_number = 0;
		$this->visits_number = 0;
		$this->thumbnail_url = FormFieldThumbnail::DEFAULT_VALUE;
		$this->route = 1;
		$this->travel_type = self::TRAVEL_TYPE_DRIVING;
		$this->website_url = new Url('');
        $this->facebook = new Url('');
        $this->twitter = new Url('');
        $this->instagram = new Url('');
        $this->youtube = new Url('');
	}

	public function get_item_url()
	{
		$category = $this->get_category();
		return SpotsUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $this->id, $this->rewrited_title)->rel();
	}

	public function get_template_vars()
	{
		$category = $this->get_category();
		$content = FormatingHelper::second_parse($this->content);
		$rich_content = HooksService::execute_hook_display_action('spots', $content, $this->get_properties());
		$user = $this->get_author_user();
		$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
		$comments_number = CommentsService::get_comments_number('spots', $this->id);
        $config = SpotsConfig::load();

        // Convertisseur degres decimaux -> derges, minutes, secondes
        // Latitude
        $loca_lat = $this->location_latitude;

        if($loca_lat > 0)
            $card_lat = 'N';
        else
            $card_lat = 'S';

        $loca_lat = abs($loca_lat);
        $loca_lat_deg = intval($loca_lat);
        $loca_lat_min = ($loca_lat - $loca_lat_deg)*60;
        $loca_lat_sec = ($loca_lat_min - intval($loca_lat))*60;

        // Longitude
        $loca_lng = $this->location_longitude;

        if($loca_lng > 0)
            $card_lng = 'E';
        else
            $card_lng = 'W';

        $loca_lng = abs($loca_lng);
        $loca_lng_deg = intval($loca_lng);
        $loca_lng_min = ($loca_lng - $loca_lng_deg)*60;
        $loca_lng_sec = ($loca_lng_min - intval($loca_lng))*60;

		$root_category = $category->get_id() == Category::ROOT_CATEGORY;

        $category_address_values = TextHelper::deserialize($root_category ? GoogleMapsConfig::load()->get_default_marker_address() : $category->get_category_address());

		return array_merge(
			Date::get_array_tpl_vars($this->creation_date, 'date'),
			array(
            'C_NEW_WINDOW'         => $config->get_new_window(true),
            'C_GMAP_ENABLED'       => SpotsService::is_gmap_enabled(),
            'C_ROUTE'              => $this->is_route_enabled(),
            'C_LOCATION'           => ($this->location_latitude) && ($this->location_longitude),
            'C_CONTENT'            => !empty($content),
			'C_VISIBLE'            => $this->is_published(),
			'C_CONTROLS'           => $this->is_authorized_to_edit() || $this->is_authorized_to_delete(),
			'C_EDIT'               => $this->is_authorized_to_edit(),
			'C_DELETE'             => $this->is_authorized_to_delete(),
			'C_AUTHOR_GROUP_COLOR' => !empty($user_group_color),
            'C_VISIT'              => !empty($this->website_url->absolute()),
			'C_HAS_THUMBNAIL'      => $this->has_thumbnail(),
			'C_DIRECT_CONTACT'	   => !empty($this->phone) || !empty($this->spot_email),
            'C_PHONE'              => !empty($this->phone),
            'C_EMAIL'              => !empty($this->spot_email),
            'C_NETWORK'            => !empty($this->facebook->absolute()) || !empty($this->twitter->absolute()) || !empty($this->instagram->absolute()) || !empty($this->youtube->absolute()),
			'C_FACEBOOK'           => !empty($this->facebook->absolute()),
			'C_TWITTER'            => !empty($this->twitter->absolute()),
			'C_INSTAGRAM'          => !empty($this->instagram->absolute()),
			'C_YOUTUBE'            => !empty($this->youtube->absolute()),
			'C_DEFAULT_ADDRESS'    => !empty(GoogleMapsConfig::load()->get_default_marker_address()),
			'C_CONTACT'			   => !empty($this->phone) || !empty($this->spot_email) || !empty($this->facebook->absolute()) || !empty($this->twitter->absolute()) || !empty($this->instagram->absolute()) || !empty($this->youtube->absolute()),

            // // Deafult values
            'GMAP_API_KEY' => GoogleMapsConfig::load()->get_api_key(),
            'C_GMAP_API'   => ModulesManager::is_module_installed('GoogleMaps') && ModulesManager::is_module_activated('GoogleMaps'),

			// // Category
			'C_ROOT_CATEGORY'      => $root_category,
			'CATEGORY_ID'          => $category->get_id(),
			'CATEGORY_NAME'        => $category->get_name(),
			'CATEGORY_COLOR'   	   => !$root_category ? $category->get_color() : $config->get_default_color(),
			'CATEGORY_INNER_ICON'  => !$root_category ? (!empty($category->get_inner_icon()) ? $category->get_inner_icon() : $config->get_default_inner_icon()) : $config->get_default_inner_icon(),
			'CATEGORY_LATITUDE'    => TextHelper::is_serialized($root_category ? GoogleMapsConfig::load()->get_default_marker_latitude() : $category->get_category_address()) && !empty($category->get_category_address()) ? $category_address_values['latitude'] : GoogleMapsConfig::load()->get_default_marker_latitude(),
			'CATEGORY_LONGITUDE'   => TextHelper::is_serialized($root_category ? GoogleMapsConfig::load()->get_default_marker_longitude() : $category->get_category_address()) && !empty($category->get_category_address()) ? $category_address_values['longitude'] : GoogleMapsConfig::load()->get_default_marker_longitude(),

			'U_EDIT_CATEGORY'      => $root_category ? SpotsUrlBuilder::configuration()->rel() : CategoriesUrlBuilder::edit($category->get_id(), 'spots')->rel(),

			'C_COMMENTS'      => !empty($comments_number),
			'L_COMMENTS'      => CommentsService::get_lang_comments('spots', $this->id),
			'COMMENTS_NUMBER' => $comments_number,

			// // Item
			'ID'                  => $this->id,
			'TITLE'               => $this->title,
			'REWRITED_TITLE'      => $this->rewrited_title,
			'WEBSITE_URL'         => $this->website_url->absolute(),
			'CONTENT'             => $rich_content,
			'STATUS'              => $this->get_status(),
			'C_AUTHOR_EXIST'      => $user->get_id() !== User::VISITOR_LEVEL,
			'AUTHOR_DISPLAY_NAME' => $user->get_display_name(),
			'AUTHOR_LEVEL_CLASS'  => UserService::get_level_class($user->get_level()),
			'AUTHOR_GROUP_COLOR'  => $user_group_color,
			'VIEWS_NUMBER'        => $this->views_number,
			'VISITS_NUMBER'       => $this->visits_number,
            'PHONE'               => $this->phone,
            'EMAIL'               => $this->spot_email,
            'TRAVEL_TYPE'         => $this->travel_type,
			'DEFAULT_LAT'         => GoogleMapsConfig::load()->get_default_marker_latitude(),
			'DEFAULT_LNG'         => GoogleMapsConfig::load()->get_default_marker_longitude(),
            'LOCATION'     		  => $this->location,
            'V_LOCATION'     	  => str_replace(',', '<br />', $this->location),
            'LATITUDE'            => $this->location_latitude,
            'LONGITUDE'           => $this->location_longitude,
            'LOCA_LAT'            => str_pad($loca_lat_deg, 2, '0', STR_PAD_LEFT) . 'deg ' . intval($loca_lat_min) . "min " . number_format($loca_lat_sec, 2) . 'sec ' . $card_lat,
            'LOCA_LNG'            => str_pad($loca_lng_deg, 2, '0', STR_PAD_LEFT) . 'deg ' . intval($loca_lng_min) . "min " . number_format($loca_lng_sec, 2) . 'sec ' . $card_lng,

			'U_SYNDICATION'    => SyndicationUrlBuilder::rss('spots', $this->id_category)->rel(),
			'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($this->get_author_user()->get_id())->rel(),
			'U_AUTHOR_CONTRIB' => SpotsUrlBuilder::display_member_items($this->get_author_user()->get_id())->rel(),
			'U_ITEM'           => SpotsUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $this->id, $this->rewrited_title)->rel(),
			'U_VISIT'          => SpotsUrlBuilder::visit($this->id)->rel(),
			'U_DEADLINK'       => SpotsUrlBuilder::dead_link($this->id)->rel(),
			'U_CATEGORY'       => SpotsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel(),
			'U_EDIT'           => SpotsUrlBuilder::edit($this->id)->rel(),
			'U_DELETE'         => SpotsUrlBuilder::delete($this->id)->rel(),
			'U_THUMBNAIL'      => $this->get_thumbnail()->rel(),
			'U_FACEBOOK'       => $this->facebook->absolute(),
			'U_TWITTER'        => $this->twitter->absolute(),
			'U_INSTAGRAM'      => $this->instagram->absolute(),
			'U_YOUTUBE'        => $this->youtube->absolute(),
			'U_COMMENTS'       => SpotsUrlBuilder::display_comments($category->get_id(), $category->get_rewrited_name(), $this->id, $this->rewrited_title)->rel()
			)
		);

	}
}
?>
