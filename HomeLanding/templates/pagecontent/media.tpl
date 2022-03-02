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

			# IF C_ITEMS #
				<div class="cell-flex cell-columns-{ITEMS_PER_ROW}" data-type="content" data-listorder-group="media-items">
					# START items #
						<div class="cell" itemscope="itemscope" itemtype="https://schema.org/CreativeWork">
							<header class="cell-header">
								<h3 class="cell-name"><a class="offload" href="{items.U_ITEM}">{items.TITLE}</a></h3>
								<i class="fa fa-# IF items.C_AUDIO #music# ELSE #film# ENDIF #" aria-hidden="true"></i>
							</header>
							<div class="cell-infos">
								<div class="more">
									<span class="pinned item-author"><i class="fa fa-fw fa-user"></i> {items.PSEUDO}</span>
									<span class="pinned item-creation-date"><i class="far fa-fw fa-calendar-alt"></i> {items.DATE}</span>
									<span class="pinned item-category"><i class="far fa-fw fa-folder"></i> {items.CATEGORY_NAME}</span>
								</div>
							</div>
							# IF items.C_THUMBNAIL #
								<div class="cell-thumbnail cell-landscape cell-center">
									<img src="{items.U_THUMBNAIL}" alt="{items.TITLE}">
									<a class="cell-thumbnail-caption offload" href="{items.U_ITEM}"><i class="fa fa-2x fa-play-circle" aria-hidden="true"></i></a>
								</div>
							# ENDIF #
							<div class="cell-body">
								# IF items.C_SUMMARY #
									<div class="cell-content">
										{items.SUMMARY}
									</div>
								# ENDIF #
							</div>
						</div>
					# END items #
				</div>
			# ELSE #
				<div class="content">
					<div class="message-helper bgc notice">
						{@common.no.item.now}
					</div>
				</div>
			# ENDIF #
		</article>

	</div>
</div>
