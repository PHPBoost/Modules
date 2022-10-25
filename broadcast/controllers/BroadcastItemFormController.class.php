<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 10 25
 * @since       PHPBoost 6.0 - 2022 10 25
 */

class BroadcastItemFormController extends DefaultModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->build_form($request);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->redirect();
		}

		$this->view->put('CONTENT', $this->form->display());

		return $this->generate_response($this->view);
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$form = new HTMLForm(__CLASS__);
		$form->set_layout_title($this->get_item()->get_id() === null ? $this->lang['broadcast.add.item'] : ($this->lang['broadcast.edit.item'] . ': ' . $this->get_item()->get_title()));

		$fieldset = new FormFieldsetHTML('broadcast', $this->lang['form.parameters']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('title', $this->lang['form.title'], $this->get_item()->get_title(),
			array('required' => true)
		));

		if (CategoriesService::get_categories_manager()->get_categories_cache()->has_categories())
		{
			$search_category_children_options = new SearchCategoryChildrensOptions();
			// $search_category_children_options->add_authorizations_bits(Category::CONTRIBUTION_AUTHORIZATIONS);
			$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);
			$fieldset->add_field(CategoriesService::get_categories_manager()->get_select_categories_form_field('id_category', $this->lang['form.category'], $this->get_item()->get_id_category(), $search_category_children_options));
		}

		$fieldset->add_field(new FormFieldRichTextEditor('content', $this->lang['form.description'], $this->get_item()->get_content(),
			array('rows' => 15)
		));

		$fieldset->add_field(new FormFieldTextEditor('author_custom_name', $this->lang['broadcast.announcer'], $this->get_item()->get_author_custom_name(),
			array('required' => true)
		));

		$fieldset->add_field(new FormFieldMultipleCheckbox('release_days', $this->lang['broadcast.release.day'], TextHelper::unserialize($this->get_item()->get_release_days()),
			array(
				new FormFieldMultipleCheckboxOption(BroadcastItem::MONDAY, $this->lang['date.monday']),
				new FormFieldMultipleCheckboxOption(BroadcastItem::TUESDAY, $this->lang['date.tuesday']),
				new FormFieldMultipleCheckboxOption(BroadcastItem::WEDNESDAY, $this->lang['date.wednesday']),
				new FormFieldMultipleCheckboxOption(BroadcastItem::THURSDAY, $this->lang['date.thursday']),
				new FormFieldMultipleCheckboxOption(BroadcastItem::FRIDAY, $this->lang['date.friday']),
				new FormFieldMultipleCheckboxOption(BroadcastItem::SATURDAY, $this->lang['date.saturday']),
				new FormFieldMultipleCheckboxOption(BroadcastItem::SUNDAY, $this->lang['date.sunday']),
			),
			array('required' => true)
		));

		$fieldset->add_field(new FormFieldDateTime('start_time', $this->lang['broadcast.start.time'], $this->get_item()->get_start_time(),
			array('required' => true, 'five_minutes_step' => true)
		));

		$fieldset->add_field(new FormFieldDateTime('end_time', $this->lang['broadcast.end.time'], $this->get_item()->get_end_time(),
			array('required' => true, 'five_minutes_step' => true)
		));

		$options_fieldset = new FormFieldsetHTML('other', $this->lang['form.options']);
		$form->add_fieldset($options_fieldset);

		$options_fieldset->add_field(new FormFieldThumbnail('thumbnail', $this->lang['form.thumbnail'], $this->get_item()->get_thumbnail()->relative(), BroadcastItem::THUMBNAIL_URL));

		if (CategoriesAuthorizationsService::check_authorizations($this->get_item()->get_id_category())->moderation())
		{
			$publication_fieldset = new FormFieldsetHTML('publication', $this->lang['form.publication']);
			$form->add_fieldset($publication_fieldset);

			$publication_fieldset->add_field(new FormFieldDateTime('creation_date', $this->lang['form.creation.date'], $this->get_item()->get_creation_date(),
				array('required' => true)
			));

			if (!$this->get_item()->is_published())
			{
				$publication_fieldset->add_field(new FormFieldCheckbox('update_creation_date', $this->lang['form.update.date'], false,
					array('hidden' => $this->get_item()->get_status() != BroadcastItem::PUBLISHED_NOW)
				));
			}

			$publication_fieldset->add_field(new FormFieldSimpleSelectChoice('published', $this->lang['form.publication'], $this->get_item()->get_publishing_state(),
				array(
					new FormFieldSelectChoiceOption($this->lang['form.publication.draft'], BroadcastItem::NOT_PUBLISHED),
					new FormFieldSelectChoiceOption($this->lang['form.publication.now'], BroadcastItem::PUBLISHED_NOW),
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
		if ($this->get_item()->get_id() === null && $this->is_contributor_member()) {
			$fieldset = new FormFieldsetHTML('contribution', $this->lang['contribution.contribution']);
			$fieldset->set_description(MessageHelper::display($this->lang['contribution.extended.warning'], MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('contribution_description', $this->lang['contribution.description'], '',
				array('description' => $this->lang['contribution.description.clue'])
			));
		}
		elseif ($this->get_item()->is_published() && $this->get_item()->is_authorized_to_edit() && $this->is_contributor_member())
		{
			$fieldset = new FormFieldsetHTML('member_edition', $this->lang['contribution.member.edition']);
			$fieldset->set_description(MessageHelper::display($this->lang['contribution.edition.warning'], MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('edition_description', $this->lang['contribution.edition.description'], '',
				array('description' => $this->lang['contribution.edition.description.clue'])
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
					$this->item = BroadcastService::get_item($id);
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->is_new_item = true;
				$this->item = new BroadcastItem();
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
		$item->set_rewrited_title(Url::encode_rewrite($this->form->get_value('title')));

		if (CategoriesService::get_categories_manager()->get_categories_cache()->has_categories())
			$item->set_id_category($this->form->get_value('id_category')->get_raw_value());

		$item->set_content($this->form->get_value('content'));

		$release_days_list = array();
		foreach($this->form->get_value('release_days') as $id => $value)
		{
			$release_days_list[] = $value;
		}
		$item->set_release_days(TextHelper::serialize($release_days_list));

		$item->set_start_time($this->form->get_value('start_time'));
		$item->set_end_time($this->form->get_value('end_time'));
		$item->set_thumbnail($this->form->get_value('thumbnail'));

		$item->set_author_custom_name(($this->form->get_value('author_custom_name') && $this->form->get_value('author_custom_name') !== $item->get_author_user()->get_display_name() ? $this->form->get_value('author_custom_name') : ''));

		if (!CategoriesAuthorizationsService::check_authorizations($item->get_id_category())->moderation())
		{
			if ($this->is_new_item)
				$item->set_creation_date(new Date());
		}
		else
		{
			if ($this->form->get_value('update_creation_date'))
				$item->set_creation_date(new Date());
			else
				$item->set_creation_date($this->form->get_value('creation_date'));

			if (CategoriesAuthorizationsService::check_authorizations($item->get_id_category())->contribution() && !CategoriesAuthorizationsService::check_authorizations($item->get_id_category())->write())
				$item->set_publishing_state(BroadcastItem::NOT_PUBLISHED);
			else
			{
				$item->set_publishing_state($this->form->get_value('published')->get_raw_value());
				$item->set_published($this->form->get_value('published')->get_raw_value());
			}
		}

		if ($this->is_new_item) {
			$id = BroadcastService::add($item);
			$item->set_id($id);

			if (!$this->is_contributor_member())
				HooksService::execute_hook_action('add', self::$module_id, array_merge($item->get_properties(), array('item_url' => $item->get_item_url())));
		}
		else {
			$item->set_update_date(new Date());
			BroadcastService::update($item);

			if (!$this->is_contributor_member())
				HooksService::execute_hook_action('edit', self::$module_id, array_merge($item->get_properties(), array('item_url' => $item->get_item_url())));
		}

		$this->contribution_actions($item);

		BroadcastService::clear_cache();
	}

	private function contribution_actions(BroadcastItem $item)
	{
		if ($this->is_contributor_member())
		{
			$contribution = new Contribution();
			$contribution->set_id_in_module($item->get_id());
			if ($this->is_new_item)
				$contribution->set_description(stripslashes($this->form->get_value('contribution_description')));
			else
				$contribution->set_description(stripslashes($this->form->get_value('edition_description')));

			$contribution->set_entitled($item->get_title());
			$contribution->set_fixing_url(BroadcastUrlBuilder::edit($item->get_id())->relative());
			$contribution->set_poster_id(AppContext::get_current_user()->get_id());
			$contribution->set_module('broadcast');
			$contribution->set_auth(
				Authorizations::capture_and_shift_bit_auth(
				CategoriesService::get_categories_manager()->get_heritated_authorizations($item->get_id_category(), Category::MODERATION_AUTHORIZATIONS, Authorizations::AUTH_CHILD_PRIORITY),
					Category::MODERATION_AUTHORIZATIONS, Contribution::CONTRIBUTION_AUTH_BIT
				)
			);
			ContributionService::save_contribution($contribution);
			HooksService::execute_hook_action($this->is_new_item ? 'add_contribution' : 'edit_contribution', self::$module_id, array_merge($item->get_properties(), array('item_url' => $item->get_item_url())));
		}
		else
		{
			$corresponding_contributions = ContributionService::find_by_criteria('broadcast', $item->get_id());
			if (count($corresponding_contributions) > 0)
			{
				foreach ($corresponding_contributions as $contribution)
				{
					$contribution->set_status(Event::EVENT_STATUS_PROCESSED);
					ContributionService::save_contribution($contribution);
				}
				HooksService::execute_hook_action('process_contribution', self::$module_id, array_merge($item->get_properties(), array('item_url' => $item->get_item_url())));
			}
		}
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
				AppContext::get_response()->redirect(BroadcastUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_rewrited_title()), StringVars::replace_vars($this->lang['broadcast.message.success.add'], array('title' => $item->get_title())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : BroadcastUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_rewrited_title())), StringVars::replace_vars($this->lang['broadcast.message.success.edit'], array('title' => $item->get_title())));
		}
		else
		{
			if ($this->is_new_item)
				AppContext::get_response()->redirect(BroadcastUrlBuilder::display_pending(), StringVars::replace_vars($this->lang['broadcast.message.success.add'], array('title' => $item->get_title())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : BroadcastUrlBuilder::display_pending()), StringVars::replace_vars($this->lang['broadcast.message.success.edit'], array('title' => $item->get_title())));
		}
	}

	private function generate_response(View $view)
	{
		$item = $this->get_item();

		$location_id = $item->get_id() ? 'broadcast-edit-' . $item->get_id() : '';

		$response = new SiteDisplayResponse($view, $location_id);
		$graphical_environment = $response->get_graphical_environment();

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['broadcast.module.title'], BroadcastUrlBuilder::home());

		if ($this->get_item()->get_id() === null)
		{
			$breadcrumb->add($this->lang['broadcast.add.item'], BroadcastUrlBuilder::add($item->get_id_category()));
			$graphical_environment->set_page_title($this->lang['broadcast.add.item']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['broadcast.add.item'], $this->lang['broadcast.module.title']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(BroadcastUrlBuilder::add($item->get_id_category()));
		}
		else {
			if (!AppContext::get_session()->location_id_already_exists($location_id))
				$graphical_environment->set_location_id($location_id);

			$graphical_environment->set_page_title($this->lang['broadcast.edit.item']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['broadcast.edit.item']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(BroadcastUrlBuilder::edit($item->get_id()));

			$categories = array_reverse(CategoriesService::get_categories_manager()->get_parents($item->get_id_category(), true));
			foreach ($categories as $id => $category)
			{
				if ($category->get_id() != Category::ROOT_CATEGORY)
					$breadcrumb->add($category->get_name(), BroadcastUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
			}
			$category = $item->get_category();
			$breadcrumb->add($item->get_title(), BroadcastUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_rewrited_title()));
			$breadcrumb->add($this->lang['broadcast.edit.item'], BroadcastUrlBuilder::edit($item->get_id()));
		}

		return $response;
	}
}
?>
