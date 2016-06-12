
<article id="last_coms" style="order: {LASTCOMS_POSITION}; -webkit-order: {LASTCOMS_POSITION}; -ms-flex-order: {LASTCOMS_POSITION}">
	<header>
		<h2>
			${Langloader::get_message('last.comments', 'common', 'HomeLanding')}
		</h2>
	</header>
	<div class="content">
		# IF C_NO_COMMENT #
		<div class="center">
			${LangLoader::get_message('no.comment', 'common', 'HomeLanding')}
		</div>
		# ENDIF #
		# START lastcoms_items #
		<li>
			# IF lastcoms_items.C_AUTHOR_EXIST #<a class="{lastcoms_items.USER_LEVEL_CLASS}" href="{lastcoms_items.U_AUTHOR_PROFILE}"# IF lastcoms_items.C_USER_GROUP_COLOR # style="{lastcoms_items.USER_GROUP_COLOR}"# ENDIF #>{lastcoms_items.PSEUDO}</a># ELSE #{lastcoms_items.PSEUDO}# ENDIF #
			${Langloader::get_message('in.modules', 'common', 'HomeLanding')} <i><a href="{lastcoms_items.ARTICLE}">{lastcoms_items.MODULE_NAME}</a></i>
			({lastcoms_items.DATE}) : {lastcoms_items.CONTENTS}# IF lastcoms_items.C_READ_MORE #... <a href="{lastcoms_items.U_LINK}">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF #
		</li>
		# END lastcoms_items #
	</div>            
	<footer></footer>
</article>