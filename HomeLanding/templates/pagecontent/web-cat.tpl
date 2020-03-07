
<article id="web-cat" style="order: {WEB_CAT_POSITION};">
	<header>
		<h2>
			${Langloader::get_message('last.web.cat', 'common', 'HomeLanding')} {CATEGORY_NAME}
		</h2>
		<span class="controls">
			<a href="{PATH_TO_ROOT}/web">
				${Langloader::get_message('link.to.web', 'common', 'HomeLanding')}
			</a>
		</span>
	</header>
	<div class="content">
		# IF C_NO_WEB_ITEM #
			<div class="align-center">
				${LangLoader::get_message('no.web.item', 'common', 'HomeLanding')}
			</div>
		# ENDIF #
		<ul>
			# START item #
				<li>
					<a href="{item.U_ITEM}">
						# IF item.C_HAS_PARTNER_THUMBNAIL #
							<img class="item-picture" src="{item.U_PARTNER_THUMBNAIL}" alt="{item.TITLE}" />
						# ELSE #
							# IF item.C_HAS_THUMBNAIL #
								<img class="item-picture" src="{item.U_THUMBNAIL}" alt="{item.TITLE}" />
							# ELSE #
								{item.TITLE}
							# ENDIF #
						# ENDIF #
					</a>
				</li>
			# END item #
		</ul>
	</div>
	<footer></footer>
</article>
