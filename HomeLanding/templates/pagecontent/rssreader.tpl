
<article id="rssreader-panel" style="order: {MODULE_POSITION};">
	<header>
		<h2>
			${Langloader::get_message('link.to.rss.site', 'common', 'HomeLanding')}
			<a href="{SITE_URL}" target="_blank">
				{SITE_TITLE}
			</a>
		</h2>
	</header>
	<div class="content">
		# IF C_RSS_FILE #
			<ul>
				# START item #

					<li>
						<span class="flex-between">
							<a class="big" href="{item.LINK_FEED}" target="_blank" rel="noopener noreferrer">
								{item.TITLE_FEED}
							</a>
							<span class="small">{item.DATE_FEED}</span>
						</span>
						<p>
							# IF item.C_IMG_FEED #
								<img src="{item.IMG_FEED}" class="align-left" alt="{item.TITLE_FEED}" />
							# ENDIF #
							{item.DESC}# IF item.C_READ_MORE #...# ENDIF #
						</p>
					</li>

				# END item #
			</ul>
		# ELSE #
			{NO_RSS_FILE}
		# ENDIF #
	</div>
	<footer></footer>
</article>
