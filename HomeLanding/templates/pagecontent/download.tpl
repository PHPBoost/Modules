
<article id="last_download" style="order: {DOWNLOAD_POSITION}; -webkit-order: {DOWNLOAD_POSITION}; -ms-flex-order: {DOWNLOAD_POSITION}">
	<header>
		<h2>
			${Langloader::get_message('last.download', 'common', 'HomeLanding')}
			<span class="actions">
				<a href="{PATH_TO_ROOT}/download" title="${Langloader::get_message('link.to.download', 'common', 'HomeLanding')}">
					${Langloader::get_message('link.to.download', 'common', 'HomeLanding')}
				</a>
			</span>
		</h2>
	</header>
	<div class="content"> 
	# START download_items #	
		<div class="item-content">
			
			<h3>
				<a href="{download_items.U_LINK}">{download_items.NAME}</a>
				<span class="actions">
					# IF download_items.C_EDIT #
						<a href="{download_items.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
					# ENDIF #
					# IF download_items.C_DELETE #
						<a href="{download_items.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
					# ENDIF #
				</span>
			</h3>
			
			<div class="more">
				# IF download_items.C_AUTHOR_DISPLAYED #
					${LangLoader::get_message('by', 'common')}
					# IF download_items.C_AUTHOR_EXIST #<a itemprop="author" href="{download_items.U_AUTHOR}" class="{download_items.USER_LEVEL_CLASS}" # IF C_USER_GROUP_COLOR # style="color:{download_items.USER_GROUP_COLOR}"# ENDIF #>{download_items.PSEUDO}</a># ELSE #{download_items.PSEUDO}# ENDIF #,
				# ENDIF # 
				${TextHelper::lowercase_first(LangLoader::get_message('the', 'common'))} <time datetime="{download_items.DATE_ISO8601}" itemprop="datePublished">{download_items.DATE}</time> 
				${TextHelper::lowercase_first(LangLoader::get_message('in', 'common'))} <a itemprop="about" href="{download_items.U_CATEGORY}">{download_items.CATEGORY_NAME}</a>
			</div>
			
			# IF download_items.C_PICTURE #
				<img class="item-picture" src="{download_items.U_PICTURE}" alt="{download_items.NAME}" />
			# ENDIF #
			<p class="item-desc">
				{download_items.DESCRIPTION}...
			</p>
			<a href="{download_items.U_LINK}">[${LangLoader::get_message('read-more', 'common')}]</a>
			
		</div>		
	# END download_items #
	</div>            
	<footer></footer>
</article>