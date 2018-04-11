
<article id="guestbook" style="order: {GUESTBOOK_POSITION}; -webkit-order: {GUESTBOOK_POSITION}; -ms-flex-order: {GUESTBOOK_POSITION}">
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
	<div class="elements-container columns-3 no-style">
		# IF C_EMPTY_GUESTBOOK #
		<div class="center">
			${LangLoader::get_message('empty.guestbook', 'common', 'HomeLanding')}
		</div>
		# ENDIF #
	# START item #
		<div class="item-content block" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">

			<img class="avatar" src="{item.U_AVATAR}" alt="{item.PSEUDO}" />

			<div class="more">
				<p>
					<i class="fa fa-fw fa-user"></i> # IF item.C_AUTHOR_EXIST #
					<a href="{item.U_AUTHOR_PROFILE}" class="{item.USER_LEVEL_CLASS}" # IF item.C_USER_GROUP_COLOR # style="color:{item.USER_GROUP_COLOR}" # ENDIF #>{item.PSEUDO}</a>
					# ELSE #
					{item.PSEUDO}
					# ENDIF #
				</p>
				<p><i class="fa fa-fw fa-clock-o"></i> {item.DATE}</p>
				<p><i class="fa fa-hand-o-right"></i> <a href="{item.U_ANCHOR}">${Langloader::get_message('guestbook.user.message', 'common', 'HomeLanding')}</a></p>
			</div>

			<p class="item-desc">
				{item.CONTENTS}# IF item.C_READ_MORE #...# ENDIF #
			</p>

		</div>
	# END item #
	</div>
	<footer></footer>
</article>
