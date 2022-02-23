# IF C_CATEGORIES #
	<div class="cell-body">
		<nav id="{MENU_ID}-list" class="cssmenu # IF C_VERTICAL #cssmenu-vertical# ELSE #cssmenu-horizontal# ENDIF ## IF C_LEFT # cssmenu-left# ENDIF ## IF C_RIGHT # cssmenu-right# ENDIF #">
			<ul>
				# START items #
					<li data-id="{items.ID}" data-parent-id="{items.ID_PARENT}" data-c-order="{items.SUB_ORDER}" class="{MENU_ID}-li category-{items.ID}">
						<a href="{items.U_CATEGORY}" class="cssmenu-title offload">{items.CATEGORY_NAME}</a>
					</li>
				# END items #
			</ul>
		</nav>

		<script>
			jQuery(document).ready(function () {
				// Sort order categories
				jQuery('\#{MENU_ID}-list').append({MODULE_ID}CreatChild(0)).find('ul:first').remove();
				function {MODULE_ID}CreatChild(id){
					var $li = jQuery('.{MENU_ID}-li[data-parent-id=' + id + ']').sort(function(a, b){
						return jQuery(a).attr('data-c-order') - jQuery(b).attr('data-c-order');
					});
					if($li.length > 0){
						for(var i = 0; i < $li.length; i++){
							var $this = $li.eq(i);
							$this.append({MODULE_ID}CreatChild($this.attr('data-id')));
						}
						return jQuery('<ul class="{MENU_ID}-ul">').append($li);
					}
				}
				// Add sub-menu icon
				jQuery('li').has('ul.{MENU_ID}-ul').addClass('has-sub');
				jQuery("\#{MENU_ID}-list").menumaker({title: "{MENU_TITLE}", format: "multitoggle", breakpoint: 768});
			});
		</script>
	</div>
# ELSE #
	<div class="cell-alert">
		<div class="message-helper bgc notice">{@category.no.element}</div>		
	</div>
# ENDIF #
