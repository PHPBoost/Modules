<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 25
 * @since       PHPBoost 5.1 - 2018 03 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class AdminSmalladsMiniMenuConfigController extends AdminModuleController
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
	private $admin_common_lang;

	/**
	 * @var SmalladsConfig
	 */
	private $config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();

		$view = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$view->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('mini_menu_autoplay_speed')->set_hidden(!$this->config->is_slideshow_autoplayed());
			$this->form->get_field_by_id('mini_menu_autoplay_hover')->set_hidden(!$this->config->is_slideshow_autoplayed());
			$view->put('MSG', MessageHelper::display(LangLoader::get_message('warning.success.config', 'warning-lang'), MessageHelper::SUCCESS, 4));
		}

		$view->put('FORM', $this->form->display());

		return new AdminSmalladsDisplayResponse($view, $this->lang['config.mini.title']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'smallads');
		$this->config = SmalladsConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('mini_configuration', $this->lang['config.mini.title']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldNumberEditor('mini_menu_items_nb', $this->lang['config.mini.items.nb'], $this->config->get_mini_menu_items_nb(),
			array('min' => 1, 'max' => 10,),
			array(new FormFieldConstraintIntegerRange(1, 10))
		));

		$fieldset->add_field(new FormFieldNumberEditor('mini_menu_animation_speed', $this->lang['config.mini.animation.speed'], $this->config->get_mini_menu_animation_speed(),
			array('description' => $this->lang['config.mini.speed.desc'])
		));

		$fieldset->add_field(new FormFieldSpacer('1_separator', ''));

		$fieldset->add_field(new FormFieldCheckbox('mini_menu_autoplay', $this->lang['config.mini.autoplay'], $this->config->is_slideshow_autoplayed(),
			array(
				'class' => 'custom-checkbox',
				'events' => array('click' => '
					if (HTMLForms.getField("mini_menu_autoplay").getValue()) {
						HTMLForms.getField("mini_menu_autoplay_speed").enable();
						HTMLForms.getField("mini_menu_autoplay_hover").enable();
					} else {
						HTMLForms.getField("mini_menu_autoplay_speed").disable();
						HTMLForms.getField("mini_menu_autoplay_hover").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldNumberEditor('mini_menu_autoplay_speed', $this->lang['config.mini.autoplay.speed'], $this->config->get_mini_menu_autoplay_speed(),
			array(
				'description' => $this->lang['config.mini.speed.desc'],
				'hidden' => !$this->config->is_slideshow_autoplayed()
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('mini_menu_autoplay_hover', $this->lang['config.mini.autoplay.hover'], $this->config->is_slideshow_hover_enabled(),
			array(
				'class' => 'custom-checkbox',
				'hidden' => !$this->config->is_slideshow_autoplayed())
		));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}


	private function save()
	{
		$this->config->set_mini_menu_items_nb($this->form->get_value('mini_menu_items_nb'));
		$this->config->set_mini_menu_animation_speed($this->form->get_value('mini_menu_animation_speed'));

		if ($this->form->get_value('mini_menu_autoplay'))
		{
			$this->config->play_mini_menu_autoplay();
			$this->config->set_mini_menu_autoplay_speed($this->form->get_value('mini_menu_autoplay_speed'));
			if ($this->form->get_value('mini_menu_autoplay_hover'))
				$this->config->play_mini_menu_autoplay_hover();
			else
				$this->config->stop_mini_menu_autoplay_hover();
		}
		else
		{
			$this->config->stop_mini_menu_autoplay();
			$this->config->stop_mini_menu_autoplay_hover();
		}

		SmalladsConfig::save();
		CategoriesService::get_categories_manager()->regenerate_cache();
		SmalladsCache::invalidate();
	}
}
?>
