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
					<div class="accordion-container basic">
						<div class="accordion-controls">
							<span class="open-all-accordions" aria-label="{@common.open.panels}"><i
									class="fa fa-fw fa-chevron-down"></i></span>
							<span class="close-all-accordions" aria-label="{@common.close.panels}"><i
									class="fa fa-fw fa-chevron-up"></i></span>
						</div>
						<nav id="lorem" class="accordion-nav">
							<ul>
								<li><a href="#" data-accordion data-target="monday">{@date.monday}</a></li>
								<li><a href="#" data-accordion data-target="tuesday">{@date.tuesday}</a></li>
								<li><a href="#" data-accordion data-target="wednesday">{@date.wednesday}</a></li>
								<li><a href="#" data-accordion data-target="thursday">{@date.thursday}</a></li>
								<li><a href="#" data-accordion data-target="friday">{@date.friday}</a></li>
								<li><a href="#" data-accordion data-target="saturday">{@date.saturday}</a></li>
								<li><a href="#" data-accordion data-target="sunday">{@date.sunday}</a></li>
							</ul>
						</nav>
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
					<script>
						jQuery(document).ready(function(){
							var today = new Date();
							var weekdays = new Array(7);
							weekdays[0] = "sunday";
							weekdays[1] = "monday";
							weekdays[2] = "tuesday";
							weekdays[3] = "wednesday";
							weekdays[4] = "thursday";
							weekdays[5] = "friday";
							weekdays[6] = "saturday";
							var openedDay = weekdays[today.getDay()];
							jQuery('[data-target="' + openedDay + '"').addClass('active-tab')
						})
					</script>
				# ENDIF #

				# IF C_CALENDAR_VIEW #
					</div>
				# ENDIF #
			</article>
		</div>
	</div>

	<footer></footer>
</section>
