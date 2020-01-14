<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 14
 * @since       PHPBoost 4.1 - 2016 02 15
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DictionaryHomeController extends ModuleController
{
	private $view;

	public function execute(HTTPRequestCustom $request)
	{
		$this->build_view();

		return $this->generate_response();
	}

	private function build_view()
	{
		global $LANG;

		load_module_lang('dictionary'); //Chargement de la langue du module.

		$current_user = AppContext::get_current_user();
		$config = DictionaryConfig::load();

		if (DictionaryAuthorizationsService::check_authorizations()->read())
		{
			$this->view = new FileTemplate('dictionary/dictionary.tpl');

			$letter = retrieve(GET, 'l', '', TSTRING);

			$nbr_words = PersistenceContext::get_querier()->count(DictionarySetup::$dictionary_table, "WHERE word LIKE '" . $letter . "%'");

			$page = AppContext::get_request()->get_getint('p', 1);
			$pagination = new ModulePagination($page, $nbr_words, $config->get_items_number_per_page());
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
				$nb_word = PersistenceContext::get_querier()->count(DictionarySetup::$dictionary_table, "WHERE (approved = 1)");

			}
			elseif (!empty($letter) && TextHelper::strlen($letter) > 1)
			{
				$result1 = PersistenceContext::get_querier()->select("SELECT l.id, l.description, l.word,l.cat,c.images
				FROM ".PREFIX."dictionary AS l
				LEFT JOIN ".PREFIX."dictionary_cat AS c ON l.cat = c.id
				WHERE l.word LIKE '%" .$letter. "%' AND `approved`  = '" . $quotes_approved . "'
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

				$this->view->assign_block_vars('dictionary', array(
					'NAME' => $name,
					'ID' => Url::encode_rewrite($name),
					'PROPER_NAME' => Texthelper::ucfirst(TextHelper::strtolower(stripslashes($row['word']))),
					'DESC' => Texthelper::ucfirst(FormatingHelper::second_parse(stripslashes($row['description']))),
					'CAT' => TextHelper::strtoupper($row['cat']),
					'CAT_IMG' => $img,
					'C_CONTROLS' => $edit || $del,
					'EDIT_CODE' => $edit,
					'ID_EDIT' => $row['id'],
					'ID_DEL' => $row['id'],
					'DEL_CODE' => $del,
					'C_AFF' => $aff
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
					'ID' => $row_cat['id'],
					'NAME' => TextHelper::strtoupper($row_cat['name']),
				));
			}
			$result_cat->dispose();

			$result_cat = PersistenceContext::get_querier()->select("SELECT id, name
			FROM ".PREFIX."dictionary_cat
			ORDER BY id");
			while ($row_cat = $result_cat->fetch())
			{
				$this->view->assign_block_vars('cat_list', array(
					'ID' => $row_cat['id'],
					'NAME' => TextHelper::strtoupper($row_cat['name']),
				));
			}
			$result_cat->dispose();

			$this->view->put_all(array(
				'C_EDIT' => false,
				'C_LETTER' => $letter,
				'C_RESULTS' => $nbr_words,
				'TITLE' => $LANG['dictionary'],
				'L_NO_SCRIPT' => $LANG['no.script'],
				'C_AJOUT' => DictionaryAuthorizationsService::check_authorizations()->write() || DictionaryAuthorizationsService::check_authorizations()->contribution(),
				'L_DELETE_DICTIONARY' => $LANG['delete.dictionary'],
				'L_ADD_DICTIONARY'    => $LANG['create.dictionary'],
				'L_ALL_DEFINITIONS' => $LANG['all.definitions'],
				'L_ALL_CAT' => $LANG['all.cat'],
				'L_CAT_FILTER' => $LANG['cat.filter'],
				'L_DEL_CAT_FILTER' => $LANG['delete.cat.filter'],
				'L_CATEGORY' => $LANG['category'],
				'L_NB_DEF' => $LANG['nb.def'],
				'L_DEF_REP' => $LANG['def.set'],
				'L_CAT_S' => $LANG['cat.s'],
				'SWITCH_DEF' => $LANG['switch.def'],
				'L_NO_WORD' => $LANG['message.no_word'],
				'L_NO_WORD_LETTER' => StringVars::replace_vars($LANG['message.empty.result.letter'], array('letter' => TextHelper::strtoupper($letter))),
				'REWRITE'=> (int)ServerEnvironmentConfig::load()->is_url_rewriting_enabled(),
				'C_PAGINATION' => $pagination->has_several_pages(),
				'PAGINATION' => $pagination->display()
			));

			return $this->view;
		}
		else
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response()
	{
		global $LANG;
		load_module_lang('dictionary');

		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($LANG['dictionary']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(DictionaryUrlBuilder::home());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($LANG['dictionary'], DictionaryUrlBuilder::home());

		return $response;
	}

	public static function get_view()
	{
		$object = new self();
		$object->build_view();
		return $object->view;
	}
}
?>
