<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 04
 * @since       PHPBoost 6.0 - 2021 08 22
*/

class SpotsItemFormController extends DefaultModuleController
{
	private $item;
	private $is_new_item;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->build_form($request);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->form->get_field_by_id('travel_type')->set_hidden(!$this->get_item()->is_route_enabled());
			$this->save();
			$this->redirect();
		}

		$this->view->put('CONTENT', $this->form->display());

		return $this->build_response($this->view);
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$form = new HTMLForm(__CLASS__);
		$form->set_layout_title($this->get_item()->get_id() === null ? $this->lang['spots.add'] : ($this->lang['spots.edit']));

		$fieldset = new FormFieldsetHTML('spots', $this->lang['form.parameters']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('title', $this->lang['form.name'], $this->get_item()->get_title(),
			array('required' => true)
		));

		if (CategoriesService::get_categories_manager()->get_categories_cache()->has_categories())
		{
			$search_category_children_options = new SearchCategoryChildrensOptions();
			$search_category_children_options->add_authorizations_bits(Category::CONTRIBUTION_AUTHORIZATIONS);
			$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);
			$fieldset->add_field(CategoriesService::get_categories_manager()->get_select_categories_form_field('id_category', $this->lang['form.category'], $this->get_item()->get_id_category(), $search_category_children_options));
		}

		$fieldset->add_field(new FormFieldThumbnail('thumbnail', $this->lang['form.thumbnail'], $this->get_item()->get_thumbnail()->relative(), SpotsItem::THUMBNAIL_URL));

		$fieldset->add_field(new FormFieldUrlEditor('website_url', $this->lang['form.website'], $this->get_item()->get_website_url()->absolute()));

        $fieldset->add_field(new FormFieldTelEditor('phone', $this->lang['form.phone.number'], $this->get_item()->get_phone()));

        $fieldset->add_field(new FormFieldMailEditor('spot_email', $this->lang['form.email'], $this->get_item()->get_spot_email()));

        if(SpotsService::is_gmap_enabled()) {
            $fieldset->add_field(new GoogleMapsFormFieldMapAddress('gps', $this->lang['spots.location'], new GoogleMapsMarker($this->get_item()->get_location(), $this->get_item()->get_location_latitude(), $this->get_item()->get_location_longitude()),
    			array('description' => $this->lang['spots.location.clue'], 'always_display_marker' => true)
    		));

			$fieldset->add_field(new FormFieldCheckbox('route', $this->lang['spots.display.route'], $this->get_item()->is_route_enabled(),
				array(
					'description' => $this->lang['spots.display.route.clue'],
					'events' => array('change' =>
						'if (HTMLForms.getField("route").getValue()) {
							HTMLForms.getField("travel_type").enable();
						} else {
							HTMLForms.getField("travel_type").disable();
						}'
					)
				)
			));

			$fieldset->add_field(new FormFieldSimpleSelectChoice('travel_type', $this->lang['spots.travel.type'], $this->get_item()->get_travel_type(),
				array(
					new FormFieldSelectChoiceOption($this->lang['spots.travel.type.car'], SpotsItem::TRAVEL_TYPE_DRIVING, array('data_option_icon' => 'fa fa-car')),
					new FormFieldSelectChoiceOption($this->lang['spots.travel.type.walk'],  SpotsItem::TRAVEL_TYPE_WALKING, array('data_option_icon' => 'fa fa-walking')),
					new FormFieldSelectChoiceOption($this->lang['spots.travel.type.bike'],  SpotsItem::TRAVEL_TYPE_BICYCLING, array('data_option_icon' => 'fa fa-bicycle')),
					new FormFieldSelectChoiceOption($this->lang['spots.travel.type.train'],  SpotsItem::TRAVEL_TYPE_TRANSIT, array('data_option_icon' => 'fa fa-train')),
				),
				array(
					'select_to_list' => true,
					'hidden' => !$this->get_item()->is_route_enabled()
				)
			));
        } else {
            $fieldset->add_field(new FormFieldFree('location', $this->lang['spots.location'], $this->lang['spots.no.gmap']));
        }

		$fieldset->add_field(new FormFieldRichTextEditor('content', $this->lang['form.description'], $this->get_item()->get_content()));

        $social_fieldset = new FormFieldsetHTML('social_network', $this->lang['spots.social.network']);
        $form->add_fieldset($social_fieldset);

        $social_fieldset->add_field(new FormFieldUrlEditor('facebook', $this->lang['spots.labels.facebook'], $this->get_item()->get_facebook()->absolute(),
			array('placeholder' => $this->lang['spots.placeholder.facebook'])
		));

        $social_fieldset->add_field(new FormFieldUrlEditor('twitter', $this->lang['spots.labels.twitter'], $this->get_item()->get_twitter()->absolute(),
			array('placeholder' => $this->lang['spots.placeholder.twitter'])
		));

        $social_fieldset->add_field(new FormFieldUrlEditor('instagram', $this->lang['spots.labels.instagram'], $this->get_item()->get_instagram()->absolute(),
			array('placeholder' => $this->lang['spots.placeholder.instagram'])
		));

        $social_fieldset->add_field(new FormFieldUrlEditor('youtube', $this->lang['spots.labels.youtube'], $this->get_item()->get_youtube()->absolute(),
			array('placeholder' => $this->lang['spots.placeholder.youtube'])
		));

        if (CategoriesAuthorizationsService::check_authorizations($this->get_item()->get_id_category())->moderation())
		{
			$publication_fieldset = new FormFieldsetHTML('publication', $this->lang['form.publication']);
			$form->add_fieldset($publication_fieldset);

			$publication_fieldset->add_field(new FormFieldDateTime('creation_date', $this->lang['form.creation.date'], $this->get_item()->get_creation_date(),
				array('required' => true)
			));

			if (!$this->get_item()->is_published())
			{
				$publication_fieldset->add_field(new FormFieldCheckbox('update_creation_date', $this->lang['form.update.creation.date'], false,
					array('hidden' => $this->get_item()->get_status() != SpotsItem::NOT_PUBLISHED)
				));
			}

			$publication_fieldset->add_field(new FormFieldSimpleSelectChoice('published', $this->lang['form.publication'], $this->get_item()->get_published(),
				array(
					new FormFieldSelectChoiceOption($this->lang['form.publication.draft'], SpotsItem::NOT_PUBLISHED),
					new FormFieldSelectChoiceOption($this->lang['form.publication.now'], SpotsItem::PUBLISHED),
				)
			));
		}

		$this->build_contribution_fieldset($form);

		$fieldset->add_field(new FormFieldHidden('referrer', $request->get_url_referrer()));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function build_contribution_fieldset($form)
	{
		$contribution = LangLoader::get('contribution-lang');
		if ($this->get_item()->get_id() === null && $this->is_contributor_member())
		{
			$fieldset = new FormFieldsetHTML('contribution', $contribution['contribution.contribution']);
			$fieldset->set_description(MessageHelper::display($contribution['contribution.extended.warning'], MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('contribution_description', $contribution['contribution.description'], '',
				array('description' => $contribution['contribution.description.clue'])
			));
		}
		elseif ($this->get_item()->is_published() && $this->get_item()->is_authorized_to_edit() && !AppContext::get_current_user()->check_level(User::ADMINISTRATOR_LEVEL))
		{
			$fieldset = new FormFieldsetHTML('member_edition', $contribution['contribution.member.edition']);
			$fieldset->set_description(MessageHelper::display($contribution['contribution.edition.warning'], MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('edition_description', $contribution['contribution.edition.description'], '',
				array('description' => $contribution['contribution.edition.description.clue'])
			));
		}
	}

	private function is_contributor_member()
	{
		return (!CategoriesAuthorizationsService::check_authorizations()->write() && CategoriesAuthorizationsService::check_authorizations()->contribution());
	}

	private function get_item()
	{
		if ($this->item === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->item = SpotsService::get_item($id);
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->is_new_item = true;
				$this->item = new SpotsItem();
				$this->item->init_default_properties(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY));
			}
		}
		return $this->item;
	}

	private function check_authorizations()
	{
		$item = $this->get_item();

		if ($item->get_id() === null)
		{
			if (!$item->is_authorized_to_add())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!$item->is_authorized_to_edit())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		if (AppContext::get_current_user()->is_readonly())
		{
			$controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($controller);
		}
	}

	private function save()
	{
		$item = $this->get_item();

		$item->set_title($this->form->get_value('title'));
		$item->set_rewrited_title(Url::encode_rewrite($item->get_title()));

		if (CategoriesService::get_categories_manager()->get_categories_cache()->has_categories())
			$item->set_id_category($this->form->get_value('id_category')->get_raw_value());

		$item->set_website_url(new Url($this->form->get_value('website_url')));
		$item->set_thumbnail($this->form->get_value('thumbnail'));

		$item->set_phone($this->form->get_value('phone'));
		$item->set_spot_email($this->form->get_value('spot_email'));
		$item->set_facebook(new Url($this->form->get_value('facebook')));
		$item->set_twitter(new Url($this->form->get_value('twitter')));
		$item->set_instagram(new Url($this->form->get_value('instagram')));
		$item->set_youtube(new Url($this->form->get_value('youtube')));
		$item->set_content($this->form->get_value('content'));

        if(SpotsService::is_gmap_enabled()) {
            $location = new GoogleMapsMarker();
			$location->set_properties(TextHelper::unserialize($this->form->get_value('gps')));

			$item->set_location($location->get_address());
			$item->set_route($this->form->get_value('route'));
			if ($this->form->get_value('route'))
				$item->set_travel_type($this->form->get_value('travel_type')->get_raw_value());
			$item->set_location_latitude($location->get_latitude());
			$item->set_location_longitude($location->get_longitude());
        }

		if (!CategoriesAuthorizationsService::check_authorizations($item->get_id_category())->moderation())
		{
			if ($item->get_id() === null )
				$item->set_creation_date(new Date());

			if (CategoriesAuthorizationsService::check_authorizations($item->get_id_category())->contribution() && !CategoriesAuthorizationsService::check_authorizations($item->get_id_category())->write())
				$item->set_published(SpotsItem::NOT_PUBLISHED);
		}
		else
		{
			if ($this->form->get_value('update_creation_date'))
			{
				$item->set_creation_date(new Date());
			}
			else
			{
				$item->set_creation_date($this->form->get_value('creation_date'));
			}
			$item->set_published($this->form->get_value('published')->get_raw_value());
		}

		if ($item->get_id() === null)
		{
			$id = SpotsService::add($item);
			$item->set_id($id);

			if (!$this->is_contributor_member())
				HooksService::execute_hook_action('add', self::$module_id, array_merge($item->get_properties(), array('item_url' => $item->get_item_url())));
		}
		else
		{
			$item->set_update_date(new Date());
			$id = $item->get_id();
			SpotsService::update($item);

			if (!$this->is_contributor_member())
				HooksService::execute_hook_action('edit', self::$module_id, array_merge($item->get_properties(), array('item_url' => $item->get_item_url())));
		}

		$this->contribution_actions($item, $id);

		SpotsService::clear_cache();
	}

	private function contribution_actions(SpotsItem $item, $id)
	{
		if ($this->is_contributor_member())
		{
			$contribution = new Contribution();
			$contribution->set_id_in_module($id);
			if ($item->get_id() === null)
				$contribution->set_description(stripslashes($this->form->get_value('contribution_description')));
			else
				$contribution->set_description(stripslashes($this->form->get_value('edition_description')));
			$contribution->set_entitled($item->get_title());
			$contribution->set_fixing_url(SpotsUrlBuilder::edit($id)->relative());
			$contribution->set_poster_id(AppContext::get_current_user()->get_id());
			$contribution->set_module('spots');
			$contribution->set_auth(
				Authorizations::capture_and_shift_bit_auth(
					CategoriesService::get_categories_manager()->get_heritated_authorizations($item->get_id_category(), Category::MODERATION_AUTHORIZATIONS, Authorizations::AUTH_CHILD_PRIORITY),
					Category::MODERATION_AUTHORIZATIONS, Contribution::CONTRIBUTION_AUTH_BIT
				)
			);
			ContributionService::save_contribution($contribution);
			HooksService::execute_hook_action($this->is_new_item ? 'add_contribution' : 'edit_contribution', self::$module_id, array_merge($contribution->get_properties(), $item->get_properties(), array('item_url' => $item->get_item_url())));
		}
		else
		{
			$corresponding_contributions = ContributionService::find_by_criteria('spots', $id);
			if (count($corresponding_contributions) > 0)
			{
				foreach ($corresponding_contributions as $contribution)
				{
					$contribution->set_status(Event::EVENT_STATUS_PROCESSED);
					ContributionService::save_contribution($contribution);
				}
				HooksService::execute_hook_action('process_contribution', self::$module_id, array_merge($contribution->get_properties(), $item->get_properties(), array('item_url' => $item->get_item_url())));
			}
		}
		$item->set_id($id);
	}

	private function redirect()
	{
		$item = $this->get_item();
		$category = $item->get_category();

		if ($this->is_new_item && $this->is_contributor_member() && !$item->is_published())
		{
			DispatchManager::redirect(new UserContributionSuccessController());
		}
		elseif ($item->is_published())
		{
			if ($this->is_new_item)
				AppContext::get_response()->redirect(SpotsUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_rewrited_title()), StringVars::replace_vars($this->lang['spots.message.success.add'], array('name' => $item->get_title())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : SpotsUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_rewrited_title())), StringVars::replace_vars($this->lang['spots.message.success.edit'], array('name' => $item->get_title())));
		}
		else
		{
			if ($this->is_new_item)
				AppContext::get_response()->redirect(SpotsUrlBuilder::display_pending(), StringVars::replace_vars($this->lang['spots.message.success.add'], array('name' => $item->get_title())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : SpotsUrlBuilder::display_pending()), StringVars::replace_vars($this->lang['spots.message.success.edit'], array('name' => $item->get_title())));
		}
	}

	private function build_response(View $view)
	{
		$item = $this->get_item();

		$response = new SiteDisplayResponse($view);
		$graphical_environment = $response->get_graphical_environment();

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['spots.module.title'], SpotsUrlBuilder::home());

		if ($item->get_id() === null)
		{
			$graphical_environment->set_page_title($this->lang['spots.add']);
			$breadcrumb->add($this->lang['spots.add'], SpotsUrlBuilder::add($item->get_id_category()));
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['spots.add'], $this->lang['spots.module.title']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(SpotsUrlBuilder::add($item->get_id_category()));
		}
		else
		{
			$graphical_environment->set_page_title($this->lang['spots.edit']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['spots.edit'], $this->lang['spots.module.title']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(SpotsUrlBuilder::edit($item->get_id()));

			$categories = array_reverse(CategoriesService::get_categories_manager()->get_parents($item->get_id_category(), true));
			foreach ($categories as $id => $category)
			{
				if ($category->get_id() != Category::ROOT_CATEGORY)
					$breadcrumb->add($category->get_name(), SpotsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
			}
			$category = $item->get_category();
			$breadcrumb->add($item->get_title(), SpotsUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_rewrited_title()));
			$breadcrumb->add($this->lang['spots.edit'], SpotsUrlBuilder::edit($item->get_id()));
		}

		return $response;
	}
}
?>
