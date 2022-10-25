<section id="module-broadcast">
	<header class="section-header">
		<div class="controls align-right">
			<a class="offload" href="{U_SYNDICATION}" aria-label="{@common.syndication}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
			{@broadcast.module.title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF #
			# IF IS_ADMIN #<a class="offload" href="{U_EDIT_CATEGORY}" aria-label="{@common.edit}"><i class="far fa-edit" aria-hidden="true"></i></a># ENDIF #
		</div>
		<h1>
			<span itemprop="name">{TITLE}</span>
		</h1>
	</header>
	<div class="sub-section">
		<div class="content-container">
			# IF NOT C_VISIBLE #
				<div class="content">
					# INCLUDE NOT_VISIBLE_MESSAGE #
				</div>
			# ENDIF #
			<article itemscope="itemscope" itemtype="http://schema.org/CreativeWork" id="article-broadcast-{ID}" class="broadcast-item">
				# IF C_CONTROLS #
					<div class="controls align-right">
						# IF C_EDIT #<a href="{U_EDIT}" aria-label="{@common.edit}"><i class="fa fa-edit"></i></a># ENDIF #
						# IF C_DELETE #<a href="{U_DELETE}" aria-label="{@common.delete}" data-confirmation="delete-element"><i class="fa fa-trash-alt"></i></a># ENDIF #
					</div>
				# ENDIF #

				<div class="content cell-tile">
					<div class="cell-options cell">
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
								<li># START days #{days.DAY}# IF days.C_SEPARATOR #, # ENDIF ## END days #</li>
								<li class="li-stretch"><span>{@broadcast.announcer}</span><span>{AUTHOR_CUSTOM_NAME}</span></li>
								<li class="li-stretch"><span>{@broadcast.hourly}</span><span>{START_HOURS}h{START_MINUTES} - {END_HOURS}h{END_MINUTES}</span></li>
							</ul>
						</div>
					</div>
					<div itemprop="text">{CONTENT}</div>
				</div>
			</article>
		</div>		
	</div>
	<footer>
		<meta itemprop="url" content="{U_ITEM}">
		<meta itemprop="description" content="${escape(CONTENT)}" />
	</footer>
</section>
