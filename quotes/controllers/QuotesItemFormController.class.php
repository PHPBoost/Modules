<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 19
 * @since       PHPBoost 5.0 - 2016 02 18
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class QuotesItemFormController extends ModuleController
{
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonSubmit
	 */
	private $submit_button;

	private $lang;
	private $common_lang;

	private $config;

	private $quote;
	private $is_new_quote;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->check_authorizations();

		$this->build_form($request);

		$tpl = new StringTemplate('# INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->redirect();
		}

		$tpl->put('FORM', $this->form->display());

		return $this->generate_response($tpl);
	}

	private function init()
	{
		$this->config = QuotesConfig::load();
		$this->lang = LangLoader::get('common', 'quotes');
		$this->common_lang = LangLoader::get('common');
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('quotes', $this->lang['module_title']);
		$form->add_fieldset($fieldset);

		if (CategoriesService::get_categories_manager()->get_categories_cache()->has_categories())
		{
			$search_category_children_options = new SearchCategoryChildrensOptions();
			$search_category_children_options->add_authorizations_bits(Category::CONTRIBUTION_AUTHORIZATIONS);
			$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);
			$fieldset->add_field(CategoriesService::get_categories_manager()->get_select_categories_form_field('id_category', $this->common_lang['form.category'], $this->get_quote()->get_id_category(), $search_category_children_options));
		}

		$fieldset->add_field(new FormFieldAjaxCompleter('writer', $this->common_lang['writer'], $this->get_quote()->get_writer(),
			array(
				'required' => true,
				'file' => QuotesUrlBuilder::ajax_writers()->rel()
			)
		));

		$fieldset->add_field(new FormFieldRichTextEditor('quote', $this->lang['quote'], $this->get_quote()->get_quote(),
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
			$fieldset->add_field(new FormFieldCheckbox('approved', $this->common_lang['form.approve'], $this->get_quote()->is_approved()));
		}
	}

	private function build_contribution_fieldset($form)
	{
		if ($this->get_quote()->get_id() === null && $this->is_contributor_member())
		{
			$fieldset = new FormFieldsetHTML('contribution', LangLoader::get_message('contribution', 'user-common'));
			$fieldset->set_description(MessageHelper::display(LangLoader::get_message('contribution.explain', 'user-common') . ' ' . $this->lang['quotes.form.contribution.explain'], MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('contribution_description', LangLoader::get_message('contribution.description', 'user-common'), '',
				array('description' => LangLoader::get_message('contribution.description.explain', 'user-common'))
			));
		}
	}

	private function is_contributor_member()
	{
		return (!CategoriesAuthorizationsService::check_authorizations()->write() && CategoriesAuthorizationsService::check_authorizations()->contribution());
	}

	private function get_quote()
	{
		if ($this->quote === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->quote = QuotesService::get_quote('WHERE id=:id', array('id' => $id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->is_new_quote = true;
				$this->quote = new QuotesItem();
				$this->quote->init_default_properties(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY));
				$rewrited_writer = AppContext::get_request()->get_getvalue('writer', '');
				if ($rewrited_writer)
				{
					if ($writer = QuotesCache::load()->get_writer($rewrited_writer))
					{
						$this->quote->set_writer($writer);
						$this->quote->set_rewrited_writer($rewrited_writer);
					}
				}
			}
		}
		return $this->quote;
	}

	private function check_authorizations()
	{
		$quote = $this->get_quote();

		if ($quote->get_id() === null)
		{
			if (!CategoriesAuthorizationsService::check_authorizations()->write() && !CategoriesAuthorizationsService::check_authorizations()->contribution())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!(CategoriesAuthorizationsService::check_authorizations($quote->get_id_category())->moderation() || ((CategoriesAuthorizationsService::check_authorizations($quote->get_id_category())->write() || CategoriesAuthorizationsService::check_authorizations($quote->get_id_category())->contribution()) && $quote->get_author_user()->get_id() == AppContext::get_current_user()->get_id())))
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
	}

	private function save()
	{
		$quote = $this->get_quote();

		$previous_category_id = $quote->get_id_category();

		if (CategoriesService::get_categories_manager()->get_categories_cache()->has_categories())
			$quote->set_id_category($this->form->get_value('id_category')->get_raw_value());

		if (!$this->is_contributor_member() && $this->form->get_value('approved'))
			$quote->approve();
		else
			$quote->unapprove();

		$quote->set_writer($this->form->get_value('writer'));
		$quote->set_quote($this->form->get_value('quote'));

		if ($quote->get_id() === null)
		{
			$id = QuotesService::add($quote);
		}
		else
		{
			$id = $quote->get_id();
			QuotesService::update($quote);
		}

		$this->contribution_actions($quote, $id);

		QuotesService::clear_cache();
	}

	private function contribution_actions(QuotesItem $quote, $id)
	{
		if ($this->is_contributor_member() && $quote->get_id() === null)
		{
			$contribution = new Contribution();
			$contribution->set_id_in_module($id);
			$contribution->set_description(stripslashes($quote->get_quote()));
			$contribution->set_entitled(StringVars::replace_vars($this->lang['quotes.form.contribution.title'], array('name' => $quote->get_writer())));
			$contribution->set_fixing_url(QuotesUrlBuilder::edit($id)->relative());
			$contribution->set_poster_id(AppContext::get_current_user()->get_id());
			$contribution->set_module('quotes');
			$contribution->set_auth(
				Authorizations::capture_and_shift_bit_auth(
					CategoriesService::get_categories_manager()->get_heritated_authorizations($quote->get_id_category(), Category::MODERATION_AUTHORIZATIONS, Authorizations::AUTH_CHILD_PRIORITY),
					Category::MODERATION_AUTHORIZATIONS, Contribution::CONTRIBUTION_AUTH_BIT
				)
			);
			ContributionService::save_contribution($contribution);
		}
		else
		{
			$corresponding_contributions = ContributionService::find_by_criteria('quotes', $id);
			if (!$this->is_contributor_member() && count($corresponding_contributions) > 0)
			{
				$quote_contribution = $corresponding_contributions[0];
				$quote_contribution->set_status(Event::EVENT_STATUS_PROCESSED);

				ContributionService::save_contribution($quote_contribution);
			}
		}
		$quote->set_id($id);
	}

	private function redirect()
	{
		$quote = $this->get_quote();
		$category = $quote->get_category();

		if ($this->is_new_quote && $this->is_contributor_member() && !$quote->is_approved())
		{
			DispatchManager::redirect(new UserContributionSuccessController());
		}
		elseif ($quote->is_approved())
		{
			if ($this->is_new_quote)
				AppContext::get_response()->redirect(QuotesUrlBuilder::home(), StringVars::replace_vars($this->lang['quotes.message.success.add'], array('writer' => $quote->get_writer())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : QuotesUrlBuilder::home()), StringVars::replace_vars($this->lang['quotes.message.success.edit'], array('writer' => $quote->get_writer())));
		}
		else
		{
			if ($this->is_new_quote)
				AppContext::get_response()->redirect(QuotesUrlBuilder::display_pending(), StringVars::replace_vars($this->lang['quotes.message.success.add'], array('writer' => $quote->get_writer())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : QuotesUrlBuilder::display_pending()), StringVars::replace_vars($this->lang['quotes.message.success.edit'], array('writer' => $quote->get_writer())));
		}
	}

	private function generate_response(View $tpl)
	{
		$quote = $this->get_quote();

		$location_id = $quote->get_id() ? 'quotes-edit-'. $quote->get_id() : '';

		$response = new SiteDisplayResponse($tpl, $location_id);
		$graphical_environment = $response->get_graphical_environment();

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], QuotesUrlBuilder::home());

		if ($quote->get_id() === null)
		{
			$graphical_environment->set_page_title($this->lang['quotes.add'], $this->lang['module_title']);
			$breadcrumb->add($this->lang['quotes.add'], QuotesUrlBuilder::add($quote->get_id_category()));
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['quotes.add']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(QuotesUrlBuilder::add($quote->get_id_category()));
		}
		else
		{
			if (!AppContext::get_session()->location_id_already_exists($location_id))
				$graphical_environment->set_location_id($location_id);

			$graphical_environment->set_page_title($this->lang['quotes.edit'], $this->lang['module_title']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['quotes.edit']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(QuotesUrlBuilder::edit($quote->get_id()));

			$categories = array_reverse(CategoriesService::get_categories_manager()->get_parents($quote->get_id_category(), true));
			foreach ($categories as $id => $category)
			{
				if ($category->get_id() != Category::ROOT_CATEGORY)
					$breadcrumb->add($category->get_name(), QuotesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
			}
			$category = $quote->get_category();
			$breadcrumb->add($this->lang['quotes.edit'], QuotesUrlBuilder::edit($quote->get_id()));
		}

		return $response;
	}
}
?>
