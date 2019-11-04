<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2019 11 04
 * @since   	PHPBoost 5.0 - 2016 01 02
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class HomeLandingHomeController extends ModuleController
{
	private $view;
	private $lang;
	private $querier;
	private $form;
	private $submit_button;

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

		$this->build_view();

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'HomeLanding');
		$this->view = new FileTemplate('HomeLanding/home.tpl');
		$this->view->add_lang($this->lang);
		$this->config = HomeLandingConfig::load();
		$this->modules = HomeLandingModulesList::load();
		$this->querier = PersistenceContext::get_querier();

		$columns_disabled = ThemesManager::get_theme(AppContext::get_current_user()->get_theme())->get_columns_disabled();
		$columns_disabled->set_disable_left_columns($this->config->get_left_columns());
		$columns_disabled->set_disable_right_columns($this->config->get_right_columns());
		$columns_disabled->set_disable_top_central($this->config->get_top_central());
		$columns_disabled->set_disable_bottom_central($this->config->get_bottom_central());
		$columns_disabled->set_disable_top_footer($this->config->get_top_footer());
	}

	private function build_view()
	{
		//Config HomeLanding title & edito
		$this->view->put_all(array(
			'MODULE_TITLE' => $this->config->get_module_title(),
			'C_EDITO_ENABLED' => $this->modules[HomeLandingConfig::MODULE_EDITO]->is_displayed(),
			'EDITO' => FormatingHelper::second_parse($this->config->get_edito()),
			'EDITO_POSITION' => $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_EDITO),
		));

		if ($this->modules[HomeLandingConfig::MODULE_ONEPAGE_MENU]->is_displayed())
			$this->build_onepage_menu_view();

		if ($this->modules[HomeLandingConfig::MODULE_CAROUSEL]->is_displayed())
			$this->build_carousel_view();

		if ($this->modules[HomeLandingConfig::MODULE_LASTCOMS]->is_displayed())
			$this->build_lastcoms_view();

		if ($this->modules[HomeLandingConfig::MODULE_ARTICLES]->is_displayed() && CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, HomeLandingConfig::MODULE_ARTICLES)->read())
			$this->build_articles_view();

		if ($this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->is_displayed() && CategoriesAuthorizationsService::check_authorizations($this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->get_id_category(), HomeLandingConfig::MODULE_ARTICLES)->read())
			$this->build_articles_cat_view();

		if ($this->modules[HomeLandingConfig::MODULE_CONTACT]->is_displayed() && ContactAuthorizationsService::check_authorizations()->read())
			$this->build_contact_view();

		if ($this->modules[HomeLandingConfig::MODULE_CALENDAR]->is_displayed() && CalendarAuthorizationsService::check_authorizations()->read())
			$this->build_events_view();

		if ($this->modules[HomeLandingConfig::MODULE_DOWNLOAD]->is_displayed() && DownloadAuthorizationsService::check_authorizations()->read())
			$this->build_download_view();

		if ($this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->is_displayed() && DownloadAuthorizationsService::check_authorizations($this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->get_id_category())->read())
			$this->build_download_cat_view();

		if ($this->modules[HomeLandingConfig::MODULE_FORUM]->is_displayed() && ForumAuthorizationsService::check_authorizations()->read())
			$this->build_forum_view();

		if ($this->modules[HomeLandingConfig::MODULE_GALLERY]->is_displayed() && GalleryAuthorizationsService::check_authorizations()->read())
			$this->build_gallery_view();

		if ($this->modules[HomeLandingConfig::MODULE_GUESTBOOK]->is_displayed() && GuestbookAuthorizationsService::check_authorizations()->read())
			$this->build_guestbook_view();

		if ($this->modules[HomeLandingConfig::MODULE_MEDIA]->is_displayed() && MediaAuthorizationsService::check_authorizations()->read())
			$this->build_media_view();

		if ($this->modules[HomeLandingConfig::MODULE_NEWS]->is_displayed() && CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, HomeLandingConfig::MODULE_NEWS)->read())
			$this->build_news_view();

		if ($this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->is_displayed() && CategoriesAuthorizationsService::check_authorizations($this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->get_id_category(), HomeLandingConfig::MODULE_NEWS)->read())
			$this->build_news_cat_view();

		// if ($this->modules[HomeLandingConfig::MODULE_RSS]->is_displayed())
		// 	$this->build_external_rss_view();

		if ($this->modules[HomeLandingConfig::MODULE_WEB]->is_displayed() && WebAuthorizationsService::check_authorizations()->read())
			$this->build_web_view();

		if ($this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->is_displayed() && WebAuthorizationsService::check_authorizations($this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->get_id_category())->read())
			$this->build_web_cat_view();
	}

	//MODULE CATEGORY : Articles - Download - News - Web (if partner)

	private function build_articles_cat_view()
	{
		$now = new Date();
		$tpl = new FileTemplate('HomeLanding/pagecontent/articles-cat.tpl');
		$articles_config = ArticlesConfig::load();

		$categories_id = $this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->is_subcategories_content_displayed() ? CategoriesService::get_authorized_categories($this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->get_id_category(), $articles_config->are_descriptions_displayed_to_guests(), HomeLandingConfig::MODULE_ARTICLES) : array($this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->get_id_category());

		$result = $this->querier->select('SELECT articles.*, member.*, com.number_comments, notes.average_notes, notes.number_notes, note.note
		FROM ' . ArticlesSetup::$articles_table . ' articles
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = articles.author_user_id
		LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = articles.id AND com.module_id = \'articles\'
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = articles.id AND notes.module_name = \'articles\'
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = articles.id AND note.module_name = \'articles\' AND note.user_id = :user_id
		WHERE (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0))) AND id_category IN :categories_id
		ORDER BY articles.date_created DESC
		LIMIT :articles_cat_limit', array(
			'user_id' => AppContext::get_current_user()->get_id(),
			'timestamp_now' => $now->get_timestamp(),
			'categories_id' => $categories_id,
			'articles_cat_limit' => $this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->get_elements_number_displayed()
		));

		$category = CategoriesService::get_categories_manager(HomeLandingConfig::MODULE_ARTICLES)->get_categories_cache()->get_category($this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->get_id_category());
		$tpl->put_all(array(
			'ARTICLES_CAT_POSITION' => $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_ARTICLES_CATEGORY),
			'CATEGORY_NAME' => $category->get_name(),
			'C_NO_ARTICLES_ITEM' => $result->get_rows_count() == 0,
			'C_DISPLAY_BLOCK' => $articles_config->get_display_type() == ArticlesConfig::DISPLAY_MOSAIC,
			'COL_NBR' => $articles_config->get_number_cols_display_per_line()
		));

		while ($row = $result->fetch())
		{
			$article = new Article();
			$article->set_properties($row);

			$contents = @strip_tags(FormatingHelper::second_parse($article->get_contents()));
			$short_contents = @strip_tags(FormatingHelper::second_parse($article->get_description()));
			$nb_char = $this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->get_characters_number_displayed();
			$description = trim(TextHelper::substr($short_contents, 0, $nb_char));
			$cut_contents = trim(TextHelper::substr($contents, 0, $nb_char));

			$tpl->assign_block_vars('item', array_merge($article->get_array_tpl_vars(), array(
				'C_DESCRIPTION' => $article->get_description(),
				'C_READ_MORE' => $article->get_description() ? ($description != $short_contents) : ($cut_contents != $contents),
				'DATE' => $article->get_date_created()->format(Date::FORMAT_DAY_MONTH_YEAR),
				'DESCRIPTION' => $description,
				'CONTENTS' => $cut_contents
			)));
		}
		$result->dispose();

		$this->view->put('ARTICLES_CAT', $tpl);
	}

	private function build_download_cat_view()
	{
		$now = new Date();
		$tpl = new FileTemplate('HomeLanding/pagecontent/download-cat.tpl');
		$download_config = DownloadConfig::load();

		$categories_id = $this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->is_subcategories_content_displayed() ? DownloadService::get_authorized_categories($this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->get_id_category()) : array($this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->get_id_category());

		$result = $this->querier->select('SELECT download.*, member.*, com.number_comments, notes.average_notes, notes.number_notes, note.note
		FROM ' . DownloadSetup::$download_table . ' download
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = download.author_user_id
		LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = download.id AND com.module_id = \'download\'
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = download.id AND notes.module_name = \'download\'
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = download.id AND note.module_name = \'download\' AND note.user_id = :user_id
		WHERE (approbation_type = 1 OR (approbation_type = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0))) AND id_category IN :categories_id
		ORDER BY download.creation_date DESC
		LIMIT :download_cat_limit', array(
			'user_id' => AppContext::get_current_user()->get_id(),
			'timestamp_now' => $now->get_timestamp(),
			'categories_id' => $categories_id,
			'download_cat_limit' => $this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->get_elements_number_displayed()
		));

		$category = DownloadService::get_categories_manager()->get_categories_cache()->get_category($this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->get_id_category());
		$tpl->put_all(array(
			'DOWNLOAD_CAT_POSITION' => $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY),
			'CATEGORY_NAME' => $category->get_name(),
			'C_NO_DOWNLOAD_ITEM' => $result->get_rows_count() == 0,
			'C_DISPLAY_BLOCK' => $download_config->is_category_displayed_summary(),
			'C_DISPLAY_TABLE' => $download_config->is_category_displayed_table(),
			'COL_NBR' => $download_config->get_columns_number_per_line()
		));

		while ($row = $result->fetch())
		{
			$file = new DownloadFile();
			$file->set_properties($row);

			$contents = @strip_tags(FormatingHelper::second_parse($file->get_contents()));
			$short_contents = @strip_tags(FormatingHelper::second_parse($file->get_short_contents()));
			$nb_char = $this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->get_characters_number_displayed();
			$description = trim(TextHelper::substr($short_contents, 0, $nb_char));
			$cut_contents = trim(TextHelper::substr($contents, 0, $nb_char));

			$tpl->assign_block_vars('item', array_merge($file->get_array_tpl_vars(), array(
				'C_DESCRIPTION' => $file->get_short_contents(),
				'C_READ_MORE' => $file->get_short_contents() ? ($description != $short_contents) : ($cut_contents != $contents),
				'DATE' => $file->get_creation_date()->format(Date::FORMAT_DAY_MONTH_YEAR),
				'DESCRIPTION' => $description,
				'CONTENTS' => $cut_contents
			)));
		}
		$result->dispose();

		$this->view->put('DOWNLOAD_CAT', $tpl);
	}

	private function build_news_cat_view()
	{
		$now = new Date();
		$tpl = new FileTemplate('HomeLanding/pagecontent/news-cat.tpl');
		$news_config = NewsConfig::load();

		$categories_id = $this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->is_subcategories_content_displayed() ? CategoriesService::get_authorized_categories($this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->get_id_category(), $news_config->are_descriptions_displayed_to_guests(), HomeLandingConfig::MODULE_NEWS) : array($this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->get_id_category());

		$result = $this->querier->select('SELECT news.*, member.*
		FROM ' . NewsSetup::$news_table . ' news
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = news.author_user_id
		WHERE (approbation_type = 1 OR (approbation_type = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0))) AND id_category IN :categories_id
		ORDER BY news.creation_date DESC
		LIMIT :news_cat_limit', array(
			'timestamp_now' => $now->get_timestamp(),
			'categories_id' => $categories_id,
			'news_cat_limit' => $this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->get_elements_number_displayed()
		));

		$category = CategoriesService::get_categories_manager(HomeLandingConfig::MODULE_NEWS)->get_categories_cache()->get_category($this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->get_id_category());
		$tpl->put_all(array(
			'NEWS_CAT_POSITION' => $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_NEWS_CATEGORY),
			'CATEGORY_NAME' => $category->get_name(),
			'C_NO_NEWS_ITEM' => $result->get_rows_count() == 0,
			'C_DISPLAY_BLOCK' => $news_config->get_display_type() == NewsConfig::DISPLAY_BLOCK,
			'COL_NBR' => $news_config->get_number_columns_display_news()
		));

		while ($row = $result->fetch())
		{
			$news = new News();
			$news->set_properties($row);

			$contents = @strip_tags(FormatingHelper::second_parse($news->get_contents()));
			$short_contents = @strip_tags(FormatingHelper::second_parse($news->get_short_contents()));
			$nb_char = $this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->get_characters_number_displayed();
			$description = trim(TextHelper::substr($short_contents, 0, $nb_char));
			$cut_contents = trim(TextHelper::substr($contents, 0, $nb_char));

			$tpl->assign_block_vars('item', array_merge($news->get_array_tpl_vars(), array(
				'C_DESCRIPTION' => $news->get_short_contents(),
				'C_READ_MORE' => $news->get_short_contents() ? ($description != $short_contents) : ($cut_contents != $contents),
				'DATE' => $news->get_creation_date()->format(Date::FORMAT_DAY_MONTH_YEAR),
				'DESCRIPTION' => $description,
				'CONTENTS' => $cut_contents
			)));
		}
		$result->dispose();

		$this->view->put('NEWS_CAT', $tpl);
	}

	private function build_web_cat_view()
	{
		$now = new Date();
		$tpl = new FileTemplate('HomeLanding/pagecontent/web-cat.tpl');

		$categories_id = $this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->is_subcategories_content_displayed() ? WebService::get_authorized_categories($this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->get_id_category()) : array($this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->get_id_category());

		$result = $this->querier->select('SELECT web.*, member.*, com.number_comments, notes.average_notes, notes.number_notes, note.note
		FROM '. WebSetup::$web_table .' web
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = web.author_user_id
		LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = web.id AND com.module_id = \'web\'
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = web.id AND notes.module_name = \'web\'
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = web.id AND note.module_name = \'web\' AND note.user_id = :user_id
		WHERE (approbation_type = 1 OR (approbation_type = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0))) AND partner = 1 AND id_category IN :categories_id
		ORDER BY web.rewrited_name ASC
		LIMIT :web_cat_limit', array(
			'user_id' => AppContext::get_current_user()->get_id(),
			'timestamp_now' => $now->get_timestamp(),
			'categories_id' => $categories_id,
			'web_cat_limit' => $this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->get_elements_number_displayed()
		));

		$category = WebService::get_categories_manager()->get_categories_cache()->get_category($this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->get_id_category());
		$tpl->put_all(array(
			'WEB_CAT_POSITION' => $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_WEB_CATEGORY),
			'CATEGORY_NAME' => $category->get_name(),
			'C_NO_WEB_ITEM' => $result->get_rows_count() == 0
		));

		while ($row = $result->fetch())
		{
			$link = new WebLink();
			$link->set_properties($row);

			$contents = @strip_tags(FormatingHelper::second_parse($link->get_contents()));
			$short_contents = @strip_tags(FormatingHelper::second_parse($link->get_short_contents()));
			$nb_char = $this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->get_characters_number_displayed();
			$description = trim(TextHelper::substr($short_contents, 0, $nb_char));
			$cut_contents = trim(TextHelper::substr($contents, 0, $nb_char));

			$tpl->assign_block_vars('item', array_merge($link->get_array_tpl_vars(), array(
				'C_DESCRIPTION' => $link->get_short_contents(),
				'C_READ_MORE' => $link->get_short_contents() ? ($description != $short_contents) : ($cut_contents != $contents),
				'DATE' => $link->get_creation_date()->format(Date::FORMAT_DAY_MONTH_YEAR),
				'DESCRIPTION' => $description,
				'CONTENTS' => $cut_contents
			)));
		}
		$result->dispose();

		$this->view->put('WEB_CAT', $tpl);
	}

	private function build_carousel_view()
	{
		$tpl = new FileTemplate('HomeLanding/pagecontent/carousel.tpl');
		$carousel = $this->config->get_carousel();

		$nb_dots = 0;
		foreach ($carousel as $id => $options)
		{
			$tpl->assign_block_vars('item', array(
				'DESCRIPTION' => $options['description'],
				'PICTURE_TITLE' => $options['description'] ? $options['description'] : basename($options['picture_url']),
				'PICTURE_URL' => Url::to_rel($options['picture_url']),
				'LINK' => Url::to_rel($options['link'])
			));
			$nb_dots++;
		}

		$tpl->put_all(array(
			'CAROUSEL_POSITION' => $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_CAROUSEL),
			'NB_DOTS' => $nb_dots,
			'CAROUSEL_SPEED' => $this->config->get_carousel_speed(),
			'CAROUSEL_TIME' => $this->config->get_carousel_time(),
			'CAROUSEL_NAV' => $this->config->get_carousel_nav(),
			'CAROUSEL_HOVER' => $this->config->get_carousel_hover(),
			'C_CAROUSEL_CROPPED' => $this->config->get_carousel_display(),
			'CAROUSEL_MINI' => $this->config->get_carousel_mini(),
		));
		$this->view->put('CAROUSEL', $tpl);
	}

	// One page Menu
	private function build_onepage_menu_view()
	{
		$tpl = new FileTemplate('HomeLanding/pagecontent/onepage.tpl');

		if($this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->is_displayed())
			$articles_cat = CategoriesService::get_categories_manager(HomeLandingConfig::MODULE_ARTICLES)->get_categories_cache()->get_category($this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->get_id_category())->get_name();
		else
			$articles_cat = '';

		if($this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->is_displayed())
			$download_cat = DownloadService::get_categories_manager()->get_categories_cache()->get_category($this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->get_id_category())->get_name();
		else
			$download_cat = '';

		if($this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->is_displayed())
			$news_cat = CategoriesService::get_categories_manager(HomeLandingConfig::MODULE_NEWS)->get_categories_cache()->get_category($this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->get_id_category())->get_name();
		else
			$news_cat = '';

		if($this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->is_displayed())
			$web_cat = WebService::get_categories_manager()->get_categories_cache()->get_category($this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->get_id_category())->get_name();
		else
			$web_cat = '';

		$tpl->put_all(array(
			// location of the menu in the page
			'ONEPAGE_POSITION' => $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_ONEPAGE_MENU),

			// Presence of modules on the page
			'C_DISPLAYED_EDITO' => $this->modules[HomeLandingConfig::MODULE_EDITO]->is_displayed(),
			'C_DISPLAYED_CAROUSEL' => $this->modules[HomeLandingConfig::MODULE_CAROUSEL]->is_displayed(),
			'C_DISPLAYED_LASTCOMS' => $this->modules[HomeLandingConfig::MODULE_LASTCOMS]->is_displayed(),
			'C_DISPLAYED_ARTICLES' => $this->modules[HomeLandingConfig::MODULE_ARTICLES]->is_displayed() && CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, HomeLandingConfig::MODULE_ARTICLES)->read(),
			'C_DISPLAYED_ARTICLES_CAT' => $this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->is_displayed() && CategoriesAuthorizationsService::check_authorizations($this->modules[HomeLandingConfig::MODULE_ARTICLES_CATEGORY]->get_id_category(), HomeLandingConfig::MODULE_ARTICLES)->read(),
			'C_DISPLAYED_CONTACT' => $this->modules[HomeLandingConfig::MODULE_CONTACT]->is_displayed() && ContactAuthorizationsService::check_authorizations()->read(),
			'C_DISPLAYED_EVENTS' => $this->modules[HomeLandingConfig::MODULE_CALENDAR]->is_displayed() && CalendarAuthorizationsService::check_authorizations()->read(),
			'C_DISPLAYED_DOWNLOAD' => $this->modules[HomeLandingConfig::MODULE_DOWNLOAD]->is_displayed() && DownloadAuthorizationsService::check_authorizations()->read(),
			'C_DISPLAYED_DOWNLOAD_CAT' => $this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->is_displayed() && DownloadAuthorizationsService::check_authorizations($this->modules[HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY]->get_id_category())->read(),
			'C_DISPLAYED_FORUM' => $this->modules[HomeLandingConfig::MODULE_FORUM]->is_displayed() && ForumAuthorizationsService::check_authorizations()->read(),
			'C_DISPLAYED_GALLERY' => $this->modules[HomeLandingConfig::MODULE_GALLERY]->is_displayed() && GalleryAuthorizationsService::check_authorizations()->read(),
			'C_DISPLAYED_GUESTBOOK' => $this->modules[HomeLandingConfig::MODULE_GUESTBOOK]->is_displayed() && GuestbookAuthorizationsService::check_authorizations()->read(),
			'C_DISPLAYED_MEDIA' => $this->modules[HomeLandingConfig::MODULE_MEDIA]->is_displayed() && MediaAuthorizationsService::check_authorizations()->read(),
			'C_DISPLAYED_NEWS' => $this->modules[HomeLandingConfig::MODULE_NEWS]->is_displayed() && CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, HomeLandingConfig::MODULE_NEWS)->read(),
			'C_DISPLAYED_NEWS_CAT' => $this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->is_displayed() && CategoriesAuthorizationsService::check_authorizations($this->modules[HomeLandingConfig::MODULE_NEWS_CATEGORY]->get_id_category(), HomeLandingConfig::MODULE_NEWS)->read(),
			'C_DISPLAYED_WEB' => $this->modules[HomeLandingConfig::MODULE_WEB]->is_displayed() && WebAuthorizationsService::check_authorizations()->read(),
			'C_DISPLAYED_WEB_CAT' => $this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->is_displayed() && WebAuthorizationsService::check_authorizations($this->modules[HomeLandingConfig::MODULE_WEB_CATEGORY]->get_id_category())->read(),

			// Names of categories
			'ARTICLES_CAT' => $category = $articles_cat,
			'DOWNLOAD_CAT' => $category = $download_cat,
			'NEWS_CAT' => $category = $news_cat,
			'WEB_CAT' => $category = $web_cat,

			// Position of the tabs in the menu
			'OPM_EDITO_POS' => $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_EDITO),
			'OPM_LASTCOMS_POS' => $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_LASTCOMS),
			'OPM_ARTICLES_POS' => $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_ARTICLES),
			'OPM_ARTICLES_CAT_POS' => $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_ARTICLES_CATEGORY),
			'OPM_CONTACT_POS' => $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_CONTACT),
			'OPM_EVENTS_POS' => $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_CALENDAR),
			'OPM_DOWNLOAD_POS' => $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_DOWNLOAD),
			'OPM_DOWNLOAD_CAT_POS' => $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_DOWNLOAD_CATEGORY),
			'OPM_FORUM_POS' => $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_FORUM),
			'OPM_GALLERY_POS' => $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_GALLERY),
			'OPM_GUESTBOOK_POS' => $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_GUESTBOOK),
			'OPM_MEDIA_POS' => $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_MEDIA),
			'OPM_NEWS_POS' => $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_NEWS),
			'OPM_NEWS_CAT_POS' => $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_NEWS_CATEGORY),
			'OPM_WEB_POS' => $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_WEB),
			'OPM_WEB_CAT_POS' => $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_WEB_CATEGORY),
		));

		$this->view->put('ONEPAGE_MENU', $tpl);
	}

	//Lastcoms
	private function build_lastcoms_view()
	{
		$tpl = new FileTemplate('HomeLanding/pagecontent/lastcoms.tpl');
		$modules_config = ModulesConfig::load();
		$user_accounts_config = UserAccountsConfig::load();
		$result = $this->querier->select('SELECT c.id, c.user_id, c.pseudo, c.message, c.timestamp, ct.module_id, ct.is_locked, ct.path, m.*, ext_field.user_avatar
		FROM ' . DB_TABLE_COMMENTS . ' AS c
		LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' AS ct ON ct.id_topic = c.id_topic
		LEFT JOIN ' . DB_TABLE_MEMBER . ' AS m ON c.user_id = m.user_id
		LEFT JOIN ' . DB_TABLE_MEMBER_EXTENDED_FIELDS . ' ext_field ON ext_field.user_id = m.user_id
		WHERE ct.is_locked = 0
		AND ct.module_id != :forbidden_module
		ORDER BY c.timestamp DESC
		LIMIT :last_coms_limit', array(
			'last_coms_limit' => $this->modules[HomeLandingConfig::MODULE_LASTCOMS]->get_elements_number_displayed(),
			'forbidden_module' => 'user'
		));

		$tpl->put_all(array(
			'LASTCOMS_POSITION' => $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_LASTCOMS),
			'C_NO_COMMENT' => $result->get_rows_count() == 0,
		));

		while ($row = $result->fetch())
		{
			$contents = @strip_tags(FormatingHelper::second_parse($row['message']));
			$nb_char = $this->modules[HomeLandingConfig::MODULE_LASTCOMS]->get_characters_number_displayed();
			$cut_contents = trim(TextHelper::substr($contents, 0, $nb_char));
			$date = new Date($row['timestamp'], Timezone::SERVER_TIMEZONE);

			$user_avatar = !empty($row['user_avatar']) ? Url::to_rel($row['user_avatar']) : ($user_accounts_config->is_default_avatar_enabled() ? Url::to_rel('/templates/' . AppContext::get_current_user()->get_theme() . '/images/' .  $user_accounts_config->get_default_avatar_name()) : '');

			$author = new User();
			if (!empty($row['user_id']))
				$author->set_properties($row);
			else
				$author->init_visitor_user();
			$user_group_color = User::get_group_color($author->get_groups(), $author->get_level(), true);

			$tpl->assign_block_vars('item', array(
				'C_USER_GROUP_COLOR' => !empty($user_group_color),
				'C_AUTHOR_EXIST' => $author->get_id() !== User::VISITOR_LEVEL,
				'U_AVATAR' => $user_avatar,
				'PSEUDO' => $author->get_display_name(),
				'USER_LEVEL_CLASS' => UserService::get_level_class($author->get_level()),
				'USER_GROUP_COLOR' => $user_group_color,
				'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($author->get_id())->rel(),
				'MODULE_NAME' => $modules_config->get_module($row['module_id']) ? $modules_config->get_module($row['module_id'])->get_configuration()->get_name() : '',
				'C_READ_MORE' => $cut_contents != $contents,
				'ARTICLE' => Url::to_rel($row['path']),
				'CONTENTS' => $cut_contents,
				'DATE' => $date->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE),
				'U_LINK' => Url::to_rel($row['path'] . '#com' . $row['id'])
			));
		}
		$result->dispose();

		$this->view->put('LASTCOMS', $tpl);
	}

	//FULL MODULES : Articles - Contact - Download - Forum - Gallery - Guestbook - Media - News - Web (if partner)

	//Articles
	private function build_articles_view()
	{
		$now = new Date();
		$tpl = new FileTemplate('HomeLanding/pagecontent/articles.tpl');
		$articles_config = ArticlesConfig::load();
		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, $articles_config->are_descriptions_displayed_to_guests(), HomeLandingConfig::MODULE_ARTICLES);

		$result = $this->querier->select('SELECT articles.*, member.*, com.number_comments, notes.average_notes, notes.number_notes, note.note, cat.rewrited_name AS rewrited_name_cat
		FROM ' . PREFIX . 'articles articles
		LEFT JOIN ' . PREFIX . 'articles_cats cat ON cat.id = articles.id_category
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = articles.author_user_id
		LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = articles.id AND com.module_id = \'articles\'
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = articles.id AND notes.module_name = \'articles\'
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = articles.id AND note.module_name = \'articles\' AND note.user_id = :user_id
		WHERE (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0))) AND id_category IN :authorized_categories
		ORDER BY articles.date_created DESC
		LIMIT :articles_limit', array(
			'authorized_categories' => $authorized_categories,
			'user_id' => AppContext::get_current_user()->get_id(),
			'timestamp_now' => $now->get_timestamp(),
			'articles_limit' => $this->modules[HomeLandingConfig::MODULE_ARTICLES]->get_elements_number_displayed()
		));

		while ($row = $result->fetch())
		{
			$article = new Article();
			$article->set_properties($row);

			$tpl->assign_block_vars('item', $article->get_array_tpl_vars());
			$tpl->put_all(array(
				'DATE_DAY' => strftime('%d', $article->get_date_created()->get_timestamp()),
				'DATE_MONTH_A' => strftime('%b', $article->get_date_created()->get_timestamp()),
				'ARTICLES_POSITION' => $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_ARTICLES),
				'C_DISPLAY_BLOCK' => $articles_config->get_display_type() == ArticlesConfig::DISPLAY_MOSAIC,
				'COL_NBR' => $articles_config->get_number_cols_display_per_line()
			));
		}
		$result->dispose();

		$this->view->put('ARTICLES', $tpl);
	}

	//Calendar
	private function build_events_view()
	{
		$today = new Date();
		$today->set_hours(0);
		$today->set_minutes(0);
		$today->set_seconds(0);
		$tpl = new FileTemplate('HomeLanding/pagecontent/events.tpl');
		$authorized_categories = CalendarService::get_authorized_categories(Category::ROOT_CATEGORY);

		$result = $this->querier->select('SELECT *
		FROM '. PREFIX . 'calendar_events event
		LEFT JOIN ' . PREFIX . 'calendar_events_content event_content ON event_content.id = event.content_id
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = event_content.author_id
		LEFT JOIN '. PREFIX . 'calendar_cats cat ON cat.id = event_content.id_category
		WHERE approved = 1 AND id_category IN :authorized_categories
		AND start_date >= :timestamp_today
		ORDER BY start_date
		LIMIT :calendar_limit', array(
			'authorized_categories' => $authorized_categories,
			'timestamp_today' => $today->get_timestamp(),
			'calendar_limit' => $this->modules[HomeLandingConfig::MODULE_CALENDAR]->get_elements_number_displayed()
		));

		$tpl->put_all(array(
			'CALENDAR_POSITION' => $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_CALENDAR),
			'C_NO_EVENT' => $result->get_rows_count() == 0,
		));

		while ($row = $result->fetch())
		{
			$event = new CalendarEvent();
			$event->set_properties($row);

			$description = TextHelper::substr(@strip_tags(FormatingHelper::second_parse($row['contents']), '<br><br/>'), 0, $this->modules[HomeLandingConfig::MODULE_CALENDAR]->get_characters_number_displayed());

			$tpl->assign_block_vars('item', array_merge($event->get_array_tpl_vars(), array(
				'C_READ_MORE' => TextHelper::strlen(FormatingHelper::second_parse($row['contents'])) >= $this->modules[HomeLandingConfig::MODULE_CALENDAR]->get_characters_number_displayed(),
				'DESCRIPTION' => $description
			)));
		}
		$result->dispose();

		$this->view->put('EVENTS', $tpl);
	}

	//Contact
	private function build_contact_view()
	{
		$tpl = new FileTemplate('HomeLanding/pagecontent/contact.tpl');
		$contact_config = ContactConfig::load();
		$tpl->put_all(array(
			'CONTACT_POSITION' => $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_CONTACT),
			'C_MAP_ENABLED' => $contact_config->is_map_enabled(),
			'C_MAP_TOP' => $contact_config->is_map_enabled() && $contact_config->is_map_top(),
			'C_MAP_BOTTOM' => $contact_config->is_map_enabled() && $contact_config->is_map_bottom(),
		));

		$this->build_contact_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			if ($this->send_contact_mail())
			{
				$tpl->put('MSG', MessageHelper::display($this->lang['send.email.success'] . (ContactConfig::load()->is_sender_acknowledgment_enabled() ? ' ' . $this->lang['send.email.acknowledgment'] : ''), MessageHelper::SUCCESS));
				$tpl->put('C_MAIL_SENT', true);
			}
			else
				$tpl->put('MSG', MessageHelper::display($this->lang['send.email.error'], MessageHelper::ERROR, 5));
		}

		if ($contact_config->is_map_enabled()) {
			$this->build_map_view();
			$displayed_map = $this->map->display();
		} else {
			$displayed_map = '';
		}

		$tpl->put_all(array(
			'CONTACT_FORM' => $this->form->display(),
			'MAP' => $displayed_map
		));

		$this->view->put('CONTACT', $tpl);
	}

	public function build_map_view()
	{
		$contact_config = ContactConfig::load();
		$map = new GoogleMapsDisplayMap($contact_config->get_map_markers());
		$this->map = $map;
	}

	private function build_contact_form()
	{
		$contact_config = ContactConfig::load();
		$form = new HTMLForm(__CLASS__, '', false);

		$fieldset = new FormFieldsetHTML('contact', $contact_config->get_title());
		$form->add_fieldset($fieldset);

		foreach($contact_config->get_fields() as $id => $properties)
		{
			$field = new ContactField();
			$field->set_properties($properties);

			if ($field->is_displayed() && $field->is_authorized())
			{
				if ($field->get_field_name() == 'f_sender_mail')
					$field->set_default_value(AppContext::get_current_user()->get_email());
				$field->set_fieldset($fieldset);
				ContactFieldsService::display_field($field);
			}
		}

		$fieldset->add_field(new FormFieldCaptcha('apply_form_captcha'));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function send_contact_mail()
	{
		$contact_config = ContactConfig::load();
		$message = '';
		$current_user = AppContext::get_current_user();

		$fields = $contact_config->get_fields();
		$recipients_field_id = $contact_config->get_field_id_by_name('f_recipients');
		$recipients_field = new ContactField();
		$recipients_field->set_properties($fields[$recipients_field_id]);
		$recipients = $recipients_field->get_possible_values();
		$recipients['admins']['email'] = implode(';', MailServiceConfig::load()->get_administrators_mails());

		$subject_field_id = $contact_config->get_field_id_by_name('f_subject');
		$subject_field = new ContactField();
		$subject_field->set_properties($fields[$subject_field_id]);
		$subjects = $subject_field->get_possible_values();

		if ($subject_field->get_field_type() == 'ContactShortTextField')
			$subject = $this->form->get_value('f_subject');
		else
			$subject = $this->form->get_value('f_subject')->get_raw_value();

		$display_message_title = false;
		if ($contact_config->is_tracking_number_enabled())
		{
			$now = new Date();

			$tracking_number = $contact_config->get_last_tracking_number();
			$tracking_number++;
			$message .= $this->lang['send.email.tracking.number'] . ' : ' . ($contact_config->is_date_in_tracking_number_enabled() ? $now->get_year() . $now->get_month() . $now->get_day() . '-' : '') . $tracking_number . '

';
			$contact_config->set_last_tracking_number($tracking_number);
			ContactConfig::save();

			$subject = '[' . $tracking_number . '] ' . $subject;

			$display_message_title = true;
		}

		foreach($contact_config->get_fields() as $id => $properties)
		{
			$field = new ContactField();
			$field->set_properties($properties);

			if ($field->is_displayed() && $field->is_authorized() && $field->is_deletable())
			{
				try{
					$value = ContactFieldsService::get_value($this->form, $field);
						$message .= $field->get_name() . ': ' . $value . '

';
				} catch(Exception $e) {
					throw new Exception($e->getMessage());
				}

				$display_message_title = true;
			}
		}

		if ($display_message_title)
			$message .= $this->lang['contact.form.message'] . ':
';

		$message .= $this->form->get_value('f_message');

		$mail = new Mail();
		$mail->set_sender(MailServiceConfig::load()->get_default_mail_sender(), $this->lang['module_title']);
		$mail->set_reply_to($this->form->get_value('f_sender_mail'), $current_user->get_display_name());
		$mail->set_subject($subject);
		$mail->set_content(TextHelper::html_entity_decode($message));

		if ($recipients_field->is_displayed())
		{
			if (in_array($recipients_field->get_field_type(), array('ContactSimpleSelectField', 'ContactSimpleChoiceField')))
				$recipients_mails = explode(';', $recipients[$this->form->get_value('f_recipients')->get_raw_value()]['email']);
			else
			{
				$selected_recipients = $this->form->get_value('f_recipients');
				$recipients_mails = array();
				foreach ($selected_recipients as $recipient)
				{
					$mails = explode(';', $recipients[$recipient->get_id()]['email']);
					foreach ($mails as $m)
					{
						$recipients_mails[] = $m;
					}
				}
			}

			foreach ($recipients_mails as $mail_address)
			{
				$mail->add_recipient($mail_address);
			}
		}
		else if ($subject_field->get_field_type() != 'ContactShortTextField')
		{
			$recipient = $this->form->get_value('f_subject')->get_raw_value() ? $subjects[$this->form->get_value('f_subject')->get_raw_value()]['recipient'] : MailServiceConfig::load()->get_default_mail_sender() . ';' . Mail::SENDER_ADMIN;
			$recipients_mails = explode(';', $recipients[$recipient]['email']);
			foreach ($recipients_mails as $mail_address)
			{
				$mail->add_recipient($mail_address);
			}
		}
		else
		{
			$recipients_mails = explode(';', $recipients['admins']['email']);
			foreach ($recipients_mails as $mail_address)
			{
				$mail->add_recipient($mail_address);
			}
		}

		$mail_service = AppContext::get_mail_service();

		if ($contact_config->is_sender_acknowledgment_enabled())
		{
			$acknowledgment = new Mail();
			$acknowledgment->set_sender(MailServiceConfig::load()->get_default_mail_sender(), Mail::SENDER_ADMIN);
			$acknowledgment->set_subject('[' . $this->lang['send.email.acknowledgment.title'] . '] ' . $subject);
			$acknowledgment->set_content($this->lang['send.email.acknowledgment.correct'] . $message);
			$acknowledgment->add_recipient($this->form->get_value('f_sender_mail'));

			return $mail_service->try_to_send($mail) && $mail_service->try_to_send($acknowledgment);
		}

		return $mail_service->try_to_send($mail);
	}

	//Download
	private function build_download_view()
	{
		$now = new Date();
		$tpl = new FileTemplate('HomeLanding/pagecontent/download.tpl');
		$authorized_categories = DownloadService::get_authorized_categories(Category::ROOT_CATEGORY);
		$download_config = DownloadConfig::load();

		$result = $this->querier->select('SELECT download.*, member.*, notes.average_notes, notes.number_notes, note.note, cat.rewrited_name AS rewrited_name_cat
		FROM ' . PREFIX . 'download download
		LEFT JOIN ' . PREFIX . 'download_cats cat ON cat.id = download.id_category
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = download.author_user_id
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = download.id AND notes.module_name = \'download\'
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = download.id AND note.module_name = \'download\' AND note.user_id = :user_id
		WHERE (approbation_type = 1 OR (approbation_type = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0))) AND id_category IN :authorized_categories
		ORDER BY download.creation_date DESC
		LIMIT :download_limit', array(
			'authorized_categories' => $authorized_categories,
			'user_id' => AppContext::get_current_user()->get_id(),
			'timestamp_now' => $now->get_timestamp(),
			'download_limit' => $this->modules[HomeLandingConfig::MODULE_DOWNLOAD]->get_elements_number_displayed()
		));

		while ($row = $result->fetch())
		{
			$download = new DownloadFile();
			$download->set_properties($row);

			$tpl->put_all(array(
				'DOWNLOAD_POSITION' => $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_DOWNLOAD),
				'C_DISPLAY_BLOCK' => $download_config->is_category_displayed_summary(),
				'C_DISPLAY_TABLE' => $download_config->is_category_displayed_table(),
				'COL_NBR' => $download_config->get_columns_number_per_line()
			));
			$tpl->assign_block_vars('item', $download->get_array_tpl_vars());
		}
		$result->dispose();

		$this->view->put('DOWNLOAD', $tpl);
	}

	//Forum
	private function build_forum_view()
	{
		$tpl = new FileTemplate('HomeLanding/pagecontent/forum.tpl');
		$user_accounts_config = UserAccountsConfig::load();
		$authorized_categories = ForumService::get_authorized_categories(Category::ROOT_CATEGORY);

		$result = $this->querier->select('SELECT t.id, t.idcat, t.title, member.display_name AS last_login, t.last_timestamp, t.last_user_id, t.last_msg_id, t.display_msg, t.nbr_msg AS t_nbr_msg, msg.id mid, msg.contents, t.user_id as glogin, ext_field.user_avatar
		FROM ' . PREFIX . 'forum_topics t
		LEFT JOIN ' . PREFIX . 'forum_cats cat ON cat.id = t.idcat
		LEFT JOIN ' . PREFIX . 'forum_msg msg ON msg.id = t.last_msg_id
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = t.last_user_id
		LEFT JOIN ' . DB_TABLE_MEMBER_EXTENDED_FIELDS . ' ext_field ON ext_field.user_id = member.user_id
		WHERE t.display_msg = 0 AND idcat IN :authorized_categories
		ORDER BY t.last_timestamp DESC
		LIMIT :forum_limit', array(
			'authorized_categories' => $authorized_categories,
			'forum_limit' => $this->modules[HomeLandingConfig::MODULE_FORUM]->get_elements_number_displayed()
		));

		$tpl->put('FORUM_POSITION', $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_FORUM));

		while ($row = $result->fetch())
		{
			$contents = FormatingHelper::second_parse($row['contents']);
			$user_avatar = !empty($row['user_avatar']) ? Url::to_rel($row['user_avatar']) : ($user_accounts_config->is_default_avatar_enabled() ? Url::to_rel('/templates/' . AppContext::get_current_user()->get_theme() . '/images/' .  $user_accounts_config->get_default_avatar_name()) : '');

			$config = ForumConfig::load();
			$last_page = ceil($row['t_nbr_msg'] / $config->get_number_messages_per_page());
			$last_page_rewrite = ($last_page > 1) ? '-' . $last_page : '';
			$last_page = ($last_page > 1) ? 'pt=' . $last_page . '&amp;' : '';
			$link = new Url('/forum/topic' . url('.php?' . $last_page .  'id=' . $row['id'], '-' . $row['id'] . $last_page_rewrite . '+' . Url::encode_rewrite($row['title'])  . '.php') . '#m' .  $row['last_msg_id']);
			$link_message = new Url('/forum/topic' . url('.php?' . $last_page .  'id=' . $row['id'], '-' . $row['id'] . $last_page_rewrite . '+' . Url::encode_rewrite($row['title'])  . '.php'));

			$nb_char = $this->modules[HomeLandingConfig::MODULE_FORUM]->get_characters_number_displayed();

			$tpl->assign_block_vars('item', array(
				'U_AVATAR' => $user_avatar,
				'CONTENTS' => TextHelper::cut_string(@strip_tags(stripslashes($contents), 0), (int)$nb_char),
				'PSEUDO' => $row['last_login'],
				'DATE' => strftime('%d/%m/%Y - %Hh%M', $row['last_timestamp']),
				'MESSAGE' => stripslashes($row['title']),
				'U_LINK' => $link->rel(),
				'U_MESSAGE' => $link_message->rel()
			));
		}
		$result->dispose();

		$this->view->put('FORUM', $tpl);
	}

	//Gallery
	private function build_gallery_view()
	{
		$tpl = new FileTemplate('HomeLanding/pagecontent/gallery.tpl');
		$authorized_categories = GalleryService::get_authorized_categories(Category::ROOT_CATEGORY);
		$gallery_config = GalleryConfig::load();

		$result = $this->querier->select("SELECT
			g.id, g.idcat, g.name, g.path, g.timestamp, g.aprob, g.width, g.height, g.user_id, g.views, g.aprob,
			m.display_name, m.groups, m.level,
			notes.average_notes, notes.number_notes, note.note
		FROM " . GallerySetup::$gallery_table . " g
		LEFT JOIN " . PREFIX . "gallery_cats cat ON cat.id = g.idcat
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = g.user_id
		LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " com ON com.id_in_module = g.id AND com.module_id = 'gallery'
		LEFT JOIN " . DB_TABLE_AVERAGE_NOTES . " notes ON notes.id_in_module = g.id AND notes.module_name = 'gallery'
		LEFT JOIN " . DB_TABLE_NOTE . " note ON note.id_in_module = g.id AND note.module_name = 'gallery' AND note.user_id = :user_id
		WHERE idcat IN :authorized_categories
		ORDER BY g.timestamp DESC
		LIMIT :gallery_limit", array(
			'authorized_categories' => $authorized_categories,
			'gallery_limit' => $this->modules[HomeLandingConfig::MODULE_GALLERY]->get_elements_number_displayed(),
			'user_id' => AppContext::get_current_user()->get_id(),
		));

		$tpl->put_all(array(
			'GALLERY_POSITION' => $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_GALLERY),
			'COL_NBR' => $gallery_config->get_columns_number()
		));

		while ($row = $result->fetch())
		{
			$tpl->assign_block_vars('item', array(
				'U_IMG' => PATH_TO_ROOT . '/gallery/pics/' . $row['path'],
				'TITLE' => $row['name'],
				'NB_VIEWS' => $row['views'],
				'U_CATEGORY' => PATH_TO_ROOT . '/gallery/gallery' . url('.php?cat=' . $row['idcat'], '-' . $row['idcat'] . '.php')
			));
		}
		$result->dispose();

		$this->view->put('GALLERY', $tpl);
	}

	//Guestbook
	private function build_guestbook_view()
	{
		$tpl = new FileTemplate('HomeLanding/pagecontent/guestbook.tpl');
		$user_accounts_config = UserAccountsConfig::load();

		$result = $this->querier->select('SELECT member.*, guestbook.*, guestbook.login as glogin, ext_field.user_avatar
		FROM ' . GuestbookSetup::$guestbook_table . ' guestbook
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = guestbook.user_id
		LEFT JOIN ' . DB_TABLE_MEMBER_EXTENDED_FIELDS . ' ext_field ON ext_field.user_id = member.user_id
		ORDER BY guestbook.timestamp DESC
		LIMIT :guestbook_limit', array(
			'guestbook_limit' => $this->modules[HomeLandingConfig::MODULE_GUESTBOOK]->get_elements_number_displayed()
		));

		$tpl->put_all(array(
			'GUESTBOOK_POSITION' => $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_GUESTBOOK),
			'C_EMPTY_GUESTBOOK' => $result->get_rows_count() == 0,
		));

		while ($row = $result->fetch())
		{
			$message = new GuestbookMessage();
			$message->set_properties($row);

			$contents = @strip_tags(FormatingHelper::second_parse($message->get_contents()));
			$nb_char = $this->modules[HomeLandingConfig::MODULE_GUESTBOOK]->get_characters_number_displayed();
			$user_avatar = !empty($row['user_avatar']) ? Url::to_rel($row['user_avatar']) : ($user_accounts_config->is_default_avatar_enabled() ? Url::to_rel('/templates/' . AppContext::get_current_user()->get_theme() . '/images/' .  $user_accounts_config->get_default_avatar_name()) : '');
			$cut_contents = trim(TextHelper::substr($contents, 0, $nb_char));

			$tpl->assign_block_vars('item', array_merge($message->get_array_tpl_vars(), array(
				'C_READ_MORE' => $cut_contents != $contents,
				'U_AVATAR' => $user_avatar,
				'CONTENTS' => $cut_contents,
			)));
		}
		$result->dispose();

		$this->view->put('GUESTBOOK', $tpl);
	}

	//Media
	private function build_media_view()
	{
		$tpl = new FileTemplate('HomeLanding/pagecontent/media.tpl');
		$authorized_categories = MediaService::get_authorized_categories(Category::ROOT_CATEGORY);

		$result = $this->querier->select('SELECT media.*, mb.display_name, mb.groups, mb.level, notes.average_notes, notes.number_notes, note.note
		FROM ' . PREFIX . 'media AS media
		LEFT JOIN ' . PREFIX . 'media_cats cat ON cat.id = media.idcat
		LEFT JOIN ' . DB_TABLE_MEMBER . ' AS mb ON media.iduser = mb.user_id
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = media.id AND notes.module_name = \'media\'
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = media.id AND note.module_name = \'media\' AND note.user_id = :user_id
		WHERE idcat IN :authorized_categories
		ORDER BY media.timestamp DESC
		LIMIT :media_limit', array(
			'authorized_categories' => $authorized_categories,
			'user_id' => AppContext::get_current_user()->get_id(),
			'media_limit' => $this->modules[HomeLandingConfig::MODULE_MEDIA]->get_elements_number_displayed()
		));

		while ($row = $result->fetch())
		{
			$mime_type_tpl = $row['mime_type'];

			$tpl->put('MEDIA_POSITION', $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_MEDIA));

			if ($mime_type_tpl == 'application/x-shockwave-flash')
			{
				$poster = new Url($row['poster']);
				$tpl->assign_block_vars('media_swf', array(
					'PSEUDO' => $row['display_name'],
					'TITLE' => $row['name'],
					'ID' => $row['id'],
					'DATE' => strftime('%d/%m/%Y', $row['timestamp']),
					'POSTER' => $poster->rel(),

					'U_MEDIA_LINK' => PATH_TO_ROOT . '/media/' . url('media.php?id=' . $row['id'], 'media-' . $row['id'] . '-' . $row['idcat'] . '+' . Url::encode_rewrite($row['name']) . '.php'),
					'URL' => $row['url'],
					'URL_EMBED' => str_replace("v", "embed", $row['url']),
					'MIME' => $row['mime_type']
				));
			}
			elseif ($mime_type_tpl == 'video/x-flv')
			{
				$poster = new Url($row['poster']);
				$tpl->assign_block_vars('media_flv', array(
					'PSEUDO' => $row['display_name'],
					'TITLE' => $row['name'],
					'ID' => $row['id'],
					'DATE' => strftime('%d/%m/%Y', $row['timestamp']),
					'POSTER' => $poster->rel(),

					'U_MEDIA_LINK' => PATH_TO_ROOT . '/media/' . url('media.php?id=' . $row['id'], 'media-' . $row['id'] . '-' . $row['idcat'] . '+' . Url::encode_rewrite($row['name']) . '.php'),
					'URL' => $row['url'],
					'MIME' => $row['mime_type'],
					'WIDTH' => $row['width'],
					'HEIGHT' => $row['height']
				));
			}
			elseif ($mime_type_tpl == 'video/mp4')
			{
				$poster = new Url($row['poster']);
				$tpl->assign_block_vars('media_mp4', array(
					'PSEUDO' => $row['display_name'],
					'TITLE' => $row['name'],
					'ID' => $row['id'],
					'DATE' => strftime('%d/%m/%Y', $row['timestamp']),
					'C_POSTER' => !empty($poster),
					'POSTER' => $poster->rel(),

					'U_MEDIA_LINK' => PATH_TO_ROOT . '/media/' . url('media.php?id=' . $row['id'], 'media-' . $row['id'] . '-' . $row['idcat'] . '+' . Url::encode_rewrite($row['name']) . '.php'),
					'URL' => $row['url'],
					'MIME' => $row['mime_type'],
					'WIDTH' => $row['width'],
					'HEIGHT' => $row['height']
				));
			}
			elseif ($mime_type_tpl == 'audio/mpeg')
			{
				$poster = new Url($row['poster']);
				$tpl->assign_block_vars('media_mp3', array(
					'PSEUDO' => $row['display_name'],
					'TITLE' => $row['name'],
					'ID' => $row['id'],
					'DATE' => strftime('%d/%m/%Y', $row['timestamp']),
					'C_POSTER' => !empty($poster),
					'POSTER' => $poster->rel(),

					'U_MEDIA_LINK' => PATH_TO_ROOT . '/media/' . url('media.php?id=' . $row['id'], 'media-' . $row['id'] . '-' . $row['idcat'] . '+' . Url::encode_rewrite($row['name']) . '.php'),
					'URL' => $row['url'],
					'MIME' => $row['mime_type'],
					'WIDTH' => $row['width'],
					'HEIGHT' => $row['height']
				));
			}
			else
			{
				$poster = new Url($row['poster']);
				$tpl->assign_block_vars('media_other', array(
					'PSEUDO' => $row['display_name'],
					'TITLE' => $row['name'],
					'ID' => $row['id'],
					'DATE' => strftime('%d/%m/%Y', $row['timestamp']),
					'C_POSTER' => !empty($poster),
					'POSTER' => $poster->rel(),

					'U_MEDIA_LINK' => PATH_TO_ROOT . '/media/' . url('media.php?id=' . $row['id'], 'media-' . $row['id'] . '-' . $row['idcat'] . '+' . Url::encode_rewrite($row['name']) . '.php'),
					'URL' => $row['url'],
					'MIME' => $row['mime_type'],
					'WIDTH' => $row['width'],
					'HEIGHT' => $row['height']
				));
			}
		}
		$result->dispose();

		$this->view->put('MEDIA', $tpl);
	}

	//News
	private function build_news_view()
	{
		$now = new Date();
		$tpl = new FileTemplate('HomeLanding/pagecontent/news.tpl');
		$news_config = NewsConfig::load();
		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, $news_config->are_descriptions_displayed_to_guests(), HomeLandingConfig::MODULE_NEWS);

		$result = $this->querier->select('SELECT news.*, member.*, cat.rewrited_name AS rewrited_name_cat
		FROM ' . PREFIX . 'news news
		LEFT JOIN ' . PREFIX . 'news_cats cat ON cat.id = news.id_category
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = news.author_user_id
		WHERE (approbation_type = 1 OR (approbation_type = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0))) AND id_category IN :authorized_categories
		ORDER BY news.creation_date DESC
		LIMIT :news_limit', array(
			'authorized_categories' => $authorized_categories,
			'user_id' => AppContext::get_current_user()->get_id(),
			'timestamp_now' => $now->get_timestamp(),
			'news_limit' => $this->modules[HomeLandingConfig::MODULE_NEWS]->get_elements_number_displayed()
		));

		while ($row = $result->fetch())
		{
			$news = new News();
			$news->set_properties($row);

			$tpl->put_all(array(
				'NEWS_POSITION' => $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_NEWS),
				'C_DISPLAY_BLOCK' => $news_config->get_display_type() == NewsConfig::DISPLAY_BLOCK,
				'COL_NBR' => $news_config->get_number_columns_display_news()
			));

			$tpl->assign_block_vars('item', $news->get_array_tpl_vars());
		}
		$result->dispose();

		$this->view->put('NEWS', $tpl);
	}

	//Web
	private function build_web_view()
	{
		$now = new Date();
		$tpl = new FileTemplate('HomeLanding/pagecontent/web.tpl');
		$authorized_categories = WebService::get_authorized_categories(Category::ROOT_CATEGORY);

		$result = $this->querier->select('SELECT web.*, member.*, cat.rewrited_name AS rewrited_name_cat, notes.average_notes, notes.number_notes, note.note
		FROM ' . PREFIX . 'web web
		LEFT JOIN ' . PREFIX . 'web_cats cat ON cat.id = web.id_category
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = web.author_user_id
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = web.id AND notes.module_name = \'web\'
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = web.id AND note.module_name = \'web\'
		WHERE (approbation_type = 1 OR (approbation_type = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0))) AND partner = 1 AND id_category IN :authorized_categories
		ORDER BY web.rewrited_name ASC
		LIMIT :web_limit', array(
			'authorized_categories' => $authorized_categories,
			'timestamp_now' => $now->get_timestamp(),
			'web_limit' => $this->modules[HomeLandingConfig::MODULE_WEB]->get_elements_number_displayed()
		));

		while ($row = $result->fetch())
		{
			$web = new WebLink();
			$web->set_properties($row);

			$tpl->put('WEB_POSITION', $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_WEB));
			$tpl->assign_block_vars('item', $web->get_array_tpl_vars());
		}
		$result->dispose();

		$this->view->put('WEB', $tpl);
	}

	// Additionnal

	//External RSS
	// private function build_external_rss_view()
	// {
	// 	$tpl = new FileTemplate('HomeLanding/pagecontent/rssreader.tpl');
	// 	$rss_number = $this->modules[HomeLandingConfig::MODULE_RSS]->get_elements_number_displayed();
	// 	$nb_char = $this->modules[HomeLandingConfig::MODULE_RSS]->get_characters_number_displayed();
	//
	// 	$time_renew = time() + (60*60);
	// 	$d_actuelle = date('H:i');
	// 	$d_renew = date('H:i', $time_renew);
	// 	$xml_url = $this->config->get_rss_xml_url();
	//
	// 	if (empty($xml_url))
	// 	{
	// 		$xml = '';
	// 	}
	// 	else
	// 	{
	// 		$xml = simplexml_load_file($xml_url);
	// 		$items = array();
	// 		$items['title'] = array();
	// 		$items['link'] = array();
	// 		$items['desc'] = array();
	// 		$items['img'] = array();
	// 		$items['date'] = array();
	//
	// 		foreach($xml->channel->item as $i)
	// 		{
	// 			$items['title'][] = utf8_decode($i->title);
	// 			$items['link'][] = utf8_decode($i->link);
	// 			$items['desc'][] = utf8_decode($i->description);
	// 			$items['img'][] = utf8_decode($i->image);
	// 			$items['date'][] = utf8_decode($i->pubDate);
	// 		}
	//
	// 		$nbr_item = $rss_number <= count($items['title']) ? $rss_number : count($items['title']);
	//
	// 		$tpl->put_all(array(
	// 			'SITE_TITLE' => $this->config->get_rss_site_name(),
	// 			'SITE_URL' => $this->config->get_rss_site_url(),
	// 			'RSS_POSITION' => $this->config->get_module_position_by_id(HomeLandingConfig::MODULE_RSS),
	// 		));
	//
	// 		for($i = 0; $i < $nbr_item ; $i++)
	// 		{
	// 			$date = strtotime($items['date'][$i]);
	// 			$date_feed = strftime('%d/%m/%Y %Hh%M', $date);
	// 			$desc = $items['desc'][$i];
	// 			$cut_desc = strip_tags(trim(substr($desc, 0, $nb_char)));
	// 			$img_feed = $items['img'][$i];
	// 			$tpl->assign_block_vars('rssreader',array(
	// 				'TITLE_FEED' => $items['title'][$i],
	// 				'LINK_FEED' => $items['link'][$i],
	// 				'DATE_FEED' => $date_feed,
	// 				'DESC' => $cut_desc,
	// 				'C_READ_MORE' => $cut_desc != $desc,
	// 				'C_IMG_FEED' => !empty($img_feed),
	// 				'IMG_FEED' => $img_feed,
	// 			));
	// 		}
	// 		$this->view->put('RSS', $tpl);
	// 	}
	// }

	//Generation
	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->config->get_module_title());
		$graphical_environment->get_seo_meta_data()->set_description(GeneralConfig::load()->get_site_description());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(HomeLandingUrlBuilder::home());

		$graphical_environment->get_seo_meta_data()->set_picture_url(PATH_TO_ROOT.'/templates/' . AppContext::get_current_user()->get_theme() . '/theme/images/logo.png');

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->config->get_module_title(), HomeLandingUrlBuilder::home());

		return $response;
	}

	public static function get_view()
	{
		$object = new self();
		$object->init();
		$object->build_view();
		return $object->view;
	}
}
?>
