# IF C_HORIZONTAL #
	<div id="module-mini-smallads" class="cell-mini cell-tile# IF C_HIDDEN_WITH_SMALL_SCREENS # hidden-small-screens# ENDIF #">
		<div class="cell">
			<div class="cell-header">
				<h6 class="cell-name">{@smallads.mini.last.items}</h6>
			</div>
# ENDIF #
# IF C_ITEMS #
	<div class="cell-body">
		<div class="cell-content">
			# IF C_ONE_ITEM #{@smallads.mini.there.is}# ELSE #{@smallads.mini.there.are}# ENDIF # {ITEMS_TOTAL_NB} # IF C_ONE_ITEM #{@smallads.mini.one.item}# ELSE #{@smallads.mini.several.items}# ENDIF #
		</div>
	</div>
	<div class="relative-container cell-smallads">
		<ul id="smallads-flexisel">
            # START items #
				<li class="category-{items.ID_CATEGORY}">
					<a
							itemprop="url"
							href="# IF items.C_COMPLETED ### ELSE #{items.U_ITEM}# ENDIF #"
							class="offload flexisel-thumbnail # IF items.C_NEW_CONTENT # new-content# ENDIF ## IF items.C_COMPLETED # completed-smallad# ENDIF #"
							style="background-image: url(# IF items.C_HAS_THUMBNAIL #{items.U_THUMBNAIL}# ELSE #{PATH_TO_ROOT}/smallads/templates/images/no-thumb.webp# ENDIF #)">
						# IF items.C_COMPLETED #<span class="completed-item"><span>{@common.status.finished}</span></span># ENDIF #
						<div class="smallads-mini-infos">
							# IF items.C_PRICE #{items.PRICE} {CURRENCY}# ENDIF #
							<h6>{items.TITLE}</h6>
							<span class="more">{items.SMALLAD_TYPE} - <i class="fa fa-fw fa-calendar-alt" aria-hidden="true"></i> <time datetime="# IF NOT items.C_DIFFERED #{items.DATE_ISO8601}# ELSE #{items.PUBLISHING_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF NOT items.C_DIFFERED #{items.DATE_RELATIVE}# ELSE #{items.PUBLISHING_START_DATE_RELATIVE}# ENDIF #</time></span>
						</div>
					</a>
				</li>
            # END items #
        </ul>
	</div>
# ELSE #
	<div class="cell-body">
		<div class="cell-content">
			{@smallads.mini.no.item}
		</div>
	</div>
# ENDIF #
# IF C_HORIZONTAL #
		</div>
	</div>
# ENDIF #
# IF C_ITEMS #
	<script src="{PATH_TO_ROOT}/smallads/templates/js/flexisel.js"></script>
	<script>
		jQuery("#smallads-flexisel").flexisel({
			# IF C_HORIZONTAL #
				visibleItems: 4,
			# ELSE #
				visibleItems: 1,
			# ENDIF #
			animationSpeed: {ANIMATION_SPEED},
			autoPlay: ${escapejs(AUTOPLAY)},
			autoPlaySpeed: {AUTOPLAY_SPEED},
			pauseOnHover: ${escapejs(AUTOPLAY_HOVER)},
			enableResponsiveBreakpoints: true,
			# IF C_HORIZONTAL #
				responsiveBreakpoints: {
					portrait: {
					changePoint:480,
					visibleItems: 1
					},
					landscape: {
					changePoint:640,
					visibleItems: 2
					},
					tablet: {
					changePoint:768,
					visibleItems: 3
					}
				}
			# ENDIF #
		});
	</script>
# ENDIF #
