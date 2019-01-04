<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 12 24
 * @since   	PHPBoost 5.0 - 2016 02 18
 * @contributor mipel <mipel@phpboost.com>
*/

####################################################
#                       English                    #
####################################################

$lang['root_category_description'] = 'Welcome on the FAQ!
<br /><br />
A few quotes were created to show you how this module works. Here are some tips to get started on this module.
<br /><br />
<ul class="formatter-ul">
	<li class="formatter-li"> To configure or customize the module homepage your module, go into the <a href="' . QuotesUrlBuilder::configuration()->relative() . '">module administration</a></li>
	<li class="formatter-li"> To create categories, <a href="' . QuotesUrlBuilder::add_category()->relative() . '">clic here</a></li>
	<li class="formatter-li"> To create quotes, <a href="' . QuotesUrlBuilder::add()-> relative() . '">clic here</a></li>
</ul>
<br />To learn more, don \'t hesitate to consult the documentation for the module on <a href="https://www.phpboost.com">PHPBoost</a> website.';
?>
