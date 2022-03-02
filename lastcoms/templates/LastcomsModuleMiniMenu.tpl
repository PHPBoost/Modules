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
						<div class="flex-between">
							<time class="pinned notice small item-creation-date" datetime="{items.DATE_ISO8601}" itemprop="datePublished">{items.DATE_DELAY}</time>
							<span class="pinned notice small item-module">{items.MODULE_NAME}</span>
						</div>
						<p>
							<span class="item-author"># IF items.C_AUTHOR_EXISTS #<a class="{items.AUTHOR_LEVEL_CLASS} offload" itemprop="author" href="{items.U_AUTHOR_PROFILE}"# IF items.C_AUTHOR_GROUP_COLOR # style="color:{items.AUTHOR_GROUP_COLOR}"# ENDIF #>{items.AUTHOR_DISPLAY_NAME}</a># ELSE #{items.AUTHOR_DISPLAY_NAME}# ENDIF #</span>
						 	: <a aria-label="{@lastcoms.see.comment}" class="offload" href="{items.PATH}">{items.CONTENT}</a>
					 	</p>
					</li>
				# END items #
			</ul>
		</div>
	# ELSE #
		<div class="cell-alert">
			<div class="message-helper bgc notice">
				{@lastcoms.no.item}
			</div>
		</div>
	# ENDIF #
# IF C_HORIZONTAL #
		</div>
	</div>
# ENDIF #
