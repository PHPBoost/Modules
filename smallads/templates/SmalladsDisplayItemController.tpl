<section id="smallads-module" class="category-{ID_CATEGORY}">
	<header>
		<div class="align-right controls">
			<a href="{U_SYNDICATION}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-fw fa-rss warning" aria-hidden="true"></i><span class="sr-only">${LangLoader::get_message('syndication', 'common')}</span></a>
			{@smallads.module.title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF #
			# IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-fw fa-edit" aria-hidden="true"></i><span class="sr-only">${LangLoader::get_message('edit', 'common')}</span></a># ENDIF #
			<p>{SMALLAD_TYPE}# IF C_COMPLETED # - <span class="pinned bgc error">{@smallads.completed.item}</span># ENDIF #</p>
		</div>
		<h1>{TITLE}</h1>
	</header>
	# INCLUDE NOT_VISIBLE_MESSAGE #
	<div itemscope="itemscope" itemtype="http://schema.org/Smallad" id="article-smallads-{ID}" class="article-smallads# IF C_NEW_CONTENT # new-content# ENDIF #">
		<div class="flex-between">
			<div></div>
			<div class="controls">
				# IF NOT C_COMPLETED ## IF C_EDIT #<a href="{U_EDIT_ITEM}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-fw fa-edit" aria-hidden="true"></i><span class="sr-only">${LangLoader::get_message('edit', 'common')}</span></a># ENDIF ## ENDIF #
				# IF C_DELETE #<a href="{U_DELETE_ITEM}" aria-label="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-fw fa-trash-alt" aria-hidden="true"></i><span class="sr-only">${LangLoader::get_message('delete', 'common')}</span></a># ENDIF #
			</div>
		</div>

		<div class="content">
			<div class="cell-options cell-tile">
				<div class="cell">
					# IF C_HAS_THUMBNAIL #
						<div class="cell-body">
							<div class="cell-thumbnail cell-landscape">
								<img src="{U_THUMBNAIL}" alt="{TITLE}" />
								<a class="cell-thumbnail-caption" href="{U_THUMBNAIL}" aria-label="{TITLE}" data-lightbox="formatter" data-rel="lightcase:collection"><i class="fa fa-eye"></i></a>
							</div>
						</div>
					# ENDIF #
					<div class="cell-list">
						<ul>
							# IF C_PRICE #
								<li>
									<span></span>
									<div class="smallad-price">{PRICE} {CURRENCY}</div>
								</li>
							# ENDIF #
							# IF C_LOCATION #
								<li>
									# IF C_GMAP #
										# IF IS_LOCATED #
											{@location} : {LOCATION}
										# ENDIF #
									# ELSE #
										# IF IS_LOCATED #
											# IF C_OTHER_LOCATION #
												{@other.country} : {OTHER_LOCATION}
											# ELSE #
												{@location} : {LOCATION}
											# ENDIF #
										# ENDIF #
									# ENDIF #
								</li>
							# ENDIF #
							# IF NOT C_COMPLETED #
								# IF C_CONTACT #
									<li class="li-stretch">
										<span>{@smallads.contact} :</span>
										<div class="modal-container cell-modal cell-tile">
											# IF C_DISPLAYED_AUTHOR_EMAIL #
												<a href="#email-modal" data-modal data-target="email-modal" class="email-modal-btn" aria-label="{@smallads.contact.email} - {@open.modal}"><i class="fa fa-fw fa-at" aria-hidden="true"></i><span class="sr-only">{@smallads.contact.email}</span></a>
												<div id="email-modal" class="modal modal-animation">
													<div class="close-modal" role="button" aria-label="{@close.modal}"></div>
													<div class="content-panel cell">
														<div class="cell-body">
															# IF C_CONTACT_LEVEL #
																# INCLUDE MSG #
																# IF NOT C_SMALLAD_EMAIL_SENT #
																	# INCLUDE EMAIL_FORM #
																# ENDIF #
															# ELSE #
																<div class="warning is-not-connected">
																	<a href="#email-modal-close" class="modal-close" aria-label="{@close.modal}"><i class="fa fa-fw fa-times" aria-hidden="true"></i><span class="sr-only">{@close.modal}</span></a>
																	{@smallads.email.modal}
																</div>
															# ENDIF #
														</div>
													</div>
												</div>
											# ENDIF #
											# IF C_DISPLAYED_AUTHOR_PM #
												<a href="{U_AUTHOR_PM}" class="smallad-pm" aria-label="{@smallads.contact.pm}"><i class="fa fa-fw fa-envelope" aria-hidden="true"></i></a>
											 # ENDIF #

											# IF C_DISPLAYED_AUTHOR_PHONE #
												<a href="#" data-modal data-target="tel-modal" aria-label="{@smallads.contact.phone} - {@open.modal}"><i class="fa fa-fw fa-phone" aria-hidden="true"></i><span class="sr-only">{@smallads.contact.phone}</span></a>
												<div id="tel-modal" class="modal modal-animation">
													<div class="close-modal" role="button" aria-label="{@close.modal}"></div>
													<div class="content-panel cell">
														<div class="cell-body">
															<div class="cell-content align-center">
																# IF C_CONTACT_LEVEL #
																	<div class="tel-form is-connected">
																		{AUTHOR_PHONE}
																	</div>
																# ELSE #
																	<div class="warning is-not-connected">
																		{@smallads.tel.modal}
																	</div>
																# ENDIF #
															</div>
														</div>
													</div>
												</div>
											# ENDIF #
										</div>
									</li>
								# ENDIF #
								# IF C_DISPLAYED_AUTHOR #
									<li class="li-stretch">
										<span aria-label="${LangLoader::get_message('author', 'common')}"><i class="fa fa-fw fa-user" aria-hidden="true"></i></span>
										<span>
											# IF C_CUSTOM_AUTHOR_NAME #
												{CUSTOM_AUTHOR_NAME}
											# ELSE #
												# IF C_AUTHOR_EXIST #<a itemprop="author" href="{U_AUTHOR}" class="{USER_LEVEL_CLASS}" # IF C_USER_GROUP_COLOR # style="color:{USER_GROUP_COLOR}"# ENDIF #>{PSEUDO}</a># ELSE #{PSEUDO}# ENDIF #
											# ENDIF #
										</span>
									</li>
								# ENDIF #
								<li class="li-stretch">
									<span aria-label="${LangLoader::get_message('form.approbation', 'common')}"><i class="fa fa-fw fa-calendar-alt" aria-hidden="true"></i> </span>
									<time datetime="# IF NOT C_DIFFERED #{DATE_ISO8601}# ELSE #{PUBLICATION_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF NOT C_DIFFERED #{DATE}# ELSE #{PUBLICATION_START_DATE}# ENDIF #</time>
								</li>
								<li class="li-stretch">
									<span role="contentinfo" aria-label="${LangLoader::get_message('sort_by.views.number', 'common')}"><i class="fa fa-fw fa-eye" aria-hidden="true"></i> </span>
									{VIEWS_NUMBER}
								</li>
								# IF C_COMMENTS_ENABLED #
									<li class="li-stretch">
										<span aria-label="${LangLoader::get_message('sort_by.comments.number', 'common')}"><i class="fa fa-fw fa-comment" aria-hidden="true"></i></span>
										<a itemprop="discussionUrl" class="small" href="{U_COMMENTS}"> {L_COMMENTS}</a>
									</li>
								# ENDIF #
								# IF C_KEYWORDS #
									<li class="li-stretch">
										<i class="fa fa-fw fa-tags" aria-hidden="true"></i> <span class="sr-only">${LangLoader::get_message('form.keywords', 'common')}</span>
										<span>
											# START keywords #
												<a itemprop="keywords" href="{keywords.URL}">{keywords.NAME}</a># IF keywords.C_SEPARATOR #, # ENDIF #
											# END keywords #
										</span>
									</li>
								# ENDIF #
							# ENDIF #
						</ul>
					</div>
				</div>
			</div>

			# IF C_CAROUSEL #
			 	# START carousel #
					<a href="{carousel.U_PICTURE}" aria-label="{carousel.DESCRIPTION}" data-lightbox="formatter" data-rel="lightcase:collection">
						<figure class="carousel-thumbnail">
							<img src="{carousel.U_PICTURE}" alt="{carousel.DESCRIPTION}" />
							<figcaption>{carousel.DESCRIPTION}</figcaption>
						</figure>
					</a>
			 	# END carousel #
			# ENDIF #

			<div# IF C_COMPLETED # class="error"# ENDIF # itemprop="text">
				# IF C_COMPLETED #<p class="pinned bgc error larger">{@smallads.completed.item}</p># ENDIF #
				{CONTENTS}
			</div>


			<div class="spacer"></div>
		</div>
		# IF C_SOURCES #
			<aside class="sources-container">
				<span class="text-strong"><i class="fa fa-map-signs" aria-hidden="true"></i> ${LangLoader::get_message('form.sources', 'common')}</span> :
				# START sources #
					<span class="pinned question">
						<a href="{sources.URL}" itemprop="isBasedOnUrl" rel="nofollow">{sources.NAME}</a>
					</span># IF sources.C_SEPARATOR ## ENDIF #
				# END sources #
			</aside>
		# ENDIF #
		# IF C_UPDATED_DATE #
			<aside>
				<i>${LangLoader::get_message('form.date.update', 'common')} : <time datetime="{UPDATED_DATE_ISO8601}" itemprop="datePublished">{UPDATED_DATE_FULL}</time></i>
			</aside>
		# ENDIF #

		# IF C_SUGGESTED_ITEMS #
			<aside class="suggested-links">
				<span><i class="fa fa-fw fa-lightbulb" aria-hidden="true"></i> ${LangLoader::get_message('suggestions', 'common')} :</span>
				<ul>
					# START suggested_items #
						<li>
							<a href="{suggested_items.U_ITEM}# IF suggested_items.C_COMPLETED # error# ENDIF #" class="suggested-item">
								<img src="{suggested_items.U_THUMBNAIL}" alt="{suggested_items.TITLE}" /> {suggested_items.TITLE}
							</a>
						</li>
					# END suggested_items #
				</ul>
			</aside>
		# ENDIF #

		# IF C_RELATED_LINKS #
			<aside>
				<div class="related-links">
					# IF C_PREVIOUS_ITEM #
						<a class="related-item previous-item# IF C_PREVIOUS_COMPLETED # error# ENDIF #" href="{U_PREVIOUS_ITEM}">
							<i class="fa fa-chevron-left"></i>
							<img src="{U_PREVIOUS_THUMBNAIL}" alt="{PREVIOUS_ITEM}">
							{PREVIOUS_ITEM}
						</a>
					# ENDIF #
					# IF C_NEXT_ITEM #
						<a class="related-item next-item# IF C_NEXT_COMPLETED # error# ENDIF #" href="{U_NEXT_ITEM}">
							{NEXT_ITEM}
							<img src="{U_NEXT_THUMBNAIL}" alt="{NEXT_ITEM}">
							<i class="fa fa-chevron-right"></i>
						</a>
					# ENDIF #
				</div>
			</aside>
		# ENDIF #

		<aside>
			${ContentSharingActionsMenuService::display()}
		</aside>

		<aside>
			# IF C_COMMENTS_ENABLED #
				# INCLUDE COMMENTS #
			# ENDIF #
		</aside>
		<footer># IF C_USAGE_TERMS # <i class="fa fa-fw fa-book" aria-hidden="true"></i> <a href="{U_USAGE_TERMS}">{@smallads.usage.terms}</a># ENDIF #</footer>
	</div>
	<footer>
		<meta itemprop="url" content="{U_ITEM}">
		<meta itemprop="description" content="${escape(DESCRIPTION)}">
		<meta itemprop="datePublished" content="# IF NOT C_DIFFERED #{DATE_ISO8601}# ELSE #{PUBLICATION_START_DATE_ISO8601}# ENDIF #">
		# IF C_HAS_THUMBNAIL #<meta itemprop="thumbnailUrl" content="{U_THUMBNAIL}"># ENDIF #
		# IF C_COMMENTS_ENABLED #
			<meta itemprop="discussionUrl" content="{U_COMMENTS}">
			<meta itemprop="interactionCount" content="{COMMENTS_NUMBER} UserComments">
		# ENDIF #
	</footer>
</section>
