<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 06 30
 * @since       PHPBoost 1.6 - 2007 08 18
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                       English                    #
####################################################

$lang['items.reorder']    = 'Reorder pages';
$lang['items.reordering'] = 'Pages reordering';

// Configuration
$lang['config.left.column.disabled']  = 'Hide left column';
$lang['config.right.column.disabled'] = 'Hide right column';

$lang['default.root.category.description'] = '
    Your site powered by PHPBoost is successfully installed and functional. To help you get familiar with it, here are some recommendations we suggest you read carefully:
    <br />
    <h2 class="formatter-title">Don\'t forget to delete the directory /install</h2>
    <a class="offload" href="' . AdminConfigUrlBuilder::general_config()->relative() . '">Delete the /install directory</a> at the root of your site for security reasons so that no one can restart the installation.
    <br />
    <br />
    <h2 class="formatter-title">Manage your website</h2>
    Some modules, a menu and some content have been installed by défault, you can modify them:
    <br />
    Go to the <div class="pinned link-color"><a class="offload" href="' . UserUrlBuilder::administration()->relative() . '">administration panel</a></div> to configure your site as you wish!
    <br />
    <br />
    <div class="cell-flex formatter-columns cell-columns-4 cell-tile">
        <div class="cell">
            <div class="cell-header"><h4 class="formatter-title">1. Maintenance</h4></div>
            <div class="cell-body">
                <div class="cell-content">
                    <p style="text-align: center;"><a class="offload" href="' . AdminMaintainUrlBuilder::maintain()->relative() . '"><i class="fa fa-person-digging fa-2x" aria-hidden="true"></i></a></p>
                    <a class="offload" href="' . AdminMaintainUrlBuilder::maintain()->relative() . '">Put your website under maintenance</a> and you won\'t be disturbed while you\'re working on it.
                </div>
            </div>
        </div>
        <div class="cell">
            <div class="cell-header"><h4 class="formatter-title">2. Configuration</h4></div>
            <div class="cell-body">
                <div class="cell-content">
                    <p style="text-align: center;"><a class="offload" href="' . AdminConfigUrlBuilder::general_config()->relative() . '"><i class="fa fa-gears fa-2x" aria-hidden="true"></i></a></p>
                    Go to the <a class="offload" href="' . AdminConfigUrlBuilder::general_config()->relative() . '">general configuration</a> to set your default options.
                </div>
            </div>
        </div>
        <div class="cell">
            <div class="cell-header"><h4 class="formatter-title">3. Members</h4></div>
            <div class="cell-body">
                <div class="cell-content">
                    <p style="text-align: center;"><a class="offload" href="' . AdminMembersUrlBuilder::configuration()->relative() . '"><i class="fa fa-users fa-2x" aria-hidden="true"></i></a></p>
                    <a class="offload" href="' . AdminMembersUrlBuilder::configuration()->relative() . '">Configure the members settings</a>.
                </div>
            </div>
        </div>
        <div class="cell">
            <div class="cell-header"><h4 class="formatter-title">4. Editor</h4></div>
            <div class="cell-body">
                <div class="cell-content">
                    <p style="text-align: center;"><a class="offload" href="' . AdminContentUrlBuilder::content_configuration()->relative() . '"><i class="far fa-pen-to-square fa-2x" aria-hidden="true"></i></a></p>
                    <a class="offload" href="' . AdminContentUrlBuilder::content_configuration()->relative() . '">Choose a text editor</a> indispensable tool for adding content.
                </div>
            </div>
        </div>
        <div class="cell">
            <div class="cell-header"><h4 class="formatter-title">5. Menus</h4></div>
            <div class="cell-body">
                <div class="cell-content">
                    <p style="text-align: center;"><a class="offload" href="/admin/menus/menus.php"><i class="fa fa-bars fa-2x" aria-hidden="true"></i></a></p>
                    <a class="offload" href="/admin/menus/menus.php">Edit the link menu or create other menus</a> then configure their access rights.
                </div>
            </div>
        </div>
        <div class="cell">
            <div class="cell-header"><h4 class="formatter-title">6. Modules</h4></div>
            <div class="cell-body">
                <div class="cell-content">
                    <p style="text-align: center;"><a class="offload" href="' . AdminModulesUrlBuilder::add_module()->relative() . '"><i class="fa fa-puzzle-piece fa-2x" aria-hidden="true"></i></a></p>
                    <a class="offload" href="' . AdminModulesUrlBuilder::add_module()->relative() . '">Add modules</a> then configure their access rights.
                </div>
            </div>
        </div>
        <div class="cell">
            <div class="cell-header"><h4 class="formatter-title">7. Themes</h4></div>
            <div class="cell-body">
                <div class="cell-content">
                    <p style="text-align: center;"><a class="offload" href="' . AdminThemeUrlBuilder::add_theme()->relative() . '"><i class="far fa-images fa-2x" aria-hidden="true"></i></a></p>
                    <a class="offload" href="' . AdminThemeUrlBuilder::add_theme()->relative() . '">Add themes</a> (templates) then configure their access rights.
                </div>
            </div>
        </div>
        <div class="cell">
            <div class="cell-header"><h4 class="formatter-title">8. Content</h4></div>
            <div class="cell-body">
                <div class="cell-content">
                    <p style="text-align: center;"><i class="fa fa-file-signature fa-2x" aria-hidden="true"></i></p>
                    Before giving back access to your members, take time to add content to your website and then restore access to your site to your visitors <a class="offload" href="/phpboost/dev/pbtnext/admin/maintain/">maintenance</a>
                </div>
            </div>
        </div>
    </div>
    <br />
    <br />
        <h2 class="formatter-title">What to do if you encounter a problem?</h2><br />
        Feel free to consult <div class="pinned bgc-full moderator"><a class="offload" href="https://www.phpboost.com/wiki/" target="_blank" rel="noopener">the PHPBoost documentation</a></div> or to ask your questions on the <div class="pinned bgc-full link-color"><a class="offload" href="https://www.phpboost.com/forum/" target="_blank" rel="noopener">support forum</a></div>.
    <br />
    <br />
    <p class="float-right">The PHPBoost Team thanks you for using its software to create your Web site!</p>

    <h2>Welcome to the website :module_name module.</h2>
	A category and a page have been created. Here are some tips to get started on this module.
	<br />
	<ul class="formatter-ul">
		<li class="formatter-li"> To configure or customize the module homepage your module, go into the <a class="offload" href="' . ModulesUrlBuilder::configuration('pages')->relative() . '">module administration</a></li>
		<li class="formatter-li"> To create categories, <a class="offload" href="' . CategoriesUrlBuilder::add(Category::ROOT_CATEGORY, 'pages')->relative() . '">clic here</a> </li>
		<li class="formatter-li"> To create :items, <a class="offload" href="' . ItemsUrlBuilder::add(Category::ROOT_CATEGORY, 'pages')->relative() . '">clic here</a></li>
	</ul>
	To learn more, feel free to consult the documentation for the module on <a class="offload" href="https://www.phpboost.com/wiki/pages">PHPBoost</a> website.
';
?>
