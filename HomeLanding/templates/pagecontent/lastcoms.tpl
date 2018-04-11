
<article id="lastcoms" style="order: {LASTCOMS_POSITION}; -webkit-order: {LASTCOMS_POSITION}; -ms-flex-order: {LASTCOMS_POSITION}">
	<header>
		<h2>
			${Langloader::get_message('last.comments', 'common', 'HomeLanding')}
		</h2>
	</header>
	<div class="elements-container columns-3 no-style">
		# IF C_NO_COMMENT #
		<div class="center">
			${LangLoader::get_message('no.comment', 'common', 'HomeLanding')}
		</div>
		# ENDIF #
		# START item #
			<div class="item-content block">
				<img class="avatar" src="{item.U_AVATAR}" alt="{item.PSEUDO}" />
				<div class="more">
					<p>
						<i class="fa fa-fw fa-user"></i> # IF item.C_AUTHOR_EXIST #
							<a class="{item.USER_LEVEL_CLASS}" href="{item.U_AUTHOR_PROFILE}"# IF item.C_USER_GROUP_COLOR # style="{item.USER_GROUP_COLOR}"# ENDIF #>{item.PSEUDO}</a>
						# ELSE #
							{item.PSEUDO}
						# ENDIF #
					</p>
					<p><i class="fa fa-fw fa-clock-o"></i> {item.DATE}</p>
					<p><i class="fa fa-fw fa-cube"></i> <a href="{item.ARTICLE}">{item.MODULE_NAME}</a></p>
				</div>
				<p class="item-desc">
					<a href="{item.U_LINK}"><i class="fa fa-hand-o-right"></i> </a>{item.CONTENTS}# IF item.C_READ_MORE #... <a href="{item.U_LINK}">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF #
				</p>
			</div>
		# END item #
	</div>
	<footer></footer>
</article>
