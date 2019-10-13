# IF C_NEWS #
	# IF C_NO_CAT #
		{@newscat.no.news.cat}
	# ELSE #
	<nav id="newscat-list" class="cssmenu # IF C_MENU_VERTICAL #cssmenu-vertical# ELSE #cssmenu-horizontal# ENDIF ## IF C_MENU_LEFT # cssmenu-left# ENDIF ## IF C_MENU_RIGHT # cssmenu-right# ENDIF #">
		<ul>
			# START items #
				<li newscat_id="{items.ID}" newscat_parent_id="{items.ID_PARENT}" newscat_c_order="{items.SUB_ORDER}" class="">
					<a href="{items.U_CATEGORY}" class="cssmenu-title">{items.CATEGORY_NAME}</a>
				</li>
			# END items #
		</ul>
	</nav>


	<script>
		jQuery(document).ready(function () {
			// Sort order categories
			jQuery('#newscat-list').append(CreatChild(0)).find('ul:first').remove();
			function CreatChild(id){
			    var $li = jQuery('li[newscat_parent_id=' + id + ']').sort(function(a, b){
					return jQuery(a).attr('newscat_c_order') - jQuery(b).attr('newscat_c_order');
				});
			    if($li.length > 0){
			        for(var i = 0; i < $li.length; i++){
			            var $this = $li.eq(i);
						// $this[0].remove();
			            $this.append(CreatChild($this.attr('newscat_id')));
			        }
			        return jQuery('<ul class="newscat-ul">').append($li);
			    }
			}
			jQuery('li').has('ul.newscat-ul').addClass('has-sub');
		});
	</script>
	# ENDIF #
# ELSE #
	{@newscat.not.installed}
# ENDIF #
