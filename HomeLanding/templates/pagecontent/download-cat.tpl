
<article id="last_download_cat" style="order: {DOWNLOAD_CAT_POSITION}; -webkit-order: {DOWNLOAD_CAT_POSITION}; -ms-flex-order: {DOWNLOAD_CAT_POSITION}">
	<header>
		<h2>
			${Langloader::get_message('last.download.cat', 'common', 'HomeLanding')} {CATEGORY_NAME}
			<span class="actions">
				<a href="{PATH_TO_ROOT}/download" title="${Langloader::get_message('link.to.download', 'common', 'HomeLanding')}">
					${Langloader::get_message('link.to.download', 'common', 'HomeLanding')}
				</a>
			</span>
		</h2>
	</header>
	<div class="content"> 
		# IF C_NO_DOWNLOAD_ITEM #
		<div class="center">
			${LangLoader::get_message('no.download.item', 'common', 'HomeLanding')}
		</div>
		# ENDIF #
	# START item #
		<div class="item-content" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
			
			<h3 class="item-title">
				<a href="{item.U_LINK}">{item.NAME}</a>
			</h3>
			
			<div class="more">
				<i class="fa fa-user"></i> # IF item.C_AUTHOR_EXIST #<a itemprop="author" rel="author" class="{item.USER_LEVEL_CLASS}" href="{item.U_AUTHOR_PROFILE}"# IF item.C_USER_GROUP_COLOR # style="{item.USER_GROUP_COLOR}"# ENDIF #>{item.PSEUDO}</a># ELSE #{item.PSEUDO}# ENDIF # - 
				<i class="fa fa-clock-o"></i> {item.DATE} - <i class="fa fa-download"></i> {item.NUMBER_DOWNLOADS} 
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