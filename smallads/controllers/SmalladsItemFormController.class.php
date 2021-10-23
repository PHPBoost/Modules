<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 08 25
 * @since       PHPBoost 5.1 - 2018 03 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class SmalladsItemFormController extends ModuleController
{
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonSubmit
	 */
	private $submit_button;

	private $view;

	private $lang;
	private $county_lang;
	private $form_lang;

	private $item;
	private $is_new_item;
	private $config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->check_authorizations();
		$this->build_form($request);

		$view = new StringTemplate('# INCLUDE FORM #');
		$view->add_lang(array_merge($this->lang, $this->county_lang));

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->redirect();
		}

		$view->put('FORM', $this->form->display());

		return $this->generate_response($view);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'smallads');
		$this->county_lang = LangLoader::get('counties', 'smallads');
		$this->form_lang = LangLoader::get('form-lang');
		$this->config = SmalladsConfig::load();
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$form = new HTMLForm(__CLASS__);
		$form->set_layout_title($this->item->get_id() === null ? $this->lang['smallads.form.add'] : ($this->lang['smallads.form.edit'] . ': ' . $this->item->get_title()));
		$form->set_css_class('tabs-container fieldset-content');

		$fieldset_tabs_menu = new FormFieldMenuFieldset('tabs_menu', '');
		$form->add_fieldset($fieldset_tabs_menu);
		$fieldset_tabs_menu->set_css_class('tabs-nav');

        $fieldset_tabs_menu->add_field(new FormFieldMultitabsLinkList('tabs_menu_list',
			array(
				new FormFieldMultitabsLinkElement($this->form_lang['form.parameters'], 'tabs', 'SmalladsItemFormController_smallads'),
				new FormFieldMultitabsLinkElement($this->form_lang['form.options'], 'tabs', 'SmalladsItemFormController_other'),
				new FormFieldMultitabsLinkElement($this->form_lang['form.publication'], 'tabs', 'SmalladsItemFormController_publication'),
			)
		));

		$fieldset_tabs_menu->add_field(new FormFieldFree('warning', '', $this->lang['smallads.form.warning'], array('class' => 'message-helper bgc notice')));

		$fieldset = new FormFieldsetMultitabsHTML('smallads', $this->form_lang['form.parameters'],
			array('css_class' => 'tabs tabs-animation first-tab')
		);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('title', $this->form_lang['form.title'], $this->item->get_title(),
			array('required' => true)
		));

		if (CategoriesAuthorizationsService::check_authorizations($this->item->get_id_category())->moderation())
		{
			$fieldset->add_field(new FormFieldCheckbox('personalize_rewrited_title', $this->form_lang['form.rewrited.title.personalize'], $this->item->rewrited_title_is_personalized(),
				array('events' => array('click' =>'
					if (HTMLForms.getField("personalize_rewrited_title").getValue()) {
						HTMLForms.getField("rewrited_title").enable();
					} else {
						HTMLForms.getField("rewrited_title").disable();
					}'
				))
			));

			$fieldset->add_field(new FormFieldTextEditor('rewrited_title', $this->form_lang['form.rewrited.title'], $this->item->get_rewrited_title(),
				array(
					'description' => $this->form_lang['form.rewrited.title.clue'],
			      	'hidden' => ($request->is_post_method() ? !$request->get_postbool(__CLASS__ . '_personalize_rewrited_title', false) : !$this->item->rewrited_title_is_personalized())
			  	),
				array(new FormFieldConstraintRegex('`^[a-z0-9\-]+$`iu'))
			));
		}

		$fieldset->add_field(new FormFieldSimpleSelectChoice('smallad_type', $this->lang['smallads.form.smallad.type'], $this->item->get_smallad_type(), $this->smallad_type_list(),
			array('required' => true)
		));

		if (CategoriesService::get_categories_manager()->get_categories_cache()->has_categories())
		{
			$search_category_children_options = new SearchCategoryChildrensOptions();
			$search_category_children_options->add_authorizations_bits(Category::CONTRIBUTION_AUTHORIZATIONS);
			$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);
			$fieldset->add_field(CategoriesService::get_categories_manager()->get_select_categories_form_field('id_category', $this->form_lang['form.category'], $this->item->get_id_category(), $search_category_children_options,
				array('description' => $this->lang['smallads.select.category'])
			));
		}

		$fieldset->add_field(new FormFieldThumbnail('thumbnail', $this->lang['smallads.form.thumbnail'], $this->item->get_thumbnail()->relative(), SmalladsItem::THUMBNAIL_URL,
			array('description' => $this->lang['smallads.form.thumbnail.clue'])
		));

		$fieldset->add_field(new FormFieldCheckbox('enable_summary', $this->lang['smallads.form.enable.summary'], $this->item->get_summary_enabled(),
			array(
				'description' => StringVars::replace_vars($this->lang['smallads.form.enable.summary.clue'], array('number' => SmalladsConfig::load()->get_characters_number_to_cut())),
				'events' => array('click' => '
					if (HTMLForms.getField("enable_summary").getValue()) {
						HTMLForms.getField("summary").enable();
					} else {
						HTMLForms.getField("summary").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldRichTextEditor('summary', StringVars::replace_vars($this->form_lang['form.summary'], array('number' =>SmalladsConfig::load()->get_characters_number_to_cut())), $this->item->get_summary(),
			array(
				'rows' => 3,
				'hidden' => ($request->is_post_method() ? !$request->get_postbool(__CLASS__ . '_enable_summary', false) : !$this->item->get_summary_enabled())
			)
		));

		$fieldset->add_field(new FormFieldRichTextEditor('content', $this->form_lang['form.content'], $this->item->get_content(),
			array('rows' => 15, 'required' => true)
		));

		$fieldset->add_field(new FormFieldDecimalNumberEditor('price', $this->lang['smallads.form.price'], $this->item->get_price(),
			array(
				'description' => $this->lang['smallads.form.price.clue'],
				'min' => 0,
				'step' => 0.01
			)
		));

		// County
		if($this->config->is_location_displayed()) {
			if($this->config->is_googlemaps_available()) {
				$unserialized_value = @unserialize($this->item->get_location());
				$location_value = $unserialized_value !== false ? $unserialized_value : $this->item->get_location();

				$location = '';
				if (is_array($location_value) && isset($location_value['address']))
					$location = $location_value['address'];
				else if (!is_array($location_value))
					$location = $location_value;

				$fieldset->add_field(new GoogleMapsFormFieldSimpleAddress('location', $this->county_lang['location'], $location,
					array('description' => $this->county_lang['location.clue'])
				));
			}
			else {
				$location = $this->item->get_location();
				$fieldset->add_field(new FormFieldSimpleSelectChoice('location', $this->county_lang['county'], $location, $this->list_counties(),
					array(
						'events' => array('change' =>
							'if (HTMLForms.getField("location").getValue() == "other") {
								HTMLForms.getField("other_location").enable();
							} else {
								HTMLForms.getField("other_location").disable();
							}'
						)
					)
				));

				$fieldset->add_field(new FormFieldTextEditor('other_location', $this->county_lang['other.country'], $this->item->get_other_location(),
					array(
						'description' => $this->county_lang['other.country.explain'],
						'hidden' => ($request->is_post_method() ? !$request->get_postbool(__CLASS__ . '_location', false) : $this->item->get_location() != 'other')
					)
				));
			}
		}

		$other_fieldset = new FormFieldsetMultitabsHTML('other', $this->form_lang['form.options'],
			array('css_class' => 'tabs tabs-animation')
		);
		$form->add_fieldset($other_fieldset);

		if($this->config->is_max_weeks_number_displayed())
		{
			$other_fieldset->add_field(new FormFieldNumberEditor('max_weeks', $this->lang['smallads.form.max.weeks'], $this->item->get_max_weeks(),
				array(
					'min' => 1, 'max' => 52,
					'description' => $this->lang['smallads.form.max.weeks.clue']
				)
			));
		}

		$other_fieldset->add_field(new FormFieldCheckbox('displayed_author_name', $this->form_lang['form.display.author'], $this->item->get_displayed_author_name(),
			array(
				'events' => array('click' => '
					if (HTMLForms.getField("displayed_author_name").getValue()) {
						HTMLForms.getField("enabled_author_name_customization").enable();
						if (HTMLForms.getField("enabled_author_name_customization").getValue()) {
							HTMLForms.getField("custom_author_name").enable();
						}
					} else {
						HTMLForms.getField("enabled_author_name_customization").disable();
						if (HTMLForms.getField("enabled_author_name_customization").getValue()) {
							HTMLForms.getField("custom_author_name").disable();
						}
					}'
				)
			)
		));

		$other_fieldset->add_field(new FormFieldCheckbox('enabled_author_name_customization', $this->lang['smallads.form.author.name.customization'], $this->item->is_enabled_author_name_customization(),
			array(
				'hidden' => ($request->is_post_method() ? !$request->get_postbool(__CLASS__ . '_displayed_author_name', false) : !$this->item->is_displayed_author_name()),
				'events' => array('click' => '
					if (HTMLForms.getField("enabled_author_name_customization").getValue()) {
						HTMLForms.getField("custom_author_name").enable();
					} else {
						HTMLForms.getField("custom_author_name").disable();
					}'
				)
			)
		));

		$other_fieldset->add_field(new FormFieldTextEditor('custom_author_name', $this->lang['smallads.form.custom.author.name'], $this->item->get_custom_author_name(), array(
			'hidden' => ($request->is_post_method() ? !$request->get_postbool(__CLASS__ . '_enabled_author_name_customization', false) : !$this->item->is_displayed_author_name() || !$this->item->is_enabled_author_name_customization())
		)));

		$other_fieldset->add_field(KeywordsService::get_keywords_manager()->get_form_field($this->item->get_id(), 'keywords', $this->form_lang['form.keywords'],
			array('description' => $this->form_lang['form.keywords.clue'])
		));

		$other_fieldset->add_field(new FormFieldSelectSources('sources', $this->form_lang['form.sources'], $this->item->get_sources()));

		$other_fieldset->add_field(new SmalladsFormFieldCarousel('carousel', $this->lang['smallads.form.carousel'], $this->item->get_carousel()));

		if($this->config->is_email_displayed() || $this->config->is_pm_displayed() || $this->config->is_phone_displayed())
		{
			$other_fieldset->add_field(new FormFieldSubTitle('contact', $this->lang['smallads.form.contact'], ''));

			if($this->config->is_pm_displayed())
				$other_fieldset->add_field(new FormFieldCheckbox('displayed_author_pm', $this->lang['smallads.form.display.author.pm'], $this->item->get_displayed_author_pm()));

			if($this->config->is_email_displayed())
			{
				$other_fieldset->add_field(new FormFieldCheckbox('displayed_author_email', $this->lang['smallads.form.display.author.email'], $this->item->get_displayed_author_email(),
					array(
						'events' => array('click' => '
							if (HTMLForms.getField("displayed_author_email").getValue()) {
								HTMLForms.getField("enabled_author_email_customization").enable();
									if (HTMLForms.getField("enabled_author_email_customization").getValue()) {
										HTMLForms.getField("custom_author_email").enable();
									}
							} else {
								HTMLForms.getField("enabled_author_email_customization").disable();
									if (HTMLForms.getField("enabled_author_email_customization").getValue()) {
										HTMLForms.getField("custom_author_email").disable();
									}
							}'
						)
					)
				));

				$other_fieldset->add_field(new FormFieldCheckbox('enabled_author_email_customization', $this->lang['smallads.form.author.email.customization'], $this->item->is_enabled_author_email_customization(),
					array(
						'description' => $this->lang['smallads.form.author.email.customization.clue'],
						'hidden' => ($request->is_post_method() ? !$request->get_postbool(__CLASS__ . '_displayed_author_email', false) : !$this->item->is_displayed_author_email()),
						'events' => array('click' => '
							if (HTMLForms.getField("enabled_author_email_customization").getValue()) {
								HTMLForms.getField("custom_author_email").enable();
							} else {
								HTMLForms.getField("custom_author_email").disable();
							}'
						)
					)
				));

				$other_fieldset->add_field(new FormFieldMailEditor('custom_author_email', $this->lang['smallads.form.custom.author.email'], $this->item->get_custom_author_email(),
					array( 'hidden' => ($request->is_post_method() ? !$request->get_postbool(__CLASS__ . '_enabled_author_email_customization', false) : !$this->item->is_displayed_author_email() || !$this->item->is_enabled_author_email_customization()))
				));
			}

			if($this->config->is_phone_displayed())
			{
				$other_fieldset->add_field(new FormFieldCheckbox('displayed_author_phone', $this->lang['smallads.form.display.author.phone'], $this->item->get_displayed_author_phone(),
					array(
						'events' => array('click' => '
							if (HTMLForms.getField("displayed_author_phone").getValue()) {
								HTMLForms.getField("author_phone").enable();
							} else {
								HTMLForms.getField("author_phone").disable();
							}'
						)
					)
				));

				$other_fieldset->add_field(new FormFieldTelEditor('author_phone', $this->lang['smallads.form.author.phone'], $this->item->get_author_phone(),
					array('hidden' => ($request->is_post_method() ? !$request->get_postbool(__CLASS__ . '_displayed_author_phone', false) : !$this->item->get_displayed_author_phone()))
				));
			}
		}

		$publication_fieldset = new FormFieldsetMultitabsHTML('publication', $this->form_lang['form.publication'],
			array('css_class' => 'tabs tabs-animation')
		);
		$form->add_fieldset($publication_fieldset);

		if($this->item->get_id() !== null)
		{
			$publication_fieldset->add_field(new FormFieldCheckbox('completed', $this->lang['smallads.form.completed'], $this->item->get_completed(),
				array('description' => StringVars::replace_vars($this->lang['smallads.form.completed.warning'],array('delay' => SmalladsConfig::load()->get_display_delay_before_delete())))
			));
		}

		if ($this->item->is_archived())
		{
			$publication_fieldset->add_field(new FormFieldCheckbox('unarchived', $this->lang['smallads.form.unarchive'], !$this->item->is_archived(),
				array('description' => $this->lang['smallads.form.unarchive.clue'])
			));
		}

		if (CategoriesAuthorizationsService::check_authorizations($this->item->get_id_category())->moderation())
		{

			$publication_fieldset->add_field(new FormFieldDateTime('creation_date', $this->form_lang['form.creation.date'], $this->item->get_creation_date(),
				array('required' => true)
			));

			if (!$this->item->is_published())
			{
				$publication_fieldset->add_field(new FormFieldCheckbox('update_creation_date', $this->form_lang['form.update.creation.date'], false,
					array('hidden' => $this->item->get_status() != SmalladsItem::NOT_PUBLISHED)
				));
			}

			$publication_fieldset->add_field(new FormFieldSimpleSelectChoice('publication_state', $this->form_lang['form.publication'], $this->item->get_publication_state(),
				array(
					new FormFieldSelectChoiceOption($this->form_lang['form.publication.draft'], SmalladsItem::NOT_PUBLISHED),
					new FormFieldSelectChoiceOption($this->form_lang['form.publication.now'], SmalladsItem::PUBLISHED_NOW),
					new FormFieldSelectChoiceOption($this->form_lang['form.publication.deffered'], SmalladsItem::PUBLICATION_DATE),
				),
				array(
					'events' => array('change' => '
						if (HTMLForms.getField("publication_state").getValue() == 2) {
							jQuery("#' . __CLASS__ . '_publishing_start_date_field").show();
							HTMLForms.getField("end_date_enable").enable();
							if (HTMLForms.getField("end_date_enable").getValue()) {
								HTMLForms.getField("publishing_end_date").enable();
							}
						} else {
							jQuery("#' . __CLASS__ . '_publishing_start_date_field").hide();
							HTMLForms.getField("end_date_enable").disable();
							HTMLForms.getField("publishing_end_date").disable();
						}'
					)
				)
			));

			$publication_fieldset->add_field($publishing_start_date = new FormFieldDateTime('publishing_start_date', $this->form_lang['form.start.date'], ($this->item->get_publishing_start_date() === null ? new Date() : $this->item->get_publishing_start_date()),
				array('hidden' => ($request->is_post_method() ? ($request->get_postint(__CLASS__ . '_publication_state', 0) != SmalladsItem::PUBLICATION_DATE) : ($this->item->get_publication_state() != SmalladsItem::PUBLICATION_DATE)))
			));

			$publication_fieldset->add_field(new FormFieldCheckbox('end_date_enable', $this->form_lang['form.enable.end.date'], $this->item->enabled_end_date(),
				array(
					'hidden' => ($request->is_post_method() ? ($request->get_postint(__CLASS__ . '_publication_state', 0) != SmalladsItem::PUBLICATION_DATE) : ($this->item->get_publication_state() != SmalladsItem::PUBLICATION_DATE)),
					'events' => array('click' => '
						if (HTMLForms.getField("end_date_enable").getValue()) {
							HTMLForms.getField("publishing_end_date").enable();
						} else {
							HTMLForms.getField("publishing_end_date").disable();
						}'
					)
				)
			));

			$publication_fieldset->add_field($publishing_end_date = new FormFieldDateTime('publishing_end_date', $this->form_lang['form.end.date'], ($this->item->get_publishing_end_date() === null ? new date() : $this->item->get_publishing_end_date()),
				array('hidden' => ($request->is_post_method() ? !$request->get_postbool(__CLASS__ . '_end_date_enable', false) : !$this->item->enabled_end_date()))
			));

			$publishing_end_date->add_form_constraint(new FormConstraintFieldsDifferenceSuperior($publishing_start_date, $publishing_end_date));
		}

		$this->build_contribution_fieldset($form);

		$fieldset->add_field(new FormFieldHidden('referrer', $request->get_url_referrer()));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function smallad_type_list()
	{
		$options = array();
		$this->config = SmalladsConfig::load();
		$smallad_types = $this->config->get_smallad_types();

		// laisser un vide en début de liste
		$options[] = new FormFieldSelectChoiceOption('', '');

		$i = 1;
		foreach($smallad_types as $name)
		{
			$options[] = new FormFieldSelectChoiceOption($name, TextHelper::htmlspecialchars(Url::encode_rewrite($name)));
			$i++;
		}

		return $options;
	}

	// private function brand_list()
	// {
	// 	$options = array();
	// 	$this->config = SmalladsConfig::load();
	// 	$brands = $this->config->get_brand();
	//
	// 	// laisser un vide en début de liste
	// 	$options[] = new FormFieldSelectChoiceOption('', '');
	//
	// 	$i = 0;
	// 	foreach($brands as $name)
	// 	{
	// 		$options[] = new FormFieldSelectChoiceOption($name, str_replace(' ', '-', $name));
	// 		$i++;
	// 	}
	//
	// 	return $options;
	// }

	private function build_contribution_fieldset($form)
	{
		$contribution = LangLoader::get('contribution-lang');
		if ($this->item->get_id() === null && $this->is_contributor_member())
		{
			$fieldset = new FormFieldsetHTML('contribution', $contribution['contribution.contribution']);
			$fieldset->set_description(MessageHelper::display($contribution['contribution.extended.warning'], MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('contribution_description', $contribution['contribution.description'], '',
				array('description' => $contribution['contribution.description.clue'])
			));
		}
		elseif ($this->item->is_published() && $this->item->is_authorized_to_edit() && !AppContext::get_current_user()->check_level(User::ADMINISTRATOR_LEVEL))
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
				try
				{
					$this->item = SmalladsService::get_item('WHERE smallads.id=:id', array('id' => $id));
				}
				catch(RowNotFoundException $e)
				{
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->is_new_item = true;
				$this->item = new SmalladsItem();
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

	private function list_counties()
	{
		$installed_lang = LangsManager::get_lang(LangsManager::get_default_lang())->get_configuration()->get_name();
		$options = array();

		$options[] = new FormFieldSelectChoiceOption('', '');

		$options[] = new FormFieldSelectChoiceOption($this->county_lang['other.country'], 'other');

		if($installed_lang == 'Français')
		{
			for ($i = 1; $i <= 97 ; $i++)
			{
				if ($i == 20)
				{
					$options[] = new FormFieldSelectChoiceOption($this->county_lang['county.2A'], '2A');
					$options[] = new FormFieldSelectChoiceOption($this->county_lang['county.2B'], '2B');
				}
				else if ($i == 96)
				{
					$options[] = new FormFieldSelectChoiceOption($this->county_lang['county.971'], '971');
					$options[] = new FormFieldSelectChoiceOption($this->county_lang['county.972'], '972');
					$options[] = new FormFieldSelectChoiceOption($this->county_lang['county.973'], '973');
					$options[] = new FormFieldSelectChoiceOption($this->county_lang['county.974'], '974');
					$options[] = new FormFieldSelectChoiceOption($this->county_lang['county.975'], '975');
					$options[] = new FormFieldSelectChoiceOption($this->county_lang['county.976'], '976');
					$options[] = new FormFieldSelectChoiceOption($this->county_lang['county.977'], '977');
					$options[] = new FormFieldSelectChoiceOption($this->county_lang['county.978'], '978');
				}
				else if ($i == 97)
				{
					$options[] = new FormFieldSelectChoiceOption($this->county_lang['county.984'], '984');
					$options[] = new FormFieldSelectChoiceOption($this->county_lang['county.986'], '986');
					$options[] = new FormFieldSelectChoiceOption($this->county_lang['county.987'], '987');
					$options[] = new FormFieldSelectChoiceOption($this->county_lang['county.988'], '974');
					$options[] = new FormFieldSelectChoiceOption($this->county_lang['county.989'], '989');
				}
				else
					$options[] = new FormFieldSelectChoiceOption($this->county_lang['county.' . $i], $i);
			}
		}
		else if ($installed_lang == 'English')
		{
			for ($i = 1; $i <= 48 ; $i++)
			{
				$options[] = new FormFieldSelectChoiceOption($this->county_lang['county.' . $i], $i);
			}
		}


		return $options;
	}

	private function save()
	{
		$this->item->set_title($this->form->get_value('title'));

		if (CategoriesService::get_categories_manager()->get_categories_cache()->has_categories())
			$this->item->set_id_category($this->form->get_value('id_category')->get_raw_value());

		$this->item->set_summary(($this->form->get_value('enable_summary') ? $this->form->get_value('summary') : ''));
		$this->item->set_content($this->form->get_value('content'));

		if(empty($this->form->get_value('price')))
			$this->item->set_price('0');
		else
			$this->item->set_price($this->form->get_value('price'));

		$this->item->set_smallad_type($this->form->get_value('smallad_type')->get_raw_value());
		// $this->item->set_brand($this->form->get_value('brand')->get_raw_value());

		$this->item->set_thumbnail($this->form->get_value('thumbnail'));

		if($this->config->is_max_weeks_number_displayed())
		{
			if(empty($this->form->get_value('max_weeks')) || $this->form->get_value('max_weeks') === 0)
				$this->item->set_max_weeks(SmalladsConfig::load()->get_max_weeks_number());
			else
				$this->item->set_max_weeks($this->form->get_value('max_weeks'));
		}

		if($this->config->is_location_displayed()) {
			if($this->config->is_googlemaps_available()){
				$this->item->set_location($this->form->get_value('location'));
			} else {
				$location = $this->form->get_value('location')->get_raw_value();
				$this->item->set_location($location);
				if ($location === 'other')
					$this->item->set_other_location($this->form->get_value('other_location'));
				else
					$this->item->set_other_location('');
			}
		}

		$displayed_author_phone = $this->form->get_value('displayed_author_phone') ? $this->form->get_value('displayed_author_phone') : SmalladsItem::NOT_DISPLAYED_AUTHOR_PHONE;
		$this->item->set_displayed_author_phone($displayed_author_phone);

		if ($this->item->get_displayed_author_phone() == true)
			$this->item->set_author_phone($this->form->get_value('author_phone'));

		$displayed_author_pm = $this->form->get_value('displayed_author_pm') ? $this->form->get_value('displayed_author_pm') : SmalladsItem::NOT_DISPLAYED_AUTHOR_PM;
		$this->item->set_displayed_author_pm($displayed_author_pm);

		$displayed_author_email = $this->form->get_value('displayed_author_email') ? $this->form->get_value('displayed_author_email') : SmalladsItem::NOT_DISPLAYED_AUTHOR_EMAIL;
		$this->item->set_displayed_author_email($displayed_author_email);

		if ($this->item->get_displayed_author_email() == true)
			$this->item->set_custom_author_email(($this->form->get_value('custom_author_email') && $this->form->get_value('custom_author_email') !== $this->item->get_author_user()->get_email() ? $this->form->get_value('custom_author_email') : ''));

		$displayed_author_name = $this->form->get_value('displayed_author_name') ? $this->form->get_value('displayed_author_name') : SmalladsItem::NOT_DISPLAYED_AUTHOR_NAME;
		$this->item->set_displayed_author_name($displayed_author_name);

		if ($this->item->get_displayed_author_name() == true)
			$this->item->set_custom_author_name(($this->form->get_value('custom_author_name') && $this->form->get_value('custom_author_name') !== $this->item->get_author_user()->get_display_name() ? $this->form->get_value('custom_author_name') : ''));

		$this->item->set_sources($this->form->get_value('sources'));
		$this->item->set_carousel($this->form->get_value('carousel'));

		if($this->item->get_id() !== null)
			$this->item->set_completed($this->form->get_value('completed'));

		if ($this->item->is_archived()) {
			if ($this->form->get_value('unarchived'))
				$this->item->set_archived(SmalladsItem::NOT_ARCHIVED);
		}


		if (!CategoriesAuthorizationsService::check_authorizations($this->item->get_id_category())->moderation())
		{
			if ($this->item->get_id() === null)
				$this->item->set_creation_date(new Date());

			$this->item->set_rewrited_title(Url::encode_rewrite($this->item->get_title()));
			$this->item->clean_publication_start_and_end_date();

			if (CategoriesAuthorizationsService::check_authorizations($this->item->get_id_category())->contribution() && !CategoriesAuthorizationsService::check_authorizations($this->item->get_id_category())->write())
				$this->item->set_publication_state(SmalladsItem::NOT_PUBLISHED);
		}
		else
		{
			if ($this->form->get_value('update_creation_date'))
			{
				$this->item->set_creation_date(new Date());
			}
			else
			{
				$this->item->set_creation_date($this->form->get_value('creation_date'));
			}

			$rewrited_title = $this->form->get_value('rewrited_title', '');
			$rewrited_title = $this->form->get_value('personalize_rewrited_title') && !empty($rewrited_title) ? $rewrited_title : Url::encode_rewrite($this->item->get_title());
			$this->item->set_rewrited_title($rewrited_title);

			$this->item->set_publication_state($this->form->get_value('publication_state')->get_raw_value());
			if ($this->item->get_publication_state() == SmalladsItem::PUBLICATION_DATE)
			{
				$config = SmalladsConfig::load();
				$deferred_operations = $config->get_deferred_operations();

				$old_start_date = $this->item->get_publishing_start_date();
				$start_date = $this->form->get_value('publishing_start_date');
				$this->item->set_publishing_start_date($start_date);

				if ($old_start_date !== null && $old_start_date->get_timestamp() != $start_date->get_timestamp() && in_array($old_start_date->get_timestamp(), $deferred_operations))
				{
					$key = array_search($old_start_date->get_timestamp(), $deferred_operations);
					unset($deferred_operations[$key]);
				}

				if (!in_array($start_date->get_timestamp(), $deferred_operations))
					$deferred_operations[] = $start_date->get_timestamp();

				if ($this->form->get_value('end_date_enable'))
				{
					$old_end_date = $this->item->get_publishing_end_date();
					$end_date = $this->form->get_value('publishing_end_date');
					$this->item->set_publishing_end_date($end_date);

					if ($old_end_date !== null && $old_end_date->get_timestamp() != $end_date->get_timestamp() && in_array($old_end_date->get_timestamp(), $deferred_operations))
					{
						$key = array_search($old_end_date->get_timestamp(), $deferred_operations);
						unset($deferred_operations[$key]);
					}

					if (!in_array($end_date->get_timestamp(), $deferred_operations))
						$deferred_operations[] = $end_date->get_timestamp();
				}
				else
				{
					$this->item->clean_publishing_end_date();
				}

				$config->set_deferred_operations($deferred_operations);
				SmalladsConfig::save();
			}
			else
			{
				$this->item->clean_publication_start_and_end_date();
			}
		}

		if ($this->item->get_id() === null)
		{
			$this->item->set_author_user(AppContext::get_current_user());
			$item_id = SmalladsService::add($this->item);
		}
		else
		{
			$now = new Date();
			$this->item->set_update_date($now);
			$item_id = $this->item->get_id();
			SmalladsService::update($this->item);
		}

		$this->contribution_actions($this->item, $item_id);

		KeywordsService::get_keywords_manager()->put_relations($item_id, $this->form->get_value('keywords'));

		SmalladsService::clear_cache();
	}

	private function contribution_actions(SmalladsItem $item, $item_id)
	{
		if ($this->is_contributor_member())
		{
			$contribution = new Contribution();
			$contribution->set_id_in_module($item_id);
			if ($item->get_id() === null)
				$contribution->set_description(stripslashes($this->form->get_value('contribution_description')));
			else
				$contribution->set_description(stripslashes($this->form->get_value('edition_description')));

			$contribution->set_entitled($item->get_title());
			$contribution->set_fixing_url(SmalladsUrlBuilder::edit_item($item_id)->relative());
			$contribution->set_poster_id(AppContext::get_current_user()->get_id());
			$contribution->set_module('smallads');
			$contribution->set_auth(
				Authorizations::capture_and_shift_bit_auth(
					CategoriesService::get_categories_manager()->get_heritated_authorizations($item->get_id_category(), Category::MODERATION_AUTHORIZATIONS, Authorizations::AUTH_CHILD_PRIORITY),
					Category::MODERATION_AUTHORIZATIONS, Contribution::CONTRIBUTION_AUTH_BIT
				)
			);
			ContributionService::save_contribution($contribution);
		}
		else
		{
			$corresponding_contributions = ContributionService::find_by_criteria('smallads', $item_id);
			if (count($corresponding_contributions) > 0)
			{
				foreach ($corresponding_contributions as $contribution)
				{
					$contribution->set_status(Event::EVENT_STATUS_PROCESSED);
					ContributionService::save_contribution($contribution);
				}
			}
		}
		$item->set_id($item_id);
	}

	private function redirect()
	{
		$category = $this->item->get_category();

		if ($this->is_new_item && $this->is_contributor_member() && !$this->item->is_published())
		{
			DispatchManager::redirect(new UserContributionSuccessController());
		}
		elseif ($this->item->is_published())
		{
			if ($this->is_new_item)
				AppContext::get_response()->redirect(SmalladsUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title()), StringVars::replace_vars($this->lang['smallads.message.success.add'], array('title' => $this->item->get_title())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : SmalladsUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title())), StringVars::replace_vars($this->lang['smallads.message.success.edit'], array('title' => $this->item->get_title())));
		}
		else
		{
			if ($this->is_new_item)
				AppContext::get_response()->redirect(SmalladsUrlBuilder::display_pending_items(), StringVars::replace_vars($this->lang['smallads.message.success.add'], array('title' => $this->item->get_title())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : SmalladsUrlBuilder::display_pending_items()), StringVars::replace_vars($this->lang['smallads.message.success.edit'], array('title' => $this->item->get_title())));
		}
	}

	private function generate_response(View $view)
	{
		$location_id = $this->item->get_id() ? 'item-edit-'. $this->item->get_id() : '';

		$response = new SiteDisplayResponse($view, $location_id);
		$graphical_environment = $response->get_graphical_environment();

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['smallads.module.title'], SmalladsUrlBuilder::home());

		if ($this->item->get_id() === null)
		{
			$breadcrumb->add($this->lang['smallads.add.item'], SmalladsUrlBuilder::add_item($this->item->get_id_category()));
			$graphical_environment->set_page_title($this->lang['smallads.add.item'], $this->lang['smallads.module.title']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['smallads.add.item']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(SmalladsUrlBuilder::add_item($this->item->get_id_category()));
		}
		else
		{
			$categories = array_reverse(CategoriesService::get_categories_manager()->get_parents($this->item->get_id_category(), true));
			foreach ($categories as $id => $category)
			{
				if ($category->get_id() != Category::ROOT_CATEGORY)
					$breadcrumb->add($category->get_name(), SmalladsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
			}
			$breadcrumb->add($this->item->get_title(), SmalladsUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title()));

			$breadcrumb->add($this->lang['smallads.edit.item'], SmalladsUrlBuilder::edit_item($this->item->get_id()));

			if (!AppContext::get_session()->location_id_already_exists($location_id))
				$graphical_environment->set_location_id($location_id);

			$graphical_environment->set_page_title($this->lang['smallads.edit.item'], $this->lang['smallads.module.title']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['smallads.edit.item']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(SmalladsUrlBuilder::edit_item($this->item->get_id()));
		}

		return $response;
	}
}
?>
