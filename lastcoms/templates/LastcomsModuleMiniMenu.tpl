# IF C_COMS #
	# IF C_HORIZONTAL #
		<div class="block-container">
			<div class="block-content">
				<div class="sub-title">
					{@lastcoms.title}
				</div>
	# ENDIF #
				<ul class="lastcoms# IF C_HORIZONTAL # lastcoms-horizontal# ENDIF #">
					# START coms #
					<li>
						# IF coms.C_AUTHOR_EXIST #<a class="{coms.USER_LEVEL_CLASS}" href="{coms.U_AUTHOR_PROFILE}"# IF coms.C_USER_GROUP_COLOR # style="color:{coms.USER_GROUP_COLOR}"# ENDIF #>{coms.PSEUDO}</a># ELSE #{coms.PSEUDO}# ENDIF #
						<span class="small">{coms.DATE}</span>
						<p><a href="{coms.PATH}">{coms.COM_CONTENT}{coms.ETC}</a></p>
					</li>
					# END coms #
				</ul>
	# IF C_HORIZONTAL #
			</div>
		</div>
	# ENDIF #
# ELSE #
	{@lastcoms.no.com}
# ENDIF #
