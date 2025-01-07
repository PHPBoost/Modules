<div class="sub-section" style="order: {MODULE_POSITION};">
	<div class="content-container">
		<article id="{MODULE_NAME}-panel">
			<header class="module-header flex-between">
				<h2>
					{L_MODULE_TITLE}
				</h2>
				<div class="controls align-right">
					<a class="offload" href="{PATH_TO_ROOT}/{MODULE_NAME}" aria-label="{@homelanding.see.module}"><i class="fa fa-share-square" aria-hidden="true"></i></a>
				</div>
			</header>
			# IF C_NO_ITEM #
				<div class="content">
					<div class="message-helper bgc notice">
						{@common.no.item.now}
					</div>
				</div>
			# ELSE #
				<div class="cell-flex cell-columns-{ITEMS_PER_ROW} cell-tile">
					# START items #
						<div class="cell" itemscope="itemscope" itemtype="https://schema.org/CreativeWork">
							<div class="cell-body">
								<div class="cell-thumbnail cell-landscape cell-center">
									<img src="{items.U_PICTURE}" alt="{items.TITLE}" />
									<a class="cell-thumbnail-caption offload" href="{items.U_CATEGORY}" aria-label="{@common.see.category}"><i class="fa fa-eye" aria-hidden="true"></i> </a>
								</div>
							</div>
							<div class="cell-list">
								<ul>
									<li class="li-stretch">{items.TITLE} # IF C_VIEWS_ENABLED # <span><i class="far fa-eye"></i> {items.VIEWS_NUMBER}# ENDIF #</span></li>
								</ul>
							</div>
						</div>
					# END items #
				</div>
			# ENDIF #
		</article>
	</div>
</div>
