
<article id="{MODULE_NAME}# IF C_CATEGORY #_category# ENDIF #" style="order: {MODULE_POSITION};">
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
					# START item #
						<tr>
							<td><a href="{item.U_ITEM}">{item.TITLE}</a></td>
							<td><time datetime="{item.DATE_ISO8601}" itemprop="datePublished">{item.DATE}</time></td>
							# IF C_VIEWS_NUMBER #<td>{item.VIEWS_NUMBER}</td># ENDIF #
							# IF C_DL_NUMBER #<td>{item.DOWNLOADS_NUMBER}</td># ENDIF #
							# IF C_VISIT #<td><a href="{item.U_VISIT}">{@visit}</a></td># ENDIF #
						</tr>
					# END item #
				</tbody>
			</table>
		# ELSE #
			<div class="# IF C_GRID_VIEW #cell-flex cell-columns-{ITEMS_PER_ROW}# ELSE #cell-row# ENDIF #">

				# START item #
					<div class="{MODULE_NAME}-items several-items category-{item.CATEGORY_ID} cell" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
						<div class="cell-header">
							<h3 class="cell-name">
								<a href="{item.U_ITEM}">{item.TITLE}</a>
							</h3>
						</div>

						<div class="cell-body">
							<div class="cell-infos">
								<div class="more">
									# IF item.C_AUTHOR_DISPLAYED #
										<span class="pinned">
											<i class="fa fa-fw fa-user" aria-hidden="true"></i>
											# IF item.C_AUTHOR_EXIST #<a itemprop="author" class="{item.USER_LEVEL_CLASS}" href="{item.U_AUTHOR_PROFILE}"# IF item.C_USER_GROUP_COLOR # style="{item.USER_GROUP_COLOR}"# ENDIF #>{item.PSEUDO}</a># ELSE #{item.PSEUDO}# ENDIF #
										</span>
									# ENDIF #
									# IF NOT C_DATE #<span class="pinned"><i class="fa fa-fw fa-calendar-alt" aria-hidden="true"></i> <time datetime="{item.DATE_ISO8601}" itemprop="datePublished">{item.DATE}</time></span># ENDIF #
									<span class="pinned"><i class="far fa-fw fa-folder" aria-hidden="true"></i> <a itemprop="about" href="{item.U_CATEGORY}">{item.CATEGORY_NAME}</a></span>
									# IF item.C_VIEWS_NUMBER #<span class="pinned" aria-label="{item.VIEWS_NUMBER} # IF item.C_SEVERAL_VIEWS #${LangLoader::get_message('module.views', 'common', 'HomeLanding')}# ELSE #${LangLoader::get_message('module.view', 'common', 'HomeLanding')}# ENDIF #"><i class="fa fa-fw fa-eye" aria-hidden="true"></i> {item.VIEWS_NUMBER}</span># ENDIF #
								</div>
								# IF item.C_CONTROLS #
									<span class="controls">
										# IF item.C_EDIT #
											<a href="{item.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true"></i></a>
										# ENDIF #
										# IF item.C_DELETE #
											<a href="{item.U_DELETE}" aria-label="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
										# ENDIF #
									</span>
								# ENDIF #
							</div>

							# IF NOT item.C_FULL_ITEM_DISPLAY #
								# IF item.C_HAS_THUMBNAIL #
									<div class="cell-thumbnail cell-landscape cell-center">
										<img src="{item.U_THUMBNAIL}" alt="{item.TITLE}" />
										<a href="{item.U_ITEM}" class="cell-thumbnail-caption">
											# IF item.C_READ_MORE #[${LangLoader::get_message('read-more', 'common')}]# ELSE #<i class="fa fa-eye"></i># ENDIF #
										</a>
									</div>
								# ELSE #
									# IF item.C_HAS_PARTNER_THUMBNAIL #
										<div class="cell-thumbnail cell-landscape cell-center">
											<img src="{item.U_PARTNER_THUMBNAIL}" alt="{item.TITLE}" />
											<a href="{item.U_ITEM}" class="cell-thumbnail-caption">
												# IF item.C_READ_MORE #[${LangLoader::get_message('read-more', 'common')}]# ELSE #<i class="fa fa-eye"></i># ENDIF #
											</a>
										</div>
									# ENDIF #
								# ENDIF #
							# ENDIF #
							<div class="cell-content">
								# IF C_DATE #
									<div class="align-right controls"><i class="fa fa-fw fa-calendar-alt"></i><span>{item.START_DATE}</span> - <span>{item.END_DATE}</span></div>
								# ENDIF #
								# IF item.C_FULL_ITEM_DISPLAY #
									# IF item.C_HAS_THUMBNAIL #
										<img class="item-thumbnail" itemprop="thumbnailUrl" src="{item.U_THUMBNAIL}" alt="{item.TITLE}" />
									# ENDIF #
									{item.CONTENTS}
								# ELSE #
									{item.SUMMARY}# IF item.C_READ_MORE #... <a href="{item.U_ITEM}">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF #
								# ENDIF #
							</div>
						</div>

					</div>
				# END item #
			</div>
		# ENDIF #
	# ENDIF #
	<footer></footer>
</article>
