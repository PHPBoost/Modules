
<article id="download" style="order: {DOWNLOAD_POSITION}; -webkit-order: {DOWNLOAD_POSITION}; -ms-flex-order: {DOWNLOAD_POSITION}">
	<header>
		<h2>
			${Langloader::get_message('last.download', 'common', 'HomeLanding')}
			<span class="actions">
				<a href="{PATH_TO_ROOT}/download" title="${Langloader::get_message('link.to.download', 'common', 'HomeLanding')}">
					${Langloader::get_message('link.to.download', 'common', 'HomeLanding')}
				</a>
			</span>
		</h2>
	</header>
	# IF C_DISPLAY_TABLE #
		<table id="table">
			<thead>
				<tr>
					<th>${LangLoader::get_message('form.name', 'common')}</th>
					<th class="col-small"><i class="fa fa-fw fa-clock-o"></i></th>
					<th class="col-small"><i class="fa fa-fw fa-eye"></i></th>
					<th class="col-small"><i class="fa fa-fw fa-download"></i></th>
				</tr>
			</thead>
			<tbody>
				# START item #
					<tr>
						<td>
							<a href="{item.U_LINK}" itemprop="name">{item.NAME}</a>
						</td>
						<td>
							<time datetime="# IF NOT item.C_DIFFERED #{item.DATE_ISO8601}# ELSE #{item.DIFFERED_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF NOT item.C_DIFFERED #{item.DATE}# ELSE #{item.DIFFERED_START_DATE}# ENDIF #</time>
						</td>
						<td>
							{item.NUMBER_VIEW}
						</td>
						<td>
							{item.NUMBER_DOWNLOADS}
						</td>
					</tr>
				# END item #
			</tbody>
		</table>
	# ELSE #
		# IF C_DISPLAY_BLOCK #
			<div class="elements-container columns-{COL_NBR}">
		# ELSE #
			<div class="content">
		# ENDIF #
		# START item #
			<div class="item-content# IF C_DISPLAY_BLOCK # block# ENDIF #">

				<h3>
					<a href="{item.U_LINK}">{item.NAME}</a>
					<span class="actions">
						# IF item.C_EDIT #
							<a href="{item.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
						# ENDIF #
						# IF item.C_DELETE #
							<a href="{item.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
						# ENDIF #
					</span>
				</h3>

				<div class="more">
					# IF item.C_AUTHOR_DISPLAYED #
						${LangLoader::get_message('by', 'common')}
						# IF item.C_AUTHOR_EXIST #<a itemprop="author" href="{item.U_AUTHOR}" class="{item.USER_LEVEL_CLASS}" # IF C_USER_GROUP_COLOR # style="color:{item.USER_GROUP_COLOR}"# ENDIF #>{item.PSEUDO}</a># ELSE #{item.PSEUDO}# ENDIF #,
					# ENDIF #
					${TextHelper::lcfirst(LangLoader::get_message('the', 'common'))} <time datetime="{item.DATE_ISO8601}" itemprop="datePublished">{item.DATE}</time>
					${TextHelper::lcfirst(LangLoader::get_message('in', 'common'))} <a itemprop="about" href="{item.U_CATEGORY}">{item.CATEGORY_NAME}</a>
				</div>

				# IF item.C_PICTURE #
					<a href="{item.U_LINK}" class="item-picture">
						<img src="{item.U_PICTURE}" alt="{item.NAME}" />
					</a>
				# ENDIF #
				<p class="item-desc">
					{item.DESCRIPTION}# IF item.C_READ_MORE #... <a href="{item.U_LINK}">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF #
				</p>

			</div>
		# END item #
	</div>
	# ENDIF #
	<footer></footer>
</article>
