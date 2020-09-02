
<article id="{MODULE_NAME}-panel" style="order: {MODULE_POSITION};">
	<header>
		<h2>
			{L_MODULE_TITLE}
		</h2>
		<div class="controls align-right">
			<a href="{PATH_TO_ROOT}/{MODULE_NAME}">{L_SEE_ALL_ITEMS}</a>
		</div>
	</header>
	# IF C_NO_ITEM #
		<div class="message-helper bgc notice">
			${LangLoader::get_message('no_item_now', 'common')}
		</div>
	# ELSE #
		<div class="cell-flex cell-columns-{ITEMS_PER_ROW} cell-tile">
			# START item #
				<div class="cell" itemscope="itemscope" itemtype="https://schema.org/CreativeWork">
					<div class="cell-body">
						<div class="cell-thumbnail cell-landscape cell-center">
							<img src="{item.U_PICTURE}" alt="{item.TITLE}" />
							<a class="cell-thumbnail-caption" href="{item.U_CATEGORY}"><i class="fa fa-eye" aria-hidden="true"></i> </a>
						</div>
					</div>
					<div class="cell-list">
						<ul>
							<li class="li-stretch">{item.TITLE} # IF C_VIEWS_ENABLED #{item.VIEWS_NUMBER}# ENDIF #</li>
						</ul>
					</div>
				</div>
			# END item #
		</div>
	# ENDIF #
	<footer></footer>
</article>
