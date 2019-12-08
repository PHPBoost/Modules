
<article id="web" style="order: {WEB_POSITION};">
	<header>
		<h2>
			${Langloader::get_message('last.web', 'common', 'HomeLanding')}
		</h2>
		<span class="controls">
			<a href="{PATH_TO_ROOT}/web">
				${Langloader::get_message('link.to.web', 'common', 'HomeLanding')}
			</a>
		</span>
	</header>
	<div class="content">
		<ul>
		# START item #

			<li>
				<a href="{item.U_LINK}">
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
