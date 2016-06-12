
<article id="last_media" style="order: {MEDIA_POSITION}; -webkit-order: {MEDIA_POSITION}; -ms-flex-order: {MEDIA_POSITION}">
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
				<h3>{media_swf.TITLE}</h3>
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
				<h3>{media_flv.TITLE}</h3>
				<div class="more">
					${LangLoader::get_message('by', 'common')} <span class="color-topic">{media_flv.PSEUDO}</span> ${Langloader::get_message('the', 'common')} {media_flv.DATE}
				</div>				
				<p class="media-content">
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

		# START media_mp3 #		
			<div class="item-content-audio" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
				<h3>{media_mp3.TITLE}</h3>
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
				<h3>{media_other.TITLE}</h3>
				<div class="more">
					${LangLoader::get_message('by', 'common')} <span class="color-topic">{media_other.PSEUDO}</span> ${Langloader::get_message('the', 'common')} {media_other.DATE}
				</div>
				
				<p class="media-content" id="media_other-{media_other.ID}">
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
				</p>
			</div>		
		# END media_other #

	</div>            
	<footer></footer>
</article>