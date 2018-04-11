
<article id="events" style="order: {CALENDAR_POSITION}; -webkit-order: {CALENDAR_POSITION}; -ms-flex-order: {CALENDAR_POSITION}">
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
	# START item #
		<div class="item-content">
			<h3>
				<a href="{item.U_LINK}" title="{item.TITLE}">{item.TITLE}</a>
			</h3>

			<div class="more">
				${LangLoader::get_message('event.date', 'common', 'HomeLanding')} <time datetime="{item.START_DATE}" itemprop="datePublished">{item.START_DATE}</time>
			</div>
			# IF item.C_HAS_PICTURE #
                <a href="{item.U_LINK}" class="item-picture">
                    <img itemprop="thumbnailUrl" src="{item.PICTURE}" alt="{item.TITLE}" />
                </a>
            # ENDIF #
			<p class="item-desc">
				{item.DESCRIPTION}# IF item.C_READ_MORE #... <a href="{item.U_LINK}">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF #
			</p>
		</div>
	# END item #
	</div>
	<footer></footer>
</article>
