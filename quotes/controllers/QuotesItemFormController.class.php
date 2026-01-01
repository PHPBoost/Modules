<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 14
 * @since       PHPBoost 5.0 - 2016 02 18
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class QuotesItemFormController extends DefaultModuleController
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
		$form->set_layout_title($this->item->get_id() === null ? $this->lang['quotes.add.item'] : ($this->lang['quotes.edit.item'] . ': ' . $this->item->get_writer()));

		$fieldset = new FormFieldsetHTML('items', $this->lang['form.parameters']);
		$form->add_fieldset($fieldset);

		if (CategoriesService::get_categories_manager()->get_categories_cache()->has_categories())
		{
			$search_category_children_options = new SearchCategoryChildrensOptions();
			$search_category_children_options->add_authorizations_bits(Category::CONTRIBUTION_AUTHORIZATIONS);
			$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);
			$fieldset->add_field(CategoriesService::get_categories_manager()->get_select_categories_form_field('id_category', $this->lang['form.category'], $this->get_item()->get_id_category(), $search_category_children_options));
		}

		$fieldset->add_field(new FormFieldAjaxCompleter('writer', $this->lang['form.author.custom.name'], $this->get_item()->get_writer(),
			array(
				'required' => true,
				'file' => QuotesUrlBuilder::ajax_writers()->rel()
			)
		));

		$fieldset->add_field(new FormFieldRichTextEditor('content', $this->lang['quotes.form.content'], $this->get_item()->get_content(),
			array('required' => true)
		));


		$this->build_approval_field($fieldset);
		$this->build_contribution_fieldset($form);

		$fieldset->add_field(new FormFieldHidden('referrer', $request->get_url_referrer()));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function build_approval_field($fieldset)
	{
		if (!$this->is_contributor_member())
		{
			$fieldset->add_field(new FormFieldCheckbox('approved', $this->lang['form.approve'], $this->get_item()->is_approved()));
		}
	}

	private function build_contribution_fieldset($form)
	{
		if ($this->item->get_id() === null && $this->is_contributor_member())
		{
			$fieldset = new FormFieldsetHTML('contribution', $this->lang['contribution.contribution']);
			$fieldset->set_description(MessageHelper::display($this->lang['contribution.extended.warning'], MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('contribution_description', $this->lang['contribution.description'], '',
				array('description' => $this->lang['contribution.description.clue'])
			));
		}
		elseif ($this->item->is_approved() && $this->item->is_authorized_to_edit() && $this->is_contributor_member())
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
					$this->item = QuotesService::get_item($id);
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->is_new_item = true;
				$this->item = new QuotesItem();
				$this->item->init_default_properties(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY));
				$rewrited_writer = AppContext::get_request()->get_getvalue('writer', '');
				if ($rewrited_writer)
				{
					if ($writer = QuotesCache::load()->get_writer($rewrited_writer))
					{
						$this->item->set_writer($writer);
						$this->item->set_rewrited_writer($rewrited_writer);
					}
				}
			}
		}
		return $this->item;
	}

	private function check_authorizations()
	{
		$item = $this->get_item();

		if ($item->get_id() === null)
		{
			if (!CategoriesAuthorizationsService::check_authorizations()->write() && !CategoriesAuthorizationsService::check_authorizations()->contribution())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!(CategoriesAuthorizationsService::check_authorizations($item->get_id_category())->moderation() || ((CategoriesAuthorizationsService::check_authorizations($item->get_id_category())->write() || CategoriesAuthorizationsService::check_authorizations($item->get_id_category())->contribution()) && $item->get_author_user()->get_id() == AppContext::get_current_user()->get_id())))
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
	}

	private function save()
	{
		$previous_category_id = $this->item->get_id_category();

		if (CategoriesService::get_categories_manager()->get_categories_cache()->has_categories())
			$this->item->set_id_category($this->form->get_value('id_category')->get_raw_value());

		if (!$this->is_contributor_member() && $this->form->get_value('approved'))
			$this->item->approve();
		else
			$this->item->unapprove();

		$this->item->set_writer($this->form->get_value('writer'));
		$this->item->set_content($this->form->get_value('content'));

		if ($this->item->get_id() === null)
		{
			$id = QuotesService::add($this->item);
			$this->item->set_id($id);

			if (!$this->is_contributor_member())
				HooksService::execute_hook_action('add', self::$module_id, array_merge($this->item->get_properties(), array('item_url' => $this->item->get_item_url())));
		}
		else
		{
			QuotesService::update($this->item);

			if (!$this->is_contributor_member())
				HooksService::execute_hook_action('edit', self::$module_id, array_merge($this->item->get_properties(), array('item_url' => $this->item->get_item_url())));
		}

		$this->contribution_actions($this->item);

		QuotesService::clear_cache();
	}

	private function contribution_actions(QuotesItem $item)
	{
		if ($this->is_contributor_member())
		{
			$contribution = new Contribution();
			$contribution->set_id_in_module($item->get_id());
			if ($this->is_new_item)
				$contribution->set_description(stripslashes($this->form->get_value('contribution_description')));
			else
				$contribution->set_description(stripslashes($this->form->get_value('edition_description')));

			$contribution->set_entitled(StringVars::replace_vars($this->lang['quotes.form.contribution.title'], array('name' => $item->get_writer())));
			$contribution->set_fixing_url(QuotesUrlBuilder::edit($item->get_id())->relative());
			$contribution->set_poster_id(AppContext::get_current_user()->get_id());
			$contribution->set_module('quotes');
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
			$corresponding_contributions = ContributionService::find_by_criteria('quotes', $item->get_id());
			if (!$this->is_contributor_member() && count($corresponding_contributions) > 0)
			{
				$item_contribution = $corresponding_contributions[0];
				$item_contribution->set_status(Event::EVENT_STATUS_PROCESSED);

				ContributionService::save_contribution($item_contribution);
				HooksService::execute_hook_action('process_contribution', self::$module_id, array_merge($contribution->get_properties(), $item->get_properties(), array('item_url' => $item->get_item_url())));
			}
		}
	}

	private function redirect()
	{
		$category = $this->item->get_category();

		if ($this->is_new_item && $this->is_contributor_member() && !$this->item->is_approved())
		{
			DispatchManager::redirect(new UserContributionSuccessController());
		}
		elseif ($this->item->is_approved())
		{
			if ($this->is_new_item)
				AppContext::get_response()->redirect(QuotesUrlBuilder::home(), StringVars::replace_vars($this->lang['quotes.message.success.add'], array('writer' => $this->item->get_writer())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : QuotesUrlBuilder::home()), StringVars::replace_vars($this->lang['quotes.message.success.edit'], array('writer' => $this->item->get_writer())));
		}
		else
		{
			if ($this->is_new_item)
				AppContext::get_response()->redirect(QuotesUrlBuilder::display_pending(), StringVars::replace_vars($this->lang['quotes.message.success.add'], array('writer' => $this->item->get_writer())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : QuotesUrlBuilder::display_pending()), StringVars::replace_vars($this->lang['quotes.message.success.edit'], array('writer' => $this->item->get_writer())));
		}
	}

	private function generate_response(View $view)
	{
		$location_id = $this->item->get_id() ? 'item-edit-'. $this->item->get_id() : '';

		$response = new SiteDisplayResponse($view, $location_id);
		$graphical_environment = $response->get_graphical_environment();

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['quotes.module.title'], QuotesUrlBuilder::home());

		if ($this->item->get_id() === null)
		{
			$graphical_environment->set_page_title($this->lang['quotes.add.item'], $this->lang['quotes.module.title']);
			$breadcrumb->add($this->lang['quotes.add.item'], QuotesUrlBuilder::add($this->item->get_id_category()));
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['quotes.add.item']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(QuotesUrlBuilder::add($this->item->get_id_category()));
		}
		else
		{
			if (!AppContext::get_session()->location_id_already_exists($location_id))
				$graphical_environment->set_location_id($location_id);

			$graphical_environment->set_page_title($this->lang['quotes.edit.item'], $this->lang['quotes.module.title']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['quotes.edit.item']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(QuotesUrlBuilder::edit($this->item->get_id()));

			$categories = array_reverse(CategoriesService::get_categories_manager()->get_parents($this->item->get_id_category(), true));
			foreach ($categories as $id => $category)
			{
				if ($category->get_id() != Category::ROOT_CATEGORY)
					$breadcrumb->add($category->get_name(), QuotesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
			}
			$category = $this->item->get_category();
			$breadcrumb->add($this->lang['quotes.edit.item'], QuotesUrlBuilder::edit($this->item->get_id()));
		}

		return $response;
	}
}
?>
