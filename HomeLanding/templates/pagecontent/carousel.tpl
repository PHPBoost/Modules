

<div id="home-slideboost" style="order: {CAROUSEL_POSITION};">
	# START item #
		<figure>
			# IF item.DESCRIPTION #
			<figcaption>
				# IF item.LINK #<a href="{item.LINK}"># ENDIF #
					{item.DESCRIPTION}
				# IF item.LINK #</a># ENDIF #
			</figcaption>
			# ENDIF #
			<img class="slideImage" src="{item.PICTURE_URL}" alt="{item.PICTURE_URL}" />
		</figure>
	# END item #
</div>

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
