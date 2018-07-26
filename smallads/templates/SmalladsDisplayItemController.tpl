<section id="smallads-module">
	<header>
		<h1>
			<a href="{U_SYNDICATION}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-fw fa-syndication"></i></a>
			{@smallads.module.title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF # # IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-fw fa-edit smaller"></i></a># ENDIF #
		</h1>
	</header>
	# INCLUDE NOT_VISIBLE_MESSAGE #
	<article itemscope="itemscope" itemtype="http://schema.org/Smallad" id="article-smallads-{ID}" class="article-smallads# IF C_NEW_CONTENT # new-content# ENDIF #">
		<header>
			<h2>
				<p>{SMALLAD_TYPE}# IF C_COMPLETED # - <span class="completed-article">{@smallads.completed.item}</span># ENDIF #</p>
				{TITLE}
				<span class="actions">
					# IF NOT C_COMPLETED #
						# IF C_EDIT #
							<a href="{U_EDIT_ITEM}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-fw fa-edit"></i></a>
						# ENDIF #
					# ENDIF #
					# IF C_DELETE #
						<a href="{U_DELETE_ITEM}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-fw fa-delete"></i></a>
					# ENDIF #
				</span>
			</h2>

			<meta itemprop="url" content="{U_ITEM}">
			<meta itemprop="description" content="${escape(DESCRIPTION)}">
			<meta itemprop="datePublished" content="# IF NOT C_DIFFERED #{DATE_ISO8601}# ELSE #{PUBLICATION_START_DATE_ISO8601}# ENDIF #">
			<meta itemprop="discussionUrl" content="{U_COMMENTS}">
			# IF C_HAS_THUMBNAIL #<meta itemprop="thumbnailUrl" content="{THUMBNAIL}"># ENDIF #
			<meta itemprop="interactionCount" content="{COMMENTS_NUMBER} UserComments">
		</header>
		<div class="content">
			<div class="options infos thumbnail-item">
				# IF C_HAS_THUMBNAIL #
					<a href="{THUMBNAIL}" title="{TITLE}" data-lightbox="formatter" data-rel="lightcase:collection">
						<img src="{THUMBNAIL}" alt="{TITLE}" />
					</a>
				# ELSE #
					<a href="{PATH_TO_ROOT}/smallads/templates/images/no-thumb.png" title="{TITLE}" data-lightbox="formatter" data-rel="lightcase:collection">
						<img src="{PATH_TO_ROOT}/smallads/templates/images/no-thumb.png" alt="{TITLE}" />
					</a>
				# ENDIF #
				# IF C_LOCATION #
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
				# ENDIF #
				# IF NOT C_COMPLETED #
					# IF C_CONTACT #
						<hr />
						<div>
							{@smallads.contact} :
							# IF C_DISPLAYED_AUTHOR_EMAIL #
								<a href="#email-modal" class="email-modal-btn" title="{@smallads.contact.email}"><i class="fa fa-fw fa-at"></i></a>
								<div id="email-modal" class="smallad-modal">
									# IF C_CONTACT_LEVEL #
										<div class="email-form">
											<a href="#email-modal-close" class="modal-close"><i class="fa fa-fw fa-remove"></i></a>
											# INCLUDE MSG #
											# IF NOT C_SMALLAD_EMAIL_SENT #
												# INCLUDE EMAIL_FORM #
											# ENDIF  #
										</div>
									# ELSE #
										<div class="warning is-not-connected">
											<a href="#email-modal-close" class="modal-close"><i class="fa fa-fw fa-remove"></i></a>
											{@smallads.email.modal}
										</div>
									# ENDIF #
								</div>
							# ENDIF #
							# IF C_DISPLAYED_AUTHOR_PM # | <a href="{U_AUTHOR_PM}" class="smallad-pm" title="{@smallads.contact.pm}"><i class="fa fa-fw fa-envelope-o"></i></a># ENDIF #
							# IF C_DISPLAYED_AUTHOR_PHONE #
								 | <a href="#tel-modal" class="tel-modal-btn" title="{@smallads.contact.phone}"><i class="fa fa-fw fa-mobile"></i><i class="fa fa-fw fa-phone"></i></a>
								<div id="tel-modal" class="smallad-modal">
									# IF C_CONTACT_LEVEL #
										<div class="tel-form is-connected">
											<a href="#tel-modal-close" class="modal-close"><i class="fa fa-fw fa-remove"></i></a>
											{AUTHOR_PHONE}
										</div>
									# ELSE #
										<div class="warning is-not-connected">
											<a href="#tel-modal-close" class="modal-close"><i class="fa fa-fw fa-remove"></i></a>
											{@smallads.tel.modal}
										</div>
									# ENDIF #
								</div>
							# ENDIF #
						</div>
						<div class="spacer"></div>
					# ENDIF #
				# ENDIF #
				# IF C_DISPLAYED_AUTHOR #
					<hr />
					<i class="fa fa-fw fa-user" title="${LangLoader::get_message('author', 'common')}"></i>
						# IF C_CUSTOM_AUTHOR_NAME #
							{CUSTOM_AUTHOR_NAME}
						# ELSE #
							# IF C_AUTHOR_EXIST #<a itemprop="author" href="{U_AUTHOR}" class="{USER_LEVEL_CLASS}" # IF C_USER_GROUP_COLOR # style="color:{USER_GROUP_COLOR}"# ENDIF #>{PSEUDO}</a># ELSE #{PSEUDO}# ENDIF #
						# ENDIF #
				# ENDIF #
				<hr />
				<i class="fa fa-fw fa-calendar" title="${LangLoader::get_message('date', 'date-common')}"></i>&nbsp;<time datetime="# IF NOT C_DIFFERED #{DATE_ISO8601}# ELSE #{PUBLICATION_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF NOT C_DIFFERED #{DATE}# ELSE #{PUBLICATION_START_DATE}# ENDIF #</time>
				<hr /><i class="fa fa-fw fa-eye" title="{VIEWS_NUMBER} {@smallads.sort.field.views}"></i>&nbsp;<span title="{VIEWS_NUMBER} {@smallads.sort.field.views}">{VIEWS_NUMBER}</span>
				# IF C_COMMENTS_ENABLED #
					<hr />
					<i class="fa fa-fw fa-comment" title="${LangLoader::get_message('comments', 'comments-common')}"></i> <a itemprop="discussionUrl" class="small" href="{U_COMMENTS}">&nbsp;{L_COMMENTS}</a>
				# ENDIF #
				# IF C_KEYWORDS #
					<hr />
					<i title="${LangLoader::get_message('form.keywords', 'common')}" class="fa fa-fw fa-tags"></i>
					# START keywords #
						<a itemprop="keywords" href="{keywords.URL}">{keywords.NAME}</a># IF keywords.C_SEPARATOR #, # ENDIF #
					# END keywords #
				# ENDIF #
			</div>

			# IF C_CAROUSEL #
			 	# START carousel #
					<a href="# IF carousel.C_PTR #{PATH_TO_ROOT}# ENDIF #{carousel.U_PICTURE}" title="{carousel.DESCRIPTION}" data-lightbox="formatter" data-rel="lightcase:collection">
						<figure class="carousel-thumbnail">
							<img src="# IF carousel.C_PTR #{PATH_TO_ROOT}# ENDIF #{carousel.U_PICTURE}" alt="{carousel.DESCRIPTION}" />
							<figcaption>{carousel.DESCRIPTION}</figcaption>
						</figure>
					</a>
			 	# END carousel #
			# ENDIF #
			# IF C_PRICE #<div class="smallad-price">{PRICE} {CURRENCY}</div># ENDIF #
			<div itemprop="text">{CONTENTS}</div>


			<div class="spacer"></div>
		</div>
		<aside>
			# IF C_SOURCES #
				<hr />
				<div id="smallads-sources-container">
					<span>${LangLoader::get_message('form.sources', 'common')}</span> :
					# START sources #
					<a itemprop="isBasedOnUrl" href="{sources.URL}" class="small">{sources.NAME}</a># IF sources.C_SEPARATOR #, # ENDIF #
					# END sources #
				</div>
			# ENDIF #

			# IF C_UPDATED_DATE #
				<hr />
				<div>
					<i>${LangLoader::get_message('form.date.update', 'common')} : <time datetime="{UPDATED_DATE_ISO8601}" itemprop="datePublished">{UPDATED_DATE_FULL}</time></i>
				</div>
			# ENDIF #

			# IF C_SUGGESTED_ITEMS #
				<hr />
				<h6><i class="fa fa-fw fa-lightbulb-o"></i> ${LangLoader::get_message('suggestions', 'common')} :</h6>
				<div class="elements-container columns-{SUGGESTED_COLUMNS} no-style">
					# START suggested_items #
					<div class="block suggested-thumbnail">
						# IF suggested_items.C_COMPLETED #
							<figure>
								# IF suggested_items.C_HAS_THUMBNAIL #<img src="# IF suggested_items.C_PTR #{PATH_TO_ROOT}# ENDIF #{suggested_items.THUMBNAIL}" alt="{suggested_items.TITLE}" /># ENDIF #
								<figcaption>{suggested_items.TITLE} - <span class="completed-article">{@smallads.completed.item}</figcaption>
							</figure>
						# ELSE #
							<a href="{suggested_items.U_ITEM}">
								<figure>
									# IF suggested_items.C_HAS_THUMBNAIL #<img src="# IF suggested_items.C_PTR #{PATH_TO_ROOT}# ENDIF #{suggested_items.THUMBNAIL}" alt="{suggested_items.TITLE}" /># ENDIF #
									<figcaption>{suggested_items.TITLE}</figcaption>
								</figure>
							</a>
						# ENDIF #
					</div>
					# END suggested_items #
				</div>
			# ENDIF #

			# IF C_NAVIGATION_LINKS #
				<hr />
				<div class="navigation-link">
					# IF C_PREVIOUS_ITEM #
						# IF C_PREVIOUS_COMPLETED #
						<span class="navigation-link-previous">
							<figure class="navigation-link-thumbnail">
								# IF C_PREVIOUS_HAS_THUMBNAIL #<img src="# IF C_PREVIOUS_PTR #{PATH_TO_ROOT}# ENDIF #{PREVIOUS_THUMBNAIL}" alt="{PREVIOUS_ITEM_TITLE}" /># ENDIF #
								<figcaption><i class="fa fa-fw fa-arrow-circle-left"></i> {PREVIOUS_ITEM_TITLE} - <span class="completed-article">{@smallads.completed.item}</figcaption>
							</figure>
						</span>
						# ELSE #
							<span class="navigation-link-previous">
								<a href="{U_PREVIOUS_ITEM}">
									<figure class="navigation-link-thumbnail">
										# IF C_PREVIOUS_HAS_THUMBNAIL #<img src="# IF C_PREVIOUS_PTR #{PATH_TO_ROOT}# ENDIF #{PREVIOUS_THUMBNAIL}" alt="{PREVIOUS_ITEM_TITLE}" /># ENDIF #
										<figcaption><i class="fa fa-fw fa-arrow-circle-left"></i> {PREVIOUS_ITEM_TITLE}</figcaption>
									</figure>
								</a>
							</span>
						# ENDIF #
					# ENDIF #
					# IF C_NEXT_ITEM #
						# IF C_NEXT_COMPLETED #
						<span class="navigation-link-next">
							<figure class="navigation-link-thumbnail">
								# IF C_NEXT_HAS_THUMBNAIL #<img src="# IF C_NEXT_PTR #{PATH_TO_ROOT}# ENDIF #{NEXT_THUMBNAIL}" alt="{NEXT_ITEM_TITLE}" /># ENDIF #
								<figcaption>{NEXT_ITEM_TITLE} - <span class="completed-article">{@smallads.completed.item} <i class="fa fa-fw fa-arrow-circle-right"></i></figcaption>
							</figure>
						</span>
						# ELSE #
							<span class="navigation-link-next">
								<a href="{U_NEXT_ITEM}">
									<figure class="navigation-link-thumbnail">
										# IF C_NEXT_HAS_THUMBNAIL #<img src="# IF C_NEXT_PTR #{PATH_TO_ROOT}# ENDIF #{NEXT_THUMBNAIL}" alt="{NEXT_ITEM_TITLE}" /># ENDIF #
										<figcaption>{NEXT_ITEM_TITLE} <i class="fa fa-fw fa-arrow-circle-right"></i></figcaption>
									</figure>
								</a>
							</span>
						# ENDIF #
					# ENDIF #
					<div class="spacer"></div>
				</div>
			# ENDIF #

			# IF C_COMMENTS_ENABLED #
				<hr />
				# INCLUDE COMMENTS #
			# ENDIF #
		</aside>
		<footer># IF C_USAGE_TERMS # <i class="fa fa-fw fa-book"></i> <a href="{U_USAGE_TERMS}">{@smallads.usage.terms}</a># ENDIF #</footer>
	</article>
	<footer></footer>
</section>
