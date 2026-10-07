<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 07
 * @since       PHPBoost 4.1 - 2015 05 15
 * @author      mipel <mipel@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ForumCategoriesFormController extends DefaultCategoriesFormController
{
	protected function build_form(HTTPRequestCustom $request)
	{
		/** @var ForumCategory $category */
		$category = $this->get_category();
		self::$lang = LangLoader::get_all_langs('forum');
		$form = new HTMLForm(self::class);
		$form->set_layout_title($this->get_title());

		$fieldset = new FormFieldsetHTML('category', self::$lang['form.parameters']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldSimpleSelectChoice('type', self::$lang['common.type'], $category->get_type(),
			[
				new FormFieldSelectChoiceOption(self::$lang['category.category'], ForumCategory::TYPE_CATEGORY),
				new FormFieldSelectChoiceOption(self::$lang['forum.module.title'], ForumCategory::TYPE_FORUM),
				new FormFieldSelectChoiceOption(self::$lang['common.link'], ForumCategory::TYPE_URL)
			], [
				'events' => ['change' => '
				if (HTMLForms.getField("type").getValue() == ' . ForumCategory::TYPE_CATEGORY . ') {
					HTMLForms.getField("id_parent").disable();
					HTMLForms.getField("description").disable();
					HTMLForms.getField("status").disable();
					HTMLForms.getField("url").disable();
					if (HTMLForms.getField("special_authorizations").getValue()) {
						jQuery("#' . self::class . '_authorizations > div").eq(1).hide();
						jQuery("#' . self::class . '_authorizations > div").eq(2).hide();
					}
				} else if (HTMLForms.getField("type").getValue() == ' . ForumCategory::TYPE_FORUM . ') {
					HTMLForms.getField("id_parent").enable();
					HTMLForms.getField("description").enable();
					HTMLForms.getField("status").enable();
					HTMLForms.getField("url").disable();
					if (HTMLForms.getField("special_authorizations").getValue()) {
						jQuery("#' . self::class . '_authorizations > div").eq(1).show();
						jQuery("#' . self::class . '_authorizations > div").eq(2).show();
					}
				} else {
					HTMLForms.getField("id_parent").enable();
					HTMLForms.getField("description").enable();
					HTMLForms.getField("status").disable();
					HTMLForms.getField("url").enable();
					if (HTMLForms.getField("special_authorizations").getValue()) {
						jQuery("#' . self::class . '_authorizations > div").eq(1).hide();
						jQuery("#' . self::class . '_authorizations > div").eq(2).hide();
					}
				}']
			]
		));

		$search_category_children_options = new SearchCategoryChildrensOptions();
		$search_category_children_options->add_category_in_excluded_categories(Category::ROOT_CATEGORY);

		if ($category->get_id())
			$search_category_children_options->add_category_in_excluded_categories($category->get_id());

		$fieldset->add_field(self::get_categories_manager()->get_select_categories_form_field('id_parent', self::$lang['form.category'], $category->get_id_parent(), $search_category_children_options,
			[
				'required' => true,
				'hidden' => $category->get_type() == ForumCategory::TYPE_CATEGORY
			]
		));

		$fieldset->add_field(new FormFieldTextEditor('name', self::$lang['form.name'], $category->get_name(), ['required' => true]));

		$fieldset->add_field(new FormFieldCheckbox('personalize_rewrited_name', self::$lang['form.rewrited.title.personalize'], $category->rewrited_name_is_personalized(),
			[
				'events' => ['click' => '
					if (HTMLForms.getField("personalize_rewrited_name").getValue()) {
						HTMLForms.getField("rewrited_name").enable();
					} else {
						HTMLForms.getField("rewrited_name").disable();
					}'
				]
			]
		));

		$fieldset->add_field(new FormFieldTextEditor('rewrited_name', self::$lang['form.rewrited.title'], $category->get_rewrited_name(),
			[
				'description' => self::$lang['form.rewrited.title.clue'],
				'hidden' => !$category->rewrited_name_is_personalized()
			],
			[new FormFieldConstraintRegex('`^[a-z0-9\-]+$`iu')]
		));

		$fieldset->add_field(new FormFieldThumbnail('thumbnail', self::$lang['form.thumbnail'], $category->get_thumbnail()->relative(), ForumCategory::THUMBNAIL_URL,
			[
				// 'events' => ['click' => '
				// 	if (HTMLForms.getField("thumbnail").getValue() == ' . FormFieldThumbnail::NONE . ') {
				// 		HTMLForms.getField("icon").enable();
				// 	} else {
				// 		HTMLForms.getField("icon").disable();
				// 	}'
				// ]
			]
		));

		$fieldset->add_field(new FormFieldTextEditor('icon', self::$lang['forum.category.icon'], $category->get_icon(),
			[
				'description' => self::$lang['forum.category.icon.clue'],
				'placeholder' => self::$lang['forum.category.icon.placeholder'],
				// 'hidden' => !$category->get_thumbnail()->relative() == FormFieldThumbnail::NONE
			]
		));

		$fieldset->add_field(new FormFieldColorPicker('color', self::$lang['common.color'], $category->get_color()));

		$fieldset->add_field(new FormFieldRichTextEditor('description', self::$lang['form.description'], $category->get_description(),
			['hidden' => $category->get_type() == ForumCategory::TYPE_CATEGORY]
		));

		$fieldset->add_field(new FormFieldCheckbox('status', self::$lang['forum.category.status.locked'], $category->get_status(),
			['hidden' => $category->get_type() != ForumCategory::TYPE_FORUM]
		));

		$fieldset->add_field(new FormFieldUrlEditor('url', self::$lang['form.url'], $category->get_url(),
			['required' => true, 'hidden' => $category->get_type() != ForumCategory::TYPE_URL]
		));

		$fieldset_authorizations = new FormFieldsetHTML('authorizations_fieldset', self::$lang['form.authorizations']);
		$form->add_fieldset($fieldset_authorizations);

		$root_auth = self::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY)->get_authorizations();

		$fieldset_authorizations->add_field(new FormFieldCheckbox('special_authorizations', self::$lang['form.authorizations'], !$category->auth_is_equals($root_auth),
			[
				'description' => self::$lang['category.form.authorizations.clue'],
				'events' => ['click' => '
					if (HTMLForms.getField("special_authorizations").getValue()) {
						jQuery("#' . self::class . '_authorizations").show();
						if (HTMLForms.getField("type").getValue() == ' . ForumCategory::TYPE_CATEGORY . ' || HTMLForms.getField("type").getValue() == ' . ForumCategory::TYPE_URL . ') {
							jQuery("#' . self::class . '_authorizations > div").eq(1).hide();
							jQuery("#' . self::class . '_authorizations > div").eq(2).hide();
						}
					} else {
						jQuery("#' . self::class . '_authorizations").hide();
					}'
				]
			]
		));

		// Autorisations cachées à l'édition Si le type est catégorie ou url
		$fieldset_authorizations->add_field(new FormFieldFree('hide_authorizations', '', '
			<script>
				<!--
					jQuery(document).ready(function() {
						if (HTMLForms.getField("special_authorizations").getValue() && (HTMLForms.getField("type").getValue() == ' . ForumCategory::TYPE_CATEGORY . ' || HTMLForms.getField("type").getValue() == ' . ForumCategory::TYPE_URL . ')) {
							jQuery("#' . self::class . '_authorizations > div").eq(1).hide();
							jQuery("#' . self::class . '_authorizations > div").eq(2).hide();
						}
					});
				-->
			</script>'
		));

		$auth_settings = new AuthorizationsSettings($this->get_authorizations_settings());
		$auth_setter = new FormFieldAuthorizationsSetter('authorizations', $auth_settings, ['hidden' => $category->auth_is_equals($root_auth)]);
		$auth_settings->build_from_auth_array($category->get_authorizations());
		$fieldset_authorizations->add_field($auth_setter);

		$fieldset->add_field(new FormFieldHidden('referrer', $request->get_url_referrer()));
		$fieldset->add_field(new FormFieldHidden('last_topic_id', $this->is_new_category ? 0 : $category->get_last_topic_id()));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	protected function set_properties()
	{
		parent::set_properties();
		/** @var ForumCategory $category */
		$category = $this->get_category();
		$category->set_type($this->form->get_value('type')->get_raw_value());
		$category->set_additional_property('description', $this->form->get_value('description'));
		$category->set_additional_property('icon', $this->form->get_value('icon'));
		$category->set_additional_property('thumbnail', $this->form->get_value('thumbnail'));
		$category->set_additional_property('color', $this->form->get_value('color'));

		if ($category->get_type() == ForumCategory::TYPE_URL)
			$category->set_additional_property('url', $this->form->get_value('url'));
		else
			$category->set_additional_property('url', '');

		if ($category->get_type() == ForumCategory::TYPE_FORUM)
			$status = $this->form->get_value('status');
		else
			$status = ForumCategory::STATUS_UNLOCKED;

		$category->set_additional_property('status', $status);
		$category->set_additional_property('last_topic_id', (int)$this->form->get_value('last_topic_id'));

		if ($this->form->get_value('special_authorizations'))
		{
			$category->set_special_authorizations(true);
			$autorizations = $this->form->get_value('authorizations')->build_auth_array();
			if ($category->get_type() != ForumCategory::TYPE_FORUM)
			{
				foreach ($autorizations as $id => $auth)
				{
					$new_auth = ($autorizations[$id] > Category::MODERATION_AUTHORIZATIONS) ? ($autorizations[$id] - Category::MODERATION_AUTHORIZATIONS) : $autorizations[$id];
					$new_auth = ($new_auth > Category::WRITE_AUTHORIZATIONS) ? ($new_auth - Category::WRITE_AUTHORIZATIONS) : $new_auth;

					if ($new_auth == 1)
						$autorizations[$id] = $new_auth;
					else
						unset($autorizations[$id]);
				}
			}
		}
		else
		{
			$category->set_special_authorizations(false);
			$autorizations = [];
		}

		$category->set_authorizations($autorizations);
	}

	protected function check_authorizations()
	{
		if (!ForumAuthorizationsService::check_authorizations()->manage())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
