
<article id="last_news_cat" style="order: {NEWS_CAT_POSITION}; -webkit-order: {NEWS_CAT_POSITION}; -ms-flex-order: {NEWS_CAT_POSITION}">
	<header>
		<h2>
			${Langloader::get_message('last.news.cat', 'common', 'HomeLanding')} {CATEGORY_NAME}
			<span class="actions">
				<a href="{PATH_TO_ROOT}/news" title="${Langloader::get_message('link.to.news', 'common', 'HomeLanding')}">
					${Langloader::get_message('link.to.news', 'common', 'HomeLanding')}
				</a>
			</span>
		</h2>
	</header>
	<div class="content"> 
		# IF C_NO_NEWS_ITEM #
		<div class="center">
			${LangLoader::get_message('no.news.item', 'common', 'HomeLanding')}
		</div>
		# ENDIF #
	# START item #
		<div class="item-content" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
			
			<h3 class="item-title">
				<a href="{item.U_LINK}">{item.NAME}</a>
			</h3>
			
			<div class="more">
				# IF item.C_AUTHOR_DISPLAYED #
				<i class="fa fa-user"></i> # IF item.C_AUTHOR_EXIST #<a itemprop="author" class="{item.USER_LEVEL_CLASS}" href="{item.U_AUTHOR_PROFILE}"# IF item.C_USER_GROUP_COLOR # style="{item.USER_GROUP_COLOR}"# ENDIF #>{item.PSEUDO}</a># ELSE #{item.PSEUDO}# ENDIF # - 
				# ENDIF #
				<i class="fa fa-clock-o"></i> <time datetime="{item.DATE_ISO8601}" itemprop="datePublished">{item.DATE_DAY}/{item.DATE_MONTH}/{item.DATE_YEAR}</time>
			</div>
			
			# IF item.C_PICTURE #
				<img class="item-picture" src="{item.U_PICTURE}" alt="{item.NAME}" itemprop="image" />
			# ENDIF #
			
			<p class="item-desc">
				<div itemprop="text"># IF item.C_DESCRIPTION #{item.DESCRIPTION}# ELSE #{item.CONTENTS}# ENDIF ## IF item.C_READ_MORE #... <a href="{item.U_LINK}">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF #</div>
			</p>
			
		</div>
	# END item #
	</div>            
	<footer></footer>
</article>