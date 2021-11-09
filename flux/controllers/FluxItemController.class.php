<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 11 09
 * @since       PHPBoost 6.0 - 2021 10 30
*/

class FluxItemController extends ModuleController
{
	private $lang;
	private $common_lang;
	private $view;
	private $config;
	private $form;
	private $submit_button;

	private $item;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->count_views_number($request);

		$this->build_view($request);

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'flux');
		$this->common_lang = LangLoader::get('common-lang');
		$this->view = new FileTemplate('flux/FluxItemController.tpl');
		$this->view->add_lang(array_merge($this->lang, $this->common_lang, LangLoader::get('contribution-lang')));
		$this->config = FluxConfig::load();
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$item = $this->get_item();
		$category = $item->get_category();

		$this->build_refresh_form($request);

		$xml_url = $this->item->get_website_xml()->absolute();
		$rss_number = $this->config->get_rss_number();
		$char_number = $this->config->get_characters_number_to_cut();

		$host = parse_url($xml_url, PHP_URL_HOST);
		$lastname = str_replace(".", "-", $host);
		$path = parse_url($xml_url, PHP_URL_PATH);
		$firstname = preg_replace("~[/.#=?]~", "-", $path);

		$filename = PATH_TO_ROOT . '/flux/xml/' . $lastname . $firstname . '.xml';

		if ($item->is_published())
		{
			if ($this->submit_button->has_been_submited() && $this->form->validate())
			{
				// load feed items in file
				$content = file_get_contents($xml_url);
				file_put_contents($filename, $content);
				$item->set_xml_path($filename);
				FluxService::update($item);
			}
		}

		// Read cache file
		if(file_exists($filename))
		{
			$xml = simplexml_load_file($filename);
			$items = array();
			$items['title'] = array();
			$items['link']  = array();
			$items['desc']  = array();
			$items['img']   = array();
			$items['date']  = array();

			foreach($xml->channel->item as $i)
			{
				$items['title'][] = $i->title;
				$items['link'][]  = $i->link;
				$items['desc'][]  = $i->description;
				$items['img'][]   = $i->image;
				$items['date'][]  = $i->pubDate;
			}

			$items_number = $rss_number <= count($items['title']) ? $rss_number : count($items['title']);

			$this->view->put_all(array(
				'C_FEED_ITEMS' => true
			));

			for($i = 0; $i < $items_number ; $i++)
			{
				$date = strtotime($items['date'][$i]);
				$item_date = strftime('%d/%m/%Y - %Hh%M', $date);
				$desc = @strip_tags(FormatingHelper::second_parse($items['desc'][$i]));
				$cut_desc = (trim(TextHelper::substr($desc, 0, $char_number)));
				$cut_desc = TextHelper::cut_string(@strip_tags(FormatingHelper::second_parse($desc), '<br><br/>'), (int)$this->config->get_characters_number_to_cut());
				$item_img = $items['img'][$i];
				$this->view->assign_block_vars('feed_items',array(
					'TITLE'           => $items['title'][$i],
					'U_ITEM'          => $items['link'][$i],
					'DATE'            => $item_date,
					'SUMMARY'         => $cut_desc,
					'C_READ_MORE'     => strlen($desc) > $char_number,
					'WORDS_NUMBER'    => str_word_count($desc) - str_word_count($cut_desc),
					'C_HAS_THUMBNAIL' => !empty($item_img),
					'U_THUMBNAIL'     => $item_img,
				));
			}
		}
		else {
			$this->view->put_all(array(
				'C_FEED_ITEMS' => false
			));
		}

		$this->view->put_all(array_merge($item->get_array_tpl_vars(), array(
			'FORM' => $this->form->display(),
			'NOT_VISIBLE_MESSAGE' => MessageHelper::display(LangLoader::get_message('warning.element.not.visible', 'warning-lang'), MessageHelper::WARNING),
			'MODULE_NAME' => $this->config->get_module_name()
		)));
	}

	private function build_refresh_form(HTTPRequestCustom $request)
	{
		$item = $this->get_item();
		$form = new HTMLForm(__CLASS__);
		$form->set_css_class('fieldset-content front-fieldset');

		$fieldset = new FormFieldsetHTML('flux', $this->lang['flux.check.updates']);
		$form->add_fieldset($fieldset);
		if ($item->is_published())
		{
			$fieldset->set_description($this->lang['flux.rss.init.admin']);

			$this->submit_button = new FormButtonDefaultSubmit($this->lang['flux.update']);
			$form->add_button($this->submit_button);
		}
		else
			$fieldset->set_description($this->lang['flux.rss.init.contribution']);

		$this->form = $form;
	}

	private function count_views_number(HTTPRequestCustom $request)
	{
		if (!$this->item->is_published())
		{
			$this->view->put('NOT_VISIBLE_MESSAGE', MessageHelper::display(LangLoader::get_message('warning.element.not.visible', 'warning-lang'), MessageHelper::WARNING));
		}
		else
		{
			if ($request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), FluxUrlBuilder::display($this->item->get_category()->get_id(), $this->item->get_category()->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title())->rel()))
			{
				$this->item->set_views_number($this->item->get_views_number() + 1);
				FluxService::update_views_number($this->item);
			}
		}
	}

	private function get_item()
	{
		if ($this->item === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->item = FluxService::get_item($id);
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
				$this->item = new FluxItem();
		}
		return $this->item;
	}

	private function check_authorizations()
	{
		$item = $this->get_item();

		$current_user = AppContext::get_current_user();
		$not_authorized = !CategoriesAuthorizationsService::check_authorizations($item->get_id_category())->moderation() && !CategoriesAuthorizationsService::check_authorizations($item->get_id_category())->write() && (!CategoriesAuthorizationsService::check_authorizations($item->get_id_category())->contribution() || $item->get_author_user()->get_id() != $current_user->get_id());

		switch ($item->get_published()) {
			case FluxItem::PUBLISHED:
				if (!CategoriesAuthorizationsService::check_authorizations($item->get_id_category())->read())
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case FluxItem::NOT_PUBLISHED:
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
		$graphical_environment->get_seo_meta_data()->set_canonical_url(FluxUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_rewrited_title()));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->config->get_module_name(),FluxUrlBuilder::home());

		$categories = array_reverse(CategoriesService::get_categories_manager()->get_parents($item->get_id_category(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), FluxUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}
		$breadcrumb->add($item->get_title(), FluxUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_rewrited_title()));

		return $response;
	}
}
?>
