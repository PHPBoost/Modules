<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 11
 * @since       PHPBoost 2.0 - 2008 02 24
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class WikiExtensionPointProvider extends ItemsModuleExtensionPointProvider
{
    public function home_page(): mixed
    {
        $config = WikiConfig::load();
        if ($config->get_homepage() == WikiConfig::EXPLORER)
        {
            return new DefaultHomePageDisplay($this->get_id(), WikiExplorerController::get_view());
        }
        elseif ($config->get_homepage() == WikiConfig::OVERVIEW)
        {
            return new DefaultHomePageDisplay($this->get_id(), WikiIndexController::get_view());
        }
        else
        {
            return new DefaultHomePageDisplay($this->get_id(), WikiCategoryController::get_view());
        }
    }

	public function lobby(): mixed
	{
		return [
			new WikiLobbyItemsProvider('wiki'),
			new WikiLobbyCategoryProvider('wiki'),
		];
	}

    public function user(): mixed
    {
        return new WikiUserExtensionPoint();
    }
}
?>
