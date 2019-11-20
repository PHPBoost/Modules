
<article id="web-cat" style="order: {WEB_CAT_POSITION};">
	<header>
		<h2>
			${Langloader::get_message('last.web.cat', 'common', 'HomeLanding')} {CATEGORY_NAME}
		</h2>
		<span class="actions">
			<a href="{PATH_TO_ROOT}/web">
				${Langloader::get_message('link.to.web', 'common', 'HomeLanding')}
			</a>
		</span>
	</header>
	<div class="content">
		# IF C_NO_WEB_ITEM #
		<div class="center">
			${LangLoader::get_message('no.web.item', 'common', 'HomeLanding')}
		</div>
		# ENDIF #
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
