<section id="module-quotes" class="several-items">
	<header class="section-header">
		<div class="controls align-right">
			# IF NOT C_ROOT_CATEGORY #{@quotes.module.title}# ENDIF #
			# IF C_CATEGORY # # IF IS_ADMIN #<a class="offload" href="{U_EDIT_CATEGORY}" aria-label="{@common.edit}"><i class="fa fa-edit" aria-hidden="true"></i></a># ENDIF ## ENDIF #
		</div>
		<h1>
			# IF C_PENDING_ITEMS #
				{@quotes.pending.items}
			# ELSE #
				# IF C_MEMBER_ITEMS #
			 		# IF C_MY_ITEMS #{@quotes.my.items}# ELSE #{@quotes.member.items} {MEMBER_NAME}# ENDIF #
				# ELSE #
					# IF C_ROOT_CATEGORY #{@quotes.module.title}# ELSE ## IF C_WRITER_ITEMS #{WRITER_NAME}# ELSE #{CATEGORY_NAME}# ENDIF ## ENDIF #
				# ENDIF #
			# ENDIF #
		</h1>
	</header>
	# IF C_CATEGORY_DESCRIPTION #
		<div class="sub-section">
			<div class="content-container">
				<div class="cat-description">
					{CATEGORY_DESCRIPTION}
				</div>
			</div>
		</div>
	# ENDIF #

	# IF C_SUB_CATEGORIES #
		<div class="sub-section">
			<div class="content-container">
				<div class="cell-flex cell-tile cell-columns-{CATEGORIES_PER_ROW}">
					# START sub_categories_list #
						<div class="cell category-{sub_categories_list.CATEGORY_ID}">
							<div class="cell-header">
								<div class="cell-name" itemprop="about">
									<a class="offload" href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME}</a>
								</div>
								<span class="small pinned notice" aria-label="{sub_categories_list.ELEMENTS_NUMBER} # IF sub_categories_list.C_MORE_THAN_ONE_ELEMENT #${TextHelper::lcfirst(@items)}# ELSE #${TextHelper::lcfirst(@item)}# ENDIF #">{sub_categories_list.ELEMENTS_NUMBER}</span>
							</div>
							# IF sub_categories_list.C_CATEGORY_THUMBNAIL #
								<div class="cell-body">
									<div class="cell-thumbnail cell-landscape cell-center">
										<img itemprop="thumbnailUrl" src="{sub_categories_list.U_CATEGORY_THUMBNAIL}" alt="{sub_categories_list.CATEGORY_NAME}" />
										<a class="cell-thumbnail-caption offload" itemprop="about" href="{sub_categories_list.U_CATEGORY}">
											{@common.see.category}
										</a>
									</div>
								</div>
							# ENDIF #
						</div>
					# END sub_categories_list #
				</div>
				# IF C_SUBCATEGORIES_PAGINATION #<div class="align-center"># INCLUDE SUBCATEGORIES_PAGINATION #</div># ENDIF #
			</div>
		</div>
	# ENDIF #

	# IF C_ITEMS #
		<div class="sub-section">
			<div class="content-container">
				# START items #
					<article id="quotes-item-{items.ID}" class="quotes-item several-items category-{items.CATEGORY_ID}" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
						<div class="content">
							<blockquote class="formatter-container formatter-blockquote# IF C_WRITER_ITEMS # writer-items# ENDIF #">
								<h2 class="title-perso">
									# IF NOT C_WRITER_ITEMS #
										<a href="{items.U_WRITER}" class="small offload">{items.WRITER_NAME}</a> :
									# ENDIF #
								</h2>
								<div class="formatter-content">
									<div class="controls align-right">
										<a class="offload copy-link-to-clipboard" href="{U_SITE}{items.U_ITEM}" aria-label="{@common.copy.link.to.clipboard}"><i class="fa fa-hashtag" aria-hidden="true"></i></a>
										# IF items.C_CONTROLS #
											# IF items.C_EDIT #
												<a class="offload" href="{items.U_EDIT}" aria-label="{@common.edit}"><i class="fa fa-edit" aria-hidden="true"></i></a>
											# ENDIF #
											# IF items.C_DELETE #
												<a href="{items.U_DELETE}" data-confirmation="delete-element" aria-label="{@common.delete}"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
											# ENDIF #
										# ENDIF #
									</div>
									<p itemprop="text">{items.CONTENT}</p>
									<div class="align-right small">
										<a class="offload" href="{items.U_CATEGORY}"><i class="far fa-folder"></i> {items.CATEGORY_NAME}</a>
									</div>
								</div>
				            </blockquote>
						</div>
						<footer></footer>
					</article>
				# END items #
			</div>
		</div>
	# ELSE #
		# IF NOT C_HIDE_NO_ITEM_MESSAGE #
			<div class="sub-section">
				<div class="content-container">
					<div class="content">
						<div class="message-helper bgc notice align-center">
							{@common.no.item.now}
						</div>
					</div>
				</div>
			</div>
		# ENDIF #
	# ENDIF #
	<footer># IF C_PAGINATION #<div class="sub-section"><div class="content-container"># INCLUDE PAGINATION #</div></div># ENDIF #</footer>
</section>
