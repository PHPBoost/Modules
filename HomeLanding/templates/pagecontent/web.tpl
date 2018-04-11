
<article id="web" style="order: {WEB_POSITION}; -webkit-order: {WEB_POSITION}; -ms-flex-order: {WEB_POSITION}">
	<header>
		<h2>
			${Langloader::get_message('last.web', 'common', 'HomeLanding')}
			<span class="actions">
				<a href="{PATH_TO_ROOT}/web" title="${Langloader::get_message('link.to.web', 'common', 'HomeLanding')}">
					${Langloader::get_message('link.to.web', 'common', 'HomeLanding')}
				</a>
			</span>
		</h2>
	</header>
	<div class="content">
		<ul>
		# START item #

			<li>
				<a href="{item.U_LINK}" title="{item.NAME}">
					# IF item.C_HAS_PARTNER_PICTURE #
						<img class="item-picture" src="{item.U_PARTNER_PICTURE}" alt="{item.NAME}" />
					# ELSE #
						# IF item.C_PICTURE #
							<img class="item-picture" src="{item.U_PICTURE}" alt="{item.NAME}" />
						# ELSE #
							{item.NAME}
						# ENDIF #
					# ENDIF #
				</a>
			</li>

		# END item #
		</ul>
	</div>
	<footer></footer>
</article>
