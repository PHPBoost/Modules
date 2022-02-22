# IF NOT C_NO_ITEM #
	<div class="sub-section" style="order: {MODULE_POSITION};">
		<div class="content-container">
			<article id="pinned_news-panel">
				<header class="module-header flex-between">
					<h2>{L_MODULE_TITLE}</h2>
					<div class="controls align-right">
						<a class="offload" href="{PATH_TO_ROOT}/{MODULE_NAME}" aria-label="{@homelanding.see.module}"><i class="fa fa-share-square" aria-hidden="true"></i></a>
					</div>
				</header>
				<div class="cell-row">

					# START items #
						<div class="{MODULE_NAME}-item category-{items.CATEGORY_ID} cell prime-item" itemscope="itemscope" itemtype="https://schema.org/CreativeWork">
							<div class="cell-header">
								<h3 class="cell-name">
									<a class="offload" href="{items.U_ITEM}">{items.TITLE}</a>
								</h3>
							</div>
							<div class="cell-body">
								<div class="cell-infos">
									<div class="more">
										# IF C_AUTHOR_DISPLAYED #
											<span class="pinned item-author">
												<i class="fa fa-fw fa-user" aria-hidden="true"></i>
												# IF items.C_AUTHOR_EXISTS #<a itemprop="author" class="{items.AUTHOR_LEVEL_CLASS} offload" href="{items.U_AUTHOR_PROFILE}"# IF items.C_AUTHOR_GROUP_COLOR # style="{items.AUTHOR_GROUP_COLOR}"# ENDIF #>{items.AUTHOR_DISPLAY_NAME}</a># ELSE #{items.AUTHOR_DISPLAY_NAME}# ENDIF #
											</span>
										# ENDIF #
										# IF items.C_HAS_UPDATE #
											<span class="pinned notice text-italic item-modified-date"><i class="far fa-fw fa-calendar-plus" aria-hidden="true"></i> <time datetime="{items.UPDATE_DATE_ISO8601}" itemprop="dateModified">{items.UPDATE_DATE}</time></span>
										# ELSE #
											<span class="pinned item-creation-date"><i class="far fa-fw fa-calendar-alt" aria-hidden="true"></i> <time datetime="{items.DATE_ISO8601}" itemprop="datePublished">{items.DATE}</time></span>
										# ENDIF #
										<span class="pinned item-category"><i class="far fa-fw fa-folder" aria-hidden="true"></i> <a class="offload" itemprop="about" href="{items.U_CATEGORY}">{items.CATEGORY_NAME}</a></span>
										# IF C_VIEWS_NUMBER #<span class="pinned item-views-number" aria-label="{items.VIEWS_NUMBER} # IF items.C_SEVERAL_VIEWS #{@common.views}# ELSE #{@common.view}# ENDIF #"><i class="fa fa-fw fa-eye" aria-hidden="true"></i> {items.VIEWS_NUMBER}</span># ENDIF #
									</div>
									# IF items.C_CONTROLS #
										<span class="controls">
											# IF items.C_EDIT #
												<a class="offload item-edit" href="{items.U_EDIT}" aria-label="{@common.edit}"><i class="fa fa-edit" aria-hidden="true"></i></a>
											# ENDIF #
											# IF items.C_DELETE #
												<a class="item-delete" href="{items.U_DELETE}" aria-label="{@common.delete}" data-confirmation="delete-element"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
											# ENDIF #
										</span>
									# ENDIF #
								</div>
								<div class="cell-content">
									# IF items.C_HAS_THUMBNAIL #
										<a href="{items.U_ITEM}" class="item-thumbnail">
											<img src="{items.U_THUMBNAIL}" alt="{items.TITLE}" />
										</a>
									# ENDIF #
									{items.CONTENT}
								</div>
							</div>

						</div>
					# END items #
				</div>
			</article>
		</div>
	</div>
# ENDIF #
