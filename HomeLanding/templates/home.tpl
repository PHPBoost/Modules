<section id="module-homelanding" class="several-items">
	<header class="section-header" style="order: 0;">
		<h1>{MODULE_TITLE}</h1>
	</header>

	# INCLUDE ANCHORS_MENU #

	# INCLUDE CAROUSEL #

	# IF C_EDITO_ENABLED #
		<div class="sub-section" style="order: {EDITO_POSITION};">
			<div class="content-container">
				<article id="edito-panel">
					<div class="content">
						{EDITO}
						<div class="spacer"></div>
					</div>
				</article>
			</div>
		</div>
	# ENDIF #

	# INCLUDE LASTCOMS #


	# INCLUDE ARTICLES #

	# INCLUDE ARTICLES_CAT #


	# INCLUDE CALENDAR #


	# INCLUDE CONTACT #


	# INCLUDE DOWNLOAD #

	# INCLUDE DOWNLOAD_CAT #


	# INCLUDE FLUX #


	# INCLUDE FORUM #


	# INCLUDE GALLERY #


	# INCLUDE GUESTBOOK #


	# INCLUDE MEDIA #


	# INCLUDE NEWS #

	# INCLUDE NEWS_CAT #

	# INCLUDE PINNED_NEWS #


	# INCLUDE SMALLADS #

	# INCLUDE SMALLADS_CAT #


	# INCLUDE  WEB #

	# INCLUDE  WEB_CAT #

	<!-- Additional modules -->

	<footer style="order: 9999;"></footer>
</section>

<script>
	jQuery('document').ready(function(){
		listorder.init();
	});
</script>
