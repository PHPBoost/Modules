# IF C_SMALLADS #
	# IF C_HORIZONTAL #
		<div class="block-container">
			<div class="block-content">
				<div class="sub-title">
					<h6>{@mini.last.smallads}</h6>
				</div>
	# ENDIF #
		# IF C_ONE_SMALLAD #{@mini.there.is}# ELSE #{@mini.there.are}# ENDIF # {SMALLADS_TOTAL_NB} # IF C_ONE_SMALLAD #{@mini.one.smallad}# ELSE #{@mini.several.smallads}# ENDIF #
		<div class="relative-container">
			<ul id="smallads-flexisel">
	            # START items #
				<li>
	        		<a
						itemprop="url"
						href="# IF items.C_COMPLETED ### ELSE #{items.U_ITEM}# ENDIF #"
						class="flexisel-thumbnail # IF items.C_NEW_CONTENT # new-content# ENDIF ## IF items.C_COMPLETED # completed-smallad# ENDIF #"
						style="background-image: url(# IF items.C_HAS_THUMBNAIL #{items.THUMBNAIL}# ELSE #{PATH_TO_ROOT}/smallads/templates/images/no-thumb.png# ENDIF #)">
						# IF items.C_COMPLETED #<span class="completed-item"><span>{@smallads.completed.item}</span></span># ENDIF #
						<div class="smallads-mini-infos">
							# IF items.C_PRICE #{items.PRICE} {CURRENCY}# ENDIF #
							<h6><p>{items.TITLE}</p></h6>
							<span class="more">{items.SMALLAD_TYPE} - <i class="fa fa-fw fa-calendar"></i> <time datetime="# IF NOT items.C_DIFFERED #{items.DATE_ISO8601}# ELSE #{items.PUBLICATION_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF NOT items.C_DIFFERED #{items.DATE_RELATIVE}# ELSE #{items.PUBLICATION_START_DATE_RELATIVE}# ENDIF #</time></span>
						</div>
					</a>
				</li>
	            # END items #
	        </ul>
		</div>
	# IF C_HORIZONTAL #
			</div>
		</div>
	# ENDIF #
# ELSE #
	{@mini.no.smallad}
# ENDIF #

# IF C_SMALLADS #
	<script src="{PATH_TO_ROOT}/smallads/templates/js/flexisel.js"></script>
	<script>
		$("#smallads-flexisel").flexisel({
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
