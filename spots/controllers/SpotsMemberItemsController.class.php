<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2025 01 12
 * @since       PHPBoost 6.0 - 2021 08 22
*/

class SpotsMemberItemsController extends DefaultModuleController
{
	private $member;
	private $authorized_categories;

	protected function get_template_to_use()
	{
		return new FileTemplate('spots/SpotsSeveralItemsController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->check_authorizations();

		$this->view->put('C_MEMBER_ITEMS', true);

		if ($this->member === null)
            $this->build_members_listing_view();
        else
            $this->build_view($request);

		return $this->generate_response($request);
	}

	private function init()
	{
        $this->member = AppContext::get_request()->get_getint('user_id', 0) ? UserService::get_user(AppContext::get_request()->get_getint('user_id', 0)) : null;
		$this->authorized_categories = CategoriesService::get_authorized_categories();
    }

	private function build_members_listing_view()
	{
		$now = new Date();
        $result = PersistenceContext::get_querier()->select('SELECT spots.*, member.*
            FROM ' . SpotsSetup::$spots_table . ' spots
            LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = spots.author_user_id
            WHERE id_category IN :authorized_categories
            AND published = 1
            ORDER BY member.display_name, spots.creation_date DESC', [
                'authorized_categories' => $this->authorized_categories,
                'timestamp_now' => $now->get_timestamp()
            ]
        );

        $this->view->put_all([
            'C_MEMBERS_LIST' => true,
            'C_SELF_ITEMS' => false
        ]);

        $contributors = [];
        if ($result->get_rows_count() > 0)
        {
            foreach ($result as $smallad)
            {
                $contributors[] = $smallad['author_user_id'];
            }
            $this->view->put('C_MEMBERS', count($contributors) > 0);

            foreach (array_unique($contributors) as $user_id)
            {
                $user = UserService::get_user($user_id);
                if ($user)
                {
                    $this->view->assign_block_vars('users', [
                        'C_AVATAR' => UserService::get_avatar($user) || UserAccountsConfig::load()->is_default_avatar_enabled(),
                        'USER_NAME' => $user->get_display_name(),
                        'U_USER' => SpotsUrlBuilder::display_member_items($user->get_id())->rel(),
                        'U_AVATAR' => UserService::get_avatar($user)
                    ]);
                }
            }
        }
    }

	public function build_view(HTTPRequestCustom $request)
	{
		$now = new Date();
		$config = SpotsConfig::load();

		$condition = 'WHERE id_category IN :authorized_categories
		AND author_user_id = :user_id
		AND published = 1';
		$parameters = [
			'user_id' => $this->get_member()->get_id(),
			'authorized_categories' => $this->authorized_categories,
			'timestamp_now' => $now->get_timestamp()
        ];

		$page = AppContext::get_request()->get_getint('page', 1);
		$pagination = $this->get_pagination($condition, $parameters, $page);

		$result = PersistenceContext::get_querier()->select('SELECT spots.*, member.*
		FROM '. SpotsSetup::$spots_table .' spots
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = spots.author_user_id
		' . $condition . '
		ORDER BY spots.creation_date DESC
		LIMIT :number_items_per_page OFFSET :display_from', array_merge($parameters, [
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		]));

		$this->view->put_all([
			'C_MY_ITEMS'         => $this->is_current_member_displayed(),
			'C_SELF_ITEMS'       => $result->get_rows_count() > 0,
			'C_CONTROLS'         => CategoriesAuthorizationsService::check_authorizations()->moderation(),
			'C_SEVERAL_ITEMS'    => $result->get_rows_count() > 1,
			'C_GRID_VIEW'        => $config->get_display_type() == SpotsConfig::GRID_VIEW,
			'C_TABLE_VIEW'       => $config->get_display_type() == SpotsConfig::TABLE_VIEW,
			'C_PAGINATION'       => $pagination->has_several_pages(),

			'ITEMS_PER_ROW'      => $config->get_items_per_row(),
			'PAGINATION'         => $pagination->display(),
			'MEMBER_NAME'        => $this->get_member()->get_display_name()
		]);

		while ($row = $result->fetch())
		{
			$item = new SpotsItem();
			$item->set_properties($row);

			$this->view->assign_block_vars('self_items', array_merge($item->get_template_vars()));
		}
		$result->dispose();
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

	private function get_pagination($condition, $parameters, $page)
	{
		$items_number = SpotsService::count($condition, $parameters);

		$pagination = new ModulePagination($page, $items_number, (int)SpotsConfig::load()->get_items_per_page());
		$pagination->set_url(SpotsUrlBuilder::display_pending('%d'));

		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
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
		$page = $request->get_getint('page', 1);
		$page_title = $this->member ? ($this->is_current_member_displayed() ? $this->lang['spots.my.items'] : $this->get_member()->get_display_name()) : $this->lang['contribution.members.list'];
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($page_title, $this->config->get_module_name());
		if ($this->member)
        {
            $graphical_environment->get_seo_meta_data()->set_description(StringVars::replace_vars($this->lang['spots.seo.description.member'], ['author' => $this->get_member()->get_id()]));
            $graphical_environment->get_seo_meta_data()->set_canonical_url(SpotsUrlBuilder::display_member_items($this->get_member()->get_id(), $page));
        }
        else
        {
            $graphical_environment->get_seo_meta_data()->set_description($this->lang['contribution.members.list']);
            $graphical_environment->get_seo_meta_data()->set_canonical_url(SpotsUrlBuilder::display_member_items());
        }

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->config->get_module_name(), SpotsUrlBuilder::home());
		$breadcrumb->add($this->lang['contribution.members.list'], SpotsUrlBuilder::display_member_items());
        if ($this->member)
            $breadcrumb->add($page_title, SpotsUrlBuilder::display_member_items($this->get_member()->get_id(), $page));

		return $response;
	}
}
?>
