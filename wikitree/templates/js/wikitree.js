/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Xela <xela@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 02 19
 * @since       PHPBoost 5.1 - 2017 09 11
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

jQuery(document).ready(function () {
    jQuery('#wiki-nav').append(WikitreeCreateChild(0)).find('ul:first').remove();

	function WikitreeCreateChild(id){
		var $li = jQuery('li[data-wiki-parent-id="' + id + '"]').sort(function(a, b){
			return jQuery(a).attr('data-wiki-order-id') - jQuery(b).attr('data-wiki-order-id');
		});
		if($li.length > 0){
			for(var i = 0; i < $li.length; i++){
				var $this = $li.eq(i);
                $this.append(WikitreeCreateChild($this.attr('data-wiki-id')));
			}
            return jQuery('<ul class="level-' + id + '">').append($li);
		}
    }

    jQuery('#wiki-nav .items-list li').each(function() {
        var target = jQuery(this).parent().siblings('[class^="level-"]');
        if(target)
            target.prepend(jQuery(this));
    });
    jQuery('#wiki-nav li').has('ul').addClass('has-sub');
});
