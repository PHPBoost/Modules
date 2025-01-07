<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 11 13
 * @since       PHPBoost 6.0 - 2021 10 30
*/

class AdminFluxConfigController extends DefaultAdminModuleController
{
	private $update_form;
	private $update_button;

	protected function get_template_to_use()
	{
		return new FileTemplate('flux/AdminFluxConfigController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->build_form();
		$this->build_update_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('items_per_row')->set_hidden($this->config->get_display_type() !== FluxConfig::GRID_VIEW);
			$this->form->get_field_by_id('last_feeds_number')->set_hidden(!$this->config->get_last_feeds_display());
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.success.config'], MessageHelper::SUCCESS, 5));
		}

		if ($this->update_button->has_been_submited())
		{
			// Delete unsed files from cache folder
			$result = PersistenceContext::get_querier()->select('SELECT flux.*
			FROM ' . FluxSetup::$flux_table . ' flux
			WHERE published = 1
			OR published = 0'
			);

			$cache_list = new Folder(PATH_TO_ROOT . '/flux/xml');
			foreach($cache_list->get_files() as $file)
			{
				if($file->get_name() !== '.empty')
				{
					$is_in_content = array();
					foreach($result as $row)
					{
						if($row['xml_path'])
						{
							$filepath = $file->get_path();
							$xmlpath = PATH_TO_ROOT . $row['xml_path'];

							if(strcmp($filepath, $xmlpath) !== 0)
								$is_in_content[] = 'none';
							else
								$is_in_content[] = 'ok';
						}
					}
					if (!in_array('ok', $is_in_content))
						$file->delete();
				}	
			}

			// Update all items
			$result = PersistenceContext::get_querier()->select('SELECT flux.*
			FROM ' . FluxSetup::$flux_table . ' flux
			WHERE published = 1'
			);

			while ($row = $result->fetch())
			{
				// get xml path
				$file = new File(PATH_TO_ROOT . $row['xml_path']);
				if($file->get_path() != '..' && $file->exists()) // && !empty(PATH_TO_ROOT . $filename
				{
					// get file from target website
					$xml_url = Url::to_absolute($row['website_xml']);
					// load target feed items
					$content = file_get_contents($xml_url);
                    $content = substr($content, 0, strpos($content, '</rss>'));
                    $content .= '</rss>';
					// write target feed items in server file
					file_put_contents($file->get_path(), $content);
				}
			}
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['flux.success.update'], MessageHelper::SUCCESS, 5));
		}

		$this->view->put_all(array(
			'CONTENT' 	   => $this->form->display(),
			'UPDATE_CACHE' => $this->update_form->display()
		));

		return new DefaultAdminDisplayResponse($this->view);
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('config', $this->lang['form.configuration']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('module_name', $this->lang['flux.module.name'], $this->config->get_module_name()));

		$fieldset->add_field(new FormFieldCheckbox('new_window', $this->lang['form.new.window'], $this->config->get_new_window(),
			array(
				'description' => $this->lang['form.new.window.clue'],
				'class' => 'custom-checkbox'
			)
		));

        $fieldset->add_field(new FormFieldNumberEditor('rss_number', $this->lang['flux.rss.number'], $this->config->get_rss_number(),
			array('min' => 1, 'max' => 10, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 10))
		));

        $fieldset->add_field(new FormFieldNumberEditor('characters_number_to_cut', $this->lang['flux.characters.number.to.cut'], $this->config->get_characters_number_to_cut(),
			array('min' => 32, 'max' => 512, 'required' => true),
			array(new FormFieldConstraintIntegerRange(32, 512))
		));

		$fieldset->add_field(new FormFieldSpacer('default_config', ''));

        $fieldset->add_field(new FormFieldNumberEditor('items_per_page', $this->lang['form.items.per.page'], $this->config->get_items_per_page(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

        $fieldset->add_field(new FormFieldSimpleSelectChoice('display_type', $this->lang['form.display.type'], $this->config->get_display_type(),
			array(
				new FormFieldSelectChoiceOption($this->lang['form.display.type.grid'], FluxConfig::GRID_VIEW, array('data_option_icon' => 'fa fa-th-large')),
				new FormFieldSelectChoiceOption($this->lang['form.display.type.table'], FluxConfig::TABLE_VIEW, array('data_option_icon' => 'fa fa-table'))
			),
			array(
				'select_to_list' => true,
				'events' => array('change' => '
					if (HTMLForms.getField("display_type").getValue() == \'' . FluxConfig::GRID_VIEW . '\') {
						HTMLForms.getField("items_per_row").enable();
					} else {
						HTMLForms.getField("items_per_row").disable();
					}'
				)
			)
		));

        $fieldset->add_field(new FormFieldNumberEditor('items_per_row', $this->lang['form.items.per.row'], $this->config->get_items_per_row(),
			array(
				'min' => 1, 'max' => 4, 'required' => true,
				'hidden' => $this->config->get_display_type() !== FluxConfig::GRID_VIEW
			),
			array(new FormFieldConstraintIntegerRange(1, 4))
		));

		$fieldset->add_field(new FormFieldSpacer('last_feeds', ''));

		$fieldset->add_field(new FormFieldCheckbox('display_last_feeds', $this->lang['flux.display.last.feeds'], $this->config->get_last_feeds_display(),
			array(
				'class' => 'custom-checkbox',
				'events' => array('change' => '
					if (HTMLForms.getField("display_last_feeds").getValue()) {
						HTMLForms.getField("last_feeds_number").enable();
					} else {
						HTMLForms.getField("last_feeds_number").disable();
					}'
				)
			)
		));

        $fieldset->add_field(new FormFieldNumberEditor('last_feeds_number', $this->lang['flux.last.feeds.number'], $this->config->get_last_feeds_number(),
			array(
				'min' => 1, 'max' => 32, 'required' => true,
				'hidden' => !$this->config->get_last_feeds_display()
			),
			array(new FormFieldConstraintIntegerRange(1, 32))
		));

		$fieldset->add_field(new FormFieldRichTextEditor('default_content', $this->lang['form.item.default.content'], $this->config->get_default_content(),
			array('rows' => 8, 'cols' => 47)
		));

		$fieldset->add_field(new FormFieldNumberEditor('categories_per_page', $this->lang['form.categories.per.page'], $this->config->get_categories_per_page(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldNumberEditor('categories_per_row', $this->lang['form.categories.per.row'], $this->config->get_categories_per_row(),
			array('min' => 1, 'max' => 4, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 4))
		));

		$fieldset->add_field(new FormFieldRichTextEditor('root_category_description', $this->lang['form.root.category.description'], $this->config->get_root_category_description(),
			array('rows' => 8, 'cols' => 47)
		));

		$fieldset_authorizations = new FormFieldsetHTML('authorizations_fieldset', $this->lang['form.authorizations'],
			array('description' => $this->lang['form.authorizations.clue'])
		);
		$form->add_fieldset($fieldset_authorizations);

		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['form.authorizations.read'], Category::READ_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['form.authorizations.write'], Category::WRITE_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['form.authorizations.contribution'], Category::CONTRIBUTION_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['form.authorizations.moderation'], Category::MODERATION_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['form.authorizations.categories'], Category::CATEGORIES_MANAGEMENT_AUTHORIZATIONS)
		));
		$auth_setter = new FormFieldAuthorizationsSetter('authorizations', $auth_settings);
		$auth_settings->build_from_auth_array($this->config->get_authorizations());
		$fieldset_authorizations->add_field($auth_setter);

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function build_update_form()
	{
		$update_form = new HTMLForm(__CLASS__ . 'Update');

		$fieldset = new FormFieldsetHTML('update_all', $this->lang['flux.update.all']);
		$update_form->add_fieldset($fieldset);

		$fieldset->set_description($this->lang['flux.update.clue']);

		$this->update_button = new FormButtonDefaultSubmit($this->lang['flux.update']);
		$update_form->add_button($this->update_button);

		$this->update_form = $update_form;
	}

	private function save()
	{
		$this->config->set_module_name($this->form->get_value('module_name'));
		$this->config->set_new_window($this->form->get_value('new_window'));
		$this->config->set_rss_number($this->form->get_value('rss_number'));
		$this->config->set_characters_number_to_cut($this->form->get_value('characters_number_to_cut'));

		$this->config->set_last_feeds_display($this->form->get_value('display_last_feeds'));
		if ($this->form->get_value('display_last_feeds'))
			$this->config->set_last_feeds_number($this->form->get_value('last_feeds_number'));

		$this->config->set_display_type($this->form->get_value('display_type')->get_raw_value());
		$this->config->set_items_per_page($this->form->get_value('items_per_page'));
		if ($this->form->get_value('display_type')->get_raw_value() == FluxConfig::GRID_VIEW)
			$this->config->set_items_per_row($this->form->get_value('items_per_row'));
		$this->config->set_default_content($this->form->get_value('default_content'));

		$this->config->set_categories_per_page($this->form->get_value('categories_per_page'));
		$this->config->set_categories_per_row($this->form->get_value('categories_per_row'));
		$this->config->set_root_category_description($this->form->get_value('root_category_description'));

		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());

		FluxConfig::save();
		CategoriesService::get_categories_manager()->regenerate_cache();

		HooksService::execute_hook_action('edit_config', self::$module_id, array('title' => StringVars::replace_vars($this->lang['form.module.title'], array('module_name' => self::get_module_configuration()->get_name())), 'url' => ModulesUrlBuilder::configuration()->rel()));
	}
}
?>
