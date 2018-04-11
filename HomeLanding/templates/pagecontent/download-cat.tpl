
<article id="download-cat" style="order: {DOWNLOAD_CAT_POSITION}; -webkit-order: {DOWNLOAD_CAT_POSITION}; -ms-flex-order: {DOWNLOAD_CAT_POSITION}">
	<header>
		<h2>
			${Langloader::get_message('last.download.cat', 'common', 'HomeLanding')} {CATEGORY_NAME}
			<span class="actions">
				<a href="{PATH_TO_ROOT}/download" title="${Langloader::get_message('link.to.download', 'common', 'HomeLanding')}">
					${Langloader::get_message('link.to.download', 'common', 'HomeLanding')}
				</a>
			</span>
		</h2>
	</header>
	# IF C_DISPLAY_TABLE #
		<table id="table2">
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
			# IF C_NO_DOWNLOAD_ITEM #
			<div class="center">
				${LangLoader::get_message('no.download.item', 'common', 'HomeLanding')}
			</div>
			# ENDIF #
		# START item #
			<div class="item-content# IF C_DISPLAY_BLOCK # block# ENDIF #" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">

				<h3 class="item-title">
					<a href="{item.U_LINK}">{item.NAME}</a>
				</h3>

				<div class="more">
					<i class="fa fa-user"></i> # IF item.C_AUTHOR_EXIST #<a itemprop="author" rel="author" class="{item.USER_LEVEL_CLASS}" href="{item.U_AUTHOR_PROFILE}"# IF item.C_USER_GROUP_COLOR # style="{item.USER_GROUP_COLOR}"# ENDIF #>{item.PSEUDO}</a># ELSE #{item.PSEUDO}# ENDIF # -
					<i class="fa fa-clock-o"></i> {item.DATE} - <i class="fa fa-download"></i> {item.NUMBER_DOWNLOADS}
				</div>

				# IF item.C_PICTURE #
					<a href="{item.U_LINK}" class="item-picture">
						<img src="{item.U_PICTURE}" alt="{item.NAME}" itemprop="image" />
					</a>
				# ENDIF #

				<p class="item-desc">
					<div itemprop="text"># IF item.C_DESCRIPTION #{item.DESCRIPTION}# ELSE #{item.CONTENTS}# ENDIF ## IF item.C_READ_MORE #... <a href="{item.U_LINK}">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF #</div>
				</p>

			</div>
		# END item #
		</div>
	# ENDIF #
	<footer></footer>
</article>
