
<article id="download" style="order: {DOWNLOAD_POSITION};">
	<header>
		<h2>
			${Langloader::get_message('last.download', 'common', 'HomeLanding')}
		</h2>
		<span class="controls">
			<a href="{PATH_TO_ROOT}/download">
				${Langloader::get_message('link.to.download', 'common', 'HomeLanding')}
			</a>
		</span>
	</header>
	# IF C_DISPLAY_TABLE #
		<table class="table">
			<thead>
				<tr>
					<th>${LangLoader::get_message('form.name', 'common')}</th>
					<th class="col-small"><i class="fa fa-fw fa-clock"></i></th>
					<th class="col-small"><i class="fa fa-fw fa-eye"></i></th>
					<th class="col-small"><i class="fa fa-fw fa-download"></i></th>
				</tr>
			</thead>
			<tbody>
				# START item #
					<tr>
						<td>
							<a href="{item.U_ITEM}" itemprop="name">{item.TITLE}</a>
						</td>
						<td>
							<time datetime="# IF NOT item.C_DIFFERED #{item.DATE_ISO8601}# ELSE #{item.DIFFERED_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF NOT item.C_DIFFERED #{item.DATE}# ELSE #{item.DIFFERED_START_DATE}# ENDIF #</time>
						</td>
						<td>
							{item.VIEWS_NUMBER}
						</td>
						<td>
							{item.DOWNLOADS_NUMBER}
						</td>
					</tr>
				# END item #
			</tbody>
		</table>
	# ELSE #
		# IF C_DISPLAY_GRID_VIEW #
			<div class="elements-container columns-{COL_NBR}">
		# ELSE #
			<div class="content">
		# ENDIF #
		# START item #
			<div class="item-content# IF C_DISPLAY_GRID_VIEW # block# ENDIF #">

				<h3>
					<a href="{item.U_ITEM}">{item.TITLE}</a>
				</h3>
				<span class="controls">
					# IF item.C_EDIT #
						<a href="{item.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"aria-hidden="true"></i></a>
					# ENDIF #
					# IF item.C_DELETE #
						<a href="{item.U_DELETE}" aria-label="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-trash-alt"aria-hidden="true"></i></a>
					# ENDIF #
				</span>

				<div class="more">
					# IF item.C_AUTHOR_DISPLAYED #
						${LangLoader::get_message('by', 'common')}
						# IF item.C_AUTHOR_EXIST #<a itemprop="author" href="{item.U_AUTHOR}" class="{item.USER_LEVEL_CLASS}" # IF C_USER_GROUP_COLOR # style="color:{item.USER_GROUP_COLOR}"# ENDIF #>{item.PSEUDO}</a># ELSE #{item.PSEUDO}# ENDIF #,
					# ENDIF #
					${TextHelper::lcfirst(LangLoader::get_message('the', 'common'))} <time datetime="{item.DATE_ISO8601}" itemprop="datePublished">{item.DATE}</time>
					${TextHelper::lcfirst(LangLoader::get_message('in', 'common'))} <a itemprop="about" href="{item.U_CATEGORY}">{item.CATEGORY_NAME}</a>
				</div>

				# IF item.C_HAS_THUMBNAIL #
					<a href="{item.U_ITEM}" class="item-picture">
						<img src="{item.U_THUMBNAIL}" alt="{item.TITLE}" />
					</a>
				# ENDIF #
				<p class="item-desc">
					{item.DESCRIPTION}# IF item.C_READ_MORE #... <a href="{item.U_ITEM}">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF #
				</p>

			</div>
		# END item #
	</div>
	# ENDIF #
	<footer></footer>
</article>
