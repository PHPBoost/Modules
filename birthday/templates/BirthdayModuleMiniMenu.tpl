# IF C_BIRTHDAY #
	<div class="cell-tile cell-mini# IF C_VERTICAL # cell-mini-vertical# ENDIF ## IF C_HIDDEN_WITH_SMALL_SCREENS # hidden-small-screens# ENDIF #">
		<div class="cell">
			<div class="cell-header">
				<h6 class="cell-name">{@birthday.happy.birthday}</h6>
			</div>
			<div class="cell-list cell-list-inline">
				<ul>
					# START birthday #
						<li>
							<a href="{birthday.U_USER_PROFILE}" class="{birthday.USER_LEVEL_CLASS}" # IF birthday.C_USER_GROUP_COLOR # style="color:{birthday.USER_GROUP_COLOR}" # ENDIF #>{birthday.LOGIN}</a># IF C_DISPLAY_MEMBERS_AGE # ({birthday.AGE})# ENDIF #
						</li>
					# END birthday #
				</ul>
			</div>
		</div>
	</div>
# ENDIF #
