
<article id="last_articles" style="order: {ARTICLES_POSITION}; -webkit-order: {ARTICLES_POSITION}; -ms-flex-order: {ARTICLES_POSITION}">
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
	<div class="content">
	# START articles_items #
		<div class="item-content">
			
			<h3>
				<a href="{articles_items.U_ARTICLE}">{articles_items.TITLE}</a>
				<span class="actions">
					# IF articles_items.C_EDIT #
						<a href="{articles_items.U_EDIT_ARTICLE}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
					# ENDIF #
					# IF articles_items.C_DELETE #
						<a href="{articles_items.U_DELETE_ARTICLE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
					# ENDIF #
				</span>
			</h3>
			
			<div class="more">
				# IF articles_items.C_AUTHOR_DISPLAYED #
					${LangLoader::get_message('by', 'common')}
					# IF articles_items.C_AUTHOR_EXIST #<a itemprop="author" href="{articles_items.U_AUTHOR}" class="{articles_items.USER_LEVEL_CLASS}" # IF C_USER_GROUP_COLOR # style="color:{articles_items.USER_GROUP_COLOR}"# ENDIF #>{articles_items.PSEUDO}</a># ELSE #{articles_items.PSEUDO}# ENDIF #,
				# ENDIF # 
				${TextHelper::lowercase_first(LangLoader::get_message('the', 'common'))} <time datetime="{articles_items.DATE_ISO8601}" itemprop="datePublished">{articles_items.DATE}</time> 
				${TextHelper::lowercase_first(LangLoader::get_message('in', 'common'))} <a itemprop="about" href="{articles_items.U_CATEGORY}">{articles_items.CATEGORY_NAME}</a>
			</div>
			
			# IF articles_items.C_HAS_PICTURE #
				<img class="item-picture" src="{articles_items.PICTURE}" alt="{articles_items.NAME}" />
			# ENDIF #
			<p class="item-desc">
				{articles_items.DESCRIPTION} ...
			</p>
			<a href="{articles_items.U_ARTICLE}">[${LangLoader::get_message('read-more', 'common')}]</a>
			
		</div>	
	# END articles_items #
	</div>
	<footer></footer>
</article>