<div class="sub-section">
	<div class="content-container">
		<article id="{MODULE_NAME}-panel" style="order: {MODULE_POSITION};">
			<header>
				<h2>
					{L_MODULE_TITLE}
				</h2>
				<span class="controls align-right">
					<a href="{PATH_TO_ROOT}/{MODULE_NAME}">{L_SEE_ALL_ITEMS}</a>
				</span>
			</header>

			# IF C_NO_ITEM #
				<div class="message-helper bgc notice">
					${LangLoader::get_message('no_item_now', 'common')}
				</div>
			# ELSE #
				<div class="content">
					# START media_swf #
						<div class="item-content" itemscope="itemscope" itemtype="https://schema.org/CreativeWork">
							<h3><a href="{media_swf.U_MEDIA_LINK}">{media_swf.TITLE}</a></h3>
							<div class="more">
								<span class="pinned"><i class="fa fa-fw fa-user"></i> {media_swf.PSEUDO}
								<span class="pinned"><i class="far fa-fw fa-calendar-alt"></i> {media_swf.DATE}</span>
							</div>
							<div class="media-content media-swf" id="media_swf-{media_swf.ID}" style="width:{media_swf.WIDTH}px;height:{media_swf.HEIGHT}px;">
								<object type="{media_swf.MIME}" data="{media_swf.URL}">
									<param name="allowScriptAccess" value="samedomain" />
									<param name="allowFullScreen" value="true">
									<param name="play" value="true" />
									<param name="movie" value="{media_swf.URL}" />
									<param name="menu" value="false" />
									<param name="quality" value="high" />
									<param name="scalemode" value="noborder" />
									<param name="wmode" value="transparent" />
									<param name="bgcolor" value="#000000" />
								</object>
							</div>
						</div>
					# END media_swf #

					# START media_host #
						<div class="item-content" itemscope="itemscope" itemtype="https://schema.org/CreativeWork">
							<h3><a href="{media_host.U_MEDIA_LINK}">{media_host.TITLE}</a></h3>
							<div class="more">
								<span class="pinned"><i class="fa fa-fw fa-user"></i> {media_host.PSEUDO}
								<span class="pinned"><i class="far fa-fw fa-calendar-alt"></i> {media_host.DATE}</span>
							</div>
							<div class="media-content media-host" style="width: {media_host.WIDTH}px; height: {media_host.HEIGHT}px">
								<iframe class="youtube-player" type="text/html" src="{media_host.PLAYER}{media_host.MEDIA_ID}" frameborder="0" allowfullscreen></iframe>
							</div>
						</div>
					# END media_host #

					# START media_mp4 #
						<div class="item-content" itemscope="itemscope" itemtype="https://schema.org/CreativeWork">
							<h3><a href="{media_mp4.U_MEDIA_LINK}">{media_mp4.TITLE}</a></h3>
							<div class="more">
								<span class="pinned"><i class="fa fa-fw fa-user"></i> {media_mp4.PSEUDO}</span>
								<span class="pinned"><i class="far fa-fw fa-calendar-alt"></i> {media_mp4.DATE}</span>
							</div>
							<div class="media-content media-html5" style="width:{media_mp4.WIDTH}px;height:{media_mp4.HEIGHT}px;">
								<video class="video-player"# IF media_mp4.C_POSTER # poster="{media_mp4.POSTER}"# ENDIF # controls>
									<source src="{media_mp4.URL}" type="{media_mp4.MIME}" />
								</video>
							</div>
						</div>
					# END media_mp4 #

					# START media_mp3 #
						<div class="item-content-audio" itemscope="itemscope" itemtype="https://schema.org/CreativeWork">
							<h3><a href="{media_mp3.U_MEDIA_LINK}">{media_mp3.TITLE}</a></h3>
							<div class="more">
								<span class="pinned"><i class="fa fa-fw fa-user"></i> {media_mp3.PSEUDO}</span>
								<span class="pinned"><i class="far fa-fw fa-calendar-alt"></i> {media_mp3.DATE}</span>
							</div>

							<div class="media-content-audio" id="media_mp3-{media_mp3.ID}">
								<audio controls>
									<source src="{media_mp3.URL}" type="{media_mp3.MIME}"></source>
								</audio>
							</div>
						</div>
					# END media_mp3 #

					# START media_other #
						<div class="item-content" itemscope="itemscope" itemtype="https://schema.org/CreativeWork">
							<h3><a href="{media_other.U_MEDIA_LINK}">{media_other.TITLE}</a></h3>
							<div class="more">
								<span class="pinned"><i class="fa fa-fw fa-user"></i> {media_other.PSEUDO}</span>
								<span class="pinned"><i class="far fa-fw fa-calendar-alt"></i> {media_other.DATE}</span>
							</div>

							<div class="media-content media-other" id="media_other-{media_other.ID}" style="width:{media_other.WIDTH}px;height:{media_other.HEIGHT}px;">
								<object type="{media_other.MIME}" data="{media_other.URL}">
									<param name="allowScriptAccess" value="samedomain" />
									<param name="allowFullScreen" value="true">
									<param name="play" value="true" />
									<param name="movie" value="{media_other.URL}" />
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
