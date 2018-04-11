
<article id="media" style="order: {MEDIA_POSITION}; -webkit-order: {MEDIA_POSITION}; -ms-flex-order: {MEDIA_POSITION}">
	<header>
		<h2>
			${Langloader::get_message('last.media', 'common', 'HomeLanding')}
			<span class="actions">
				<a href="{PATH_TO_ROOT}/media" title="${Langloader::get_message('link.to.media', 'common', 'HomeLanding')}">
					${Langloader::get_message('link.to.media', 'common', 'HomeLanding')}
				</a>
			</span>
		</h2>
	</header>
	<div class="content">

		# START media_swf #
			<div class="item-content" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
				<h3><a href="{media_swf.U_MEDIA_LINK}">{media_swf.TITLE}</a></h3>
				<div class="more">
					${LangLoader::get_message('by', 'common')} <span class="color-topic">{media_swf.PSEUDO}</span> ${Langloader::get_message('the', 'common')} {media_swf.DATE}
				</div>
				<p class="media-content" id="media_swf-{media_swf.ID}">
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
				</p>
			</div>
		# END media_swf #

		# START media_flv #
			<div class="item-content" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
				<h3><a href="{media_flv.U_MEDIA_LINK}">{media_flv.TITLE}</a></h3>
				<div class="more">
					${LangLoader::get_message('by', 'common')} <span class="color-topic">{media_flv.PSEUDO}</span> ${Langloader::get_message('the', 'common')} {media_flv.DATE}
				</div>
				<p class="media-content media-flv">
					<a href="{media_flv.URL}" id="media_flv-{media_flv.ID}" class="media-flv" style="width:{media_flv.WIDTH}px;height:{media_flv.HEIGHT}px;"></a>
					<script>
					<!--
					jQuery(document).ready(function() {
						insertMoviePlayer('media_flv-{media_flv.ID}');
					});
					-->
					</script>
				</p>
			</div>
		# END media_flv #

		# START media_mp4 #
			<div class="item-content" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
				<h3><a href="{media_mp4.U_MEDIA_LINK}">{media_mp4.TITLE}</a></h3>
				<div class="more">
					${LangLoader::get_message('by', 'common')} <span class="color-topic">{media_mp4.PSEUDO}</span> ${Langloader::get_message('the', 'common')} {media_mp4.DATE}
				</div>
				<p class="media-content media-mp4">
					<video class="video-player" width="{media_mp4.WIDTH}" height="{media_mp4.HEIGHT}"# IF media_mp4.C_POSTER # poster="{media_mp4.POSTER}"# ENDIF # controls>
						<source src="{media_mp4.URL}" type="{media_mp4.MIME}" />
					</video>
				</p>
			</div>
		# END media_mp4 #

		# START media_mp3 #
			<div class="item-content-audio" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
				<h3><a href="{media_mp3.U_MEDIA_LINK}">{media_mp3.TITLE}</a></h3>
				<div class="more">
					${LangLoader::get_message('by', 'common')} <span class="color-topic">{media_mp3.PSEUDO}</span> ${Langloader::get_message('the', 'common')} {media_mp3.DATE}
				</div>

				<p class="media-content-audio" id="media_mp3-{media_mp3.ID}">
					<audio controls>
						<source src="{media_mp3.URL}" type="{media_mp3.MIME}"></source>
					</audio>
				</p>
			</div>
		# END media_mp3 #

		# START media_other #
			<div class="item-content" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
				<h3><a href="{media_other.U_MEDIA_LINK}">{media_other.TITLE}</a></h3>
				<div class="more">
					${LangLoader::get_message('by', 'common')} <span class="color-topic">{media_other.PSEUDO}</span> ${Langloader::get_message('the', 'common')} {media_other.DATE}
				</div>

				<p class="media-content" id="media_other-{media_other.ID}">
					<video class="youtube-player" controls="" src="{media_other.URL}">
						<source src="{media_other.URL}" type="video/mp4"></source>
					</video>
				</p>
			</div>
		# END media_other #

	</div>
	<footer></footer>
</article>
