
<article id="news" style="order: {NEWS_POSITION}; -webkit-order: {NEWS_POSITION}; -ms-flex-order: {NEWS_POSITION}">
	<header>
		<h2>
			${Langloader::get_message('last.news', 'common', 'HomeLanding')}
			<span class="actions">
				<a href="{PATH_TO_ROOT}/news" title="${Langloader::get_message('link.to.news', 'common', 'HomeLanding')}">
					${Langloader::get_message('link.to.news', 'common', 'HomeLanding')}
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
			<div class="item-content# IF C_DISPLAY_BLOCK # block# ENDIF #" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">

				<h3 class="item-title">
					<a href="{item.U_LINK}">{item.NAME}</a>

					<span class="actions">
						# IF item.C_EDIT #
							<a href="{item.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
						# ENDIF #
						# IF item.C_DELETE #
							<a href="{item.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
						# ENDIF #
					</span>
				</h3>

				<div class="more">
					# IF item.C_AUTHOR_DISPLAYED #
						${LangLoader::get_message('by', 'common')}
						# IF item.C_AUTHOR_EXIST #<a itemprop="author" class="{item.USER_LEVEL_CLASS}" href="{item.U_AUTHOR_PROFILE}"# IF item.C_USER_GROUP_COLOR # style="{item.USER_GROUP_COLOR}"# ENDIF #>{item.PSEUDO}</a>, # ELSE #{item.PSEUDO}# ENDIF #
					# ENDIF #
					${TextHelper::lcfirst(LangLoader::get_message('the', 'common'))} <time datetime="{item.DATE_ISO8601}" itemprop="datePublished">{item.DATE_DAY}/{item.DATE_MONTH}/{item.DATE_YEAR}</time>
					${TextHelper::lcfirst(LangLoader::get_message('in', 'common'))} <a itemprop="about" href="{item.U_CATEGORY}">{item.CATEGORY_NAME}</a>
					# IF item.C_NB_VIEW_ENABLED #- <span>{item.NUMBER_VIEW} ${LangLoader::get_message('module.views', 'common', 'HomeLanding')}</span># ENDIF #
				</div>

				# IF item.C_PICTURE #
					<a href="{item.U_LINK}" class="item-picture">
						<img src="{item.U_PICTURE}" alt="{item.NAME}" />
					</a>
				# ENDIF #
				<p class="item-desc">
					<div itemprop="text">{item.DESCRIPTION}# IF item.C_READ_MORE #... <a href="{item.U_LINK}">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF #</div>
				</p>

			</div>
	# END item #
	</div>
	<footer></footer>
</article>
