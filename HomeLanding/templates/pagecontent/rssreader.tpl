
<article id="rssreader" style="order: {RSS_POSITION}; -webkit-order: {RSS_POSITION}; -ms-flex-order: {RSS_POSITION}">
	<header>
		<h2>
			${Langloader::get_message('link.to.rss.site', 'common', 'HomeLanding')}
			<a href="{SITE_URL}" target="_blank" title="${Langloader::get_message('link.to.web', 'common', 'HomeLanding')}">
				{SITE_TITLE}
			</a>
		</h2>
	</header>
	<div class="content"> 
		<ul>
		# START rssreader #
			
			<li>
				<a href="{rssreader.LINK_FEED}" title="{rssreader.TITLE_FEED}" target="_blank">
					<i class="fa fa-hand-o-right"></i> 
						{rssreader.TITLE_FEED}
				</a> - <span class="small">{rssreader.DATE_FEED}</span>
				<p>
					# IF rssreader.C_IMG_FEED #
					<img src="{rssreader_IMG_FEED}" class="left" alt="{rssreader.TITLE_FEED}" />
					# ENDIF #
					{rssreader.DESC} ...
				</p>
			</li>	
			
		# END rssreader #
		</ul>
	</div>        
	<footer></footer>
</article>