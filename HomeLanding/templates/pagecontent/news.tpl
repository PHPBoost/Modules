
<article id="last_news" style="order: {NEWS_POSITION}; -webkit-order: {NEWS_POSITION}; -ms-flex-order: {NEWS_POSITION}">
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
	<div class="content">
	# START news_items #
		<div class="item-content" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">

			<h3 class="item-title">
				<a href="{news_items.U_LINK}">{news_items.NAME}</a>

				<span class="actions">
					# IF news_items.C_EDIT #
						<a href="{news_items.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
					# ENDIF #
					# IF news_items.C_DELETE #
						<a href="{news_items.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
					# ENDIF #
				</span>
			</h3>

			<div class="more">
				# IF news_items.C_AUTHOR_DISPLAYED #
					${LangLoader::get_message('by', 'common')}
					# IF news_items.C_AUTHOR_EXIST #<a itemprop="author" class="{news_items.USER_LEVEL_CLASS}" href="{news_items.U_AUTHOR_PROFILE}"# IF news_items.C_USER_GROUP_COLOR # style="{news_items.USER_GROUP_COLOR}"# ENDIF #>{news_items.PSEUDO}</a>, # ELSE #{news_items.PSEUDO}# ENDIF #
				# ENDIF #
				${TextHelper::lcfirst(LangLoader::get_message('the', 'common'))} <time datetime="{news_items.DATE_ISO8601}" itemprop="datePublished">{news_items.DATE_DAY}/{news_items.DATE_MONTH}/{news_items.DATE_YEAR}</time>
				${TextHelper::lcfirst(LangLoader::get_message('in', 'common'))} <a itemprop="about" href="{news_items.U_CATEGORY}">{news_items.CATEGORY_NAME}</a>
			</div>

			# IF news_items.C_PICTURE #
				<img class="item-picture" src="{news_items.U_PICTURE}" alt="{news_items.NAME}" />
			# ENDIF #
			<p class="item-desc">
				<div itemprop="text">{news_items.DESCRIPTION}# IF news_items.C_READ_MORE #... <a href="{news_items.U_LINK}">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF #</div>
			</p>

		</div>
	# END news_items #
	</div>
	<footer></footer>
</article>
