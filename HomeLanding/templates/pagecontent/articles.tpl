
<article id="articles" style="order: {ARTICLES_POSITION}; -webkit-order: {ARTICLES_POSITION}; -ms-flex-order: {ARTICLES_POSITION}">
	<header>
		<h2>
			${Langloader::get_message('last.articles', 'common', 'HomeLanding')}
			<span class="actions">
				<a href="{PATH_TO_ROOT}/articles" title="${Langloader::get_message('link.to.articles', 'common', 'HomeLanding')}">
					${Langloader::get_message('link.to.articles', 'common', 'HomeLanding')}
				</a>
			</span>
		</h2>
	</header>
	# IF C_DISPLAY_BLOCK #
		<div class="elements-container columns-{COL_NBR}">
	# ELSE #
		<div class="content">
	# ENDIF #
	# START item #
		<div class="item-content# IF C_DISPLAY_BLOCK # block# ENDIF #">

			<h3>
				<a href="{item.U_ARTICLE}">{item.TITLE}</a>
				<span class="actions">
					# IF item.C_EDIT #
						<a href="{item.U_EDIT_ARTICLE}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
					# ENDIF #
					# IF item.C_DELETE #
						<a href="{item.U_DELETE_ARTICLE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
					# ENDIF #
				</span>
			</h3>

			<div class="more">
				# IF item.C_AUTHOR_DISPLAYED #
					${LangLoader::get_message('by', 'common')}
					# IF item.C_AUTHOR_EXIST #<a itemprop="author" href="{item.U_AUTHOR}" class="{item.USER_LEVEL_CLASS}" # IF C_USER_GROUP_COLOR # style="color:{item.USER_GROUP_COLOR}"# ENDIF #>{item.PSEUDO}</a># ELSE #{item.PSEUDO}# ENDIF #,
				# ENDIF #
				${TextHelper::lcfirst(LangLoader::get_message('the', 'common'))} <time datetime="{item.DATE_ISO8601}" itemprop="datePublished">{item.DATE_SHORT}</time>
				${TextHelper::lcfirst(LangLoader::get_message('in', 'common'))} <a itemprop="about" href="{item.U_CATEGORY}">{item.CATEGORY_NAME}</a> - {item.NUMBER_VIEW} ${LangLoader::get_message('module.views', 'common', 'HomeLanding')}
			</div>

			# IF item.C_HAS_PICTURE #
				<a href="{item.U_ARTICLE}" class="item-picture"><img src="{item.PICTURE}" alt="{item.TITLE}" /></a>
			# ENDIF #
			<p class="item-desc">
				{item.DESCRIPTION}# IF item.C_READ_MORE #... <a href="{item.U_ARTICLE}">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF #
			</p>

		</div>
	# END item #
	</div>
	<footer></footer>
</article>
