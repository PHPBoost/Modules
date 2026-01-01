<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2024 01 31
 * @since       PHPBoost 6.0 - 2021 08 22
*/

class SpotsItemController extends DefaultModuleController
{
	protected function get_template_to_use()
	{
		return new FileTemplate('spots/SpotsItemController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->count_views_number($request);

		$this->build_view($request);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$new_location = new GoogleMapsMarker(TextHelper::unserialize($this->form->get_value('new_gps')));
			$this->view->put_all(array(
				'C_NEW_ADDRESS' => !empty($this->form->get_value('new_gps')),
				'NEW_LAT' => $new_location->get_properties()['address']['latitude'],
				'NEW_LNG' => $new_location->get_properties()['address']['longitude'],
			));
		}

		return $this->generate_response();
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$item = $this->get_item();
		$category = $item->get_category();
		$comments_config = CommentsConfig::load();

		$this->build_new_address_form($request);

		$this->view->put_all(array_merge($item->get_template_vars(), array(
			'C_ENABLED_COMMENTS' => $comments_config->module_comments_is_enabled('download'),
			'FORM' => $this->form->display(),
			'NOT_VISIBLE_MESSAGE' => MessageHelper::display(LangLoader::get_message('warning.element.not.visible', 'warning-lang'), MessageHelper::WARNING),
			'MODULE_NAME' => $this->config->get_module_name(),
			'DEFAULT_ADDRESS' => GoogleMapsConfig::load()->get_default_marker_address()
		)));

		if ($comments_config->module_comments_is_enabled('spots'))
		{
			$comments_topic = new SpotsCommentsTopic($item);
			$comments_topic->set_id_in_module($item->get_id());
			$comments_topic->set_url(SpotsUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_rewrited_title()));

			$this->view->put('COMMENTS', $comments_topic->display());
		}
	}

	private function count_views_number(HTTPRequestCustom $request)
	{
		if (!$this->item->is_published())
		{
			$this->view->put('NOT_VISIBLE_MESSAGE', MessageHelper::display(LangLoader::get_message('warning.element.not.visible', 'warning-lang'), MessageHelper::WARNING));
		}
		else
		{
			if ($request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), SpotsUrlBuilder::display($this->item->get_category()->get_id(), $this->item->get_category()->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title())->rel()))
			{
				$this->item->set_views_number($this->item->get_views_number() + 1);
				SpotsService::update_views_number($this->item);
			}
		}
	}

	private function build_new_address_form(HTTPRequestCustom $request)
	{
		$form = new HTMLForm(__CLASS__);
		$form->set_css_class('front-fieldset');

		$fieldset = new FormFieldsetHTML('spots', $this->lang['spots.change.orign.address']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldSpacer('change_address', $this->lang['spots.route.infos'],
			array('class' => 'message-helper bgc notice')
		));

        $fieldset->add_field(new GoogleMapsFormFieldMapAddress('new_gps', $this->lang['spots.new.location'], '',
			array('description' => $this->lang['spots.new.location.clue'])
		));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);

		$this->form = $form;
	}

	private function get_item()
	{
		if ($this->item === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->item = SpotsService::get_item($id);
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
				$this->item = new SpotsItem();
		}
		return $this->item;
	}

	private function check_authorizations()
	{
		$item = $this->get_item();

		$current_user = AppContext::get_current_user();
		$not_authorized = !CategoriesAuthorizationsService::check_authorizations($item->get_id_category())->moderation() && !CategoriesAuthorizationsService::check_authorizations($item->get_id_category())->write() && (!CategoriesAuthorizationsService::check_authorizations($item->get_id_category())->contribution() || $item->get_author_user()->get_id() != $current_user->get_id());

		switch ($item->get_published()) {
			case SpotsItem::PUBLISHED:
				if (!CategoriesAuthorizationsService::check_authorizations($item->get_id_category())->read())
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case SpotsItem::NOT_PUBLISHED:
				if ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL))
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			default:
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			break;
		}
	}

	private function generate_response()
	{
		$item = $this->get_item();
		$category = $item->get_category();
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($item->get_title(), $this->config->get_module_name());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(SpotsUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_rewrited_title()));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->config->get_module_name(),SpotsUrlBuilder::home());

		$categories = array_reverse(CategoriesService::get_categories_manager()->get_parents($item->get_id_category(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), SpotsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}
		$breadcrumb->add($item->get_title(), SpotsUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_rewrited_title()));

		return $response;
	}
}
?>
