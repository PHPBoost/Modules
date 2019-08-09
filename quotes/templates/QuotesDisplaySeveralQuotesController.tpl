<section id="module-quotes">
	<header>
		<div class="cat-actions">
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
	<div class="subcat-container">
		# START sub_categories_list #
		<div class="subcat-element" style="width:{CATS_COLUMNS_WIDTH}%;">
			<div class="subcat-content">
				# IF sub_categories_list.C_CATEGORY_IMAGE #<a itemprop="about" href="{sub_categories_list.U_CATEGORY}"><img itemprop="thumbnailUrl" src="{sub_categories_list.CATEGORY_IMAGE}" alt="{sub_categories_list.CATEGORY_NAME}" /></a># ENDIF #
				<br />
				<a itemprop="about" href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME}</a>
				<br />
				<span class="small">{sub_categories_list.ELEMENTS_NUMBER} # IF sub_categories_list.C_MORE_THAN_ONE_ELEMENT #${TextHelper::lcfirst(LangLoader::get_message('quotes', 'common', 'quotes'))}# ELSE #${TextHelper::lcfirst(LangLoader::get_message('quote', 'common', 'quotes'))}# ENDIF #</span>
			</div>
		</div>
		# END sub_categories_list #
		<div class="spacer"></div>
	</div>
	# IF C_SUBCATEGORIES_PAGINATION #<span class="center"># INCLUDE SUBCATEGORIES_PAGINATION #</span># ENDIF #
	# ENDIF #

	<div class="elements-container columns-1">
	# IF C_RESULTS #
		# START quotes #
			<article id="article-quotes-{quotes.ID}" class="block" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
				# IF quotes.C_MODERATION #
					<header>
						<h2>
							<span class="actions">
								# IF quotes.C_EDIT #
									<a href="{quotes.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true"></i></a>
								# ENDIF #
								# IF quotes.C_DELETE #
									<a href="{quotes.U_DELETE}" data-confirmation="delete-element" aria-label="${LangLoader::get_message('delete', 'common')}"><i class="fa fa-delete" aria-hidden="true"></i></a>
								# ENDIF #
							</span>
						</h2>
					</header>
				# ENDIF #

				<div class="content">
					<p itemprop="text">{quotes.QUOTE}</p>
					<div class="spacer"></div>
					<div class="left text-strong"><a href="{quotes.U_AUTHOR_LINK}" class="small">{quotes.AUTHOR}</a></div>
				</div>
				<footer></footer>
			</article>
		# END quotes #
	# ELSE #
		# IF NOT C_HIDE_NO_ITEM_MESSAGE #
		<div class="center">
			${LangLoader::get_message('no_item_now', 'common')}
		</div>
		# ENDIF #
	# ENDIF #
	</div>
	<footer># IF C_PAGINATION # # INCLUDE PAGINATION # # ENDIF #</footer>
</section>
