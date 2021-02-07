<article id="home-slideboost" class="sub-section" style="order: {CAROUSEL_POSITION};">
	# START items #
		<figure>
			# IF items.DESCRIPTION #
			<figcaption>
				# IF items.LINK #<a href="{items.LINK}"># ENDIF #
					{items.DESCRIPTION}
				# IF items.LINK #</a># ENDIF #
			</figcaption>
			# ENDIF #
			<img class="slideImage" src="{items.PICTURE_URL}" alt="{items.PICTURE_URL}" />
		</figure>
	# END items #
</article>

<script>
	$('#home-slideboost').addClass('owl-carousel').owlCarousel({
		autoplay: true,
		autoplayTimeout: ${escapejs(CAROUSEL_TIME)},
		smartSpeed: ${escapejs(CAROUSEL_SPEED)},
		loop: ${escapejs(CAROUSEL_AUTO)},
		margin: 15,
		autoplayHoverPause: ${escapejs(CAROUSEL_HOVER)},
		responsive: {
			0: { items: 1},
			768: { items: 2},
			1024: { items: ${escapejs(CAROUSEL_NUMBER)}}
		}
	})
	;
</script>
