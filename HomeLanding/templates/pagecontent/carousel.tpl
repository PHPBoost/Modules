<div class="sub-section" style="order: {CAROUSEL_POSITION};">
	<div class="content-container">
		<div class="content">
			<div id="home-slideboost">
				# START items #
					# IF items.C_LINK_ONLY #
						<a class="offload" href="{items.LINK}">
							<figure>
								<img class="slideImage" src="{items.U_DEFAULT_PICTURE}" alt="{@carousel.no.alt}" />
							</figure>
						</a>
					# ELSE #
						# IF items.LINK #<a class="offload" href="{items.LINK}"># ENDIF #
							<figure>
								# IF items.DESCRIPTION #
									<figcaption>
										{items.DESCRIPTION}
									</figcaption>
								# ENDIF #
								# IF items.U_PICTURE #
									<img class="slideImage" src="{items.U_PICTURE}" alt="# IF items.DESCRIPTION #{items.DESCRIPTION}# ELSE #{@carousel.no.alt}# ENDIF #" />
								# ENDIF #
							</figure>
						# IF items.LINK #</a># ENDIF #
					# ENDIF #
				# END items #
			</div>
		</div>
	</div>
</div>

<script>
	$('#home-slideboost').addClass('owl-carousel').owlCarousel({
		autoplay: true,
		autoplayTimeout: ${escapejs(CAROUSEL_TIME)},
		smartSpeed: ${escapejs(CAROUSEL_SPEED)},
		loop: ${escapejs(CAROUSEL_AUTO)},
		margin: 14,
		autoplayHoverPause: ${escapejs(CAROUSEL_HOVER)},
		responsive: {
			0: { items: 1},
			768: { items: 2},
			1024: { items: ${escapejs(CAROUSEL_NUMBER)}}
		}
	})
	;
</script>
