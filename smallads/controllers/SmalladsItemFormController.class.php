<?php
/*##################################################
 *                       SmalladsItemFormController.class.php
 *                            -------------------
 *   begin                : March 15, 2018
 *   copyright            : (C) 2018 Sebastien LARTIGUE
 *   email                : babsolune@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
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

	private $tpl;

	private $lang;
	private $county_lang;
	private $common_lang;

	private $smallad;
	private $is_new_smallad;
	private $config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->check_authorizations();
		$this->build_form($request);

		$tpl = new StringTemplate('# INCLUDE FORM #');
		$tpl->add_lang($this->lang);
		$tpl->add_lang($this->county_lang);

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
		$this->lang = LangLoader::get('common', 'smallads');
		$this->county_lang = LangLoader::get('counties', 'smallads');
		$this->common_lang = LangLoader::get('common');
		$this->config = SmalladsConfig::load();
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('smallads', $this->get_smallad()->get_id() === null ? $this->lang['smallads.form.add'] : $this->lang['smallads.form.edit']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('title', $this->common_lang['form.title'], $this->get_smallad()->get_title(),
			array('required' => true)
		));

		if (SmalladsAuthorizationsService::check_authorizations($this->get_smallad()->get_id_category())->moderation())
		{
			$fieldset->add_field(new FormFieldCheckbox('personalize_rewrited_title', $this->common_lang['form.rewrited_name.personalize'], $this->get_smallad()->rewrited_title_is_personalized(),
				array('events' => array('click' =>'
					if (HTMLForms.getField("personalize_rewrited_title").getValue()) {
						HTMLForms.getField("rewrited_title").enable();
					} else {
						HTMLForms.getField("rewrited_title").disable();
					}'
				))
			));

			$fieldset->add_field(new FormFieldTextEditor('rewrited_title', $this->common_lang['form.rewrited_name'], $this->get_smallad()->get_rewrited_title(),
				array('description' => $this->common_lang['form.rewrited_name.description'],
				      'hidden' => !$this->get_smallad()->rewrited_title_is_personalized()),
				array(new FormFieldConstraintRegex('`^[a-z0-9\-]+$`iu'))
			));
		}

		$fieldset->add_field(new FormFieldSimpleSelectChoice('smallad_type', $this->lang['smallads.form.smallad.type'], $this->get_smallad()->get_smallad_type(), $this->smallad_type_list(),
			array('required' => true)
		));

		if (SmalladsService::get_categories_manager()->get_categories_cache()->has_categories())
		{
			$search_category_children_options = new SearchCategoryChildrensOptions();
			$search_category_children_options->add_authorizations_bits(Category::CONTRIBUTION_AUTHORIZATIONS);
			$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);
			$fieldset->add_field(SmalladsService::get_categories_manager()->get_select_categories_form_field('id_category', $this->lang['smallads.category'], $this->get_smallad()->get_id_category(), $search_category_children_options,
				array('description' => $this->lang['smallads.select.category'])
			));
		}

		$fieldset->add_field(new FormFieldUploadPictureFile('thumbnail', $this->lang['smallads.form.thumbnail'], $this->get_smallad()->get_thumbnail()->relative(),
			array('description' => $this->lang['smallads.form.thumbnail.desc'])
		));

		$fieldset->add_field(new FormFieldCheckbox('enable_description', $this->lang['smallads.form.enabled.description'], $this->get_smallad()->get_description_enabled(),
			array('description' => StringVars::replace_vars($this->lang['smallads.form.enabled.description.description'],
			array('number' => SmalladsConfig::load()->get_characters_number_to_cut())),
				'events' => array('click' => '
					if (HTMLForms.getField("enable_description").getValue()) {
						HTMLForms.getField("description").enable();
					} else {
						HTMLForms.getField("description").disable();
					}'
		))));

		$fieldset->add_field(new FormFieldRichTextEditor('description', StringVars::replace_vars($this->lang['smallads.form.description'],
			array('number' =>SmalladsConfig::load()->get_characters_number_to_cut())), $this->get_smallad()->get_description(),
			array('rows' => 3, 'hidden' => !$this->get_smallad()->get_description_enabled())
		));

		$fieldset->add_field(new FormFieldRichTextEditor('contents', $this->common_lang['form.contents'], $this->get_smallad()->get_contents(),
			array('rows' => 15, 'required' => true)
		));

		$fieldset->add_field(new FormFieldDecimalNumberEditor('price', $this->lang['smallads.form.price'], $this->get_smallad()->get_price(),
			array(
				'description' => $this->lang['smallads.form.price.desc'],
				'min' => 0,
				'step' => 0.01
		)));

		// County
		if($this->config->is_location_displayed()) {
			if($this->config->is_googlemaps_available()) {
				$unserialized_value = @unserialize($this->get_smallad()->get_location());
				$location_value = $unserialized_value !== false ? $unserialized_value : $this->get_smallad()->get_location();

				$location = '';
				if (is_array($location_value) && isset($location_value['address']))
					$location = $location_value['address'];
				else if (!is_array($location_value))
					$location = $location_value;

				$fieldset->add_field(new GoogleMapsFormFieldSimpleAddress('location', $this->county_lang['location'], $location,
					array('description' => $this->county_lang['location.desc'])
				));
			}
			else {
				$location = $this->get_smallad()->get_location();
				$fieldset->add_field(new FormFieldSimpleSelectChoice('location', $this->county_lang['county'], $location, $this->list_counties(),
					array('events' => array('change' =>
						'if (HTMLForms.getField("location").getValue() == "other") {
							HTMLForms.getField("other_location").enable();
						} else {
							HTMLForms.getField("other_location").disable();
						}'
					))
				));

				$fieldset->add_field(new FormFieldTextEditor('other_location', $this->county_lang['other.country'], $this->get_smallad()->get_other_location(),
					array('description' => $this->county_lang['other.country.explain'], 'hidden' => $this->get_smallad()->get_location() != 'other')
				));
			}
		}

		if($this->config->is_email_displayed() || $this->config->is_pm_displayed() || $this->config->is_phone_displayed())
		{
			$contact_fieldset = new FormFieldsetHTML('contact', $this->lang['smallads.form.contact']);
			$form->add_fieldset($contact_fieldset);

			if($this->config->is_pm_displayed())
				$contact_fieldset->add_field(new FormFieldCheckbox('displayed_author_pm', $this->lang['smallads.form.displayed.author.pm'], $this->get_smallad()->get_displayed_author_pm()));

			if($this->config->is_email_displayed())
			{
				$contact_fieldset->add_field(new FormFieldCheckbox('displayed_author_email', $this->lang['smallads.form.displayed.author.email'], $this->get_smallad()->get_displayed_author_email(),
					array('events' => array('click' => '
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
					}'))
				));

				$contact_fieldset->add_field(new FormFieldCheckbox('enabled_author_email_customization', $this->lang['smallads.form.enabled.author.email.customisation'], $this->get_smallad()->is_enabled_author_email_customization(),
					array(
						'description' => $this->lang['smallads.form.enabled.author.email.customisation.desc'],
						'hidden' => !$this->get_smallad()->is_displayed_author_email(),
						'events' => array('click' => '
							if (HTMLForms.getField("enabled_author_email_customization").getValue()) {
								HTMLForms.getField("custom_author_email").enable();
							} else {
								HTMLForms.getField("custom_author_email").disable();
							}'))
				));

				$contact_fieldset->add_field(new FormFieldMailEditor('custom_author_email', $this->lang['smallads.form.custom.author.email'], $this->get_smallad()->get_custom_author_email(),
					array( 'hidden' => !$this->get_smallad()->is_displayed_author_email() || !$this->get_smallad()->is_enabled_author_email_customization())
				));
			}

			if($this->config->is_phone_displayed())
			{
				$contact_fieldset->add_field(new FormFieldCheckbox('displayed_author_phone', $this->lang['smallads.form.displayed.author.phone'], $this->get_smallad()->get_displayed_author_phone(),
					array(
						'events' => array('click' => '
							if (HTMLForms.getField("displayed_author_phone").getValue()) {
								HTMLForms.getField("author_phone").enable();
							} else {
								HTMLForms.getField("author_phone").disable();
							}'))
				));

				$contact_fieldset->add_field(new FormFieldTelEditor('author_phone', $this->lang['smallads.form.author.phone'], $this->get_smallad()->get_author_phone(), array(
					'hidden' => !$this->get_smallad()->get_displayed_author_phone(),
				)));
			}
		}

		$other_fieldset = new FormFieldsetHTML('other', $this->common_lang['form.other']);
		$form->add_fieldset($other_fieldset);

		if($this->config->is_max_weeks_number_displayed())
		{
			$other_fieldset->add_field(new FormFieldNumberEditor('max_weeks', $this->lang['smallads.form.max.weeks'], $this->get_smallad()->get_max_weeks(),
				array('min' => 1, 'max' => 52)
			));
		}

		$other_fieldset->add_field(new FormFieldCheckbox('displayed_author_name', LangLoader::get_message('config.author_displayed', 'admin-common'), $this->get_smallad()->get_displayed_author_name(),
			array('events' => array('click' => '
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
			}'))
		));

		$other_fieldset->add_field(new FormFieldCheckbox('enabled_author_name_customization', $this->lang['smallads.form.enabled.author.name.customisation'], $this->get_smallad()->is_enabled_author_name_customization(),
			array(
				'hidden' => !$this->get_smallad()->is_displayed_author_name(),
				'events' => array('click' => '
					if (HTMLForms.getField("enabled_author_name_customization").getValue()) {
						HTMLForms.getField("custom_author_name").enable();
					} else {
						HTMLForms.getField("custom_author_name").disable();
					}'))
		));

		$other_fieldset->add_field(new FormFieldTextEditor('custom_author_name', $this->lang['smallads.form.custom.author.name'], $this->get_smallad()->get_custom_author_name(), array(
			'hidden' => !$this->get_smallad()->is_displayed_author_name() ||  !$this->get_smallad()->is_enabled_author_name_customization(),
		)));

		$other_fieldset->add_field(SmalladsService::get_keywords_manager()->get_form_field($this->get_smallad()->get_id(), 'keywords', $this->common_lang['form.keywords'],
			array('description' => $this->common_lang['form.keywords.description'])
		));

		$other_fieldset->add_field(new SmalladsFormFieldSelectSources('sources', $this->common_lang['form.sources'], $this->get_smallad()->get_sources()));

		$other_fieldset->add_field(new SmalladsFormFieldCarousel('carousel', $this->lang['smallads.form.carousel'], $this->get_smallad()->get_carousel()));

		if($this->get_smallad()->get_id() !== null)
		{
			$completed_fieldset = new FormFieldsetHTML('completed_ad', $this->lang['smallads.form.completed.ad']);
			$form->add_fieldset($completed_fieldset);

			$completed_fieldset->add_field(new FormFieldCheckbox('completed', $this->lang['smallads.form.completed'], $this->get_smallad()->get_completed(),
				array('description' => StringVars::replace_vars($this->lang['smallads.form.completed.warning']
				,array('delay' => SmalladsConfig::load()->get_display_delay_before_delete()))
			)));
		}

		if (SmalladsAuthorizationsService::check_authorizations($this->get_smallad()->get_id_category())->moderation())
		{
			$publication_fieldset = new FormFieldsetHTML('publication', $this->common_lang['form.approbation']);
			$form->add_fieldset($publication_fieldset);

			$publication_fieldset->add_field(new FormFieldDateTime('creation_date', $this->common_lang['form.date.creation'], $this->get_smallad()->get_creation_date(),
				array('required' => true)
			));

			if (!$this->get_smallad()->is_published())
			{
				$publication_fieldset->add_field(new FormFieldCheckbox('update_creation_date', $this->common_lang['form.update.date.creation'], false, array('hidden' => $this->get_smallad()->get_status() != Smallad::NOT_PUBLISHED)
				));
			}

			$publication_fieldset->add_field(new FormFieldSimpleSelectChoice('publication_state', $this->common_lang['form.approbation'], $this->get_smallad()->get_publication_state(),
				array(
					new FormFieldSelectChoiceOption($this->common_lang['form.approbation.not'], Smallad::NOT_PUBLISHED),
					new FormFieldSelectChoiceOption($this->common_lang['form.approbation.now'], Smallad::PUBLISHED_NOW),
					new FormFieldSelectChoiceOption($this->common_lang['status.approved.date'], Smallad::PUBLICATION_DATE),
				),
				array('events' => array('change' => '
				if (HTMLForms.getField("publication_state").getValue() == 2) {
					jQuery("#' . __CLASS__ . '_publication_start_date_field").show();
					HTMLForms.getField("end_date_enable").enable();
				} else {
					jQuery("#' . __CLASS__ . '_publication_start_date_field").hide();
					HTMLForms.getField("end_date_enable").disable();
				}'))
			));

			$publication_fieldset->add_field(new FormFieldDateTime('publication_start_date', $this->common_lang['form.date.start'],
				($this->get_smallad()->get_publication_start_date() === null ? new Date() : $this->get_smallad()->get_publication_start_date()),
				array('hidden' => ($this->get_smallad()->get_publication_state() != Smallad::PUBLICATION_DATE))
			));

			$publication_fieldset->add_field(new FormFieldCheckbox('end_date_enable', $this->common_lang['form.date.end.enable'], $this->get_smallad()->enabled_end_date(),
				array('hidden' => ($this->get_smallad()->get_publication_state() != Smallad::PUBLICATION_DATE),
					'events' => array('click' => '
						if (HTMLForms.getField("end_date_enable").getValue()) {
							HTMLForms.getField("publication_end_date").enable();
						} else {
							HTMLForms.getField("publication_end_date").disable();
						}'
				))
			));

			$publication_fieldset->add_field(new FormFieldDateTime('publication_end_date', $this->common_lang['form.date.end'],
				($this->get_smallad()->get_publication_end_date() === null ? new date() : $this->get_smallad()->get_publication_end_date()),
				array('hidden' => !$this->get_smallad()->enabled_end_date())
			));
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
			$options[] = new FormFieldSelectChoiceOption($name, str_replace(' ', '-', $name));
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
		if ($this->get_smallad()->get_id() === null && $this->is_contributor_member())
		{
			$fieldset = new FormFieldsetHTML('contribution', LangLoader::get_message('contribution', 'user-common'));
			$fieldset->set_description(MessageHelper::display(LangLoader::get_message('smallads.form.member.contribution.explain', 'common', 'smallads'), MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('contribution_description', LangLoader::get_message('contribution.description', 'user-common'), '',
				array('description' => LangLoader::get_message('contribution.description.explain', 'user-common'))
			));
		}
		elseif ($this->get_smallad()->is_published() && $this->get_smallad()->is_authorized_to_edit() && !AppContext::get_current_user()->check_level(User::ADMIN_LEVEL))
		{
			$fieldset = new FormFieldsetHTML('member_edition', LangLoader::get_message('smallads.form.member.edition', 'common', 'smallads'));
			$fieldset->set_description(MessageHelper::display(LangLoader::get_message('smallads.form.member.edition.explain', 'common', 'smallads'), MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('edittion_description', LangLoader::get_message('smallads.form.member.edition.description', 'common', 'smallads'), '',
				array('description' => LangLoader::get_message('smallads.form.member.edition.description.desc', 'common', 'smallads'))
			));
		}
	}

	private function is_contributor_member()
	{
		return (!SmalladsAuthorizationsService::check_authorizations()->write() && SmalladsAuthorizationsService::check_authorizations()->contribution());
	}

	private function get_smallad()
	{
		if ($this->smallad === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try
				{
					$this->smallad = SmalladsService::get_smallad('WHERE smallads.id=:id', array('id' => $id));
				}
				catch(RowNotFoundException $e)
				{
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->is_new_smallad = true;
				$this->smallad = new Smallad();
				$this->smallad->init_default_properties(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY));
			}
		}
		return $this->smallad;
	}

	private function check_authorizations()
	{
		$smallad = $this->get_smallad();

		if ($smallad->get_id() === null)
		{
			if (!$smallad->is_authorized_to_add())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!$smallad->is_authorized_to_edit())
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
		$smallad = $this->get_smallad();

		$smallad->set_title($this->form->get_value('title'));

		if (SmalladsService::get_categories_manager()->get_categories_cache()->has_categories())
			$smallad->set_id_category($this->form->get_value('id_category')->get_raw_value());

		$smallad->set_description(($this->form->get_value('enable_description') ? $this->form->get_value('description') : ''));
		$smallad->set_contents($this->form->get_value('contents'));

		if(empty($this->form->get_value('price')))
			$smallad->set_price('0');
		else
			$smallad->set_price($this->form->get_value('price'));

		$smallad->set_smallad_type($this->form->get_value('smallad_type')->get_raw_value());
		// $smallad->set_brand($this->form->get_value('brand')->get_raw_value());

		$smallad->set_thumbnail(new Url($this->form->get_value('thumbnail')));

		if($this->config->is_max_weeks_number_displayed())
		{
			if(empty($this->form->get_value('max_weeks')) || $this->form->get_value('max_weeks') === 0)
				$smallad->set_max_weeks(SmalladsConfig::load()->get_max_weeks_number());
			else
				$smallad->set_max_weeks($this->form->get_value('max_weeks'));
		}

		if($this->config->is_location_displayed()) {
			if($this->config->is_googlemaps_available()){
				$smallad->set_location($this->form->get_value('location'));
			} else {
				$location = $this->form->get_value('location')->get_raw_value();
				$smallad->set_location($location);
				if ($location === 'other')
					$smallad->set_other_location($this->form->get_value('other_location'));
				else
					$smallad->set_other_location('');
			}
		}

		$displayed_author_phone = $this->form->get_value('displayed_author_phone') ? $this->form->get_value('displayed_author_phone') : Smallad::NOTDISPLAYED_AUTHOR_PHONE;
		$smallad->set_displayed_author_phone($displayed_author_phone);

		if ($this->get_smallad()->get_displayed_author_phone() == true)
			$smallad->set_author_phone($this->form->get_value('author_phone'));

		$displayed_author_pm = $this->form->get_value('displayed_author_pm') ? $this->form->get_value('displayed_author_pm') : Smallad::NOTDISPLAYED_AUTHOR_PM;
		$smallad->set_displayed_author_pm($displayed_author_pm);

		$displayed_author_email = $this->form->get_value('displayed_author_email') ? $this->form->get_value('displayed_author_email') : Smallad::NOTDISPLAYED_AUTHOR_EMAIL;
		$smallad->set_displayed_author_email($displayed_author_email);

		if ($this->get_smallad()->get_displayed_author_email() == true)
			$smallad->set_custom_author_email(($this->form->get_value('custom_author_email') && $this->form->get_value('custom_author_email') !== $smallad->get_author_user()->get_email() ? $this->form->get_value('custom_author_email') : ''));

		$displayed_author_name = $this->form->get_value('displayed_author_name') ? $this->form->get_value('displayed_author_name') : Smallad::NOTDISPLAYED_AUTHOR_NAME;
		$smallad->set_displayed_author_name($displayed_author_name);

		if ($this->get_smallad()->get_displayed_author_name() == true)
			$smallad->set_custom_author_name(($this->form->get_value('custom_author_name') && $this->form->get_value('custom_author_name') !== $smallad->get_author_user()->get_display_name() ? $this->form->get_value('custom_author_name') : ''));

		$smallad->set_sources($this->form->get_value('sources'));
		$smallad->set_carousel($this->form->get_value('carousel'));

		if($this->get_smallad()->get_id() !== null)
			$smallad->set_completed($this->form->get_value('completed'));

		if (!SmalladsAuthorizationsService::check_authorizations($smallad->get_id_category())->moderation())
		{
			if ($smallad->get_id() === null)
				$smallad->set_creation_date(new Date());

			$smallad->set_rewrited_title(Url::encode_rewrite($smallad->get_title()));
			$smallad->clean_publication_start_and_end_date();

			if (SmalladsAuthorizationsService::check_authorizations($smallad->get_id_category())->contribution() && !SmalladsAuthorizationsService::check_authorizations($smallad->get_id_category())->write())
				$smallad->set_publication_state(Smallad::NOT_PUBLISHED);
		}
		else
		{
			if ($this->form->get_value('update_creation_date'))
			{
				$smallad->set_creation_date(new Date());
			}
			else
			{
				$smallad->set_creation_date($this->form->get_value('creation_date'));
			}

			$rewrited_title = $this->form->get_value('rewrited_title', '');
			$rewrited_title = $this->form->get_value('personalize_rewrited_title') && !empty($rewrited_title) ? $rewrited_title : Url::encode_rewrite($smallad->get_title());
			$smallad->set_rewrited_title($rewrited_title);

			$smallad->set_publication_state($this->form->get_value('publication_state')->get_raw_value());
			if ($smallad->get_publication_state() == Smallad::PUBLICATION_DATE)
			{
				$config = SmalladsConfig::load();
				$deferred_operations = $config->get_deferred_operations();

				$old_start_date = $smallad->get_publication_start_date();
				$start_date = $this->form->get_value('publication_start_date');
				$smallad->set_publication_start_date($start_date);

				if ($old_start_date !== null && $old_start_date->get_timestamp() != $start_date->get_timestamp() && in_array($old_start_date->get_timestamp(), $deferred_operations))
				{
					$key = array_search($old_start_date->get_timestamp(), $deferred_operations);
					unset($deferred_operations[$key]);
				}

				if (!in_array($start_date->get_timestamp(), $deferred_operations))
					$deferred_operations[] = $start_date->get_timestamp();

				if ($this->form->get_value('end_date_enable'))
				{
					$old_end_date = $smallad->get_publication_end_date();
					$end_date = $this->form->get_value('publication_end_date');
					$smallad->set_publication_end_date($end_date);

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
					$smallad->clean_publication_end_date();
				}

				$config->set_deferred_operations($deferred_operations);
				SmalladsConfig::save();
			}
			else
			{
				$smallad->clean_publication_start_and_end_date();
			}
		}

		if ($smallad->get_id() === null)
		{
			$smallad->set_author_user(AppContext::get_current_user());
			$id_smallad = SmalladsService::add($smallad);
		}
		else
		{
			$now = new Date();
			$smallad->set_updated_date($now);
			$id_smallad = $smallad->get_id();
			SmalladsService::update($smallad);
		}

		$this->contribution_actions($smallad, $id_smallad);

		SmalladsService::get_keywords_manager()->put_relations($id_smallad, $this->form->get_value('keywords'));

		Feed::clear_cache('smallads');
		SmalladsCache::invalidate();
		SmalladsCategoriesCache::invalidate();
	}

	private function contribution_actions(Smallad $smallad, $id_smallad)
	{
		if ($this->is_contributor_member())
		{
			$contribution = new Contribution();
			$contribution->set_id_in_module($id_smallad);
			if ($smallad->get_id() === null)
				$contribution->set_description(stripslashes($this->form->get_value('contribution_description')));
			else
				$contribution->set_description(stripslashes($this->form->get_value('edittion_description')));

			$contribution->set_entitled($smallad->get_title());
			$contribution->set_fixing_url(SmalladsUrlBuilder::edit_item($id_smallad)->relative());
			$contribution->set_poster_id(AppContext::get_current_user()->get_id());
			$contribution->set_module('smallads');
			$contribution->set_auth(
				Authorizations::capture_and_shift_bit_auth(
					SmalladsService::get_categories_manager()->get_heritated_authorizations($smallad->get_id_category(), Category::MODERATION_AUTHORIZATIONS, Authorizations::AUTH_CHILD_PRIORITY),
					Category::MODERATION_AUTHORIZATIONS, Contribution::CONTRIBUTION_AUTH_BIT
				)
			);
			ContributionService::save_contribution($contribution);
		}
		else
		{
			$corresponding_contributions = ContributionService::find_by_criteria('smallads', $id_smallad);
			if (count($corresponding_contributions) > 0)
			{
				foreach ($corresponding_contributions as $contribution)
				{
					$contribution->set_status(Event::EVENT_STATUS_PROCESSED);
					ContributionService::save_contribution($contribution);
				}
			}
		}
		$smallad->set_id($id_smallad);
	}

	private function redirect()
	{
		$smallad = $this->get_smallad();
		$category = $smallad->get_category();

		if ($this->is_new_smallad && $this->is_contributor_member() && !$smallad->is_published())
		{
			DispatchManager::redirect(new UserContributionSuccessController());
		}
		elseif ($smallad->is_published())
		{
			if ($this->is_new_smallad)
				AppContext::get_response()->redirect(SmalladsUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $smallad->get_id(), $smallad->get_rewrited_title(), AppContext::get_request()->get_getint('page', 1)), StringVars::replace_vars($this->lang['smallads.message.success.add'], array('title' => $smallad->get_title())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : SmalladsUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $smallad->get_id(), $smallad->get_rewrited_title(), AppContext::get_request()->get_getint('page', 1))), StringVars::replace_vars($this->lang['smallads.message.success.edit'], array('title' => $smallad->get_title())));
		}
		else
		{
			if ($this->is_new_smallad)
				AppContext::get_response()->redirect(SmalladsUrlBuilder::display_pending_items(), StringVars::replace_vars($this->lang['smallads.message.success.add'], array('title' => $smallad->get_title())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : SmalladsUrlBuilder::display_pending_items()), StringVars::replace_vars($this->lang['smallads.message.success.edit'], array('title' => $smallad->get_title())));
		}
	}

	private function generate_response(View $tpl)
	{
		$smallad = $this->get_smallad();

		$location_id = $smallad->get_id() ? 'smallads-edit-'. $smallad->get_id() : '';
		
		$response = new SiteDisplayResponse($tpl, $location_id);
		$graphical_environment = $response->get_graphical_environment();

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['smallads.module.title'], SmalladsUrlBuilder::home());

		if ($smallad->get_id() === null)
		{
			$breadcrumb->add($this->lang['smallads.add'], SmalladsUrlBuilder::add_item($smallad->get_id_category()));
			$graphical_environment->set_page_title($this->lang['smallads.add'], $this->lang['smallads.module.title']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['smallads.add']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(SmalladsUrlBuilder::add_item($smallad->get_id_category()));
		}
		else
		{
			$categories = array_reverse(SmalladsService::get_categories_manager()->get_parents($smallad->get_id_category(), true));
			foreach ($categories as $id => $category)
			{
				if ($category->get_id() != Category::ROOT_CATEGORY)
					$breadcrumb->add($category->get_name(), SmalladsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
			}
			$breadcrumb->add($smallad->get_title(), SmalladsUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $smallad->get_id(), $smallad->get_rewrited_title()));

			$breadcrumb->add($this->lang['smallads.edit'], SmalladsUrlBuilder::edit_item($smallad->get_id()));
			
			if (!AppContext::get_session()->location_id_already_exists($location_id))
				$graphical_environment->set_location_id($location_id);
			
			$graphical_environment->set_page_title($this->lang['smallads.edit'], $this->lang['smallads.module.title']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['smallads.edit']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(SmalladsUrlBuilder::edit_item($smallad->get_id()));
		}

		return $response;
	}
}
?>
