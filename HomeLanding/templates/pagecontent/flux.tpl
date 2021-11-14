<div class="sub-section" style="order: {MODULE_POSITION};">
	<div class="content-container">
		<article id="{MODULE_NAME}-panel" class="sub-section">
			<header class="module-header flex-between">
				<h2>{L_MODULE_TITLE}</h2>
				<div class="controls align-right">
					<a class="offload" href="{PATH_TO_ROOT}/{MODULE_NAME}" aria-label="{@homelanding.see.module}"><i class="fa fa-share-square" aria-hidden="true"></i></a>
				</div>
			</header>
			<div class="content">
				<div
					data-listorder-control="dropdown-sort"
					class="listorder-drop-down hidden"
					data-group="feed-items"
					data-name="sorttitle">
					<div data-type="panel" class="listorder-dd-panel"></div>
					<ul data-type="content" class="dropdown-container">
						<li> {@common.sort.by.date}
							<em class="sort-type bgc-full link-color" data-path=".lo-date" data-order="asc" data-type="number"><span class="sr-only">{@common.sort.by.date} &#8593;</span> <i class="fa fa-sort-numeric-up-alt"></i></em>
							<em class="sort-type bgc-full logo-color" data-path=".lo-date" data-order="desc" data-type="number" data-selected="true"><span class="sr-only">{@common.sort.by.date} &#8595;</span> <i class="fa fa-sort-numeric-down-alt"></i></em>
						</li>
					</ul>
				</div>
				<ul class="last-feeds" data-type="content" data-listorder-group="feed-items">
					# IF C_LAST_FEEDS #
						# START feed_items #
							<li data-listorder-item>
								<span class="lo-date hidden">{feed_items.SORT_DATE}</span>
								<span class="feed-title">
									<h6>
										<a href="{feed_items.U_ITEM}"# IF feed_items.C_NEW_WINDOW # target="_blank" rel="noopener noreferrer"# ENDIF #>
											{feed_items.TITLE}
										</a>
									</h6>
									<span class="small align-right"><a href="{feed_items.U_ITEM_HOST}" class="offload text-italic">{feed_items.ITEM_HOST}</a> | {feed_items.DATE}</span>
								</span>
								<p>
									# IF feed_items.C_HAS_THUMBNAIL #
										<img src="{feed_items.U_THUMBNAIL}" class="align-left" alt="{feed_items.TITLE}" />
									# ENDIF #
									{feed_items.SUMMARY} # IF feed_items.C_READ_MORE # <span aria-label="{@flux.words.not.read}" class="small text-italic pinned notice">{feed_items.WORDS_NUMBER}</span># ENDIF #
								</p>
							</li>
						# END feed_items #
					# ELSE #
						<div class="message-helper bgc notice">{@flux.no.last.feeds}</div>
					# ENDIF #
				</ul>
				<div class="sub-section items-pagination">
					<div class="content-container">
						<nav
							class="listorder-pagination pagination"
							data-listorder-control="pagination"
							data-group="feed-items"
							data-items-per-page="{LAST_FEEDS_NUMBER}"
							data-current-page="0"
							data-name="pagination1"
							data-id="paging">
						</nav>
					</div>
				</div>
			</div>
		</article>
	</div>
</div>
