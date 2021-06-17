<div class="sub-section" style="order: {MODULE_POSITION};">
	<div class="content-container">
		<article id="{MODULE_NAME}-panel">
			<header class="module-header flex-between">
				<h2>
					{L_MODULE_TITLE}
				</h2>
				<div class="controls align-right">
					<a class="offload" href="{PATH_TO_ROOT}/{MODULE_NAME}" aria-label="{@homelanding.see.module}"><i class="fa fa-share-square" aria-hidden="true"></i></a>
				</div>
			</header>

			# IF C_NO_ITEM #
				<div class="content">
					<div class="message-helper bgc notice">
						{@common.no.item.now}
					</div>
				</div>
			# ELSE #
				<div class="content">
					# START media_host #
						<div class="item-content" itemscope="itemscope" itemtype="https://schema.org/CreativeWork">
							<h3><a class="offload" href="{media_host.U_ITEM}">{media_host.TITLE}</a></h3>
							<div class="more">
								<span class="pinned"><i class="fa fa-fw fa-user"></i> {media_host.PSEUDO}</span>
								<span class="pinned"><i class="far fa-fw fa-calendar-alt"></i> {media_host.DATE}</span>
							</div>
							<div class="media-content media-host" style="width: {media_host.WIDTH}px; height: {media_host.HEIGHT}px">
								<iframe class="media-player" type="text/html" src="{media_host.PLAYER}{media_host.MEDIA_ID}" frameborder="0" allowfullscreen></iframe>
							</div>
						</div>
					# END media_host #

					# START media_mp4 #
						<div class="item-content" itemscope="itemscope" itemtype="https://schema.org/CreativeWork">
							<h3><a class="offload" href="{media_mp4.U_ITEM}">{media_mp4.TITLE}</a></h3>
							<div class="more">
								<span class="pinned"><i class="fa fa-fw fa-user"></i> {media_mp4.PSEUDO}</span>
								<span class="pinned"><i class="far fa-fw fa-calendar-alt"></i> {media_mp4.DATE}</span>
							</div>
							<div class="media-content media-html5" style="width:{media_mp4.WIDTH}px;height:{media_mp4.HEIGHT}px;">
								<video class="video-player"# IF media_mp4.C_POSTER # poster="{media_mp4.POSTER}"# ENDIF # controls>
									<source src="{media_mp4.FILE_URL}" type="{media_mp4.MIME}" />
								</video>
							</div>
						</div>
					# END media_mp4 #

					# START media_mp3 #
						<div class="item-content-audio" itemscope="itemscope" itemtype="https://schema.org/CreativeWork">
							<h3><a class="offload" href="{media_mp3.U_ITEM}">{media_mp3.TITLE}</a></h3>
							<div class="more">
								<span class="pinned"><i class="fa fa-fw fa-user"></i> {media_mp3.PSEUDO}</span>
								<span class="pinned"><i class="far fa-fw fa-calendar-alt"></i> {media_mp3.DATE}</span>
							</div>

							<div class="media-content-audio" id="media_mp3-{media_mp3.ID}">
								<audio controls>
									<source src="{media_mp3.FILE_URL}" type="{media_mp3.MIME}"></source>
								</audio>
							</div>
						</div>
					# END media_mp3 #

					# START media_other #
						<div class="item-content" itemscope="itemscope" itemtype="https://schema.org/CreativeWork">
							<h3><a class="offload" href="{media_other.U_ITEM}">{media_other.TITLE}</a></h3>
							<div class="more">
								<span class="pinned"><i class="fa fa-fw fa-user"></i> {media_other.PSEUDO}</span>
								<span class="pinned"><i class="far fa-fw fa-calendar-alt"></i> {media_other.DATE}</span>
							</div>

							<div class="media-content media-other" id="media_other-{media_other.ID}" style="width:{media_other.WIDTH}px;height:{media_other.HEIGHT}px;">
								<object type="{media_other.MIME}" data="{media_other.FILE_URL}">
									<param name="allowScriptAccess" value="samedomain" />
									<param name="allowFullScreen" value="true">
									<param name="play" value="true" />
									<param name="movie" value="{media_other.FILE_URL}" />
									<param name="menu" value="false" />
									<param name="quality" value="high" />
									<param name="scalemode" value="noborder" />
									<param name="wmode" value="transparent" />
									<param name="bgcolor" value="#000000" />
								</object>
							</div>
						</div>
					# END media_other #

				</div>
			# ENDIF #
			<footer></footer>
		</article>

	</div>
</div>
<script>
	// for several mp4 players only - pause running video when start another
	document.addEventListener('play', function(e){
		var videos = document.getElementsByTagName('video');
		for(var i = 0, len = videos.length; i < len;i++){
			if(videos[i] != e.target){
				videos[i].pause();
			}
		}
	}, true);
</script>
