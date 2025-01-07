<section id="module-broadcast">
	<header class="section-header">
		<div class="controls align-right">
			<a class="offload" href="${relative_url(SyndicationUrlBuilder::rss('broadcast', CATEGORY_ID))}" aria-label="{@common.syndication}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
			# IF NOT C_ROOT_CATEGORY #{MODULE_NAME}# ENDIF #
			# IF C_CATEGORY ## IF IS_ADMIN #<a class="offload" href="{U_EDIT_CATEGORY}" aria-label="{@common.edit}"><i class="far fa-edit" aria-hidden="true"></i></a># ENDIF ## ENDIF #
		</div>
		<h1>
			# IF C_PENDING_ITEMS #
				{@broadcast.pending.items}
			# ELSE #
				# IF C_MEMBER_ITEMS #
					# IF C_MY_ITEMS #{@my.items}# ELSE #{@member.items} {MEMBER_NAME}# ENDIF #
				# ELSE #
					{MODULE_NAME}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF #
				# ENDIF #
			# ENDIF #
		</h1>
	</header>
	<div class="sub-section">
		<div class="content-container">
			<article class="content" id="broadcast-calendar">

				# IF C_CALENDAR_VIEW #
					<div class="broadcast-flex">
				# ENDIF #
				# IF C_ACCORDION_VIEW #
					<div class="accordion-container">
				# ENDIF #

				# INCLUDE MONDAY_PRG #
				# INCLUDE TUESDAY_PRG #
				# INCLUDE WEDNESDAY_PRG #
				# INCLUDE THURSDAY_PRG #
				# INCLUDE FRIDAY_PRG #
				# INCLUDE SATURDAY_PRG #
				# INCLUDE SUNDAY_PRG #

				# IF C_ACCORDION_VIEW #
					</div>
				# ENDIF #

				# IF C_CALENDAR_VIEW #
					</div>
				# ENDIF #
			</article>
		</div>
	</div>

	<footer></footer>
</section>
