<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2025 01 09
 * @since       PHPBoost 5.1 - 2018 03 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class SmalladsMemberItemsController extends DefaultModuleController
{
	private $member;
	private $authorized_categories;

	protected function get_template_to_use()
	{
		return new FileTemplate('smallads/SmalladsSeveralItemsController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_view($request);

		$this->check_authorizations();

		return $this->generate_response($request);
	}

	private function init()
	{
        $this->member = AppContext::get_request()->get_getint('user_id', 0) ? UserService::get_user(AppContext::get_request()->get_getint('user_id', 0)) : null;
		$this->authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, $this->config->are_summaries_displayed_to_guests());
    }

	private function build_view(HTTPRequestCustom $request)
	{
		$now = new Date();
		$member_items = AppContext::get_current_user()->get_id();

        $this->view->put_all([
            'C_MEMBER_ITEMS'	   => true,
            'C_MY_ITEMS'     	   => $this->is_current_member_displayed(),
            'C_ROOT_CATEGORY'	   => false,
            'C_ENABLED_FILTERS'	   => $this->config->are_sort_filters_enabled(),
            'C_GRID_VIEW'          => $this->config->get_display_type() == SmalladsConfig::GRID_VIEW,
            'C_LIST_VIEW'          => $this->config->get_display_type() == SmalladsConfig::LIST_VIEW,
            'C_TABLE_VIEW'         => $this->config->get_display_type() == SmalladsConfig::TABLE_VIEW,
            'C_ITEMS_SORT_FILTERS' => $this->config->are_sort_filters_enabled(),
            'C_DISPLAY_CAT_ICONS'  => $this->config->are_cat_icons_enabled(),
            'C_MODERATION'         => CategoriesAuthorizationsService::check_authorizations()->moderation() || $this->is_current_member_displayed(),
            'C_USAGE_TERMS'	       => $this->config->are_usage_terms_displayed(),

            'ITEMS_PER_ROW'        => $this->config->get_items_per_row(),
            'ITEMS_PER_PAGE'       => $this->config->get_items_per_page(),
            'MEMBER_NAME'   	   => $this->member ? $this->member->get_display_name() : '',
            'U_USAGE_TERMS' 	   => SmalladsUrlBuilder::usage_terms()->rel()
        ]);

		if ($this->member === null)
            $this->build_members_listing_view();
        else
        {
            $this->build_items_listing_view($now, $member_items);
            $this->build_sorting_smallad_type();
        }
	}

	private function build_members_listing_view()
	{
		$now = new Date();
        $result = PersistenceContext::get_querier()->select('SELECT smallads.*, member.*
            FROM ' . SmalladsSetup::$smallads_table . ' smallads
            LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = smallads.author_user_id
            WHERE id_category IN :authorized_categories
            AND smallads.archived = 0
            AND ((published = 0 AND archived = 1) OR (published = 1) OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))
            ORDER BY member.display_name, smallads.creation_date DESC', [
                'authorized_categories' => $this->authorized_categories,
                'timestamp_now' => $now->get_timestamp()
            ]
        );
        $this->view->put_all([
            'C_MEMBERS_LIST' => true,
        ]);

        $users = [];
        foreach ($result as $smallad)
        {
            $users[] = $smallad['author_user_id'];
        }

        foreach (array_unique($users) as $user)
        {
            $avatar = AppContext::get_session()->get_cached_data('user_avatar') ? AppContext::get_session()->get_cached_data('user_avatar') : UserAccountsConfig::NO_AVATAR_URL;
            $this->view->assign_block_vars('users', [
                'USER_NAME' => UserService::get_user($user)->get_display_name(),
                'U_USER' => SmalladsUrlBuilder::display_member_items($user)->rel(),
                'U_AVATAR' => Url::to_rel($avatar)
            ]);
        }
    }

    private function build_items_listing_view(Date $now, $member_items)
	{
		if(!empty($member_items))
		{
			$result = PersistenceContext::get_querier()->select('SELECT smallads.*, member.*, com.comments_number
                FROM ' . SmalladsSetup::$smallads_table . ' smallads
                LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = smallads.author_user_id
                LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = smallads.id AND com.module_id = \'smallads\'
                WHERE id_category IN :authorized_categories
                AND smallads.author_user_id = :user_id
                AND smallads.archived = 0
                AND ((published = 0 AND archived = 1) OR (published = 1) OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))
                ORDER BY smallads.creation_date DESC', [
                    'authorized_categories' => $this->authorized_categories,
                    'timestamp_now' => $now->get_timestamp(),
                    'user_id' => $this->get_member()->get_id()
                ]
            );
            $this->view->put_all([
                'C_MY_ITEMS'     	   => $this->is_current_member_displayed(),
                'C_ITEMS'              => $result->get_rows_count() > 0,
                'C_SEVERAL_ITEMS'      => $result->get_rows_count() > 1,
                'C_PAGINATION'         => $result->get_rows_count() > $this->config->get_items_per_page(),
            ]);

			while($row = $result->fetch())
			{
				$item = new SmalladsItem();
				$item->set_properties($row);

				$this->view->assign_block_vars('items', $item->get_template_vars());
			}
			$result->dispose();
		}
		else
		{
			AppContext::get_response()->redirect(SmalladsUrlBuilder::home());
		}
	}

	protected function get_member()
	{
		if (!$this->member && $this->member !== null)
		{
			DispatchManager::redirect(PHPBoostErrors::unexisting_element());
		}
		return $this->member;
	}

	protected function is_current_member_displayed()
	{
		return $this->member && $this->member->get_id() == AppContext::get_current_user()->get_id();
	}

	private function build_sorting_smallad_type()
	{
		$smallad_types = $this->config->get_smallad_types();
		$type_nbr = count($smallad_types);
		if ($type_nbr)
		{
			$this->view->put('C_TYPES_FILTERS', $type_nbr > 0);

			$i = 1;
			foreach ($smallad_types as $name)
			{
				$this->view->assign_block_vars('types', array(
					'C_SEPARATOR' => $i < $type_nbr,
					'TYPE_NAME'        => $name,
					'TYPE_NAME_FILTER' => Url::encode_rewrite(TextHelper::strtolower($name)),
				));
				$i++;
			}
		}
	}

	private function check_authorizations()
	{
		if (!CategoriesAuthorizationsService::check_authorizations()->read())
        {
            $error_controller = PHPBoostErrors::user_not_authorized();
            DispatchManager::redirect($error_controller);
        }
	}

	private function generate_response(HTTPRequestCustom $request)
	{
		$page_title = $this->member ? ($this->is_current_member_displayed() ? $this->lang['smallads.my.items'] : $this->get_member()->get_display_name()) : $this->lang['contribution.contributors'];
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($page_title, $this->lang['smallads.module.title']);
		$graphical_environment->get_seo_meta_data()->set_description(StringVars::replace_vars($this->lang['smallads.seo.description.member'], array('author' => AppContext::get_current_user()->get_display_name())));
		$graphical_environment->get_seo_meta_data()->set_canonical_url(SmalladsUrlBuilder::display_member_items($this->member ? $this->get_member()->get_id() : null));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['smallads.module.title'], SmalladsUrlBuilder::home());
        $breadcrumb->add($this->lang['contribution.contributors'], SmalladsUrlBuilder::display_member_items());
        if ($this->member)
            $breadcrumb->add($page_title, SmalladsUrlBuilder::display_member_items($this->member ? $this->get_member()->get_id() : null));

		return $response;
	}
}
?>
