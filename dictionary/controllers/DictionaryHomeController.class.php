<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 16
 * @since       PHPBoost 4.1 - 2016 02 15
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DictionaryHomeController extends ModuleController
{
	private $view;
	private $lang;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_view($request);

		return $this->generate_response($request);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'dictionary');
		$this->view = new FileTemplate('dictionary/dictionary.tpl');
		$this->view->add_lang(array_merge(
			$this->lang,
			LangLoader::get('common-lang'),
			LangLoader::get('form-lang'),
			LangLoader::get('warning-lang')
		));
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$current_user = AppContext::get_current_user();
		$config = DictionaryConfig::load();

		$get_l_error = retrieve(GET, 'erroru', '');
		if (!empty($get_l_error))
		{
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang[$get_l_error], MessageHelper::ERROR));
		}

		if (DictionaryAuthorizationsService::check_authorizations()->read())
		{
			$letter = retrieve(GET, 'l', '', TSTRING);

			$words_number = PersistenceContext::get_querier()->count(DictionarySetup::$dictionary_table, "WHERE word LIKE '" . $letter . "%'");

			$page = AppContext::get_request()->get_getint('p', 1);
			$pagination = new ModulePagination($page, $words_number, $config->get_items_per_page());
			$pagination->set_url(new Url('/dictionary/dictionary.php?l=' . $letter . '&amp;p=%d'));

			if ($pagination->current_page_is_empty() && $page > 1)
			{
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}

			$aff = false;
			$quotes_approved = 1;
			if (empty($letter))
			{
				$result1 = PersistenceContext::get_querier()->select("SELECT l.id, l.description, l.word,l.cat,c.images,l.approved
				FROM ".PREFIX."dictionary AS l
				LEFT JOIN ".PREFIX."dictionary_cat AS c ON l.cat = c.id
				WHERE `approved`  = '" . $quotes_approved . "'
				ORDER BY l.word
				LIMIT :number_items_per_page OFFSET :display_from", array(
					'number_items_per_page' => $pagination->get_number_items_per_page(),
					'display_from' => $pagination->get_display_from()
				));
				$words_number = PersistenceContext::get_querier()->count(DictionarySetup::$dictionary_table, "WHERE (approved = 1)");
			}
			elseif (!empty($letter) && TextHelper::strlen($letter) > 1)
			{
				$result1 = PersistenceContext::get_querier()->select("SELECT l.id, l.description, l.word, l.cat, c.images
				FROM ".PREFIX."dictionary AS l
				LEFT JOIN ".PREFIX."dictionary_cat AS c ON l.cat = c.id
				WHERE l.word LIKE '" .$letter[0]. "%' AND `approved`  = '" . $quotes_approved . "'
				ORDER BY l.word
				LIMIT :number_items_per_page OFFSET :display_from", array(
					'number_items_per_page' => $pagination->get_number_items_per_page(),
					'display_from' => $pagination->get_display_from()
				));
				$aff=true;
			}
			else
			{
				$result1 = PersistenceContext::get_querier()->select("SELECT l.id, l.description, l.word,l.cat,c.images
				FROM ".PREFIX."dictionary AS l
				LEFT JOIN ".PREFIX."dictionary_cat AS c ON l.cat = c.id
				WHERE l.word LIKE '" .$letter. "%' AND `approved`  = '" . $quotes_approved . "'
				ORDER BY l.word
				LIMIT :number_items_per_page OFFSET :display_from", array(
					'number_items_per_page' => $pagination->get_number_items_per_page(),
					'display_from' => $pagination->get_display_from()
				));
			}

			$edit = $del = false;
			if (DictionaryAuthorizationsService::check_authorizations()->moderation())
			{
				$edit = $del = true;
			}

			while ($row = $result1->fetch())
			{
				$img = empty($row['images']) ? '<i class="fa fa-folder"></i>' : '<img src="' . $row['images'] . '" alt="' . $row['images'] . '" />';
				$name = Texthelper::ucfirst(TextHelper::strtolower(str_replace("'", "", stripslashes($row['word']))));

				$this->view->assign_block_vars('items', array(
					'C_CONTROLS' => $edit || $del,
					'C_EDIT'     => $edit,
					'C_DELETE'   => $del,
					'C_DISPLAY'  => (int)$aff,

					'NAME'          => $name,
					'ITEM_ID'       => $row['id'],
					'REWRITED_NAME' => Url::encode_rewrite($name),
					'WORD'          => Texthelper::ucfirst(TextHelper::strtolower(stripslashes($row['word']))),
					'DEFINITION'    => Texthelper::ucfirst(FormatingHelper::second_parse(stripslashes($row['description']))),
					'CATEGORY_ID'   => TextHelper::strtoupper($row['cat']),
					'CATEGORY_ICON' => $img,

					'U_EDIT'   => Url::to_rel('/dictionary/dictionary.php?edit=' . $row['id']),
					'U_DELETE' => Url::to_rel('/dictionary/dictionary.php?del=' . $row['id']),
				));
			}
			$result1->dispose();

			$letters = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
			foreach ($letters as $key => $value)
			{
				$this->view->assign_block_vars('letter', array(
					'LETTER' => TextHelper::strtoupper($value),
				));
			}

			$result_cat = PersistenceContext::get_querier()->select("SELECT id, name
			FROM ".PREFIX."dictionary_cat
			ORDER BY id");

			while ($row_cat = $result_cat->fetch())
			{
				$this->view->assign_block_vars('cat', array(
					'CATEGORY_ID' => $row_cat['id'],
					'CATEGORY_NAME' => $row_cat['name'],
				));
			}
			$result_cat->dispose();

			$result_cat = PersistenceContext::get_querier()->select("SELECT id, name
			FROM ".PREFIX."dictionary_cat
			ORDER BY id");
			while ($row_cat = $result_cat->fetch())
			{
				$this->view->assign_block_vars('cat_list', array(
					'CATEGORY_ID' => $row_cat['id'],
					'CATEGORY_NAME' => $row_cat['name'],
				));
			}
			$result_cat->dispose();

			$this->view->put_all(array(
				'C_EDIT' => false,
				'C_ITEMS' => $letter,
				'C_RESULTS' => $words_number,
				'C_PAGINATION' => $pagination->has_several_pages(),

				'REWRITE'=> (int)ServerEnvironmentConfig::load()->is_url_rewriting_enabled(),
				'PAGINATION' => $pagination->display(),

				'L_NO_WORD_LETTER' => StringVars::replace_vars($this->lang['dictionary.no.word'], array('letter' => TextHelper::strtoupper($letter))),
			));

			return $this->view;
		}
		else
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response(HTTPRequestCustom $request)
	{
		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['dictionary.module.title']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(DictionaryUrlBuilder::home());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['dictionary.module.title'], DictionaryUrlBuilder::home());

		return $response;
	}

	public static function get_view()
	{
		$object = new self();
		$object->init();
		$object->build_view(AppContext::get_request());
		return $object->view;
	}
}
?>
