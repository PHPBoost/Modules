<div id="{MODULE_ID}" class="cell-mini cell-tile# IF C_MENU_VERTICAL # cell-mini-vertical# ENDIF ## IF C_HIDDEN_WITH_SMALL_SCREENS # hidden-small-screens# ENDIF #">
	<div class="cell">
		<div class="cell-header">
			<h6 class="cell-name">{MODULE_TITLE}</h6>
		</div>
		# IF C_NEWS #
			<div class="cell-body">
				# IF C_CAT #
					<nav id="newscat-list" class="cssmenu # IF C_MENU_VERTICAL #cssmenu-vertical# ELSE #cssmenu-horizontal# ENDIF ## IF C_MENU_LEFT # cssmenu-left# ENDIF ## IF C_MENU_RIGHT # cssmenu-right# ENDIF #">
						<ul>
							# START items #
								<li data-id-cat="{items.ID}" data-parent-id="{items.ID_PARENT}" data-c-order="{items.SUB_ORDER}" class="category-{items.ID}">
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
							    var $li = jQuery('li[data-parent-id=' + id + ']').sort(function(a, b){
									return jQuery(a).attr('data-c-order') - jQuery(b).attr('data-c-order');
								});
							    if($li.length > 0){
							        for(var i = 0; i < $li.length; i++){
							            var $this = $li.eq(i);
										// $this[0].remove();
							            $this.append(CreatChild($this.attr('data-id-cat')));
							        }
							        return jQuery('<ul class="newscat-ul">').append($li);
							    }
							}
							// Add sub-menu icon
							jQuery('li').has('ul.newscat-ul').addClass('has-sub');
						});
					</script>
					<script>jQuery("#newscat-list").menumaker({ title: "{MODULE_TITLE}", format: "multitoggle", breakpoint: 768}); </script>
				# ELSE #
					<div class="cell-content align-center">
						{@newscat.no.news.cat}
					</div>
				# ENDIF #
			</div>
		# ELSE #
			<div class="cell-body">
				<div class="cell-content align-center">{@newscat.not.installed}</div>
			</div>
		# ENDIF #
	</div>
</div>
