<article id="{MODULE_NAME}" style="order: {MODULE_POSITION};">
	<header>
		<h2>{L_MODULE_TITLE}</h2>
		# IF C_MODULE_LINK #
			<div class="controls align-right">
				<a href="{PATH_TO_ROOT}/{MODULE_NAME}">{L_SEE_ALL_ITEMS}</a>
			</div>
		# ENDIF #
	</header>
	# IF C_NO_ITEM #
		<div class="message-helper bgc notice">
			${LangLoader::get_message('no_item_now', 'common')}
		</div>
	# ELSE #
		# START item #
			<div class="message-container message-small" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
				<header class="message-header-container">
					<img class="message-user-avatar" src="{item.U_AVATAR}" alt="{item.PSEUDO}" />

					<div class="message-header-infos">
						<div class="message-user">
							<h4>
								# IF item.C_AUTHOR_EXIST #
									<a class="{item.USER_LEVEL_CLASS}" href="{item.U_AUTHOR_PROFILE}"# IF item.C_USER_GROUP_COLOR # style="{item.USER_GROUP_COLOR}"# ENDIF #>{item.PSEUDO}</a>
								# ELSE #
									{item.PSEUDO}
								# ENDIF #
							</h4>
						</div>
						<div class="message-infos">
							<span aria-label="{@module.post.date}">
								<i class="far fa-fw fa-clock" aria-hidden="true"></i> {item.DATE}
							</span>
							# IF C_PARENT #
								<span aria-label="# IF C_TOPIC #{@module.posted.in.topic}# ELSE #{@module.posted.in.module}# ENDIF #">
									<i class="fa fa-fw # IF C_TOPIC #fa-file# ELSE #fa-cube# ENDIF #" aria-hidden="true"></i>  <a href="{item.U_TOPIC}">{item.TOPIC}</a>
								</span>
							# ENDIF #
						</div>
					</div>
				</header>
				<div class="message-content">
					{item.CONTENTS} ...
					<p class="align-right"><a href="{item.U_ITEM}" class="button small bgc link-color">[${LangLoader::get_message('read-more', 'common')}]</a></p>
				</div>
			</div>
		# END item #
	# ENDIF #
	<footer></footer>
</article>
