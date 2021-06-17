# IF C_HORIZONTAL #
	<div id="module-mini-lastcoms" class="cell-mini cell-tile# IF C_HIDDEN_WITH_SMALL_SCREENS # hidden-small-screens# ENDIF #">
		<div class="cell">
			<div class="cell-header">
				<h6 class="cell-name">
					{@lastcoms.module.title}
				</h6>
			</div>
# ENDIF #
	# IF C_COMS #
			<div class="cell-list">
				<ul class="lastcoms# IF C_HORIZONTAL # lastcoms-horizontal# ENDIF #">
					# START items #
						<li>
							<time class="pinned notice small" datetime="{items.DATE_ISO8601}" itemprop="datePublished">{items.DATE_DELAY}</time>
							# IF items.C_AUTHOR_EXISTS #<a class="{items.AUTHOR_LEVEL_CLASS} offload" href="{items.U_AUTHOR_PROFILE}"# IF items.C_AUTHOR_GROUP_COLOR # style="color:{items.AUTHOR_GROUP_COLOR}"# ENDIF #>{items.AUTHOR_DISPLAY_NAME}</a># ELSE #{items.AUTHOR_DISPLAY_NAME}# ENDIF #
							<p aria-label="{@lastcoms.see.comment}"><a class="offload" href="{items.PATH}"><i class="far fa-comment" aria-hidden="true"></i> {items.CONTENT}</a></p>
						</li>
					# END items #
				</ul>
			</div>
	# ELSE #
		{@lastcoms.no.item}
	# ENDIF #
# IF C_HORIZONTAL #
		</div>
	</div>
# ENDIF #
