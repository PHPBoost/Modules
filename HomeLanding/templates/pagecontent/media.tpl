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
				<div
					class="hidden"
					data-listorder-control="hidden-sort"
					data-group="media-items"
					data-path=".lo-date-media"
					data-order="desc"
					data-type="number">
				</div>
				<div class="content cell-flex cell-columns-{ITEMS_PER_ROW}" data-type="content" data-listorder-group="media-items">
					# START media_host #
						<div data-listorder-item class="cell" itemscope="itemscope" itemtype="https://schema.org/CreativeWork">
							<span class="lo-date-media hidden">{media_host.SORT_DATE}</span>
							<header class="cell-header">
								<h3 class="cell-name"><a class="offload" href="{media_host.U_ITEM}">{media_host.TITLE}</a></h3>
							</header>
							<div class="cell-body">
								<div class="cell-infos">
									<div class="more">
										<span class="pinned item-author"><i class="fa fa-fw fa-user"></i> {media_host.PSEUDO}</span>
										<span class="pinned item-creation-date"><i class="far fa-fw fa-calendar-alt"></i> {media_host.DATE}</span>
										<span class="pinned item-category"><i class="far fa-fw fa-folder"></i> {media_host.CATEGORY_NAME}</span>
									</div>
								</div>
								<div class="cell-thumbnail cell-landscape cell-center">
									<img src="{media_host.POSTER}" alt="{media_host.TITLE}">
									<a class="cell-thumbnail-caption offload" href="{media_host.U_ITEM}"><i class="fa fa-2x fa-play-circle" aria-hidden="true"></i></a>
								</div>
							</div>
						</div>
					# END media_host #

					# START media_mp4 #
						<div data-listorder-item class="cell" itemscope="itemscope" itemtype="https://schema.org/CreativeWork">
							<span class="lo-date-media hidden">{media_mp4.SORT_DATE}</span>
							<header class="cell-header">
								<h3 class="cell-name"><a class="offload" href="{media_mp4.U_ITEM}">{media_mp4.TITLE}</a></h3>
							</header>
							<div class="cell-body">
								<div class="cell-infos">
									<div class="more">
										<span class="pinned item-author"><i class="fa fa-fw fa-user"></i> {media_mp4.PSEUDO}</span>
										<span class="pinned item-creation-date"><i class="far fa-fw fa-calendar-alt"></i> {media_mp4.DATE}</span>
										<span class="pinned item-category"><i class="far fa-fw fa-folder"></i> {media_mp4.CATEGORY_NAME}</span>
									</div>
								</div>
								<div class="cell-thumbnail cell-landscape cell-center">
									<img src="{media_mp4.POSTER}" alt="{media_mp4.TITLE}">
									<a class="cell-thumbnail-caption offload" href="{media_mp4.U_ITEM}"><i class="fa fa-2x fa-play-circle" aria-hidden="true"></i></a>
								</div>
							</div>
						</div>
					# END media_mp4 #

					# START media_mp3 #
						<div data-listorder-item class="cell" itemscope="itemscope" itemtype="https://schema.org/CreativeWork">
							<span class="lo-date-media hidden">{media_mp3.SORT_DATE}</span>
							<header class="cell-header">
								<h3 class="cell-name"><a class="offload" href="{media_mp3.U_ITEM}">{media_mp3.TITLE}</a></h3>
							</header>
							<div class="cell-body">
								<div class="cell-infos">
									<div class="more">
										<span class="pinned item-author"><i class="fa fa-fw fa-user"></i> {media_mp3.PSEUDO}</span>
										<span class="pinned item-creation-date"><i class="far fa-fw fa-calendar-alt"></i> {media_mp3.DATE}</span>
										<span class="pinned item-category"><i class="far fa-fw fa-folder"></i> {media_mp3.CATEGORY_NAME}</span>
									</div>
								</div>
								<div class="cell-thumbnail cell-landscape cell-center">
									<img src="{media_mp3.POSTER}" alt="{media_mp3.TITLE}">
									<a class="cell-thumbnail-caption offload" href="{media_mp3.U_ITEM}"><i class="fa fa-2x fa-music" aria-hidden="true"></i></a>
								</div>
							</div>
						</div>
					# END media_mp3 #

					# START media_other #
						<div data-listorder-item class="cell" itemscope="itemscope" itemtype="https://schema.org/CreativeWork">
							<span class="lo-date-media hidden">{media_other.SORT_DATE}</span>
							<header class="cell-header">
								<h3 class="cell-name"><a class="offload" href="{media_other.U_ITEM}">{media_other.TITLE}</a></h3>
							</header>
							<div class="cell-body">
								<div class="cell-infos">
									<div class="more">
										<span class="pinned item-author"><i class="fa fa-fw fa-user"></i> {media_other.PSEUDO}</span>
										<span class="pinned item-creation-date"><i class="far fa-fw fa-calendar-alt"></i> {media_other.DATE}</span>
										<span class="pinned item-category"><i class="far fa-fw fa-folder"></i> {media_other.CATEGORY_NAME}</span>
									</div>
								</div>
								<div class="cell-thumbnail cell-landscape cell-center">
									<img src="{media_other.POSTER}" alt="{media_other.TITLE}">
									<a class="cell-thumbnail-caption offload" href="{media_other.U_ITEM}"><i class="fa fa-2x fa-play-circle" aria-hidden="true"></i></a>
								</div>
							</div>
						</div>
					# END media_other #

				</div>
			# ENDIF #
			<footer></footer>
		</article>

	</div>
</div>
