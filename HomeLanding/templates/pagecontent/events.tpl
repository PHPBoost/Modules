       
<article id="next_events" style="order: {CALENDAR_POSITION}; -webkit-order: {CALENDAR_POSITION}; -ms-flex-order: {CALENDAR_POSITION}">
	<header>
		<h2>
			${Langloader::get_message('next.events', 'common', 'HomeLanding')}
			<span class="actions">
				<a href="{PATH_TO_ROOT}/calendar" title="${Langloader::get_message('link.to.events', 'common', 'HomeLanding')}">
					${Langloader::get_message('link.to.events', 'common', 'HomeLanding')}
				</a>
			</span>
		</h2>
	</header>
	<div class="content"> 
		# IF C_NO_EVENT #
		<div class="center">
			${LangLoader::get_message('no.events', 'common', 'HomeLanding')}
		</div>
		# ENDIF #
	# START events_items #
		<div class="item-content">
			<h3>
				<a href="{events_items.U_LINK}" title="{events_items.TITLE}">{events_items.TITLE}</a>
			</h3>
			
			<div class="more">
				${LangLoader::get_message('event.date', 'common', 'HomeLanding')} <time datetime="{events_items.START_DATE}" itemprop="datePublished">{events_items.START_DATE}</time>
			</div>
			
			<p class="item-desc">
				{events_items.DESCRIPTION}# IF events_items.C_READ_MORE #... <a href="{events_items.U_LINK}">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF #
			</p>
		</div>
	# END events_items #
	</div>
	<footer></footer>
</article>