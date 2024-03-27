<?php
/**
 * @copyright   &copy; 2005-2024 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2024 01 31
 * @since       PHPBoost 5.0 - 2016 01 02
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class AdminHomeLandingConfigController extends DefaultAdminModuleController
{
	private $compatible;
	private $init_button;

	/**
	 * @var HomeLandingModulesList
	 */
	private $modules;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();

		$view = new StringTemplate('# INCLUDE MESSAGE_HELPER # # INCLUDE FORM #');

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

			if ($this->modules[HomeLandingConfig::MODULE_ARTICLES]->is_active())
			{
				$this->form->get_field_by_id('articles_limit')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_ARTICLES]->is_displayed());
				$this->form->get_field_by_id('articles_cat')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->is_displayed());
				$this->form->get_field_by_id('articles_subcategories_content_displayed')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->is_displayed());
				$this->form->get_field_by_id('articles_cat_limit')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->is_displayed());
				$this->form->get_field_by_id('articles_cat_char')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->is_displayed());
			}

			if ($this->modules[HomeLandingConfig::MODULE_CALENDAR]->is_active())
			{
				$this->form->get_field_by_id('calendar_limit')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_CALENDAR]->is_displayed());
				$this->form->get_field_by_id('calendar_char')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_CALENDAR]->is_displayed());
			}

			if ($this->modules[HomeLandingConfig::MODULE_DOWNLOAD]->is_active())
			{
				$this->form->get_field_by_id('download_limit')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_DOWNLOAD]->is_displayed());
				$this->form->get_field_by_id('download_cat')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->is_displayed());
				$this->form->get_field_by_id('download_subcategories_content_displayed')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->is_displayed());
				$this->form->get_field_by_id('download_cat_limit')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->is_displayed());
				$this->form->get_field_by_id('download_cat_char')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->is_displayed());
			}

			if ($this->modules[HomeLandingConfig::MODULE_FLUX]->is_active())
			{
				$this->form->get_field_by_id('flux_limit')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_FLUX]->is_displayed());
				$this->form->get_field_by_id('flux_char')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_FLUX]->is_displayed());
			}

			if ($this->modules[HomeLandingConfig::MODULE_FORUM]->is_active())
			{
				$this->form->get_field_by_id('forum_limit')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_FORUM]->is_displayed());
				$this->form->get_field_by_id('forum_char')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_FORUM]->is_displayed());
			}

			if ($this->modules[HomeLandingConfig::MODULE_GALLERY]->is_active())
			{
				$this->form->get_field_by_id('gallery_limit')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_GALLERY]->is_displayed());
			}

			if ($this->modules[HomeLandingConfig::MODULE_GUESTBOOK]->is_active())
			{
				$this->form->get_field_by_id('guestbook_limit')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_GUESTBOOK]->is_displayed());
				$this->form->get_field_by_id('guestbook_char')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_GUESTBOOK]->is_displayed());
			}

			if ($this->modules[HomeLandingConfig::MODULE_MEDIA]->is_active())
			{
				$this->form->get_field_by_id('media_limit')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_MEDIA]->is_displayed());
			}

			if ($this->modules[HomeLandingConfig::MODULE_NEWS]->is_active())
			{
				$this->form->get_field_by_id('news_limit')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_NEWS]->is_displayed());
				$this->form->get_field_by_id('news_cat')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->is_displayed());
				$this->form->get_field_by_id('news_subcategories_content_displayed')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->is_displayed());
				$this->form->get_field_by_id('news_cat_limit')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->is_displayed());
				$this->form->get_field_by_id('news_cat_char')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->is_displayed());
				$this->form->get_field_by_id('pinned_news_title')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_PINNED_NEWS]->is_displayed());
				$this->form->get_field_by_id('pinned_news_limit')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_PINNED_NEWS]->is_displayed());
			}

			if ($this->modules[HomeLandingConfig::MODULE_RECIPE]->is_active()) {
				$this->form->get_field_by_id('recipe_limit')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_RECIPE]->is_displayed());
				$this->form->get_field_by_id('recipe_cat')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_RECIPE_CATEGORY]->is_displayed());
				$this->form->get_field_by_id('recipe_subcategories_content_displayed')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_RECIPE_CATEGORY]->is_displayed());
				$this->form->get_field_by_id('recipe_cat_limit')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_RECIPE_CATEGORY]->is_displayed());
				$this->form->get_field_by_id('recipe_cat_char')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_RECIPE_CATEGORY]->is_displayed());
			}

			if ($this->modules[HomeLandingConfig::MODULE_SMALLADS]->is_active())
			{
				$this->form->get_field_by_id('smallads_limit')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_SMALLADS]->is_displayed());
				$this->form->get_field_by_id('smallads_cat')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_SMALLADS_CATEGORY]->is_displayed());
				$this->form->get_field_by_id('smallads_subcategories_content_displayed')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_SMALLADS_CATEGORY]->is_displayed());
				$this->form->get_field_by_id('smallads_cat_limit')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_SMALLADS_CATEGORY]->is_displayed());
				$this->form->get_field_by_id('smallads_cat_char')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_SMALLADS_CATEGORY]->is_displayed());
			}

			if ($this->modules[HomeLandingConfig::MODULE_VIDEO]->is_active()) {
				$this->form->get_field_by_id('video_limit')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_VIDEO]->is_displayed());
				$this->form->get_field_by_id('video_cat')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_VIDEO_CATEGORY]->is_displayed());
				$this->form->get_field_by_id('video_subcategories_content_displayed')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_VIDEO_CATEGORY]->is_displayed());
				$this->form->get_field_by_id('video_cat_limit')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_VIDEO_CATEGORY]->is_displayed());
				$this->form->get_field_by_id('video_cat_char')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_VIDEO_CATEGORY]->is_displayed());
			}

			if ($this->modules[HomeLandingConfig::MODULE_WEB]->is_active())
			{
				$this->form->get_field_by_id('web_limit')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_WEB]->is_displayed());
				$this->form->get_field_by_id('web_cat')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->is_displayed());
				$this->form->get_field_by_id('web_subcategories_content_displayed')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->is_displayed());
				$this->form->get_field_by_id('web_cat_limit')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->is_displayed());
				$this->form->get_field_by_id('web_cat_char')->set_hidden(!$this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->is_displayed());
			}

			// Files autoload for additional field state after validation
			$submit_directory = new Folder(PATH_TO_ROOT . '/HomeLanding/additional/submit/');
			$submit_files = $submit_directory->get_files();
			foreach ($submit_files as $submit_file)
			{
				require_once($submit_file->get_path());
			}

			$view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.success.config'], MessageHelper::SUCCESS, 4));
		}

		$view->put('FORM', $this->form->display());

		return new AdminHomeLandingDisplayResponse($view, $this->lang['form.configuration']);
	}

	private function init()
	{
		$this->modules = HomeLandingModulesList::load();
		$this->compatible = ModulesManager::get_activated_feature_modules('homelanding');
	}

	private function tabs_menu_list()
	{
		$tabs_li = $home_modules = $modules_from_list = array();

		// List of installed modules compatible with HomeLanding
		$home_modules += array('0' => 'configuration', '1' => 'carousel');
		foreach ($this->compatible as $module)
		{
			$home_modules[] = $module->get_id();
		}

		// list of modules installed in HomeLanding
		foreach($this->config->get_modules() as $id => $module)
		{
			$modules_from_list[] = $module['module_id'];
		}

        foreach($home_modules as $module)
        {

			if($module == 'configuration')
				$tabs_li[] = new FormFieldMultitabsLinkElement($this->lang['form.configuration'], 'tabs', 'AdminHomeLandingConfigController_configuration', 'fa fa-cogs');
			elseif($module == 'carousel')
				$tabs_li[] = new FormFieldMultitabsLinkElement($this->lang['homelanding.module.carousel'], 'tabs', 'AdminHomeLandingConfigController_admin_carousel', 'fa fa-image');
			elseif(in_array($module, $modules_from_list))
			{
				$img_url = PATH_TO_ROOT . '/' . $module . '/' . $module . '_mini.png';
				$img = new File($img_url);
				$fa_icon = ModulesManager::get_module($module)->get_configuration()->get_fa_icon();
				$hexa_icon = ModulesManager::get_module($module)->get_configuration()->get_hexa_icon();
				$thumbnail = $img->exists() ? $img_url : '';
				if ($img->exists())
					$tabs_li[] = new FormFieldMultitabsLinkElement(ModulesManager::get_module($module)->get_configuration()->get_name(), 'tabs', 'AdminHomeLandingConfigController_admin_' . $module, '', $thumbnail, $module);
				elseif (!empty($fa_icon))
					$tabs_li[] = new FormFieldMultitabsLinkElement(ModulesManager::get_module($module)->get_configuration()->get_name(), 'tabs', 'AdminHomeLandingConfigController_admin_' . $module, $fa_icon, '', $module);
				elseif (!empty($hexa_icon))
					$tabs_li[] = new FormFieldMultitabsLinkElement(ModulesManager::get_module($module)->get_configuration()->get_name(), 'tabs', 'AdminHomeLandingConfigController_admin_' . $module, $hexa_icon, '', $module);
				else
					$tabs_li[] = new FormFieldMultitabsLinkElement(ModulesManager::get_module($module)->get_configuration()->get_name(), 'tabs', 'AdminHomeLandingConfigController_admin_' . $module, '', '', $module);
			}
		}
		return $tabs_li;
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		$form->set_css_class('tabs-container');

		// New modules warning
		$compatible_list = $config_list = $new_modules_list = array();
		foreach ($this->compatible as $module)
		{
			$compatible_list[] = $module->get_id();
		}

		foreach($this->config->get_modules() as $id => $module)
		{
			$config_list[] = $module['module_id'];
		}

		$new_modules = array_diff($compatible_list, $config_list);
		if($new_modules)
		{
			$fieldset_new_modules = new FormFieldMenuFieldset('new_modules_list', $this->lang['homelanding.new.modules']);
			$form->add_fieldset($fieldset_new_modules);

			foreach($new_modules as $module)
			{
				$new_module_name[] = ModulesManager::get_module($module)->get_configuration()->get_name() . ' | ';
			}
			$modules_list = substr(json_encode($new_module_name), 2, -4);

			$fieldset_new_modules->add_field(new FormFieldFree('new_modules', $this->lang['homelanding.new.modules'], StringVars::replace_vars($this->lang['homelanding.new.modules.description'], array('modules_list' => $modules_list)) ,
				array('class' => 'bgc warning message-helper full-field')
			));
		}

		// Tabs menu
		$fieldset_tabs_menu = new FormFieldMenuFieldset('tabs_menu', '');
		$form->add_fieldset($fieldset_tabs_menu);
		$fieldset_tabs_menu->set_css_class('tabs-nav');

        $fieldset_tabs_menu->add_field(new FormFieldMultitabsLinkList('tabs_menu_list',$this->tabs_menu_list()));

		// Configuration
		$fieldset_config = new FormFieldsetMultitabsHTML('configuration', $this->lang['homelanding.config.module.title'],
				array('css_class' => 'tabs tabs-animation first-tab')
			);
			$form->add_fieldset($fieldset_config);

			$fieldset_config->add_field(new FormFieldTextEditor('module_title', $this->lang['homelanding.label.module.title'], $this->config->get_module_title(),
				array('description' => $this->lang['homelanding.label.module.title.clue'])
			));

			$fieldset_config->add_field(new FormFieldCheckbox('left_columns', $this->lang['homelanding.hide.menu.left'], $this->config->get_left_columns(),
				array('class' => 'custom-checkbox')
			));

			$fieldset_config->add_field(new FormFieldCheckbox('right_columns', $this->lang['homelanding.hide.menu.right'], $this->config->get_right_columns(),
				array('class' => 'custom-checkbox')
			));

			$fieldset_config->add_field(new FormFieldCheckbox('top_central', $this->lang['homelanding.hide.menu.top.central'], $this->config->get_top_central(),
				array('class' => 'custom-checkbox')
			));

			$fieldset_config->add_field(new FormFieldCheckbox('bottom_central', $this->lang['homelanding.hide.menu.bottom.central'], $this->config->get_bottom_central(),
				array('class' => 'custom-checkbox')
			));

			$fieldset_config->add_field(new FormFieldCheckbox('top_footer', $this->lang['homelanding.hide.menu.top.footer'], $this->config->get_top_footer(),
				array('class' => 'custom-checkbox')
			));

			$fieldset_config->add_field(new FormFieldSubTitle('admin_anchors', $this->lang['homelanding.config.anchors'], ''));

			$fieldset_config->add_field(new FormFieldCheckbox('anchors_menu_enabled', $this->lang['homelanding.display.anchors'], $this->config->get_anchors_menu(),
				array(
					'class' => 'custom-checkbox',
					'description' => $this->lang['homelanding.display.anchors.clue']
				)
			));

			$fieldset_config->add_field(new FormFieldSubTitle('admin_edito', $this->lang['homelanding.config.edito'], ''));

			$fieldset_config->add_field(new FormFieldCheckbox('edito_enabled', $this->lang['homelanding.display.edito'], $this->modules[HomeLandingConfig::MODULE_EDITO]->is_displayed(),
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

			$fieldset_config->add_field(new FormFieldRichTextEditor('edito', $this->lang['homelanding.edito.content'], $this->config->get_edito(),
				array('hidden' => !$this->modules[HomeLandingConfig::MODULE_EDITO]->is_displayed())
			));

			$fieldset_config->add_field(new FormFieldSubTitle('admin_lastcoms', $this->lang['homelanding.config.lastcoms'], ''));

			$fieldset_config->add_field(new FormFieldCheckbox('lastcoms_enabled', $this->lang['homelanding.display.lastcoms'], $this->modules[HomeLandingConfig::MODULE_LASTCOMS]->is_displayed(),
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

			$fieldset_config->add_field(new FormFieldNumberEditor('lastcoms_limit', $this->lang['homelanding.lastcoms.limit'], $this->modules[HomeLandingConfig::MODULE_LASTCOMS]->get_elements_number_displayed(),
				array(
					'min' => 1, 'max' => 100,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_LASTCOMS]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 100))
			));

			$fieldset_config->add_field(new FormFieldNumberEditor('lastcoms_char', $this->lang['homelanding.characters.limit'], $this->modules[HomeLandingConfig::MODULE_LASTCOMS]->get_characters_number_displayed(),
				array(
					'min' => 1, 'max' => 512,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_LASTCOMS]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 512))
			));

		//  Carousel
		$fieldset_carousel = new FormFieldsetMultitabsHTML('admin_carousel', $this->lang['homelanding.config.carousel'],
				array('css_class' => 'tabs tabs-animation')
			);
			$form->add_fieldset($fieldset_carousel);

			$fieldset_carousel->add_field(new FormFieldCheckbox('carousel_enabled', $this->lang['homelanding.display.carousel'], $this->modules[HomeLandingConfig::MODULE_CAROUSEL]->is_displayed(),
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

			$fieldset_carousel->add_field(new FormFieldNumberEditor('carousel_speed', $this->lang['homelanding.carousel.speed'], $this->config->get_carousel_speed(),
				array('hidden' => !$this->modules[HomeLandingConfig::MODULE_CAROUSEL]->is_displayed())
			));

			$fieldset_carousel->add_field(new FormFieldNumberEditor('carousel_time', $this->lang['homelanding.carousel.time'], $this->config->get_carousel_time(),
				array('hidden' => !$this->modules[HomeLandingConfig::MODULE_CAROUSEL]->is_displayed())
			));

			$fieldset_carousel->add_field(new FormFieldNumberEditor('carousel_number', $this->lang['homelanding.carousel.number'], $this->config->get_carousel_number(),
				array(
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_CAROUSEL]->is_displayed(),
					'description' => $this->lang['homelanding.carousel.number.clue']
				)
			));

			$fieldset_carousel->add_field(new FormFieldRadioChoice('carousel_auto', $this->lang['homelanding.carousel.auto'], $this->config->get_carousel_auto(),
				array(
					new FormFieldRadioChoiceOption($this->lang['homelanding.carousel.enabled'], HomeLandingConfig::CAROUSEL_TRUE),
					new FormFieldRadioChoiceOption($this->lang['homelanding.carousel.disabled'], HomeLandingConfig::CAROUSEL_FALSE)
				),
				array(
					'class' => 'inline-radio',
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_CAROUSEL]->is_displayed()
				)
			));

			$fieldset_carousel->add_field(new FormFieldRadioChoice('carousel_hover', $this->lang['homelanding.carousel.hover'], $this->config->get_carousel_hover(),
				array(
					new FormFieldRadioChoiceOption($this->lang['homelanding.carousel.enabled'], HomeLandingConfig::CAROUSEL_TRUE),
					new FormFieldRadioChoiceOption($this->lang['homelanding.carousel.disabled'], HomeLandingConfig::CAROUSEL_FALSE)
				),
				array(
					'class' => 'inline-radio',
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_CAROUSEL]->is_displayed()
				)
			));

			$fieldset_carousel->add_field(new HomeLandingFormFieldSliderConfig('carousel', $this->lang['homelanding.carousel.content'], $this->config->get_carousel(),
				array(
					'class' => 'full-field',
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_CAROUSEL]->is_displayed()
				)
			));

		// Articles
		if ($this->modules[HomeLandingConfig::MODULE_ARTICLES]->is_active())
		{
			$fieldset_articles = new FormFieldsetMultitabsHTML('admin_articles', $this->lang['homelanding.module.display'] . ModulesManager::get_module($this->modules[HomeLandingConfig::MODULE_ARTICLES]->get_module_id())->get_configuration()->get_name(),
				array('css_class' => 'tabs tabs-animation')
			);
			$form->add_fieldset($fieldset_articles);

			$fieldset_articles->add_field(new FormFieldCheckbox('articles_enabled', $this->lang['homelanding.show.full.module'], $this->modules[HomeLandingConfig::MODULE_ARTICLES]->is_displayed(),
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

			$fieldset_articles->add_field(new FormFieldNumberEditor('articles_limit', $this->lang['homelanding.items.number'], $this->modules[HomeLandingConfig::MODULE_ARTICLES]->get_elements_number_displayed(),
				array(
					'min' => 1, 'max' => 100,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_ARTICLES]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 100))
			));

			$fieldset_articles->add_field(new FormFieldSpacer('articles_separator', ''));

			$fieldset_articles->add_field(new FormFieldCheckbox('articles_cat_enabled', $this->lang['homelanding.display.category'], $this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->is_displayed(),
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

			$fieldset_articles->add_field(CategoriesService::get_categories_manager(HomeLandingConfig::MODULE_ARTICLES)->get_select_categories_form_field('articles_cat', $this->lang['homelanding.choose.category'], $this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->get_id_category(), new SearchCategoryChildrensOptions(),
				array('hidden' => !$this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->is_displayed())
			));

			$fieldset_articles->add_field(new FormFieldCheckbox('articles_subcategories_content_displayed', $this->lang['homelanding.display.sub.categories'], $this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->is_subcategories_content_displayed(),
				array(
					'class'=> 'custom-checkbox',
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->is_displayed())
			));

			$fieldset_articles->add_field(new FormFieldNumberEditor('articles_cat_limit', $this->lang['homelanding.items.number'], $this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->get_elements_number_displayed(),
				array(
					'min' => 1, 'max' => 100,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 100))
			));

			$fieldset_articles->add_field(new FormFieldNumberEditor('articles_cat_char', $this->lang['homelanding.characters.limit'], $this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->get_characters_number_displayed(),
				array(
					'min' => 1, 'max' => 512,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 512))
			));
		}

		// Calendar
		if ($this->modules[HomeLandingConfig::MODULE_CALENDAR]->is_active())
		{
			$fieldset_calendar = new FormFieldsetMultitabsHTML('admin_calendar', $this->lang['homelanding.module.display'] . ModulesManager::get_module($this->modules[HomeLandingConfig::MODULE_CALENDAR]->get_module_id())->get_configuration()->get_name(),
				array('css_class' => 'tabs tabs-animation')
			);
			$form->add_fieldset($fieldset_calendar);

			$fieldset_calendar->add_field(new FormFieldCheckbox('calendar_enabled', $this->lang['homelanding.show.module'], $this->modules[HomeLandingConfig::MODULE_CALENDAR]->is_displayed(),
				array(
					'class'=> 'custom-checkbox',
					'description' => $this->lang['homelanding.calendar.clue'],
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

			$fieldset_calendar->add_field(new FormFieldNumberEditor('calendar_limit', $this->lang['homelanding.items.number'], $this->modules[HomeLandingConfig::MODULE_CALENDAR]->get_elements_number_displayed(),
				array(
					'min' => 1, 'max' => 100,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_CALENDAR]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 100))
			));

			$fieldset_calendar->add_field(new FormFieldNumberEditor('calendar_char', $this->lang['homelanding.characters.limit'], $this->modules[HomeLandingConfig::MODULE_CALENDAR]->get_characters_number_displayed(),
				array(
					'min' => 1, 'max' => 512,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_CALENDAR]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 512))
			));
		}

		// Contact
		if ($this->modules[HomeLandingConfig::MODULE_CONTACT]->is_active())
		{
			$fieldset_contact = new FormFieldsetMultitabsHTML('admin_contact', $this->lang['homelanding.module.display'] . ModulesManager::get_module($this->modules[HomeLandingConfig::MODULE_CONTACT]->get_module_id())->get_configuration()->get_name(),
				array('css_class' => 'tabs tabs-animation')
			);
			$form->add_fieldset($fieldset_contact);

			$fieldset_contact->add_field(new FormFieldCheckbox('contact_enabled', $this->lang['homelanding.show.module'], $this->modules[HomeLandingConfig::MODULE_CONTACT]->is_displayed(),
				array('class'=> 'custom-checkbox')
			));
		}

		// Download
		if ($this->modules[HomeLandingConfig::MODULE_DOWNLOAD]->is_active())
		{
			$fieldset_download = new FormFieldsetMultitabsHTML('admin_download', $this->lang['homelanding.module.display'] . ModulesManager::get_module($this->modules[HomeLandingConfig::MODULE_DOWNLOAD]->get_module_id())->get_configuration()->get_name(),
				array('css_class' => 'tabs tabs-animation')
			);
			$form->add_fieldset($fieldset_download);

			$fieldset_download->add_field(new FormFieldCheckbox('download_enabled', $this->lang['homelanding.show.full.module'], $this->modules[HomeLandingConfig::MODULE_DOWNLOAD]->is_displayed(),
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

			$fieldset_download->add_field(new FormFieldNumberEditor('download_limit', $this->lang['homelanding.items.number'], $this->modules[HomeLandingConfig::MODULE_DOWNLOAD]->get_elements_number_displayed(),
				array(
					'min' => 1, 'max' => 100,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_DOWNLOAD]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 100))
			));

			$fieldset_download->add_field(new FormFieldSpacer('download_separator', ''));

			$fieldset_download->add_field(new FormFieldCheckbox('download_cat_enabled', $this->lang['homelanding.display.category'], $this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->is_displayed(),
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

			$fieldset_download->add_field(CategoriesService::get_categories_manager(HomeLandingConfig::MODULE_DOWNLOAD)->get_select_categories_form_field('download_cat', $this->lang['homelanding.choose.category'], $this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->get_id_category(), new SearchCategoryChildrensOptions(),
				array('hidden' => !$this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->is_displayed())
			));

			$fieldset_download->add_field(new FormFieldCheckbox('download_subcategories_content_displayed', $this->lang['homelanding.display.sub.categories'], $this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->is_subcategories_content_displayed(),
				array(
					'class'=> 'custom-checkbox',
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->is_displayed())
			));

			$fieldset_download->add_field(new FormFieldNumberEditor('download_cat_limit', $this->lang['homelanding.items.number'], $this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->get_elements_number_displayed(),
				array(
					'min' => 1, 'max' => 100,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 100))
			));

			$fieldset_download->add_field(new FormFieldNumberEditor('download_cat_char', $this->lang['homelanding.characters.limit'], $this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->get_characters_number_displayed(),
				array(
					'min' => 1, 'max' => 512,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 512))
			));
		}

		// Flux
		if ($this->modules[HomeLandingConfig::MODULE_FLUX]->is_active())
		{
			$fieldset_flux = new FormFieldsetMultitabsHTML('admin_flux', $this->lang['homelanding.module.display'] . ModulesManager::get_module($this->modules[HomeLandingConfig::MODULE_FLUX]->get_module_id())->get_configuration()->get_name(),
				array('css_class' => 'tabs tabs-animation')
			);
			$form->add_fieldset($fieldset_flux);

			$fieldset_flux->add_field(new FormFieldCheckbox('flux_enabled', $this->lang['homelanding.show.module'], $this->modules[HomeLandingConfig::MODULE_FLUX]->is_displayed(),
				array(
					'class'=> 'custom-checkbox',
					'events' => array('click' => '
						if (HTMLForms.getField("flux_enabled").getValue()) {
							HTMLForms.getField("flux_limit").enable();
							HTMLForms.getField("flux_char").enable();
						} else {
							HTMLForms.getField("flux_limit").disable();
							HTMLForms.getField("flux_char").disable();
						}'
					)
				)
			));

			$fieldset_flux->add_field(new FormFieldNumberEditor('flux_limit', $this->lang['homelanding.items.number'], $this->modules[HomeLandingConfig::MODULE_FLUX]->get_elements_number_displayed(),
				array(
					'min' => 1, 'max' => 100,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_FLUX]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 100))
			));

			$fieldset_flux->add_field(new FormFieldNumberEditor('flux_char', $this->lang['homelanding.characters.limit'], $this->modules[HomeLandingConfig::MODULE_FLUX]->get_characters_number_displayed(),
				array(
					'min' => 1, 'max' => 512,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_FLUX]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 512))
			));
		}

		// Forum
		if ($this->modules[HomeLandingConfig::MODULE_FORUM]->is_active())
		{
			$fieldset_forum = new FormFieldsetMultitabsHTML('admin_forum', $this->lang['homelanding.module.display'] . ModulesManager::get_module($this->modules[HomeLandingConfig::MODULE_FORUM]->get_module_id())->get_configuration()->get_name(),
				array('css_class' => 'tabs tabs-animation')
			);
			$form->add_fieldset($fieldset_forum);

			$fieldset_forum->add_field(new FormFieldCheckbox('forum_enabled', $this->lang['homelanding.show.module'], $this->modules[HomeLandingConfig::MODULE_FORUM]->is_displayed(),
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

			$fieldset_forum->add_field(new FormFieldNumberEditor('forum_limit', $this->lang['homelanding.items.number'], $this->modules[HomeLandingConfig::MODULE_FORUM]->get_elements_number_displayed(),
				array(
					'min' => 1, 'max' => 100,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_FORUM]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 100))
			));

			$fieldset_forum->add_field(new FormFieldNumberEditor('forum_char', $this->lang['homelanding.characters.limit'], $this->modules[HomeLandingConfig::MODULE_FORUM]->get_characters_number_displayed(),
				array(
					'min' => 1, 'max' => 512,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_FORUM]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 512))
			));
		}

		// Gallery
		if ($this->modules[HomeLandingConfig::MODULE_GALLERY]->is_active())
		{
			$fieldset_gallery = new FormFieldsetMultitabsHTML('admin_gallery', $this->lang['homelanding.module.display'] . ModulesManager::get_module($this->modules[HomeLandingConfig::MODULE_GALLERY]->get_module_id())->get_configuration()->get_name(),
				array('css_class' => 'tabs tabs-animation')
			);
			$form->add_fieldset($fieldset_gallery);

			$fieldset_gallery->add_field(new FormFieldCheckbox('gallery_enabled', $this->lang['homelanding.show.module'], $this->modules[HomeLandingConfig::MODULE_GALLERY]->is_displayed(),
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

			$fieldset_gallery->add_field(new FormFieldNumberEditor('gallery_limit', $this->lang['homelanding.items.number'], $this->modules[HomeLandingConfig::MODULE_GALLERY]->get_elements_number_displayed(),
				array(
					'min' => 1, 'max' => 100,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_GALLERY]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 100))
			));
		}

		// Guestbook
		if ($this->modules[HomeLandingConfig::MODULE_GUESTBOOK]->is_active())
		{
			$fieldset_guestbook = new FormFieldsetMultitabsHTML('admin_guestbook', $this->lang['homelanding.module.display'] . ModulesManager::get_module($this->modules[HomeLandingConfig::MODULE_GUESTBOOK]->get_module_id())->get_configuration()->get_name(),
				array('css_class' => 'tabs tabs-animation')
			);
			$form->add_fieldset($fieldset_guestbook);

			$fieldset_guestbook->add_field(new FormFieldCheckbox('guestbook_enabled', $this->lang['homelanding.show.module'], $this->modules[HomeLandingConfig::MODULE_GUESTBOOK]->is_displayed(),
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

			$fieldset_guestbook->add_field(new FormFieldNumberEditor('guestbook_limit', $this->lang['homelanding.items.number'], $this->modules[HomeLandingConfig::MODULE_GUESTBOOK]->get_elements_number_displayed(),
				array(
					'min' => 1, 'max' => 100,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_GUESTBOOK]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 100))
			));

			$fieldset_guestbook->add_field(new FormFieldNumberEditor('guestbook_char', $this->lang['homelanding.characters.limit'], $this->modules[HomeLandingConfig::MODULE_GUESTBOOK]->get_characters_number_displayed(),
				array(
					'min' => 1, 'max' => 512,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_GUESTBOOK]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 512))
			));
		}

		// Media
		if ($this->modules[HomeLandingConfig::MODULE_MEDIA]->is_active())
		{
			$fieldset_media = new FormFieldsetMultitabsHTML('admin_media', $this->lang['homelanding.module.display'] . ModulesManager::get_module($this->modules[HomeLandingConfig::MODULE_MEDIA]->get_module_id())->get_configuration()->get_name(),
				array('css_class' => 'tabs tabs-animation')
			);
			$form->add_fieldset($fieldset_media);

			$fieldset_media->add_field(new FormFieldCheckbox('media_enabled', $this->lang['homelanding.show.module'], $this->modules[HomeLandingConfig::MODULE_MEDIA]->is_displayed(),
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

			$fieldset_media->add_field(new FormFieldNumberEditor('media_limit', $this->lang['homelanding.items.number'], $this->modules[HomeLandingConfig::MODULE_MEDIA]->get_elements_number_displayed(),
				array(
					'min' => 1, 'max' => 100,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_MEDIA]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 100))
			));
		}

		// News
		if ($this->modules[HomeLandingConfig::MODULE_NEWS]->is_active())
		{
			$fieldset_news = new FormFieldsetMultitabsHTML('admin_news', $this->lang['homelanding.module.display'] . ModulesManager::get_module($this->modules[HomeLandingConfig::MODULE_NEWS]->get_module_id())->get_configuration()->get_name(),
				array('css_class' => 'tabs tabs-animation')
			);
			$form->add_fieldset($fieldset_news);

			$fieldset_news->add_field(new FormFieldCheckbox('news_enabled', $this->lang['homelanding.show.full.module'], $this->modules[HomeLandingConfig::MODULE_NEWS]->is_displayed(),
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

			$fieldset_news->add_field(new FormFieldNumberEditor('news_limit', $this->lang['homelanding.items.number'], $this->modules[HomeLandingConfig::MODULE_NEWS]->get_elements_number_displayed(),
				array(
					'min' => 1, 'max' => 100,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_NEWS]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 100))
			));

			$fieldset_news->add_field(new FormFieldSpacer('news_separator', ''));

			$fieldset_news->add_field(new FormFieldCheckbox('news_cat_enabled', $this->lang['homelanding.display.category'], $this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->is_displayed(),
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

			$fieldset_news->add_field(CategoriesService::get_categories_manager(HomeLandingConfig::MODULE_NEWS)->get_select_categories_form_field('news_cat', $this->lang['homelanding.choose.category'], $this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->get_id_category(), new SearchCategoryChildrensOptions(),
				array('hidden' => !$this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->is_displayed())
			));

			$fieldset_news->add_field(new FormFieldCheckbox('news_subcategories_content_displayed', $this->lang['homelanding.display.sub.categories'], $this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->is_subcategories_content_displayed(),
				array(
					'class'=> 'custom-checkbox',
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->is_displayed()
				)
			));

			$fieldset_news->add_field(new FormFieldNumberEditor('news_cat_limit', $this->lang['homelanding.items.number'], $this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->get_elements_number_displayed(),
				array(
					'min' => 1, 'max' => 100,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 100))
			));

			$fieldset_news->add_field(new FormFieldNumberEditor('news_cat_char', $this->lang['homelanding.characters.limit'], $this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->get_characters_number_displayed(),
				array(
					'min' => 1, 'max' => 512,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 512))
			));

			$fieldset_news->add_field(new FormFieldSpacer('pinned_news_separator', ''));

			$fieldset_news->add_field(new FormFieldCheckbox('pinned_news_enabled', $this->lang['homelanding.show.pinned.news'], $this->modules[HomeLandingConfig::MODULE_PINNED_NEWS]->is_displayed(),
				array(
					'class'=> 'custom-checkbox',
					'events' => array('click' => '
						if (HTMLForms.getField("pinned_news_enabled").getValue()) {
							HTMLForms.getField("pinned_news_title").enable();
							HTMLForms.getField("pinned_news_limit").enable();
						} else {
							HTMLForms.getField("pinned_news_title").disable();
							HTMLForms.getField("pinned_news_limit").disable();
						}'
					)
				)
			));

			$fieldset_news->add_field(new FormFieldTextEditor('pinned_news_title', $this->lang['homelanding.pinned.news.title'], $this->config->get_pinned_news_title(),
				array('hidden' => !$this->modules[HomeLandingConfig::MODULE_PINNED_NEWS]->is_displayed())
			));

			$fieldset_news->add_field(new FormFieldNumberEditor('pinned_news_limit', $this->lang['homelanding.items.number'], $this->modules[HomeLandingConfig::MODULE_PINNED_NEWS]->get_elements_number_displayed(),
				array(
					'min' => 1, 'max' => 100,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_PINNED_NEWS]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 100))
			));
		}

		// Recipe
		if ($this->modules[HomeLandingConfig::MODULE_RECIPE]->is_active()) {
			$fieldset_recipe = new FormFieldsetMultitabsHTML('admin_recipe', $this->lang['homelanding.module.display'] . ModulesManager::get_module($this->modules[HomeLandingConfig::MODULE_RECIPE]->get_module_id())->get_configuration()->get_name(),
				array('css_class' => 'tabs tabs-animation')
			);
			$form->add_fieldset($fieldset_recipe);

			$fieldset_recipe->add_field(new FormFieldCheckbox('recipe_enabled', $this->lang['homelanding.show.full.module'], $this->modules[HomeLandingConfig::MODULE_RECIPE]->is_displayed(),
				array(
					'class' => 'custom-checkbox',
					'events' => array('click' => '
						if (HTMLForms.getField("recipe_enabled").getValue()) {
							HTMLForms.getField("recipe_limit").enable();
						} else {
							HTMLForms.getField("recipe_limit").disable();
						}'
					)
				)
			));

			$fieldset_recipe->add_field(new FormFieldNumberEditor('recipe_limit', $this->lang['homelanding.items.number'], $this->modules[HomeLandingConfig::MODULE_RECIPE]->get_elements_number_displayed(),
				array(
					'min' => 1, 'max' => 100,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_RECIPE]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 100))
			));

			$fieldset_recipe->add_field(new FormFieldSpacer('recipe_separator', ''));

			$fieldset_recipe->add_field(new FormFieldCheckbox('recipe_cat_enabled', $this->lang['homelanding.display.category'], $this->modules[HomeLandingConfig::MODULE_RECIPE_CATEGORY]->is_displayed(),
				array(
					'class' => 'custom-checkbox',
					'events' => array('click' => '
						if (HTMLForms.getField("recipe_cat_enabled").getValue()) {
							HTMLForms.getField("recipe_cat").enable();
							HTMLForms.getField("recipe_subcategories_content_displayed").enable();
							HTMLForms.getField("recipe_cat_limit").enable();
							HTMLForms.getField("recipe_cat_char").enable();
						} else {
							HTMLForms.getField("recipe_cat").disable();
							HTMLForms.getField("recipe_subcategories_content_displayed").disable();
							HTMLForms.getField("recipe_cat_limit").disable();
							HTMLForms.getField("recipe_cat_char").disable();
						}'
					)
				)
			));

			$fieldset_recipe->add_field(CategoriesService::get_categories_manager(HomeLandingConfig::MODULE_RECIPE)->get_select_categories_form_field('recipe_cat', $this->lang['homelanding.choose.category'], $this->modules[HomeLandingConfig::MODULE_RECIPE_CATEGORY]->get_id_category(), new SearchCategoryChildrensOptions(),
				array('hidden' => !$this->modules[HomeLandingConfig::MODULE_RECIPE_CATEGORY]->is_displayed())
			));

			$fieldset_recipe->add_field(new FormFieldCheckbox('recipe_subcategories_content_displayed', $this->lang['homelanding.display.sub.categories'], $this->modules[HomeLandingConfig::MODULE_RECIPE_CATEGORY]->is_subcategories_content_displayed(),
				array(
					'class' => 'custom-checkbox',
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_RECIPE_CATEGORY]->is_displayed()
				)
			));

			$fieldset_recipe->add_field(new FormFieldNumberEditor('recipe_cat_limit', $this->lang['homelanding.items.number'], $this->modules[HomeLandingConfig::MODULE_RECIPE_CATEGORY]->get_elements_number_displayed(),
				array(
					'min' => 1, 'max' => 100,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_RECIPE_CATEGORY]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 100))
			));

			$fieldset_recipe->add_field(new FormFieldNumberEditor('recipe_cat_char', $this->lang['homelanding.characters.limit'], $this->modules[HomeLandingConfig::MODULE_RECIPE_CATEGORY]->get_characters_number_displayed(),
				array(
					'min' => 1, 'max' => 512,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_RECIPE_CATEGORY]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 512))
			));
		}

		// Smallads
		if ($this->modules[HomeLandingConfig::MODULE_SMALLADS]->is_active())
		{
			$fieldset_smallads = new FormFieldsetMultitabsHTML('admin_smallads', $this->lang['homelanding.module.display'] . ModulesManager::get_module($this->modules[HomeLandingConfig::MODULE_SMALLADS]->get_module_id())->get_configuration()->get_name(),
				array('css_class' => 'tabs tabs-animation')
			);
			$form->add_fieldset($fieldset_smallads);

			$fieldset_smallads->add_field(new FormFieldCheckbox('smallads_enabled', $this->lang['homelanding.show.full.module'], $this->modules[HomeLandingConfig::MODULE_SMALLADS]->is_displayed(),
				array(
					'class'=> 'custom-checkbox',
					'events' => array('click' => '
						if (HTMLForms.getField("smallads_enabled").getValue()) {
							HTMLForms.getField("smallads_limit").enable();
						} else {
							HTMLForms.getField("smallads_limit").disable();
						}'
					)
				)
			));

			$fieldset_smallads->add_field(new FormFieldNumberEditor('smallads_limit', $this->lang['homelanding.items.number'], $this->modules[HomeLandingConfig::MODULE_SMALLADS]->get_elements_number_displayed(),
				array(
					'min' => 1, 'max' => 100,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_SMALLADS]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 100))
			));

			$fieldset_smallads->add_field(new FormFieldSpacer('smallads_separator', ''));

			$fieldset_smallads->add_field(new FormFieldCheckbox('smallads_cat_enabled', $this->lang['homelanding.display.category'], $this->modules[HomeLandingConfig::MODULE_SMALLADS_CATEGORY]->is_displayed(),
				array(
					'class'=> 'custom-checkbox',
					'events' => array('click' => '
						if (HTMLForms.getField("smallads_cat_enabled").getValue()) {
							HTMLForms.getField("smallads_cat").enable();
							HTMLForms.getField("smallads_subcategories_content_displayed").enable();
							HTMLForms.getField("smallads_cat_limit").enable();
							HTMLForms.getField("smallads_cat_char").enable();
						} else {
							HTMLForms.getField("smallads_cat").disable();
							HTMLForms.getField("smallads_subcategories_content_displayed").disable();
							HTMLForms.getField("smallads_cat_limit").disable();
							HTMLForms.getField("smallads_cat_char").disable();
						}'
					)
				)
			));

			$fieldset_smallads->add_field(CategoriesService::get_categories_manager(HomeLandingConfig::MODULE_SMALLADS)->get_select_categories_form_field('smallads_cat', $this->lang['homelanding.choose.category'], $this->modules[HomeLandingConfig::MODULE_SMALLADS_CATEGORY]->get_id_category(), new SearchCategoryChildrensOptions(),
				array('hidden' => !$this->modules[HomeLandingConfig::MODULE_SMALLADS_CATEGORY]->is_displayed())
			));

			$fieldset_smallads->add_field(new FormFieldCheckbox('smallads_subcategories_content_displayed', $this->lang['homelanding.display.sub.categories'], $this->modules[HomeLandingConfig::MODULE_SMALLADS_CATEGORY]->is_subcategories_content_displayed(),
				array(
					'class'=> 'custom-checkbox',
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_SMALLADS_CATEGORY]->is_displayed()
				)
			));

			$fieldset_smallads->add_field(new FormFieldNumberEditor('smallads_cat_limit', $this->lang['homelanding.items.number'], $this->modules[HomeLandingConfig::MODULE_SMALLADS_CATEGORY]->get_elements_number_displayed(),
				array(
					'min' => 1, 'max' => 100,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_SMALLADS_CATEGORY]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 100))
			));

			$fieldset_smallads->add_field(new FormFieldNumberEditor('smallads_cat_char', $this->lang['homelanding.characters.limit'], $this->modules[HomeLandingConfig::MODULE_SMALLADS_CATEGORY]->get_characters_number_displayed(),
				array(
					'min' => 1, 'max' => 512,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_SMALLADS_CATEGORY]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 512))
			));
		}

		// Video
		if ($this->modules[HomeLandingConfig::MODULE_VIDEO]->is_active()) {
			$fieldset_video = new FormFieldsetMultitabsHTML('admin_video', $this->lang['homelanding.module.display'] . ModulesManager::get_module($this->modules[HomeLandingConfig::MODULE_VIDEO]->get_module_id())->get_configuration()->get_name(),
				array('css_class' => 'tabs tabs-animation')
			);
			$form->add_fieldset($fieldset_video);

			$fieldset_video->add_field(new FormFieldCheckbox('video_enabled', $this->lang['homelanding.show.full.module'], $this->modules[HomeLandingConfig::MODULE_VIDEO]->is_displayed(),
				array(
					'class' => 'custom-checkbox',
					'events' => array('click' => '
						if (HTMLForms.getField("video_enabled").getValue()) {
							HTMLForms.getField("video_limit").enable();
						} else {
							HTMLForms.getField("video_limit").disable();
						}'
					)
				)
			));

			$fieldset_video->add_field(new FormFieldNumberEditor('video_limit', $this->lang['homelanding.items.number'], $this->modules[HomeLandingConfig::MODULE_VIDEO]->get_elements_number_displayed(),
				array(
					'min' => 1, 'max' => 100,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_VIDEO]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 100))
			));

			$fieldset_video->add_field(new FormFieldSpacer('video_separator', ''));

			$fieldset_video->add_field(new FormFieldCheckbox('video_cat_enabled', $this->lang['homelanding.display.category'], $this->modules[HomeLandingConfig::MODULE_VIDEO_CATEGORY]->is_displayed(),
				array(
					'class' => 'custom-checkbox',
					'events' => array('click' => '
						if (HTMLForms.getField("video_cat_enabled").getValue()) {
							HTMLForms.getField("video_cat").enable();
							HTMLForms.getField("video_subcategories_content_displayed").enable();
							HTMLForms.getField("video_cat_limit").enable();
							HTMLForms.getField("video_cat_char").enable();
						} else {
							HTMLForms.getField("video_cat").disable();
							HTMLForms.getField("video_subcategories_content_displayed").disable();
							HTMLForms.getField("video_cat_limit").disable();
							HTMLForms.getField("video_cat_char").disable();
						}'
					)
				)
			));

			$fieldset_video->add_field(CategoriesService::get_categories_manager(HomeLandingConfig::MODULE_VIDEO)->get_select_categories_form_field('video_cat', $this->lang['homelanding.choose.category'], $this->modules[HomeLandingConfig::MODULE_VIDEO_CATEGORY]->get_id_category(), new SearchCategoryChildrensOptions(),
				array('hidden' => !$this->modules[HomeLandingConfig::MODULE_VIDEO_CATEGORY]->is_displayed())
			));

			$fieldset_video->add_field(new FormFieldCheckbox('video_subcategories_content_displayed', $this->lang['homelanding.display.sub.categories'], $this->modules[HomeLandingConfig::MODULE_VIDEO_CATEGORY]->is_subcategories_content_displayed(),
				array(
					'class' => 'custom-checkbox',
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_VIDEO_CATEGORY]->is_displayed()
				)
			));

			$fieldset_video->add_field(new FormFieldNumberEditor('video_cat_limit', $this->lang['homelanding.items.number'], $this->modules[HomeLandingConfig::MODULE_VIDEO_CATEGORY]->get_elements_number_displayed(),
				array(
					'min' => 1, 'max' => 100,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_VIDEO_CATEGORY]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 100))
			));

			$fieldset_video->add_field(new FormFieldNumberEditor('video_cat_char', $this->lang['homelanding.characters.limit'], $this->modules[HomeLandingConfig::MODULE_VIDEO_CATEGORY]->get_characters_number_displayed(),
				array(
					'min' => 1, 'max' => 512,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_VIDEO_CATEGORY]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 512))
			));
		}

		// Web
		if ($this->modules[HomeLandingConfig::MODULE_WEB]->is_active())
		{
			$fieldset_web = new FormFieldsetMultitabsHTML('admin_web',  $this->lang['homelanding.module.display'] . ModulesManager::get_module($this->modules[HomeLandingConfig::MODULE_WEB]->get_module_id())->get_configuration()->get_name(),
				array('css_class' => 'tabs tabs-animation')
			);
			$form->add_fieldset($fieldset_web);

			$fieldset_web->add_field(new FormFieldCheckbox('web_enabled', $this->lang['homelanding.show.full.module'], $this->modules[HomeLandingConfig::MODULE_WEB]->is_displayed(),
				array(
					'class'=> 'custom-checkbox',
					'description' => $this->lang['homelanding.web.clue'],
					'events' => array('click' => '
						if (HTMLForms.getField("web_enabled").getValue()) {
							HTMLForms.getField("web_limit").enable();
						} else {
							HTMLForms.getField("web_limit").disable();
						}'
					)
				)
			));

			$fieldset_web->add_field(new FormFieldNumberEditor('web_limit', $this->lang['homelanding.items.number'], $this->modules[HomeLandingConfig::MODULE_WEB]->get_elements_number_displayed(),
				array(
					'min' => 1, 'max' => 100,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_WEB]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 100))
			));

			$fieldset_web->add_field(new FormFieldSpacer('web_separator', ''));

			$fieldset_web->add_field(new FormFieldCheckbox('web_cat_enabled', $this->lang['homelanding.display.category'], $this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->is_displayed(),
				array(
					'class'=> 'custom-checkbox',
					'description' => $this->lang['homelanding.web.clue'],
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

			$fieldset_web->add_field(CategoriesService::get_categories_manager(HomeLandingConfig::MODULE_WEB)->get_select_categories_form_field('web_cat', $this->lang['homelanding.choose.category'], $this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->get_id_category(), new SearchCategoryChildrensOptions(),
				array('hidden' => !$this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->is_displayed())
			));

			$fieldset_web->add_field(new FormFieldCheckbox('web_subcategories_content_displayed', $this->lang['homelanding.display.sub.categories'], $this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->is_subcategories_content_displayed(),
				array(
					'class'=> 'custom-checkbox',
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->is_displayed()
				)
			));

			$fieldset_web->add_field(new FormFieldNumberEditor('web_cat_limit', $this->lang['homelanding.items.number'], $this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->get_elements_number_displayed(),
				array(
					'min' => 1, 'max' => 100,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 100))
			));

			$fieldset_web->add_field(new FormFieldNumberEditor('web_cat_char', $this->lang['homelanding.characters.limit'], $this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->get_characters_number_displayed(),
				array(
					'min' => 1, 'max' => 512,
					'hidden' => !$this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->is_displayed()
				),
				array(new FormFieldConstraintIntegerRange(1, 512))
			));
		}

		// Files autoload for additional fields
		$form_directory = new Folder(PATH_TO_ROOT . '/HomeLanding/additional/form/');
		$form_files = $form_directory->get_files();
		foreach ($form_files as $form_file)
		{
            require_once($form_file->get_path());
		}

		$this->submit_button = new FormButtonDefaultSubmit();
		$this->init_button = new FormButtonDefaultSubmit('add module');
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
		$this->config->set_anchors_menu($this->form->get_value('anchors_menu_enabled'));
		if ($this->form->get_value('anchors_menu_enabled'))
			$this->modules[HomeLandingConfig::MODULE_ANCHORS_MENU]->display();
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
		if ($this->modules[HomeLandingConfig::MODULE_ARTICLES]->is_active())
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
		if ($this->modules[HomeLandingConfig::MODULE_CALENDAR]->is_active())
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
		if ($this->modules[HomeLandingConfig::MODULE_CONTACT]->is_active())
		{
			if ($this->form->get_value('contact_enabled'))
				$this->modules[HomeLandingConfig::MODULE_CONTACT]->display();
			else
				$this->modules[HomeLandingConfig::MODULE_CONTACT]->hide();
		}

		// Download
		if ($this->modules[HomeLandingConfig::MODULE_DOWNLOAD]->is_active())
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

		// Flux
		if ($this->modules[HomeLandingConfig::MODULE_FLUX]->is_active())
		{
			if ($this->form->get_value('flux_enabled'))
			{
				$this->modules[HomeLandingConfig::MODULE_FLUX]->display();
				$this->modules[HomeLandingConfig::MODULE_FLUX]->set_elements_number_displayed($this->form->get_value('flux_limit'));
				$this->modules[HomeLandingConfig::MODULE_FLUX]->set_characters_number_displayed($this->form->get_value('flux_char'));
			}
			else
				$this->modules[HomeLandingConfig::MODULE_FLUX]->hide();
		}

		// Forum
		if ($this->modules[HomeLandingConfig::MODULE_FORUM]->is_active())
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
		if ($this->modules[HomeLandingConfig::MODULE_GALLERY]->is_active())
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
		if ($this->modules[HomeLandingConfig::MODULE_GUESTBOOK]->is_active())
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
		if ($this->modules[HomeLandingConfig::MODULE_MEDIA]->is_active())
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
		if ($this->modules[HomeLandingConfig::MODULE_NEWS]->is_active())
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

			if ($this->form->get_value('pinned_news_enabled'))
			{
				$this->modules[HomeLandingConfig::MODULE_PINNED_NEWS]->display();
				$this->config->set_pinned_news_title($this->form->get_value('pinned_news_title'));
				$this->modules[HomeLandingConfig::MODULE_PINNED_NEWS]->set_elements_number_displayed($this->form->get_value('pinned_news_limit'));
			}
			else
				$this->modules[HomeLandingConfig::MODULE_PINNED_NEWS]->hide();
		}

		// Recipe
		if ($this->modules[HomeLandingConfig::MODULE_RECIPE]->is_active())
		{
			if ($this->form->get_value('recipe_enabled'))
			{
				$this->modules[HomeLandingConfig::MODULE_RECIPE]->display();
				$this->modules[HomeLandingConfig::MODULE_RECIPE]->set_elements_number_displayed($this->form->get_value('recipe_limit'));
			}
			else
				$this->modules[HomeLandingConfig::MODULE_RECIPE]->hide();

			if ($this->form->get_value('recipe_cat_enabled'))
			{
				$this->modules[HomeLandingConfig::MODULE_RECIPE_CATEGORY]->display();
				if (!$this->form->field_is_disabled('recipe_cat'))
					$this->modules[HomeLandingConfig::MODULE_RECIPE_CATEGORY]->set_id_category($this->form->get_value('recipe_cat')->get_raw_value());

				if ($this->form->get_value('recipe_subcategories_content_displayed'))
					$this->modules[HomeLandingConfig::MODULE_RECIPE_CATEGORY]->display_subcategories_content();
				else
					$this->modules[HomeLandingConfig::MODULE_RECIPE_CATEGORY]->hide_subcategories_content();

				$this->modules[HomeLandingConfig::MODULE_RECIPE_CATEGORY]->set_elements_number_displayed($this->form->get_value('recipe_cat_limit'));
				$this->modules[HomeLandingConfig::MODULE_RECIPE_CATEGORY]->set_characters_number_displayed($this->form->get_value('recipe_cat_char'));
			}
			else
				$this->modules[HomeLandingConfig::MODULE_RECIPE_CATEGORY]->hide();
		}

		// Smallads
		if ($this->modules[HomeLandingConfig::MODULE_SMALLADS]->is_active())
		{
			if ($this->form->get_value('smallads_enabled'))
			{
				$this->modules[HomeLandingConfig::MODULE_SMALLADS]->display();
				$this->modules[HomeLandingConfig::MODULE_SMALLADS]->set_elements_number_displayed($this->form->get_value('smallads_limit'));
			}
			else
				$this->modules[HomeLandingConfig::MODULE_SMALLADS]->hide();

			if ($this->form->get_value('smallads_cat_enabled'))
			{
				$this->modules[HomeLandingConfig::MODULE_SMALLADS_CATEGORY]->display();
				if (!$this->form->field_is_disabled('smallads_cat'))
					$this->modules[HomeLandingConfig::MODULE_SMALLADS_CATEGORY]->set_id_category($this->form->get_value('smallads_cat')->get_raw_value());

				if ($this->form->get_value('smallads_subcategories_content_displayed'))
					$this->modules[HomeLandingConfig::MODULE_SMALLADS_CATEGORY]->display_subcategories_content();
				else
					$this->modules[HomeLandingConfig::MODULE_SMALLADS_CATEGORY]->hide_subcategories_content();

				$this->modules[HomeLandingConfig::MODULE_SMALLADS_CATEGORY]->set_elements_number_displayed($this->form->get_value('smallads_cat_limit'));
				$this->modules[HomeLandingConfig::MODULE_SMALLADS_CATEGORY]->set_characters_number_displayed($this->form->get_value('smallads_cat_char'));
			}
			else
				$this->modules[HomeLandingConfig::MODULE_SMALLADS_CATEGORY]->hide();
		}

		// Video
		if ($this->modules[HomeLandingConfig::MODULE_VIDEO]->is_active())
		{
			if ($this->form->get_value('video_enabled'))
			{
				$this->modules[HomeLandingConfig::MODULE_VIDEO]->display();
				$this->modules[HomeLandingConfig::MODULE_VIDEO]->set_elements_number_displayed($this->form->get_value('video_limit'));
			}
			else
				$this->modules[HomeLandingConfig::MODULE_VIDEO]->hide();

			if ($this->form->get_value('video_cat_enabled'))
			{
				$this->modules[HomeLandingConfig::MODULE_VIDEO_CATEGORY]->display();
				if (!$this->form->field_is_disabled('video_cat'))
					$this->modules[HomeLandingConfig::MODULE_VIDEO_CATEGORY]->set_id_category($this->form->get_value('video_cat')->get_raw_value());

				if ($this->form->get_value('video_subcategories_content_displayed'))
					$this->modules[HomeLandingConfig::MODULE_VIDEO_CATEGORY]->display_subcategories_content();
				else
					$this->modules[HomeLandingConfig::MODULE_VIDEO_CATEGORY]->hide_subcategories_content();

				$this->modules[HomeLandingConfig::MODULE_VIDEO_CATEGORY]->set_elements_number_displayed($this->form->get_value('video_cat_limit'));
				$this->modules[HomeLandingConfig::MODULE_VIDEO_CATEGORY]->set_characters_number_displayed($this->form->get_value('video_cat_char'));
			}
			else
				$this->modules[HomeLandingConfig::MODULE_VIDEO_CATEGORY]->hide();
		}

		// Web
		if ($this->modules[HomeLandingConfig::MODULE_WEB]->is_active())
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

		// Files autoload for additional saving properties
		$save_directory = new Folder(PATH_TO_ROOT . '/HomeLanding/additional/save/');
		$save_files = $save_directory->get_files();
		foreach ($save_files as $save_file)
		{
			require_once($save_file->get_path());
		}

		HomeLandingModulesList::save($this->modules);
		HomeLandingConfig::save();
		HooksService::execute_hook_action('edit_config', 'HomeLanding', array('title' => StringVars::replace_vars($this->lang['form.module.title'], array('module_name' => ModulesManager::get_module('HomeLanding')->get_configuration()->get_name())), 'url' => ModulesUrlBuilder::configuration()->rel()));
	}
}
?>
