
<article id="{MODULE_NAME}# IF C_CATEGORY #_category# ENDIF #-panel" style="order: {MODULE_POSITION};">
	<header>
		<h2>{L_MODULE_TITLE}</h2>
		<div class="controls align-right">
			<a href="{PATH_TO_ROOT}/{MODULE_NAME}">{L_SEE_ALL_ITEMS}</a>
		</div>
	</header>
	# IF C_NO_ITEM #
		<div class="message-helper bgc notice">
			${LangLoader::get_message('no_item_now', 'common')}
		</div>
	# ELSE #
		# IF C_TABLE_VIEW #
			<table class="table">
				<thead>
					<tr>
						<th>${LangLoader::get_message('form.name', 'common')}</th>
						<th class="col-small" aria-label="{@creation.date}"><i class="fa fa-fw fa-clock" aria-hidden="true"></i></th>
						# IF C_VIEWS_NUMBER #<th class="col-small" aria-label="# IF C_VISIT #{@visits_number}# ELSE #{@module.views.number}# ENDIF #"><i class="fa fa-fw fa-eye" aria-hidden="true"></i></th># ENDIF #
						# IF C_DL_NUMBER #<th class="col-small" aria-label="{@downloads.number}"><i class="fa fa-fw fa-download" aria-hidden="true"></i></th># ENDIF #
						# IF C_VISIT #<th aria-label="{@website.link}"><i class="fa fa-fw fa-share" aria-hidden="true"></i></th># ENDIF #
					</tr>
				</thead>
				<tbody>
					# START items #
						<tr>
							<td><a href="{items.U_ITEM}">{items.TITLE}</a></td>
							<td>
								# IF items.HAS_UPDATE_DATE #
									<time datetime="{items.UPDATE_DATE_ISO8601}" itemprop="dateModified">{items.UPDATE_DATE}</time>
								# ELSE #
									<time datetime="{items.DATE_ISO8601}" itemprop="datePublished">{items.DATE}</time>
								# ENDIF #
							</td>
							# IF C_VIEWS_NUMBER #<td>{items.VIEWS_NUMBER}</td># ENDIF #
							# IF C_DL_NUMBER #<td>{items.DOWNLOADS_NUMBER}</td># ENDIF #
							# IF C_VISIT #<td><a href="{items.U_VISIT}">{@visit}</a></td># ENDIF #
						</tr>
					# END items #
				</tbody>
			</table>
		# ELSE #
			<div class="# IF C_GRID_VIEW #cell-flex cell-columns-{ITEMS_PER_ROW}# ELSE #cell-row# ENDIF #">

				# START items #
					<div class="{MODULE_NAME}-items several-items category-{items.CATEGORY_ID} cell" itemscope="itemscope" itemtype="https://schema.org/CreativeWork">
						<div class="cell-header">
							<h3 class="cell-name">
								<a href="{items.U_ITEM}">{items.TITLE}</a>
							</h3>
						</div>

						<div class="cell-body">
							<div class="cell-infos">
								<div class="more">
									# IF C_AUTHOR_DISPLAYED #
										<span class="pinned">
											<i class="fa fa-fw fa-user" aria-hidden="true"></i>
											# IF items.C_AUTHOR_EXIST #<a itemprop="author" class="{items.AUTHOR_LEVEL_CLASS}" href="{items.U_AUTHOR}"# IF items.C_AUTHOR_GROUP_COLOR # style="{items.AUTHOR_GROUP_COLOR}"# ENDIF #>{items.AUTHOR_DISPLAY_NAME}</a># ELSE #{items.AUTHOR_DISPLAY_NAME}# ENDIF #
										</span>
									# ENDIF #
									# IF NOT C_DATE #
										# IF items.C_HAS_UPDATE #
											<span class="pinned notice text-italic modified-date"><i class="far fa-fw fa-calendar-plus" aria-hidden="true"></i> <time datetime="{items.UPDATE_DATE_ISO8601}" itemprop="dateModified">{items.UPDATE_DATE}</time></span>
										# ELSE #
											<span class="pinned"><i class="far fa-fw fa-calendar-alt" aria-hidden="true"></i> <time datetime="{items.DATE_ISO8601}" itemprop="datePublished">{items.DATE}</time></span>
										# ENDIF #
									# ENDIF #
									<span class="pinned"><i class="far fa-fw fa-folder" aria-hidden="true"></i> <a itemprop="about" href="{items.U_CATEGORY}">{items.CATEGORY_NAME}</a></span>
									# IF C_VIEWS_NUMBER #<span class="pinned" aria-label="{items.VIEWS_NUMBER} # IF items.C_SEVERAL_VIEWS #${LangLoader::get_message('module.views', 'common', 'HomeLanding')}# ELSE #${LangLoader::get_message('module.view', 'common', 'HomeLanding')}# ENDIF #"><i class="fa fa-fw fa-eye" aria-hidden="true"></i> {items.VIEWS_NUMBER}</span># ENDIF #
								</div>
								# IF items.C_CONTROLS #
									<span class="controls">
										# IF items.C_EDIT #
											<a href="{items.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true"></i></a>
										# ENDIF #
										# IF items.C_DELETE #
											<a href="{items.U_DELETE}" aria-label="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
										# ENDIF #
									</span>
								# ENDIF #
							</div>

							# IF NOT items.C_FULL_ITEM_DISPLAY #
								# IF items.C_HAS_THUMBNAIL #
									<div class="cell-thumbnail cell-landscape cell-center">
										<img src="{items.U_THUMBNAIL}" alt="{items.TITLE}" />
										<a href="{items.U_ITEM}" class="cell-thumbnail-caption">
											# IF items.C_READ_MORE #[${LangLoader::get_message('read-more', 'common')}]# ELSE #<i class="fa fa-eye"></i># ENDIF #
										</a>
									</div>
								# ELSE #
									# IF items.C_HAS_PARTNER_THUMBNAIL #
										<div class="cell-thumbnail cell-landscape cell-center">
											<img src="{items.U_PARTNER_THUMBNAIL}" alt="{items.TITLE}" />
											<a href="{items.U_ITEM}" class="cell-thumbnail-caption">
												# IF items.C_READ_MORE #[${LangLoader::get_message('read-more', 'common')}]# ELSE #<i class="fa fa-eye"></i># ENDIF #
											</a>
										</div>
									# ENDIF #
								# ENDIF #
							# ENDIF #
							<div class="cell-content">
								# IF C_DATE #
									<div class="align-right controls"><i class="fa fa-fw fa-calendar-alt"></i><span>{items.START_DATE}</span> - <span>{items.END_DATE}</span></div>
								# ENDIF #
								# IF items.C_FULL_ITEM_DISPLAY #
									# IF items.C_HAS_THUMBNAIL #
										<img class="item-thumbnail" itemprop="thumbnailUrl" src="{items.U_THUMBNAIL}" alt="{items.TITLE}" />
									# ENDIF #
									{items.CONTENTS}
								# ELSE #
									{items.SUMMARY}# IF items.C_READ_MORE #... <a href="{items.U_ITEM}">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF #
								# ENDIF #
							</div>
						</div>

					</div>
				# END items #
			</div>
		# ENDIF #
	# ENDIF #
	<footer></footer>
</article>
