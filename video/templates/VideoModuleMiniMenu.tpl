# IF C_ITEMS #
	<div id="video-slide">
		# START items #
			<div class="cell no-style">
				<div class="cell-thumbnail">
					# IF items.C_HAS_THUMBNAIL #<img src="{items.U_THUMBNAIL}" alt="{items.TITLE}"># ENDIF #
					<a href="{items.U_ITEM}" class="cell-thumbnail-caption offload" aria-label="{@video.watch}">
						<i class="fa fa-play" aria-hidden="true"></i>
					</a>
				</div>
				<div class="cell-header">
					<h6 class="cell-name">
						<a href="{items.U_ITEM}" class="offload">{items.TITLE}</a>
					</h6>
				</div>
				<div class="cell-infos small">
					<time datetime="{items.DATE_ISO8601}">{items.DATE_AGO}</time>
					# IF C_ENABLED_VIEWS_NUMBER #<span aria-label="{@common.views.number}"><i class="fa fa-eye" aria-hidden="true"></i> {items.VIEWS_NUMBER}</span># ENDIF #
					# IF C_ENABLED_NOTATION #{items.STATIC_NOTATION}# ENDIF #
				</div>
			</div>
		# END items #
	</div>
    <div class="cell-body">
        <div class="cell-content align-center"><a href="${relative_url(VideoUrlBuilder::home())}" class="offload button small">{@video.more.videos}</a></div>
    </div>
	<script>
		jQuery('#video-slide')
			.addClass('owl-carousel')
			.owlCarousel({
				autoplay: true,
				autoplayTimeout: 3500,
				loop: true,
				margin: 15,
				smartSpeed: 1000,
				autoplayHoverPause: true,
				# IF C_HORIZONTAL #
					responsive: {
						0: { items: 1 },
						769: { items: 2 },
						1025: { items: 3 }
					}
				# ELSE #
					responsive: {
						0: { items: 1 }
					}
				# ENDIF #
		});
	</script>
# ELSE #
	<div class="cell-alert">
		<div class="message-helper bgc notice">{@common.no.item.now}</div>
	</div>
# ENDIF #
