# IF C_ITEMS #
	<div class="cell-list cell-list-inline">
		<ul>
			# START items #
				<li aria-label="{items.MODULE_NAME} ({items.TAGS_NUMBER})">
					<a id="tag-{items.ID}-{items.ID_IN_MODULE}" class="offload" href="{items.U_TAG}">
						<i class="small {items.MODULE_ICON}"></i><span class="tag-name">{items.NAME}</span>
					</a>
				</li>
				<script>
					var fontSize = (1 + ({items.TAGS_NUMBER} / 10)) + 'em';
					jQuery('#tag-{items.ID}-{items.ID_IN_MODULE}').css('font-size', fontSize);
				</script>
			# END items #
		</ul>
	</div>
	<script src="{PATH_TO_ROOT}/tagcloud/templates/js/tagcloud# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>
# ELSE #
	<div class="cell-alert">
		<div class="message-helper bgc notice">{@common.no.item.now}</div>
	</div>
# ENDIF #

