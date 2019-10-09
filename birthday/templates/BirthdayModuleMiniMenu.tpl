# IF C_BIRTHDAY #
<div class="module-mini-container"# IF C_HORIZONTAL # style="width:auto;"# ENDIF #>
	<div class="module-mini-top">
		<div class="sub-title">{@birthday.happy.birthday}</div>
	</div>
	<div class="module-mini-contents">
		<div class="spacer">&nbsp;</div>
		# IF C_VERTICAL #
		<ul class="users-birthday-vertical">
			# START birthday #
			<li><a href="{birthday.U_USER_PROFILE}" class="{birthday.USER_LEVEL_CLASS}" # IF birthday.C_USER_GROUP_COLOR # style="color:{birthday.USER_GROUP_COLOR}" # ENDIF #>{birthday.LOGIN}</a># IF C_DISPLAY_MEMBERS_AGE # ({birthday.AGE})# ENDIF #</li>
			# END birthday #
		</ul>
		# ELSE #
			<p class="center">
			# START birthday #
			<span class="users-birthday-horizontal"><a href="{birthday.U_USER_PROFILE}" class="{birthday.USER_LEVEL_CLASS}" # IF birthday.C_USER_GROUP_COLOR # style="color:{birthday.USER_GROUP_COLOR}" # ENDIF #>{birthday.LOGIN}</a># IF C_DISPLAY_MEMBERS_AGE # ({birthday.AGE})# ENDIF #</span>
			# END birthday #
			</p>
		# ENDIF #
	</div>
	<div class="module-mini-bottom"></div>
</div>
# ENDIF #
