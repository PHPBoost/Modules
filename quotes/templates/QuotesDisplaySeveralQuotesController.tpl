<section id="module-quotes">
	<header>
		<div class="align-right controls">
			# IF C_CATEGORY ## IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF # # IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true"></i></a># ENDIF ## ENDIF #
		</div>
		<h1>
			# IF C_PENDING #{@quotes.pending}# ELSE #{@module_title}# ENDIF # # IF C_CATEGORY ## IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF ## ENDIF # # IF C_AUTHOR_NAME # - {AUTHOR_NAME}# ENDIF #
		</h1>
	</header>
	# IF C_CATEGORY_DESCRIPTION #
		<div class="cat-description">
			{CATEGORY_DESCRIPTION}
		</div>
	# ENDIF #

	# IF C_SUB_CATEGORIES #
		<div class="cell-flex cell-tile cell-columns-{CATEGORIES_PER_ROW}">
			# START sub_categories_list #
				<div class="cell category-{sub_categories_list.CATEGORY_ID}">
					<div class="cell-header">
						<div class="cell-name" itemprop="about">
							<a href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME}</a>
						</div>
						<span class="small pinned notice" aria-label="{sub_categories_list.ELEMENTS_NUMBER} # IF sub_categories_list.C_MORE_THAN_ONE_ELEMENT #${TextHelper::lcfirst(@quotes)}# ELSE #${TextHelper::lcfirst(@quote)}# ENDIF #">{sub_categories_list.ELEMENTS_NUMBER}</span>
					</div>
					# IF sub_categories_list.C_CATEGORY_THUMBNAIL #
					<div class="cell-body">
						<div class="cell-thumbnail cell-landscape cell-center">
							<img itemprop="thumbnailUrl" src="{sub_categories_list.U_CATEGORY_THUMBNAIL}" alt="{sub_categories_list.CATEGORY_NAME}" />
							<a class="cell-thumbnail-caption" itemprop="about" href="{sub_categories_list.U_CATEGORY}">
								${LangLoader::get_message('see.category', 'categories-common')}
							</a>
						</div>
					</div>

					# ENDIF #
				</div>
			# END sub_categories_list #
		</div>
		# IF C_SUBCATEGORIES_PAGINATION #<div class="align-center"># INCLUDE SUBCATEGORIES_PAGINATION #</div># ENDIF #
	# ENDIF #

	# IF C_RESULTS #
		# START quotes #
			<article id="quotes-item-{quotes.ID}" class="quotes-item several-items category-{quotes.CATEGORY_ID}" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
				<blockquote class="formatter-container formatter-blockquote">
					<h2 class="title-perso">
						# IF C_AUTHOR_NAME #
							<span class="small">{quotes.AUTHOR} :</span>
						# ELSE #
							<a href="{quotes.U_AUTHOR_LINK}" class="small">{quotes.AUTHOR}</a> :
						# ENDIF #
					</h2>
					<div class="formatter-content">
						# IF quotes.C_MODERATION #
							<div class="align-right controls">
								# IF quotes.C_EDIT #
									<a href="{quotes.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true"></i></a>
								# ENDIF #
								# IF quotes.C_DELETE #
									<a href="{quotes.U_DELETE}" data-confirmation="delete-element" aria-label="${LangLoader::get_message('delete', 'common')}"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
								# ENDIF #
							</div>
						# ENDIF #
						<p itemprop="text">{quotes.QUOTE}</p>
						<div class="align-right small">
							<a href="{quotes.U_CATEGORY}"><i class="far fa-folder"></i> {quotes.CATEGORY_NAME}</a>
						</div>
					</div>
	            </blockquote>
				<footer></footer>
			</article>
		# END quotes #
	# ELSE #
		# IF NOT C_HIDE_NO_ITEM_MESSAGE #
			<div class="align-center">
				${LangLoader::get_message('no_item_now', 'common')}
			</div>
		# ENDIF #
	# ENDIF #
	<footer># IF C_PAGINATION # # INCLUDE PAGINATION # # ENDIF #</footer>
</section>
