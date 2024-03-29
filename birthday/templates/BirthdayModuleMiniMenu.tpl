# IF C_BIRTHDAY_ENABLED #
	<div class="cell-list cell-list-inline">
		<span class="text-italic small pinned">{@date.today}</span>
		<ul>
			# IF C_HAS_BIRTHDAY #
				# START birthday #
					<li>
						<a href="{birthday.U_USER_PROFILE}" class="{birthday.USER_LEVEL_CLASS} offload"# IF birthday.C_USER_GROUP_COLOR # style="color:{birthday.USER_GROUP_COLOR}" # ENDIF #>
							{birthday.LOGIN}
						</a>
						# IF C_DISPLAY_MEMBERS_AGE # ({birthday.AGE} {@birthday.years.old})# ENDIF #
					</li>
				# END birthday #
			# ELSE #
				<li>{@common.nobody}</li>
			# ENDIF #
		</ul>
	</div>
	<div class="cell-list cell-list-inline">
        <div class="flex-between">
            <span class="text-italic small pinned">{L_COMING_NEXT}</span>
            <span class="text-italic small pinned">({UPCOMING_BIRTHDAYS_NB})</span>
        </div>
		<ul class="d-block">
			# IF C_UPCOMING_BIRTHDAYS #
				# START upcoming_birthdays #
					<li class="flex-between">
						<a href="{upcoming_birthdays.U_USER_PROFILE}" class="{upcoming_birthdays.USER_LEVEL_CLASS} offload"# IF upcoming_birthdays.C_USER_GROUP_COLOR # style="color:{upcoming_birthdays.USER_GROUP_COLOR}" # ENDIF #>
							{upcoming_birthdays.LOGIN}
						</a>
						# IF C_COMING_NEXT #<span class="text-italic small">({upcoming_birthdays.BIRTHDATE}# IF C_DISPLAY_MEMBERS_AGE # - {upcoming_birthdays.AGE} {@birthday.years.old}# ENDIF #)</span># ENDIF #
					</li>
				# END upcoming_birthdays #
			# ELSE #
				<li>{@common.nobody}</li>
			# ENDIF #
		</ul>
	</div>
# ELSE #
	# IF IS_ADMIN #
		<div class="cell-alert">
			<div class="message-helper bgc warning">
				{@H|birthday.user.born.field.disabled}
			</div>
		</div>
	# ENDIF #
# ENDIF #
