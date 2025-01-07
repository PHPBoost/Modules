<section id="module-recipe" class="category-{CATEGORY_ID} single-item">
	<header class="section-header">
		<div class="controls align-right">
			<a class="offload" href="{U_SYNDICATION}" aria-label="{@common.syndication}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
			{@recipe.module.title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF #
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
			<article itemscope="itemscope" itemtype="https://schema.org/CreativeWork" id="recipe-item-{ID}" class="recipe-item# IF C_NEW_CONTENT # new-content# ENDIF #">
				# IF C_CONTROLS #
					<div class="controls align-right">
						# IF C_EDIT #<a class="offload" href="{U_EDIT}" aria-label="{@common.edit}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a># ENDIF #
						# IF C_DELETE #<a href="{U_DELETE}" aria-label="{@common.delete}" data-confirmation="delete-element"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a># ENDIF #
					</div>
				# ENDIF #

				<div class="content" itemprop="text">{CONTENT}</div>

				<div class="content cell-tile">
					<div class="cell cell-options">
						<div class="cell-header">
							<h6 class="cell-name">{@recipe.ingredients}</h6>
						</div>
						<div class="cell-list">
							<ul>
								<li class="li-stretch">
									<span><i class="fa fa-people-group" aria-hidden="true"></i></span>
									<span>{PERSONS_NUMBER}# IF C_SEVERAL_PERSONS # {@recipe.persons}# ELSE # {@recipe.person}# ENDIF #</span>
								</li>
								# IF C_INGREDIENTS #
									# START ingredients #
										<li class="li-stretch">
											<span>{ingredients.INGREDIENT}</span>
											<span>{ingredients.AMOUNT}</span>
										</li>
									# END ingredients #
								# ENDIF #
							</ul>
						</div>
						# IF C_HAS_THUMBNAIL #
							<div class="cell-body">
								<div class="cell-thumbnail">
									<img src="{U_THUMBNAIL}" alt="{TITLE}" itemprop="image" />
								</div>
							</div>
						# ENDIF #
						<div class="cell-list small">
							<ul>
								<li class="li-stretch">
									<time aria-label="{@common.creation.date}" datetime="# IF NOT C_DIFFERED #{DATE_ISO8601}# ELSE #{DIFFERED_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"><i class="fa fa-calendar-alt"></i> # IF NOT C_DIFFERED #{DATE}# ELSE #{DIFFERED_START_DATE}# ENDIF #</time>
									# IF C_HAS_UPDATE_DATE #<time aria-label="{@common.status.last.update}" datetime="{UPDATED_DATE_ISO8601}" itemprop="dateModified"><i class="fa fa-calendar-plus"></i> {UPDATE_DATE}</time># ENDIF #
								</li>
								# IF C_AUTHOR_DISPLAYED #
									<li class="li-stretch">
										<span aria-label="{@common.author}">
											# IF C_AUTHOR_CUSTOM_NAME #
												<span class="custom-author">{AUTHOR_CUSTOM_NAME}</span>
											# ELSE #
											# IF C_AUTHOR_EXISTS #<a itemprop="author" rel="author" class="{AUTHOR_LEVEL_CLASS} offload" href="{U_AUTHOR_PROFILE}" # IF C_AUTHOR_GROUP_COLOR # style="color:{AUTHOR_GROUP_COLOR}" # ENDIF #>{AUTHOR_DISPLAY_NAME}</a># ELSE #<span class="visitor">{AUTHOR_DISPLAY_NAME}</span># ENDIF #
											# ENDIF #
										</span>
										# IF C_ENABLED_VIEWS_NUMBER #<span aria-label="{@common.views.number}"><i class="fa fa-eye"></i> {VIEWS_NUMBER}</span></li># ENDIF #
									</li>
								# ENDIF #								
								<li class="li-stretch">
									<span># IF C_ENABLED_COMMENTS ## IF C_COMMENTS # {COMMENTS_NUMBER} # ENDIF # {L_COMMENTS}# ENDIF #</span>
									<span># IF C_ENABLED_NOTATION #{NOTATION}# ENDIF #</span>
								</li>
							</ul>
						</div>
					</div>
					# IF C_STEPS #
						<div class="steps">
							# START steps #
								<div style="order: {steps.STEP_NUMBER}">
									<h6>{@recipe.step} {steps.STEP_NUMBER}</h6>
									{steps.STEP_CONTENT}
								</div>
							# END steps #
						</div>
					# ENDIF #
				</div>

				<aside class="sharing-container">${ContentSharingActionsMenuService::display()}</aside>

				# IF C_SOURCES #
					<aside class="ingredients-container">
						<span class="text-strong"><i class="fa fa-map-signs" aria-hidden="true"></i> {@common.ingredients}</span> :
						# START ingredients #
							<a itemprop="isBasedOnUrl" href="{ingredients.URL}" class="pinned link-color offload" rel="nofollow">{ingredients.NAME}</a># IF ingredients.C_SEPARATOR ## ENDIF #
						# END ingredients #
					</aside>
				# ENDIF #
				# IF C_KEYWORDS #
					<aside class="tags-container">
						<span class="text-strong"><i class="fa fa-tags" aria-hidden="true"></i> {@common.keywords} : </span>
						# START keywords #
							<a class="pinned link-color offload" href="{keywords.URL}">{keywords.NAME}</a># IF keywords.C_SEPARATOR #, # ENDIF #
						# END keywords #
					</aside>
				# ENDIF #
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
