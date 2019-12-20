
<article id="articles-cat" style="order: {ARTICLES_CAT_POSITION};">
	<header>
		<h2>
			${Langloader::get_message('last.articles.cat', 'common', 'HomeLanding')} {CATEGORY_NAME}
		</h2>
		<span class="controls">
			<a href="{PATH_TO_ROOT}/articles">
				${Langloader::get_message('link.to.articles', 'common', 'HomeLanding')}
			</a>
		</span>
	</header>
	# IF C_DISPLAY_GRID_VIEW #
		<div class="elements-container columns-{ITEMS_PER_ROW}">
	# ELSE #
		<div class="content">
	# ENDIF #
		# IF C_NO_ARTICLES_ITEM #
		<div class="align-center">
			${LangLoader::get_message('no.articles.item', 'common', 'HomeLanding')}
		</div>
		# ENDIF #
	# START item #
		<div class="item-content# IF C_DISPLAY_GRID_VIEW # block# ENDIF #" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">

			<h3 class="item-title">
				<a href="{item.U_ARTICLE}">{item.TITLE}</a>
			</h3>
			<span class="controls">
				# IF item.C_EDIT #
					<a href="{item.U_EDIT_ARTICLE}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true"></i></a>
				# ENDIF #
				# IF item.C_DELETE #
					<a href="{item.U_DELETE_ARTICLE}" aria-label="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
				# ENDIF #
			</span>

			<div class="more">
				# IF item.C_AUTHOR_DISPLAYED #
				<i class="fa fa-user"></i> # IF item.C_AUTHOR_EXIST #<a itemprop="author" class="{item.USER_LEVEL_CLASS}" href="{item.U_AUTHOR}"# IF item.C_USER_GROUP_COLOR # style="{item.USER_GROUP_COLOR}"# ENDIF #>{item.PSEUDO}</a># ELSE #{item.PSEUDO}# ENDIF # -
				# ENDIF #
				<i class="fa fa-clock"></i> <time datetime="{item.DATE_ISO8601}" itemprop="datePublished">{item.DATE_SHORT}</time> - <i class="fa fa-eye"></i> {item.NUMBER_VIEW}
			</div>

			# IF item.C_HAS_PICTURE #
				<a href="{item.U_ARTICLE}" class="item-picture"><img src="{item.PICTURE}" alt="{item.TITLE}" itemprop="thumbnailUrl" /></a>
			# ENDIF #

			<p class="item-desc">
				<div itemprop="text"># IF item.C_DESCRIPTION #{item.DESCRIPTION}# ELSE #{item.CONTENTS}# ENDIF ## IF item.C_READ_MORE #... <a href="{item.U_ARTICLE}">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF #</div>
			</p>

		</div>
	# END item #
	</div>
	<footer></footer>
</article>
