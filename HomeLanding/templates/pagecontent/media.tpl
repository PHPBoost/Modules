
<article id="{MODULE_NAME}" style="order: {MODULE_POSITION};">
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
				<div class="item-content" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
					<h3><a href="{media_swf.U_MEDIA_LINK}">{media_swf.TITLE}</a></h3>
					<div class="more">
						<span class="pinned"><i class="fa fa-fw fa-user"></i> {media_swf.PSEUDO}
						<span class="pinned"><i class="far fa-fw fa-calendar-alt"></i> {media_swf.DATE}</span>
					</div>
					<div class="media-content" id="media_swf-{media_swf.ID}">
						<object type="{media_swf.MIME}" data="{media_swf.URL}" width="{WIDTH}" height="{HEIGHT}">
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
				<div class="item-content" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
					<h3><a href="{media_host.U_MEDIA_LINK}">{media_host.TITLE}</a></h3>
					<div class="more">
						<span class="pinned"><i class="fa fa-fw fa-user"></i> {media_host.PSEUDO}
						<span class="pinned"><i class="far fa-fw fa-calendar-alt"></i> {media_host.DATE}</span>
					</div>
					<div class="media-content" style="width: {media_host.WIDTH}px; height: {media_host.HEIGHT}px">
						<iframe class="youtube-player" type="text/html" src="{media_host.PLAYER}{media_host.MEDIA_ID}" frameborder="0" allowfullscreen></iframe>
					</div>
				</div>
			# END media_host #

			# START media_flv #
				<div class="item-content" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
					<h3><a href="{media_flv.U_MEDIA_LINK}">{media_flv.TITLE}</a></h3>
					<div class="more">
						<span class="pinned"><i class="fa fa-fw fa-user"></i> {media_flv.PSEUDO}</span>
						<span class="pinned"><i class="far fa-fw fa-calendar-alt"></i> {media_flv.DATE}</span>
					</div>
					<div class="media-content media-flv">
						<a href="{media_flv.URL}" id="media_flv-{media_flv.ID}" class="media-flv" style="width:{media_flv.WIDTH}px;height:{media_flv.HEIGHT}px;"></a>
						<script>
							jQuery(document).ready(function() {
								insertMoviePlayer('media_flv-{media_flv.ID}');
							});
						</script>
					</div>
				</div>
			# END media_flv #

			# START media_mp4 #
				<div class="item-content" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
					<h3><a href="{media_mp4.U_MEDIA_LINK}">{media_mp4.TITLE}</a></h3>
					<div class="more">
						<span class="pinned"><i class="fa fa-fw fa-user"></i> {media_mp4.PSEUDO}</span>
						<span class="pinned"><i class="far fa-fw fa-calendar-alt"></i> {media_mp4.DATE}</span>
					</div>
					<div class="media-content media-mp4">
						<video class="video-player" width="{media_mp4.WIDTH}" height="{media_mp4.HEIGHT}"# IF media_mp4.C_POSTER # poster="{media_mp4.POSTER}"# ENDIF # controls>
							<source src="{media_mp4.URL}" type="{media_mp4.MIME}" />
						</video>
					</div>
				</div>
			# END media_mp4 #

			# START media_mp3 #
				<div class="item-content-audio" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
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
				<div class="item-content" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
					<h3><a href="{media_other.U_MEDIA_LINK}">{media_other.TITLE}</a></h3>
					<div class="more">
						<span class="pinned"><i class="fa fa-fw fa-user"></i> {media_other.PSEUDO}</span>
						<span class="pinned"><i class="far fa-fw fa-calendar-alt"></i> {media_other.DATE}</span>
					</div>

					<div class="media-content" id="media_other-{media_other.ID}">
						<object type="{media_other.MIME}" data="{media_other.URL}" width="{media_other.WIDTH}" height="{media_other.HEIGHT}">
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
