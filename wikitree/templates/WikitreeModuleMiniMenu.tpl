<div class="cell-body# IF C_HORIZONTAL # inline-nav# ENDIF #">
	# IF C_ROOT_ITEMS #
		<nav id="wiki-root" class="cssmenu # IF C_VERTICAL #cssmenu-vertical# ELSE #cssmenu-horizontal# ENDIF ## IF C_LEFT # cssmenu-left# ENDIF ## IF C_RIGHT # cssmenu-right# ENDIF #">
			<ul class="level-0">
				<li class="wiki-root# IF C_ROOT_ITEMS # has-sub# ENDIF #">
					<span class="cssmenu-title offload">{@wikitree.no.categories}</span>
					<ul class="level-1">
						# START root_items #
							<li>
								<a class="cssmenu-title offload" href="{PATH_TO_ROOT}/wiki/{root_items.U_ITEM}">{root_items.ITEM_TITLE}</a>
							</li>
						# END root_items #
					</ul>
				</li>
			</ul>
		</nav>
	# ENDIF #
	# IF C_CATEGORIES #
		<nav id="wiki-nav" class="cssmenu # IF C_VERTICAL #cssmenu-vertical# ELSE #cssmenu-horizontal# ENDIF ## IF C_LEFT # cssmenu-left# ENDIF ## IF C_RIGHT # cssmenu-right# ENDIF #">
			<ul>
				# START categories #
					<li
							data-wiki-id="{categories.CATEGORY_ID}"
							data-wiki-parent-id="{categories.CATEGORY_PARENT_ID}"
							data-wiki-order-id="{categories.CATEGORY_ARTICLE_ID}"
							class="sub-cat# IF categories.C_ITEMS # has-sub# ENDIF #">
						<a
								class="cssmenu-title offload"
								href="{PATH_TO_ROOT}/wiki/{categories.U_CATEGORY}">
							<i class="far fa-folder"></i>
							<span>{categories.CATEGORY_NAME}</span>
						</a>
						# IF categories.C_ITEMS #
							<ul class="items-list">
								# START categories.items #
									<li>
										<a class="cssmenu-title offload" href="{PATH_TO_ROOT}/wiki/{categories.items.U_ITEM}">
											<i class="far fa-file"></i>
											<span>{categories.items.ITEM_TITLE}</span>
										</a>
									</li>
								# END categories.items #
							</ul>
						# ENDIF #
					</li>
				# END categories #
			</ul>
		</nav>
		# IF C_CATEGORIES #
			<script src="{PATH_TO_ROOT}/wikitree/templates/js/wikitree# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js" defer></script>
		# ENDIF #
		<script>
			jQuery(document).ready(function () {
				# IF C_ROOT_ITEMS #
			    	jQuery("#wiki-root").menumaker({title: ${escapejs(@common.root)}, format: "multitoggle", breakpoint: 768});
				# ENDIF #
			    # IF C_CATEGORIES #
					jQuery("#wiki-nav").menumaker({title: ${escapejs(@wikitree.menu.title)}, format: "multitoggle", breakpoint: 768});
				# ENDIF #
		    });
		</script>
	# ELSE #
		<div class="cell-content">
			<div class="message-helper bgc notice">{@common.no.item.now}</div>
		</div>
	# ENDIF #

</div>
