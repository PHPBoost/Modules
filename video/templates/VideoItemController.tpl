<section id="module-video" class="category-{CATEGORY_ID} single-item">
	<header class="section-header">
		<div class="controls align-right">
			<a class="offload" href="{U_SYNDICATION}" aria-label="{@common.syndication}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
			{@video.module.title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF #
			# IF IS_ADMIN #<a class="offload" href="{U_EDIT_CATEGORY}" aria-label="{@common.edit}"><i class="far fa-edit" aria-hidden="true"></i></a># ENDIF #
		</div>
		<h1><span id="name" itemprop="name">{TITLE}</span></h1>
	</header>
	<div class="sub-section">
		<div class="content-container">
			# IF NOT C_VISIBLE #
				<div class="content">
					# INCLUDE NOT_VISIBLE_MESSAGE #
				</div>
			# ENDIF #
			<article itemscope="itemscope" itemtype="https://schema.org/CreativeWork" id="video-item-{ID}" class="video-item# IF C_NEW_CONTENT # new-content# ENDIF #">
				<div class="flex-between">
					<div class="more">
						# IF C_AUTHOR_DISPLAYED #
							<span class="pinned" aria-label="{@common.author}">
								<i class="fa fa-user" aria-hidden="true"></i>
								# IF C_AUTHOR_CUSTOM_NAME #
									<span class="custom-author">{AUTHOR_CUSTOM_NAME}</span>
								# ELSE #
									# IF C_AUTHOR_EXISTS #
										<a itemprop="author" rel="author" class="{AUTHOR_LEVEL_CLASS} offload" href="{U_AUTHOR_PROFILE}" # IF C_AUTHOR_GROUP_COLOR # style="color:{AUTHOR_GROUP_COLOR}" # ENDIF #>{AUTHOR_DISPLAY_NAME}</a>
									# ELSE #
										{AUTHOR_DISPLAY_NAME}
									# ENDIF #
								# ENDIF #
							</span>
						# ENDIF #
						<span class="pinned">
							<i class="fa fa-calendar-alt" aria-hidden="true"></i>
							# IF C_HAS_UPDATE_DATE #
								<time aria-label="{@common.last.update}" datetime="{UPDATED_DATE_ISO8601}" itemprop="dateModified">{UPDATE_DATE}</time>
							# ELSE #
								<time aria-label="{@common.creation.date}" datetime="# IF C_DIFFERED #{DIFFERED_START_DATE_ISO8601}# ELSE #{DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF C_DIFFERED #{DIFFERED_START_DATE}# ELSE #{DATE}# ENDIF #</time>
							# ENDIF #
						</span>
						<span class="pinned" aria-label="{@common.category}">
							<i class="fa fa-folder" aria-hidden="true"></i>
							<a class="offload" itemprop="about" href="{U_CATEGORY}">{CATEGORY_NAME}</a>
						</span>
						# IF C_ENABLED_VIEWS_NUMBER #<span class="pinned" aria-label="{@common.views.number}"><i class="fa fa-eye" aria-hidden="true"></i> {VIEWS_NUMBER}</span># ENDIF #
						# IF C_ENABLED_COMMENTS #<span class="pinned" aria-label="{@common.comments}"><i class="fa fa-comment" aria-hidden="true"></i> # IF C_COMMENTS # {COMMENTS_NUMBER} # ENDIF # {L_COMMENTS}</span># ENDIF #
						# IF C_VISIBLE #
							# IF C_ENABLED_NOTATION #
								<div class="pinned">{NOTATION}</div>
							# ENDIF #
						# ENDIF #
					</div>
					<div class="controls align-right">
						# IF C_VISIBLE #
							# IF IS_USER_CONNECTED #
								<a href="{U_DEADLINK}" data-confirmation="{@contribution.dead.link.confirmation}" aria-label="{@contribution.report.dead.link}">
									<i class="fa fa-unlink" aria-hidden="true"></i>
								</a>
							# ENDIF #
						# ENDIF #
						# IF C_CONTROLS #
							# IF C_EDIT #<a class="offload" href="{U_EDIT}" aria-label="{@common.edit}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a># ENDIF #
							# IF C_DELETE #<a href="{U_DELETE}" aria-label="{@common.delete}" data-confirmation="delete-element"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a># ENDIF #
						# ENDIF #
					</div>					
				</div>
				# IF C_KEYWORDS #
					<div class="more">
						# START keywords #
							<a class="pinned link-color offload" href="{keywords.URL}">\#{keywords.NAME}</a>
						# END keywords #
					</div>
				# ENDIF #

				<div class="content">
					# INCLUDE VIDEO_FORMAT #
					<div itemprop="text">{CONTENT}</div>
				</div>

				<aside>${ContentSharingActionsMenuService::display()}</aside>

				# IF C_ENABLED_COMMENTS #
					<aside>
						# INCLUDE COMMENTS #
					</aside>
				# ENDIF #
			</article>
		</div>
	</div>
	<footer>
		<meta itemprop="url" content="{U_ITEM}">
		<meta itemprop="description" content="${escape(SUMMARY)}" />
		# IF C_ENABLED_COMMENTS #
			<meta itemprop="discussionUrl" content="{U_COMMENTS}">
			<meta itemprop="interactionCount" content="{COMMENTS_NUMBER} UserComments">
		# ENDIF #
	</footer>
</section>
