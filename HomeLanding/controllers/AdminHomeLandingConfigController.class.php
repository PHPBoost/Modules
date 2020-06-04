<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 06 04
 * @since       PHPBoost 5.0 - 2016 01 02
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class AdminHomeLandingConfigController extends AdminModuleController
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

	/**
	 * @var HomeLandingConfig
	 */
	private $config;

	/**
	 * @var HomeLandingModulesList
	 */
	private $modules;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();

			$this->form->get_field_by_id('carousel')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_CAROUSEL]->is_displayed());
			$this->form->get_field_by_id('carousel_speed')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_CAROUSEL]->is_displayed());
			$this->form->get_field_by_id('carousel_time')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_CAROUSEL]->is_displayed());
			$this->form->get_field_by_id('carousel_number')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_CAROUSEL]->is_displayed());
			$this->form->get_field_by_id('carousel_auto')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_CAROUSEL]->is_displayed());
			$this->form->get_field_by_id('carousel_hover')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_CAROUSEL]->is_displayed());

			$this->form->get_field_by_id('edito')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_EDITO]->is_displayed());

			$this->form->get_field_by_id('lastcoms_limit')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_LASTCOMS]->is_displayed());
			$this->form->get_field_by_id('lastcoms_char')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_LASTCOMS]->is_displayed());

			if (ModulesManager::is_module_installed('articles') && ModulesManager::is_module_activated('articles'))
			{
				$this->form->get_field_by_id('articles_limit')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_ARTICLES]->is_displayed());
				$this->form->get_field_by_id('articles_cat')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->is_displayed());
				$this->form->get_field_by_id('articles_subcategories_content_displayed')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->is_displayed());
				$this->form->get_field_by_id('articles_cat_limit')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->is_displayed());
				$this->form->get_field_by_id('articles_cat_char')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->is_displayed());
			}

			if (ModulesManager::is_module_installed('calendar') && ModulesManager::is_module_activated('calendar'))
			{
				$this->form->get_field_by_id('calendar_limit')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_CALENDAR]->is_displayed());
				$this->form->get_field_by_id('calendar_char')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_CALENDAR]->is_displayed());
			}

			if (ModulesManager::is_module_installed('download') && ModulesManager::is_module_activated('download'))
			{
				$this->form->get_field_by_id('download_limit')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_DOWNLOAD]->is_displayed());
				$this->form->get_field_by_id('download_cat')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->is_displayed());
				$this->form->get_field_by_id('download_subcategories_content_displayed')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->is_displayed());
				$this->form->get_field_by_id('download_cat_limit')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->is_displayed());
				$this->form->get_field_by_id('download_cat_char')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->is_displayed());
			}

			if (ModulesManager::is_module_installed('forum') && ModulesManager::is_module_activated('forum'))
			{
				$this->form->get_field_by_id('forum_limit')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_FORUM]->is_displayed());
				$this->form->get_field_by_id('forum_char')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_FORUM]->is_displayed());
			}

			if (ModulesManager::is_module_installed('gallery') && ModulesManager::is_module_activated('gallery'))
			{
				$this->form->get_field_by_id('gallery_limit')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_GALLERY]->is_displayed());
			}

			if (ModulesManager::is_module_installed('guestbook') && ModulesManager::is_module_activated('guestbook'))
			{
				$this->form->get_field_by_id('guestbook_limit')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_GUESTBOOK]->is_displayed());
				$this->form->get_field_by_id('guestbook_char')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_GUESTBOOK]->is_displayed());
			}

			if (ModulesManager::is_module_installed('media') && ModulesManager::is_module_activated('media'))
			{
				$this->form->get_field_by_id('media_limit')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_MEDIA]->is_displayed());
			}

			if (ModulesManager::is_module_installed('news') && ModulesManager::is_module_activated('news'))
			{
				$this->form->get_field_by_id('news_limit')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_NEWS]->is_displayed());
				$this->form->get_field_by_id('news_cat')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->is_displayed());
				$this->form->get_field_by_id('news_subcategories_content_displayed')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->is_displayed());
				$this->form->get_field_by_id('news_cat_limit')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->is_displayed());
				$this->form->get_field_by_id('news_cat_char')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->is_displayed());
			}

			// $this->form->get_field_by_id('rss_site_name')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_RSS]->is_displayed());
			// $this->form->get_field_by_id('rss_site_url')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_RSS]->is_displayed());
			// $this->form->get_field_by_id('rss_xml_url')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_RSS]->is_displayed());
			// $this->form->get_field_by_id('rss_xml_nb')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_RSS]->is_displayed());
			// $this->form->get_field_by_id('rss_xml_char')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_RSS]->is_displayed());

			if (ModulesManager::is_module_installed('web') && ModulesManager::is_module_activated('web'))
			{
				$this->form->get_field_by_id('web_limit')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_WEB]->is_displayed());
				$this->form->get_field_by_id('web_cat')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->is_displayed());
				$this->form->get_field_by_id('web_subcategories_content_displayed')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->is_displayed());
				$this->form->get_field_by_id('web_cat_limit')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->is_displayed());
				$this->form->get_field_by_id('web_cat_char')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->is_displayed());
			}

			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 4));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminHomeLandingDisplayResponse($tpl, LangLoader::get_message('configuration', 'admin-common'));
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'HomeLanding');
		$this->config = HomeLandingConfig::load();
		$this->modules = HomeLandingModulesList::load();
	}

	private function tabs_menu_list()
	{
		$modules = array('configuration', 'carousel', 'articles', 'calendar', 'contact', 'download', 'forum', 'gallery', 'guestbook', 'media', 'news', 'web');
        $tabs_li = array();

        foreach($modules as $module)
        {
			if($module == 'configuration')
            	$tabs_li[] = new FormFieldMultitabsLinkElement(LangLoader::get_message('configuration', 'admin-common'), 'tabs', 'AdminHomeLandingConfigController_configuration', 'fa-cog');
			elseif($module == 'carousel')
            	$tabs_li[] = new FormFieldMultitabsLinkElement($this->lang['anchors.carousel'], 'tabs', 'AdminHomeLandingConfigController_admin_carousel', 'fa-cog');
			// elseif($module == 'rss')
            // 	$tabs_li[] = new FormFieldMultitabsLinkElement($this->lang['anchors.rss'], 'tabs', 'AdminHomeLandingConfigController_admin_rss', 'fa-rss');
			else
            	$tabs_li[] = new FormFieldMultitabsLinkElement($this->lang['anchors.'.$module.''], 'tabs', 'AdminHomeLandingConfigController_admin_'.$module, '', PATH_TO_ROOT.'/'.$module.'/'.$module.'_mini.png', $module);
        }
		return $tabs_li;
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		$form->set_css_class('tabs-container fieldset-content');

		// Tabs menu
		$fieldset_tabs_menu = new FormFieldMenuFieldset('tabs_menu', '');
		$form->add_fieldset($fieldset_tabs_menu);
		$fieldset_tabs_menu->set_css_class('tabs-nav');

        $fieldset_tabs_menu->add_field(new FormFieldMultitabsLinkList('tabs_menu_list',$this->tabs_menu_list()));

		// Configuration
		$fieldset_config = new FormFieldsetMultitabsHTML('configuration', LangLoader::get_message('configuration', 'admin-common'),
				array('css_class' => 'tabs tabs-animation first-tab')
			);
			$form->add_fieldset($fieldset_config);

			$fieldset_config->add_field(new FormFieldTextEditor('module_title', $this->lang['admin.module.title'], $this->config->get_module_title(),
				array('description' => $this->lang['admin.module.title.desc'])
			));

			$fieldset_config->add_field(new FormFieldCheckbox('left_columns', $this->lang['admin.menu.left'], $this->config->get_left_columns(),
				array('class' => 'custom-checkbox')
			));

			$fieldset_config->add_field(new FormFieldCheckbox('right_columns', $this->lang['admin.menu.right'], $this->config->get_right_columns(),
				array('class' => 'custom-checkbox')
			));

			$fieldset_config->add_field(new FormFieldCheckbox('top_central', $this->lang['admin.menu.top.central'], $this->config->get_top_central(),
				array('class' => 'custom-checkbox')
			));

			$fieldset_config->add_field(new FormFieldCheckbox('bottom_central', $this->lang['admin.menu.bottom.central'], $this->config->get_bottom_central(),
				array('class' => 'custom-checkbox')
			));

			$fieldset_config->add_field(new FormFieldCheckbox('top_footer', $this->lang['admin.menu.top.footer'], $this->config->get_top_footer(),
				array('class' => 'custom-checkbox')
			));

			$fieldset_config->add_field(new FormFieldSubTitle('admin_anchors', LangLoader::get_message('admin.anchors', 'common', 'HomeLanding'), ''));

			$fieldset_config->add_field(new FormFieldCheckbox('anchors_menu', $this->lang['admin.menu.anchors'], $this->config->get_anchors_menu(),
				array(
					'class' => 'custom-checkbox',
					'description' => $this->lang['admin.menu.anchors.explain']
				)
			));

			$fieldset_config->add_field(new FormFieldSubTitle('admin_edito', LangLoader::get_message('admin.edito', 'common', 'HomeLanding'), ''));

			$fieldset_config->add_field(new FormFieldCheckbox('edito_enabled', $this->lang['admin.edito.enabled'], $this->modules[HomeLandingConfig::MODULE_EDITO]->is_displayed(),
				array(
					'class' => 'custom-checkbox',
					'events' => array('click' => '
						if (HTMLForms.getField("edito_enabled").getValue()) {
							HTMLForms.getField("edito").enable();
						} else {
							HTMLForms.getField("edito").disable();
						}'
					)
				)
			));

			$fieldset_config->add_field(new FormFieldRichTextEditor('edito', $this->lang['admin.edito.content'], $this->config->get_edito(),
				array('hidden' => !$this->modules[HomeLandingConfig::MODULE_EDITO]->is_displayed())
			));

			$fieldset_config->add_field(new FormFieldSubTitle('admin_lastcoms', LangLoader::get_message('admin.lastcoms', 'common', 'HomeLanding'), ''));

			$fieldset_config->add_field(new FormFieldCheckbox('lastcoms_enabled', $this->lang['admin.lastcoms.enabled'], $this->modules[HomeLandingConfig::MODULE_LASTCOMS]->is_displayed(),
				array(
					'class' => 'custom-checkbox',
					'events' => array('click' => '
						if (HTMLForms.getField("lastcoms_enabled").getValue()) {
							HTMLForms.getField("lastcoms_limit").enable();
							HTMLForms.getField("lastcoms_char").enable();
						} else {
							HTMLForms.getField("lastcoms_limit").disable();
							HTMLForms.getField("lastcoms_char").disable();
						}'
					)
				)
			));

			$fieldset_config->add_field(new FormFieldNumberEditor('lastcoms_limit', $this->lang['admin.lastcoms.limit'], $this->modules[HomeLandingConfig::MODULE_LASTCOMS]->get_elements_number_displayed(),
				array(
					'min' => 1, 'max' => 100,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_LASTCOMS]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 100))
			));

			$fieldset_config->add_field(new FormFieldNumberEditor('lastcoms_char', $this->lang['admin.char'], $this->modules[HomeLandingConfig::MODULE_LASTCOMS]->get_characters_number_displayed(),
				array(
					'min' => 1, 'max' => 512,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_LASTCOMS]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 512))
			));

		//  Carousel
		$fieldset_carousel = new FormFieldsetMultitabsHTML('admin_carousel', LangLoader::get_message('admin.carousel', 'common', 'HomeLanding'),
				array('css_class' => 'tabs tabs-animation')
			);
			$form->add_fieldset($fieldset_carousel);

			$fieldset_carousel->add_field(new FormFieldCheckbox('carousel_enabled', $this->lang['admin.carousel.enabled'], $this->modules[HomeLandingConfig::MODULE_CAROUSEL]->is_displayed(),
				array(
					'class'=> 'top-field custom-checkbox',
					'events' => array('click' => '
						if (HTMLForms.getField("carousel_enabled").getValue()) {
							HTMLForms.getField("carousel").enable();
							HTMLForms.getField("carousel_speed").enable();
							HTMLForms.getField("carousel_time").enable();
							HTMLForms.getField("carousel_number").enable();
							HTMLForms.getField("carousel_auto").enable();
							HTMLForms.getField("carousel_hover").enable();
						} else {
							HTMLForms.getField("carousel").disable();
							HTMLForms.getField("carousel_speed").disable();
							HTMLForms.getField("carousel_time").disable();
							HTMLForms.getField("carousel_number").disable();
							HTMLForms.getField("carousel_auto").disable();
							HTMLForms.getField("carousel_hover").disable();
						}'
					)
				)
			));

			$fieldset_carousel->add_field(new FormFieldNumberEditor('carousel_speed', $this->lang['admin.form.carousel.speed'], $this->config->get_carousel_speed(),
				array('hidden' => !$this->modules[HomeLandingConfig::MODULE_CAROUSEL]->is_displayed())
			));

			$fieldset_carousel->add_field(new FormFieldNumberEditor('carousel_time', $this->lang['admin.form.carousel.time'], $this->config->get_carousel_time(),
				array('hidden' => !$this->modules[HomeLandingConfig::MODULE_CAROUSEL]->is_displayed())
			));

			$fieldset_carousel->add_field(new FormFieldNumberEditor('carousel_number', $this->lang['admin.form.carousel.number'], $this->config->get_carousel_number(),
				array(
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_CAROUSEL]->is_displayed(),
					'description' => $this->lang['admin.form.carousel.number.explain']
				)
			));

			$fieldset_carousel->add_field(new FormFieldRadioChoice('carousel_auto', $this->lang['admin.form.carousel.auto'], $this->config->get_carousel_auto(),
				array(
					new FormFieldRadioChoiceOption($this->lang['admin.form.carousel.enabled'], HomeLandingConfig::CAROUSEL_TRUE),
					new FormFieldRadioChoiceOption($this->lang['admin.form.carousel.disabled'], HomeLandingConfig::CAROUSEL_FALSE)
				),
				array(
					'class' => 'inline-radio',
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_CAROUSEL]->is_displayed()
				)
			));

			$fieldset_carousel->add_field(new FormFieldRadioChoice('carousel_hover', $this->lang['admin.form.carousel.hover'], $this->config->get_carousel_hover(),
				array(
					new FormFieldRadioChoiceOption($this->lang['admin.form.carousel.enabled'], HomeLandingConfig::CAROUSEL_TRUE),
					new FormFieldRadioChoiceOption($this->lang['admin.form.carousel.disabled'], HomeLandingConfig::CAROUSEL_FALSE)
				),
				array(
					'class' => 'inline-radio',
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_CAROUSEL]->is_displayed()
				)
			));

			$fieldset_carousel->add_field(new HomeLandingFormFieldSliderConfig('carousel', $this->lang['admin.form.carousel'], $this->config->get_carousel(),
				array(
					'class' => 'full-field',
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_CAROUSEL]->is_displayed()
				)
			));

		// Articles
		if (ModulesManager::is_module_installed('articles') && ModulesManager::is_module_activated('articles'))
		{
			$fieldset_articles = new FormFieldsetMultitabsHTML('admin_articles', $this->lang['admin.articles'],
				array('css_class' => 'tabs tabs-animation')
			);
			$form->add_fieldset($fieldset_articles);

			$fieldset_articles->add_field(new FormFieldCheckbox('articles_enabled', $this->lang['admin.articles.enabled'], $this->modules[HomeLandingConfig::MODULE_ARTICLES]->is_displayed(),
				array(
					'class'=> 'custom-checkbox',
					'events' => array('click' => '
						if (HTMLForms.getField("articles_enabled").getValue()) {
							HTMLForms.getField("articles_limit").enable();
						} else {
							HTMLForms.getField("articles_limit").disable();
						}'
					)
				)
			));

			$fieldset_articles->add_field(new FormFieldNumberEditor('articles_limit', $this->lang['admin.articles.limit'], $this->modules[HomeLandingConfig::MODULE_ARTICLES]->get_elements_number_displayed(),
				array(
					'min' => 1, 'max' => 100,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_ARTICLES]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 100))
			));

			$fieldset_articles->add_field(new FormFieldSpacer('articles_separator', ''));

			$fieldset_articles->add_field(new FormFieldCheckbox('articles_cat_enabled', $this->lang['admin.articles.cat.enabled'], $this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->is_displayed(),
				array(
					'class'=> 'custom-checkbox',
					'events' => array('click' => '
						if (HTMLForms.getField("articles_cat_enabled").getValue()) {
							HTMLForms.getField("articles_cat").enable();
							HTMLForms.getField("articles_subcategories_content_displayed").enable();
							HTMLForms.getField("articles_cat_limit").enable();
							HTMLForms.getField("articles_cat_char").enable();
						} else {
							HTMLForms.getField("articles_cat").disable();
							HTMLForms.getField("articles_subcategories_content_displayed").disable();
							HTMLForms.getField("articles_cat_limit").disable();
							HTMLForms.getField("articles_cat_char").disable();
						}'
					)
				)
			));

			$fieldset_articles->add_field(CategoriesService::get_categories_manager(HomeLandingConfig::MODULE_ARTICLES)->get_select_categories_form_field('articles_cat', $this->lang['admin.cat'], $this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->get_id_category(), new SearchCategoryChildrensOptions(),
				array('hidden' => !$this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->is_displayed())
			));

			$fieldset_articles->add_field(new FormFieldCheckbox('articles_subcategories_content_displayed', $this->lang['admin.subcategories_content_displayed'], $this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->is_subcategories_content_displayed(),
				array(
					'class'=> 'custom-checkbox',
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->is_displayed())
			));

			$fieldset_articles->add_field(new FormFieldNumberEditor('articles_cat_limit', $this->lang['admin.articles.cat.limit'], $this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->get_elements_number_displayed(),
				array(
					'min' => 1, 'max' => 100,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 100))
			));

			$fieldset_articles->add_field(new FormFieldNumberEditor('articles_cat_char', $this->lang['admin.char'], $this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->get_characters_number_displayed(),
				array(
					'min' => 1, 'max' => 512,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 512))
			));
		}

		// Calendar
		if (ModulesManager::is_module_installed('calendar') && ModulesManager::is_module_activated('calendar'))
		{
			$fieldset_calendar = new FormFieldsetMultitabsHTML('admin_calendar', $this->lang['admin.calendar'],
				array('css_class' => 'tabs tabs-animation')
			);
			$form->add_fieldset($fieldset_calendar);

			$fieldset_calendar->add_field(new FormFieldCheckbox('calendar_enabled', $this->lang['admin.calendar.enabled'], $this->modules[HomeLandingConfig::MODULE_CALENDAR]->is_displayed(),
				array(
					'class'=> 'custom-checkbox',
					'description' => $this->lang['admin.calendar.enabled.desc'],
					'events' => array('click' => '
						if (HTMLForms.getField("calendar_enabled").getValue()) {
							HTMLForms.getField("calendar_limit").enable();
							HTMLForms.getField("calendar_char").enable();
						} else {
							HTMLForms.getField("calendar_limit").disable();
							HTMLForms.getField("calendar_char").disable();
						}'
					)
				)
			));

			$fieldset_calendar->add_field(new FormFieldNumberEditor('calendar_limit', $this->lang['admin.calendar.limit'], $this->modules[HomeLandingConfig::MODULE_CALENDAR]->get_elements_number_displayed(),
				array(
					'min' => 1, 'max' => 100,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_CALENDAR]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 100))
			));

			$fieldset_calendar->add_field(new FormFieldNumberEditor('calendar_char', $this->lang['admin.char'], $this->modules[HomeLandingConfig::MODULE_CALENDAR]->get_characters_number_displayed(),
				array(
					'min' => 1, 'max' => 512,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_CALENDAR]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 512))
			));
		}

		// Contact
		if (ModulesManager::is_module_installed('contact') && ModulesManager::is_module_activated('contact'))
		{
			$fieldset_contact = new FormFieldsetMultitabsHTML('admin_contact', $this->lang['admin.contact'],
				array('css_class' => 'tabs tabs-animation')
			);
			$form->add_fieldset($fieldset_contact);

			$fieldset_contact->add_field(new FormFieldCheckbox('contact_enabled', $this->lang['admin.contact.enabled'], $this->modules[HomeLandingConfig::MODULE_CONTACT]->is_displayed(),
				array('class'=> 'custom-checkbox')
			));
		}

		// Download
		if (ModulesManager::is_module_installed('download') && ModulesManager::is_module_activated('download'))
		{
			$fieldset_download = new FormFieldsetMultitabsHTML('admin_download', $this->lang['admin.download'],
				array('css_class' => 'tabs tabs-animation')
			);
			$form->add_fieldset($fieldset_download);

			$fieldset_download->add_field(new FormFieldCheckbox('download_enabled', $this->lang['admin.download.enabled'], $this->modules[HomeLandingConfig::MODULE_DOWNLOAD]->is_displayed(),
				array(
					'class'=> 'custom-checkbox',
					'events' => array('click' => '
						if (HTMLForms.getField("download_enabled").getValue()) {
							HTMLForms.getField("download_limit").enable();
						} else {
							HTMLForms.getField("download_limit").disable();
						}'
					)
				)
			));

			$fieldset_download->add_field(new FormFieldNumberEditor('download_limit', $this->lang['admin.download.limit'], $this->modules[HomeLandingConfig::MODULE_DOWNLOAD]->get_elements_number_displayed(),
				array(
					'min' => 1, 'max' => 100,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_DOWNLOAD]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 100))
			));

			$fieldset_download->add_field(new FormFieldSpacer('download_separator', ''));

			$fieldset_download->add_field(new FormFieldCheckbox('download_cat_enabled', $this->lang['admin.download.cat.enabled'], $this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->is_displayed(),
				array(
					'class'=> 'custom-checkbox',
					'events' => array('click' => '
						if (HTMLForms.getField("download_cat_enabled").getValue()) {
							HTMLForms.getField("download_cat").enable();
							HTMLForms.getField("download_subcategories_content_displayed").enable();
							HTMLForms.getField("download_cat_limit").enable();
							HTMLForms.getField("download_cat_char").enable();
						} else {
							HTMLForms.getField("download_cat").disable();
							HTMLForms.getField("download_subcategories_content_displayed").disable();
							HTMLForms.getField("download_cat_limit").disable();
							HTMLForms.getField("download_cat_char").disable();
						}'
					)
				)
			));

			$fieldset_download->add_field(CategoriesService::get_categories_manager(HomeLandingConfig::MODULE_DOWNLOAD)->get_select_categories_form_field('download_cat', $this->lang['admin.cat'], $this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->get_id_category(), new SearchCategoryChildrensOptions(),
				array('hidden' => !$this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->is_displayed())
			));

			$fieldset_download->add_field(new FormFieldCheckbox('download_subcategories_content_displayed', $this->lang['admin.subcategories_content_displayed'], $this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->is_subcategories_content_displayed(),
				array(
					'class'=> 'custom-checkbox',
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->is_displayed())
			));

			$fieldset_download->add_field(new FormFieldNumberEditor('download_cat_limit', $this->lang['admin.download.cat.limit'], $this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->get_elements_number_displayed(),
				array(
					'min' => 1, 'max' => 100,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 100))
			));

			$fieldset_download->add_field(new FormFieldNumberEditor('download_cat_char', $this->lang['admin.char'], $this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->get_characters_number_displayed(),
				array(
					'min' => 1, 'max' => 512,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 512))
			));
		}

		// Forum
		if (ModulesManager::is_module_installed('forum') && ModulesManager::is_module_activated('forum'))
		{
			$fieldset_forum = new FormFieldsetMultitabsHTML('admin_forum', $this->lang['admin.forum'],
				array('css_class' => 'tabs tabs-animation')
			);
			$form->add_fieldset($fieldset_forum);

			$fieldset_forum->add_field(new FormFieldCheckbox('forum_enabled', $this->lang['admin.forum.enabled'], $this->modules[HomeLandingConfig::MODULE_FORUM]->is_displayed(),
				array(
					'class'=> 'custom-checkbox',
					'events' => array('click' => '
						if (HTMLForms.getField("forum_enabled").getValue()) {
							HTMLForms.getField("forum_limit").enable();
							HTMLForms.getField("forum_char").enable();
						} else {
							HTMLForms.getField("forum_limit").disable();
							HTMLForms.getField("forum_char").disable();
						}'
					)
				)
			));

			$fieldset_forum->add_field(new FormFieldNumberEditor('forum_limit', $this->lang['admin.forum.limit'], $this->modules[HomeLandingConfig::MODULE_FORUM]->get_elements_number_displayed(),
				array(
					'min' => 1, 'max' => 100,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_FORUM]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 100))
			));

			$fieldset_forum->add_field(new FormFieldNumberEditor('forum_char', $this->lang['admin.char'], $this->modules[HomeLandingConfig::MODULE_FORUM]->get_characters_number_displayed(),
				array(
					'min' => 1, 'max' => 512,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_FORUM]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 512))
			));
		}

		// Gallery
		if (ModulesManager::is_module_installed('gallery') && ModulesManager::is_module_activated('gallery'))
		{
			$fieldset_gallery = new FormFieldsetMultitabsHTML('admin_gallery', $this->lang['admin.gallery'],
				array('css_class' => 'tabs tabs-animation')
			);
			$form->add_fieldset($fieldset_gallery);

			$fieldset_gallery->add_field(new FormFieldCheckbox('gallery_enabled', $this->lang['admin.gallery.enabled'], $this->modules[HomeLandingConfig::MODULE_GALLERY]->is_displayed(),
				array(
					'class'=> 'custom-checkbox',
					'events' => array('click' => '
						if (HTMLForms.getField("gallery_enabled").getValue()) {
							HTMLForms.getField("gallery_limit").enable();
						} else {
							HTMLForms.getField("gallery_limit").disable();
						}'
					)
				)
			));

			$fieldset_gallery->add_field(new FormFieldNumberEditor('gallery_limit', $this->lang['admin.gallery.limit'], $this->modules[HomeLandingConfig::MODULE_GALLERY]->get_elements_number_displayed(),
				array(
					'min' => 1, 'max' => 100,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_GALLERY]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 100))
			));
		}

		// Guestbook
		if (ModulesManager::is_module_installed('guestbook') && ModulesManager::is_module_activated('guestbook'))
		{
			$fieldset_guestbook = new FormFieldsetMultitabsHTML('admin_guestbook', $this->lang['admin.guestbook'],
				array('css_class' => 'tabs tabs-animation')
			);
			$form->add_fieldset($fieldset_guestbook);

			$fieldset_guestbook->add_field(new FormFieldCheckbox('guestbook_enabled', $this->lang['admin.guestbook.enabled'], $this->modules[HomeLandingConfig::MODULE_GUESTBOOK]->is_displayed(),
				array(
					'class'=> 'custom-checkbox',
					'events' => array('click' => '
						if (HTMLForms.getField("guestbook_enabled").getValue()) {
							HTMLForms.getField("guestbook_limit").enable();
							HTMLForms.getField("guestbook_char").enable();
						} else {
							HTMLForms.getField("guestbook_limit").disable();
							HTMLForms.getField("guestbook_char").disable();
						}'
					)
				)
			));

			$fieldset_guestbook->add_field(new FormFieldNumberEditor('guestbook_limit', $this->lang['admin.guestbook.limit'], $this->modules[HomeLandingConfig::MODULE_GUESTBOOK]->get_elements_number_displayed(),
				array(
					'min' => 1, 'max' => 100,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_GUESTBOOK]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 100))
			));

			$fieldset_guestbook->add_field(new FormFieldNumberEditor('guestbook_char', $this->lang['admin.char'], $this->modules[HomeLandingConfig::MODULE_GUESTBOOK]->get_characters_number_displayed(),
				array(
					'min' => 1, 'max' => 512,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_GUESTBOOK]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 512))
			));
		}

		// Media
		if (ModulesManager::is_module_installed('media') && ModulesManager::is_module_activated('media'))
		{
			$fieldset_media = new FormFieldsetMultitabsHTML('admin_media', $this->lang['admin.media'],
				array('css_class' => 'tabs tabs-animation')
			);
			$form->add_fieldset($fieldset_media);

			$fieldset_media->add_field(new FormFieldCheckbox('media_enabled', $this->lang['admin.media.enabled'], $this->modules[HomeLandingConfig::MODULE_MEDIA]->is_displayed(),
				array(
					'class'=> 'custom-checkbox',
					'events' => array('click' => '
						if (HTMLForms.getField("media_enabled").getValue()) {
							HTMLForms.getField("media_limit").enable();
						} else {
							HTMLForms.getField("media_limit").disable();
						}'
					)
				)
			));

			$fieldset_media->add_field(new FormFieldNumberEditor('media_limit', $this->lang['admin.media.limit'], $this->modules[HomeLandingConfig::MODULE_MEDIA]->get_elements_number_displayed(),
				array(
					'min' => 1, 'max' => 100,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_MEDIA]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 100))
			));
		}

		// News
		if (ModulesManager::is_module_installed('news') && ModulesManager::is_module_activated('news'))
		{
			$fieldset_news = new FormFieldsetMultitabsHTML('admin_news',  $this->lang['admin.news'],
				array('css_class' => 'tabs tabs-animation')
			);
			$form->add_fieldset($fieldset_news);

			$fieldset_news->add_field(new FormFieldCheckbox('news_enabled', $this->lang['admin.news.enabled'], $this->modules[HomeLandingConfig::MODULE_NEWS]->is_displayed(),
				array(
					'class'=> 'custom-checkbox',
					'events' => array('click' => '
						if (HTMLForms.getField("news_enabled").getValue()) {
							HTMLForms.getField("news_limit").enable();
						} else {
							HTMLForms.getField("news_limit").disable();
						}'
					)
				)
			));

			$fieldset_news->add_field(new FormFieldNumberEditor('news_limit', $this->lang['admin.news.limit'], $this->modules[HomeLandingConfig::MODULE_NEWS]->get_elements_number_displayed(),
				array(
					'min' => 1, 'max' => 100,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_NEWS]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 100))
			));

			$fieldset_news->add_field(new FormFieldSpacer('news_separator', ''));

			$fieldset_news->add_field(new FormFieldCheckbox('news_cat_enabled', $this->lang['admin.news.cat.enabled'], $this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->is_displayed(),
				array(
					'class'=> 'custom-checkbox',
					'events' => array('click' => '
						if (HTMLForms.getField("news_cat_enabled").getValue()) {
							HTMLForms.getField("news_cat").enable();
							HTMLForms.getField("news_subcategories_content_displayed").enable();
							HTMLForms.getField("news_cat_limit").enable();
							HTMLForms.getField("news_cat_char").enable();
						} else {
							HTMLForms.getField("news_cat").disable();
							HTMLForms.getField("news_subcategories_content_displayed").disable();
							HTMLForms.getField("news_cat_limit").disable();
							HTMLForms.getField("news_cat_char").disable();
						}'
					)
				)
			));

			$fieldset_news->add_field(CategoriesService::get_categories_manager(HomeLandingConfig::MODULE_NEWS)->get_select_categories_form_field('news_cat', $this->lang['admin.cat'], $this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->get_id_category(), new SearchCategoryChildrensOptions(),
				array('hidden' => !$this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->is_displayed())
			));

			$fieldset_news->add_field(new FormFieldCheckbox('news_subcategories_content_displayed', $this->lang['admin.subcategories_content_displayed'], $this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->is_subcategories_content_displayed(),
				array(
					'class'=> 'custom-checkbox',
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->is_displayed()
				)
			));

			$fieldset_news->add_field(new FormFieldNumberEditor('news_cat_limit', $this->lang['admin.news.cat.limit'], $this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->get_elements_number_displayed(),
				array(
					'min' => 1, 'max' => 100,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 100))
			));

			$fieldset_news->add_field(new FormFieldNumberEditor('news_cat_char', $this->lang['admin.char'], $this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->get_characters_number_displayed(),
				array(
					'min' => 1, 'max' => 512,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 512))
			));
		}

		// External Rss
		// $fieldset_rss = new FormFieldsetMultitabsHTML('admin_rss',  $this->lang['admin.rss'],
		// 		array('css_class' => 'tabs tabs-animation')
		// 	);
		// 	$form->add_fieldset($fieldset_rss);
		//
		// 	$fieldset_rss->add_field(new FormFieldCheckbox('rss_enabled', $this->lang['admin.rss.enabled'], $this->modules[HomeLandingConfig::MODULE_RSS]->is_displayed(),
		// 		array(
		// 		'class'=> 'custom-checkbox',
		// 		'events' => array('click' => '
		// 			if (HTMLForms.getField("rss_enabled").getValue()) {
		// 				HTMLForms.getField("rss_site_name").enable();
		// 				HTMLForms.getField("rss_site_url").enable();
		// 				HTMLForms.getField("rss_xml_url").enable();
		// 				HTMLForms.getField("rss_xml_nb").enable();
		// 				HTMLForms.getField("rss_xml_char").enable();
		// 			} else {
		// 				HTMLForms.getField("rss_site_name").disable();
		// 				HTMLForms.getField("rss_site_url").disable();
		// 				HTMLForms.getField("rss_xml_url").disable();
		// 				HTMLForms.getField("rss_xml_nb").disable();
		// 				HTMLForms.getField("rss_xml_char").disable();
		// 			}'
		// 		)
		// 	)
		// 	));
		//
		// 	$fieldset_rss->add_field(new FormFieldTextEditor('rss_site_name', $this->lang['admin.rss.site.name'], $this->config->get_rss_site_name(),
		// 		array('hidden' => !$this->modules[HomeLandingConfig::MODULE_RSS]->is_displayed())
		// 	));
		//
		// 	$fieldset_rss->add_field(new FormFieldUrlEditor('rss_site_url', $this->lang['admin.rss.site.url'], $this->config->get_rss_site_url(),
		// 		array('hidden' => !$this->modules[HomeLandingConfig::MODULE_RSS]->is_displayed())
		// 	));
		//
		// 	$fieldset_rss->add_field(new FormFieldUrlEditor('rss_xml_url', $this->lang['admin.rss.xml.url'], $this->config->get_rss_xml_url(),
		// 		array('hidden' => !$this->modules[HomeLandingConfig::MODULE_RSS]->is_displayed())
		// 	));
		//
		// 	$fieldset_rss->add_field(new FormFieldNumberEditor('rss_xml_nb', $this->lang['admin.rss.xml.nb'], $this->modules[HomeLandingConfig::MODULE_RSS]->get_elements_number_displayed(),
		// 		array(
		// 			'min' => 1, 'max' => 100,
		// 			'hidden' => !$this->modules[HomeLandingConfig::MODULE_RSS]->is_displayed()
		// 		),
		// 		array(new FormFieldConstraintIntegerRange(1, 100))
		// 	));
		//
		// 	$fieldset_rss->add_field(new FormFieldNumberEditor('rss_xml_char', $this->lang['admin.rss.xml.char'], $this->modules[HomeLandingConfig::MODULE_RSS]->get_characters_number_displayed(),
		// 		array(
		// 			'min' => 0, 'max' => 512,
		// 			'hidden' => !$this->modules[HomeLandingConfig::MODULE_RSS]->is_displayed()
		// 		),
		// 		array(new FormFieldConstraintIntegerRange(0, 512))
		// 	));

		// Web
		if (ModulesManager::is_module_installed('web') && ModulesManager::is_module_activated('web'))
		{
			$fieldset_web = new FormFieldsetMultitabsHTML('admin_web',  $this->lang['admin.web'],
				array('css_class' => 'tabs tabs-animation')
			);
			$form->add_fieldset($fieldset_web);

			$fieldset_web->add_field(new FormFieldCheckbox('web_enabled', $this->lang['admin.web.enabled'], $this->modules[HomeLandingConfig::MODULE_WEB]->is_displayed(),
				array(
					'class'=> 'custom-checkbox',
					'description' => $this->lang['admin.web.enabled.desc'],
					'events' => array('click' => '
						if (HTMLForms.getField("web_enabled").getValue()) {
							HTMLForms.getField("web_limit").enable();
						} else {
							HTMLForms.getField("web_limit").disable();
						}'
					)
				)
			));

			$fieldset_web->add_field(new FormFieldNumberEditor('web_limit', $this->lang['admin.web.limit'], $this->modules[HomeLandingConfig::MODULE_WEB]->get_elements_number_displayed(),
				array(
					'min' => 1, 'max' => 100,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_WEB]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 100))
			));

			$fieldset_web->add_field(new FormFieldSpacer('web_separator', ''));

			$fieldset_web->add_field(new FormFieldCheckbox('web_cat_enabled', $this->lang['admin.web.cat.enabled'], $this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->is_displayed(),
				array(
					'class'=> 'custom-checkbox',
					'description' => $this->lang['admin.web.enabled.desc'],
					'events' => array('click' => '
						if (HTMLForms.getField("web_cat_enabled").getValue()) {
							HTMLForms.getField("web_cat").enable();
							HTMLForms.getField("web_subcategories_content_displayed").enable();
							HTMLForms.getField("web_cat_limit").enable();
							HTMLForms.getField("web_cat_char").enable();
						} else {
							HTMLForms.getField("web_cat").disable();
							HTMLForms.getField("web_subcategories_content_displayed").disable();
							HTMLForms.getField("web_cat_limit").disable();
							HTMLForms.getField("web_cat_char").disable();
						}'
					)
				)
			));

			$fieldset_web->add_field(CategoriesService::get_categories_manager(HomeLandingConfig::MODULE_WEB)->get_select_categories_form_field('web_cat', $this->lang['admin.cat'], $this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->get_id_category(), new SearchCategoryChildrensOptions(),
				array('hidden' => !$this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->is_displayed())
			));

			$fieldset_web->add_field(new FormFieldCheckbox('web_subcategories_content_displayed', $this->lang['admin.subcategories_content_displayed'], $this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->is_subcategories_content_displayed(),
				array(
					'class'=> 'custom-checkbox',
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->is_displayed()
				)
			));

			$fieldset_web->add_field(new FormFieldNumberEditor('web_cat_limit', $this->lang['admin.web.cat.limit'], $this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->get_elements_number_displayed(),
				array(
					'min' => 1, 'max' => 100,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 100))
			));

			$fieldset_web->add_field(new FormFieldNumberEditor('web_cat_char', $this->lang['admin.char'], $this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->get_characters_number_displayed(),
				array(
					'min' => 1, 'max' => 512,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 512))
			));
		}

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function save()
	{
		$this->config->set_module_title($this->form->get_value('module_title'));

		// Menu columns settings
		$this->config->set_left_columns($this->form->get_value('left_columns'));
		$this->config->set_right_columns($this->form->get_value('right_columns'));
		$this->config->set_top_central($this->form->get_value('top_central'));
		$this->config->set_bottom_central($this->form->get_value('bottom_central'));
		$this->config->set_top_footer($this->form->get_value('top_footer'));

		// Carousel
		if ($this->form->get_value('carousel_enabled'))
		{
			$this->modules[HomeLandingConfig::MODULE_CAROUSEL]->display();
			$this->config->set_carousel($this->form->get_value('carousel'));
			$this->config->set_carousel_speed($this->form->get_value('carousel_speed'));
			$this->config->set_carousel_time($this->form->get_value('carousel_time'));
			$this->config->set_carousel_number($this->form->get_value('carousel_number'));
			$this->config->set_carousel_auto($this->form->get_value('carousel_auto')->get_raw_value());
			$this->config->set_carousel_hover($this->form->get_value('carousel_hover')->get_raw_value());
		}
		else
			$this->modules[HomeLandingConfig::MODULE_CAROUSEL]->hide();

		// One page Menu
		if ($this->form->get_value('anchors_menu'))
		{
			$this->modules[HomeLandingConfig::MODULE_ANCHORS_MENU]->display();
			$this->config->set_anchors_menu($this->form->get_value('anchors_menu'));
		}
		else
			$this->modules[HomeLandingConfig::MODULE_ANCHORS_MENU]->hide();


		// Edito
		if ($this->form->get_value('edito_enabled'))
		{
			$this->modules[HomeLandingConfig::MODULE_EDITO]->display();
			$this->config->set_edito($this->form->get_value('edito'));
		}
		else
			$this->modules[HomeLandingConfig::MODULE_EDITO]->hide();

		// Lastcoms
		if ($this->form->get_value('lastcoms_enabled'))
		{
			$this->modules[HomeLandingConfig::MODULE_LASTCOMS]->display();
			$this->modules[HomeLandingConfig::MODULE_LASTCOMS]->set_elements_number_displayed($this->form->get_value('lastcoms_limit'));
			$this->modules[HomeLandingConfig::MODULE_LASTCOMS]->set_characters_number_displayed($this->form->get_value('lastcoms_char'));
		}
		else
			$this->modules[HomeLandingConfig::MODULE_LASTCOMS]->hide();

		// Articles
		if (ModulesManager::is_module_installed('articles') & ModulesManager::is_module_activated('articles'))
		{
			if ($this->form->get_value('articles_enabled'))
			{
				$this->modules[HomeLandingConfig::MODULE_ARTICLES]->display();
				$this->modules[HomeLandingConfig::MODULE_ARTICLES]->set_elements_number_displayed($this->form->get_value('articles_limit'));
			}
			else
				$this->modules[HomeLandingConfig::MODULE_ARTICLES]->hide();

			if ($this->form->get_value('articles_cat_enabled'))
			{
				$this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->display();
				if (!$this->form->field_is_disabled('articles_cat'))
					$this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->set_id_category($this->form->get_value('articles_cat')->get_raw_value());

				if ($this->form->get_value('articles_subcategories_content_displayed'))
					$this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->display_subcategories_content();
				else
					$this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->hide_subcategories_content();

				$this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->set_elements_number_displayed($this->form->get_value('articles_cat_limit'));
				$this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->set_characters_number_displayed($this->form->get_value('articles_cat_char'));
			}
			else
				$this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->hide();
		}

		// Calendar
		if (ModulesManager::is_module_installed('calendar') & ModulesManager::is_module_activated('calendar'))
		{
			if ($this->form->get_value('calendar_enabled'))
			{
				$this->modules[HomeLandingConfig::MODULE_CALENDAR]->display();
				$this->modules[HomeLandingConfig::MODULE_CALENDAR]->set_elements_number_displayed($this->form->get_value('calendar_limit'));
				$this->modules[HomeLandingConfig::MODULE_CALENDAR]->set_characters_number_displayed($this->form->get_value('calendar_char'));
			}
			else
				$this->modules[HomeLandingConfig::MODULE_CALENDAR]->hide();
		}

		// Contact
		if (ModulesManager::is_module_installed('contact') & ModulesManager::is_module_activated('contact'))
		{
			if ($this->form->get_value('contact_enabled'))
				$this->modules[HomeLandingConfig::MODULE_CONTACT]->display();
			else
				$this->modules[HomeLandingConfig::MODULE_CONTACT]->hide();
		}

		// Download
		if (ModulesManager::is_module_installed('download') & ModulesManager::is_module_activated('download'))
		{
			if ($this->form->get_value('download_enabled'))
			{
				$this->modules[HomeLandingConfig::MODULE_DOWNLOAD]->display();
				$this->modules[HomeLandingConfig::MODULE_DOWNLOAD]->set_elements_number_displayed($this->form->get_value('download_limit'));
			}
			else
				$this->modules[HomeLandingConfig::MODULE_DOWNLOAD]->hide();

			if ($this->form->get_value('download_cat_enabled'))
			{
				$this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->display();
				if (!$this->form->field_is_disabled('download_cat'))
					$this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->set_id_category($this->form->get_value('download_cat')->get_raw_value());

				if ($this->form->get_value('download_subcategories_content_displayed'))
					$this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->display_subcategories_content();
				else
					$this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->hide_subcategories_content();

				$this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->set_elements_number_displayed($this->form->get_value('download_cat_limit'));
				$this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->set_characters_number_displayed($this->form->get_value('download_cat_char'));
			}
			else
				$this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->hide();
		}

		// Forum
		if (ModulesManager::is_module_installed('forum') & ModulesManager::is_module_activated('forum'))
		{
			if ($this->form->get_value('forum_enabled'))
			{
				$this->modules[HomeLandingConfig::MODULE_FORUM]->display();
				$this->modules[HomeLandingConfig::MODULE_FORUM]->set_elements_number_displayed($this->form->get_value('forum_limit'));
				$this->modules[HomeLandingConfig::MODULE_FORUM]->set_characters_number_displayed($this->form->get_value('forum_char'));
			}
			else
				$this->modules[HomeLandingConfig::MODULE_FORUM]->hide();
		}

		// Gallery
		if (ModulesManager::is_module_installed('gallery') & ModulesManager::is_module_activated('gallery'))
		{
			if ($this->form->get_value('gallery_enabled'))
			{
				$this->modules[HomeLandingConfig::MODULE_GALLERY]->display();
				$this->modules[HomeLandingConfig::MODULE_GALLERY]->set_elements_number_displayed($this->form->get_value('gallery_limit'));
			}
			else
				$this->modules[HomeLandingConfig::MODULE_GALLERY]->hide();
		}

		// Guestbook
		if (ModulesManager::is_module_installed('guestbook') & ModulesManager::is_module_activated('guestbook'))
		{
			if ($this->form->get_value('guestbook_enabled'))
			{
				$this->modules[HomeLandingConfig::MODULE_GUESTBOOK]->display();
				$this->modules[HomeLandingConfig::MODULE_GUESTBOOK]->set_elements_number_displayed($this->form->get_value('guestbook_limit'));
				$this->modules[HomeLandingConfig::MODULE_GUESTBOOK]->set_characters_number_displayed($this->form->get_value('guestbook_char'));
			}
			else
				$this->modules[HomeLandingConfig::MODULE_GUESTBOOK]->hide();
		}

		// Media
		if (ModulesManager::is_module_installed('media') & ModulesManager::is_module_activated('media'))
		{
			if ($this->form->get_value('media_enabled'))
			{
				$this->modules[HomeLandingConfig::MODULE_MEDIA]->display();
				$this->modules[HomeLandingConfig::MODULE_MEDIA]->set_elements_number_displayed($this->form->get_value('media_limit'));
			}
			else
				$this->modules[HomeLandingConfig::MODULE_MEDIA]->hide();
		}

		// News
		if (ModulesManager::is_module_installed('news') & ModulesManager::is_module_activated('news'))
		{
			if ($this->form->get_value('news_enabled'))
			{
				$this->modules[HomeLandingConfig::MODULE_NEWS]->display();
				$this->modules[HomeLandingConfig::MODULE_NEWS]->set_elements_number_displayed($this->form->get_value('news_limit'));
			}
			else
				$this->modules[HomeLandingConfig::MODULE_NEWS]->hide();

			if ($this->form->get_value('news_cat_enabled'))
			{
				$this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->display();
				if (!$this->form->field_is_disabled('news_cat'))
					$this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->set_id_category($this->form->get_value('news_cat')->get_raw_value());

				if ($this->form->get_value('news_subcategories_content_displayed'))
					$this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->display_subcategories_content();
				else
					$this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->hide_subcategories_content();

				$this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->set_elements_number_displayed($this->form->get_value('news_cat_limit'));
				$this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->set_characters_number_displayed($this->form->get_value('news_cat_char'));
			}
			else
				$this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->hide();
		}

		// External Rss
		// if ($this->form->get_value('rss_enabled'))
		// {
		// 	$this->modules[HomeLandingConfig::MODULE_RSS]->display();
		// 	$this->config->set_rss_site_name($this->form->get_value('rss_site_name'));
		// 	$this->config->set_rss_site_url($this->form->get_value('rss_site_url'));
		// 	$this->config->set_rss_xml_url($this->form->get_value('rss_xml_url'));
		// 	$this->modules[HomeLandingConfig::MODULE_RSS]->set_elements_number_displayed($this->form->get_value('rss_xml_nb'));
		// 	$this->modules[HomeLandingConfig::MODULE_RSS]->set_characters_number_displayed($this->form->get_value('rss_xml_char'));
		// }
		// else
		// 	$this->modules[HomeLandingConfig::MODULE_RSS]->hide();

		// Web
		if (ModulesManager::is_module_installed('web') & ModulesManager::is_module_activated('web'))
		{
			if ($this->form->get_value('web_enabled'))
			{
				$this->modules[HomeLandingConfig::MODULE_WEB]->display();
				$this->modules[HomeLandingConfig::MODULE_WEB]->set_elements_number_displayed($this->form->get_value('web_limit'));
			}
			else
				$this->modules[HomeLandingConfig::MODULE_WEB]->hide();

			if ($this->form->get_value('web_cat_enabled'))
			{
				$this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->display();
				if (!$this->form->field_is_disabled('web_cat'))
					$this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->set_id_category($this->form->get_value('web_cat')->get_raw_value());

				if ($this->form->get_value('web_subcategories_content_displayed'))
					$this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->display_subcategories_content();
				else
					$this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->hide_subcategories_content();

				$this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->set_elements_number_displayed($this->form->get_value('web_cat_limit'));
				$this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->set_characters_number_displayed($this->form->get_value('web_cat_char'));
			}
			else
				$this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->hide();
		}

		HomeLandingModulesList::save($this->modules);
		HomeLandingConfig::save();
	}
}
?>
