<section id="module-smallads" class="category-{ID_CATEGORY} single-item">
	<header class="section-header">
		<div class="controls align-right">
			<a class="offload" href="{U_SYNDICATION}" aria-label="{@common.syndication}"><i class="fa fa-fw fa-rss warning" aria-hidden="true"></i></a>
			{@smallads.module.title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF #
			# IF IS_ADMIN #<a class="offload" href="{U_EDIT_CATEGORY}" aria-label="{@common.edit}"><i class="fa fa-fw fa-edit" aria-hidden="true"></i></a># ENDIF #
		</div>
		<p>{SMALLAD_TYPE}# IF C_COMPLETED # - <span class="pinned bgc error">{@common.status.finished}</span># ENDIF #</p>
		<h1>{TITLE}</h1>
	</header>
	<div class="sub-section">
		<div class="content-container">
			# IF NOT C_VISIBLE #
				<div class="content">
					# INCLUDE NOT_VISIBLE_MESSAGE #
				</div>
			# ENDIF #
			<article itemscope="itemscope" itemtype="https://schema.org/Smallad" id="smallads-item-{ID}" class="smallads-item# IF C_NEW_CONTENT # new-content# ENDIF #">
				<div class="flex-between">
					# IF C_HAS_UPDATE_DATE #
						<span class="pinned notice small text-italic modified-date">
							{@common.last.update} : <time datetime="{UPDATE_DATE_ISO8601}" itemprop="dateModified">{UPDATE_DATE_FULL}</time>
						</span>
					# ELSE #
						<span></span>
					# ENDIF #
					# IF C_CONTROLS #
						<div class="controls align-right">
							# IF C_DUPLICATE #<a class="offload" href="{U_DUPLICATE}" aria-label="{@common.duplicate}"><i class="far fa-fw fa-clone" aria-hidden="true"></i></a># ENDIF #
							# IF NOT C_COMPLETED ## IF C_EDIT #<a class="offload" href="{U_EDIT}" aria-label="{@common.edit}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a># ENDIF ## ENDIF #
							# IF C_DELETE #<a href="{U_DELETE}" aria-label="{@common.delete}" data-confirmation="delete-element"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a># ENDIF #
						</div>
					# ENDIF #
				</div>

				<div class="content">
					<div class="cell-options cell-tile">
						<div class="cell">
							# IF C_HAS_THUMBNAIL #
								<div class="cell-body">
									<div class="cell-thumbnail cell-landscape">
										<img src="{U_THUMBNAIL}" alt="{TITLE}" />
										<a class="cell-thumbnail-caption offload" href="{U_THUMBNAIL}" aria-label="{TITLE}" data-lightbox="formatter" data-rel="lightcase:collection"><i class="fa fa-eye"></i></a>
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
														<a href="#email-modal" class="email-modal-btn modal-button --email-modal" aria-label="{@smallads.contact.email} - {@smallads.open.modal}"><i class="fa fa-fw iboost fa-iboost-email" aria-hidden="true"></i></a>
														<div id="email-modal" class="modal">
															<div class="modal-overlay close-modal" role="button" aria-label="{@smallads.close.modal}"></div>
															<div class="modal-content cell">
																<div class="align-right"><a href="#" class="error big hide-modal close-modal" aria-label="{@common.close}"><i class="far fa-circle-xmark" aria-hidden="true"></i></a></div>
																<div class="cell-body">
																	# IF C_CONTACT_LEVEL #
																		# INCLUDE MESSAGE_HELPER #
																		# IF NOT C_SMALLAD_EMAIL_SENT #
																			# INCLUDE EMAIL_FORM #
																		# ENDIF #
																	# ELSE #
																		<div class="warning is-not-connected">
																			<a href="#email-modal-close" class="modal-close" aria-label="{@smallads.close.modal}"><i class="fa fa-fw fa-times" aria-hidden="true"></i></a>
																			{@smallads.email.modal}
																		</div>
																	# ENDIF #
																</div>
															</div>
														</div>
													# ENDIF #
													# IF C_DISPLAYED_AUTHOR_PM #
														<a href="{U_AUTHOR_PM}" class="smallad-pm offload" aria-label="{@smallads.contact.pm}"><i class="fa fa-fw fa-people-arrows" aria-hidden="true"></i></a>
													# ENDIF #

													# IF C_DISPLAYED_AUTHOR_PHONE #
														<a href="#" class="modal-button --tel-modal" aria-label="{@smallads.contact.phone} - {@smallads.open.modal}"><i class="fa fa-fw fa-phone" aria-hidden="true"></i><span class="sr-only">{@smallads.contact.phone}</span></a>
														<div id="tel-modal" class="modal modal-quarter">
															<div class="modal-overlay close-modal" role="button" aria-label="{@smallads.close.modal}"></div>
															<div class="modal-content cell">
																<div class="align-right"><a href="#" class="error big hide-modal close-modal" aria-label="{@common.close}"><i class="far fa-circle-xmark" aria-hidden="true"></i></a></div>
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
												<span aria-label="{@common.author}"><i class="fa fa-fw fa-user" aria-hidden="true"></i></span>
												<span>
													# IF C_CUSTOM_AUTHOR_NAME #
														{CUSTOM_AUTHOR_NAME}
													# ELSE #
														# IF C_AUTHOR_EXISTS #<a itemprop="author" href="{U_AUTHOR_PROFILE}" class="{AUTHOR_LEVEL_CLASS} offload" # IF C_AUTHOR_GROUP_COLOR # style="color:{AUTHOR_GROUP_COLOR}"# ENDIF #>{AUTHOR_DISPLAY_NAME}</a># ELSE #{AUTHOR_DISPLAY_NAME}# ENDIF #
													# ENDIF #
												</span>
											</li>
										# ENDIF #
										<li class="li-stretch">
											<span aria-label="{@common.creation.date}"><i class="fa fa-fw fa-calendar-alt" aria-hidden="true"></i> </span>
											# IF C_ARCHIVED #
												{@common.status.archived.alt}
											# ELSE #
												<time datetime="# IF NOT C_DIFFERED #{DATE_ISO8601}# ELSE #{PUBLISHING_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF NOT C_DIFFERED #{DATE}# ELSE #{PUBLISHING_START_DATE}# ENDIF #</time>
											# ENDIF #

										</li>
										<li class="li-stretch">
											<span role="contentinfo" aria-label="{@common.views.number}"><i class="fa fa-fw fa-eye" aria-hidden="true"></i> </span>
											{VIEWS_NUMBER}
										</li>
										# IF C_ENABLED_COMMENTS #
											<li class="li-stretch">
												<span aria-label="{@common.comments.number}"><i class="fa fa-fw fa-comment" aria-hidden="true"></i></span>
												<a itemprop="discussionUrl" class="small" href="{U_COMMENTS}"> {L_COMMENTS}</a>
											</li>
										# ENDIF #
										# IF C_KEYWORDS #
											<li class="li-stretch">
												<span aria-label="{@common.keywords}"><i class="fa fa-fw fa-tags" aria-hidden="true"></i></span>
												<span>
													# START keywords #
														<a class="offload" itemprop="keywords" href="{keywords.URL}">{keywords.NAME}</a># IF keywords.C_SEPARATOR #, # ENDIF #
													# END keywords #
												</span>
											</li>
										# ENDIF #
									# ENDIF #
								</ul>
							</div>
						</div>
					</div>

					<div# IF C_COMPLETED # class="error"# ENDIF # itemprop="text">
						# IF C_COMPLETED #<p class="pinned bgc error larger">{@common.status.finished}</p># ENDIF #
						{CONTENT}
					</div>

					<div class="spacer"></div>
				</div>

				# IF C_CAROUSEL #
					<aside class="carousel-container">
						# START carousel #
							<a href="{carousel.U_PICTURE}" # IF carousel.C_DESCRIPTION #aria-label="{carousel.DESCRIPTION}"# ENDIF # data-lightbox="formatter" data-rel="lightcase:collection">
								<figure class="carousel-thumbnail">
									<img src="{carousel.U_PICTURE}" alt="{carousel.DESCRIPTION}" />
									# IF carousel.C_DESCRIPTION #<figcaption>{carousel.DESCRIPTION}</figcaption># ENDIF #
								</figure>
							</a>
						# END carousel #
					</aside>
				# ENDIF #
				# IF C_SOURCES #
					<aside class="sources-container">
						<span class="text-strong"><i class="fa fa-map-signs" aria-hidden="true"></i> {@common.sources}</span> :
						# START sources #
							<span class="pinned question">
								<a class="offload" href="{sources.URL}" itemprop="isBasedOnUrl" rel="nofollow">{sources.NAME}</a>
							</span># IF sources.C_SEPARATOR ## ENDIF #
						# END sources #
					</aside>
				# ENDIF #

				# IF C_SUGGESTED_ITEMS #
					<aside class="suggested-links">
						<span><i class="fa fa-fw fa-lightbulb" aria-hidden="true"></i> {@common.suggestions} :</span>
						<div class="cell-flex cell-row">
							# START suggested #
								<div class="flex-between flex-between-large cell">
									<div class="cell-body">
										<div class="cell-content">
											<a href="{suggested.U_ITEM}# IF suggested.C_COMPLETED # error# ENDIF #" class="suggested-item offload">
												<h6>{suggested.TITLE}</h6>
											</a>
											<span class="more">{suggested.DATE}</span>
										</div>
									</div>
									# IF suggested.C_HAS_THUMBNAIL #
										<div class="cell-thumbnail cell-landscape cell-center">
											<img src="{suggested.U_THUMBNAIL}" alt="{suggested.TITLE}" />
										</div>
									# ENDIF #
								</div>
							# END suggested #
						</div>
					</aside>
				# ENDIF #

				# IF C_RELATED_LINKS #
					<aside>
						<div class="related-links">
							# IF C_PREVIOUS_ITEM #
								<a class="related-item previous-item offload# IF C_PREVIOUS_COMPLETED # error# ENDIF #" href="{U_PREVIOUS_ITEM}">
									<i class="fa fa-chevron-left"></i>
									# IF C_PREVIOUS_HAS_THUMBNAIL #<img src="{U_PREVIOUS_THUMBNAIL}" alt="{PREVIOUS_ITEM}"># ENDIF #
									{PREVIOUS_ITEM}
								</a>
							# ELSE #
								<span></span>
							# ENDIF #
							# IF C_NEXT_ITEM #
								<a class="related-item next-item offload# IF C_NEXT_COMPLETED # error# ENDIF #" href="{U_NEXT_ITEM}">
									{NEXT_ITEM}
									# IF C_NEXT_HAS_THUMBNAIL #<img src="{U_NEXT_THUMBNAIL}" alt="{NEXT_ITEM}"># ENDIF #
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
					# IF C_ENABLED_COMMENTS #
						# INCLUDE COMMENTS #
					# ENDIF #
				</aside>
				<footer># IF C_USAGE_TERMS # <i class="fa fa-fw fa-book" aria-hidden="true"></i> <a class="offload" href="{U_USAGE_TERMS}">{@smallads.usage.terms}</a># ENDIF #</footer>
			</article>
		</div>
	</div>
	<footer>
		<meta itemprop="url" content="{U_ITEM}">
		<meta itemprop="description" content="${escape(SUMMARY)}">
		<meta itemprop="datePublished" content="# IF NOT C_DIFFERED #{DATE_ISO8601}# ELSE #{PUBLISHING_START_DATE_ISO8601}# ENDIF #">
		# IF C_HAS_THUMBNAIL #<meta itemprop="thumbnailUrl" content="{U_THUMBNAIL}"># ENDIF #
		# IF C_ENABLED_COMMENTS #
			<meta itemprop="discussionUrl" content="{U_COMMENTS}">
			<meta itemprop="interactionCount" content="{COMMENTS_NUMBER} UserComments">
		# ENDIF #
	</footer>
</section>
