
<article id="last_guestbook" style="order: {GUESTBOOK_POSITION}; -webkit-order: {GUESTBOOK_POSITION}; -ms-flex-order: {GUESTBOOK_POSITION}">
	<header>
		<h2>
			${Langloader::get_message('last.guestbook', 'common', 'HomeLanding')}
			<span class="actions">
				<a href="{PATH_TO_ROOT}/guestbook" title="${Langloader::get_message('link.to.guestbook', 'common', 'HomeLanding')}">
					${Langloader::get_message('link.to.guestbook', 'common', 'HomeLanding')}
				</a>
			</span>
		</h2>
	</header>
	<div class="content"> 
		# IF C_EMPTY_GUESTBOOK #
		<div class="center">
			${LangLoader::get_message('empty.guestbook', 'common', 'HomeLanding')}
		</div>
		# ENDIF #
	# START guestbook_items #	
		<div class="item-content" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
			
			<img class="avatar" src="{guestbook_items.U_AVATAR}" alt="{guestbook_items.PSEUDO}" />
			
			<div class="more">
				${LangLoader::get_message('by', 'common')}
				# IF guestbook_items.C_AUTHOR_EXIST #
				<a href="{guestbook_items.U_AUTHOR_PROFILE}" class="{guestbook_items.USER_LEVEL_CLASS}" # IF guestbook_items.C_USER_GROUP_COLOR # style="color:{guestbook_items.USER_GROUP_COLOR}" # ENDIF #>{guestbook_items.PSEUDO}</a>
				# ELSE #
				{guestbook_items.PSEUDO}
				# ENDIF #
				<p>{guestbook_items.DATE}</p>
			</div>
			
			<p class="item-desc">				
				{guestbook_items.CONTENTS}# IF guestbook_items.C_READ_MORE #...# ENDIF #
			</p>
			
		</div>	
	# END guestbook_items #
	</div>            
	<footer></footer>
</article>