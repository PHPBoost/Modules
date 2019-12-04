# IF C_HORIZONTAL #
	<div id="module-mini-lastcoms" class="cell-mini cell-tile# IF C_HIDDEN_WITH_SMALL_SCREENS # hidden-small-screens# ENDIF #">
		<div class="cell">
			<div class="cell-header">
				<h6 class="cell-name">
					{@lastcoms.title}
				</h6>
			</div>
# ENDIF #
	# IF C_COMS #
			<div class="cell-list">
				<ul class="lastcoms# IF C_HORIZONTAL # lastcoms-horizontal# ENDIF #">
					# START coms #
					<li>
						<span class="pinned notice small">{coms.DATE}</span>
						# IF coms.C_AUTHOR_EXIST #<a class="{coms.USER_LEVEL_CLASS}" href="{coms.U_AUTHOR_PROFILE}"# IF coms.C_USER_GROUP_COLOR # style="color:{coms.USER_GROUP_COLOR}"# ENDIF #>{coms.PSEUDO}</a># ELSE #{coms.PSEUDO}# ENDIF #
						<p><a href="{coms.PATH}"><i class="far fa-comment"></i> {coms.COM_CONTENT}{coms.ETC}</a></p>
					</li>
					# END coms #
				</ul>
			</div>
	# ELSE #
		{@lastcoms.no.com}
	# ENDIF #
# IF C_HORIZONTAL #
		</div>
	</div>
# ENDIF #
