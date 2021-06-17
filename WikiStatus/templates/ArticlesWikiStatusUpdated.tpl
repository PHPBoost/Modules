<div class="cell-table">
	<table id="table-mini-wiki-status" class="table">
		<thead>
			<tr>
				<th><span aria-label="{@common.date}"><i class="far fa-fw fa-calendar" aria-hidden="true"></i></span><span class="hidden-large-screens">{@common.date}</span></th>
				<th><span aria-label="{@wiki.status.item}"><i class="far fa-fw fa-file-alt" aria-hidden="true"></i></span><span class="hidden-large-screens">{@wiki.status.item}</span></th>
				# IF C_HORIZONTAL #
					<th><span aria-label="{@common.status}"><i class="fa fa-fw fa-tasks" aria-hidden="true"></i></span><span class="hidden-large-screens">{@common.status}</span></th>
					<th><span aria-label="{@common.author}"><i class="fa fa-fw fa-user" aria-hidden="true"></i></span><span class="hidden-large-screens">{@common.author}</span></th>
				# ENDIF #
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="# IF C_HORIZONTAL #4# ELSE #2# ENDIF #" class="more">{@wiki.status.clue}</td>
			</tr>
		</tfoot>
		<tbody>
			# START wiki_items #
				<tr>
					<td># IF C_HORIZONTAL #{wiki_items.DATE}# ELSE #{wiki_items.SHORT_DATE}# ENDIF #</td>
					<td><a class="offload" href="{PATH_TO_ROOT}/wiki/{wiki_items.U_ITEM}">{wiki_items.TITLE}</a></td>
					# IF C_HORIZONTAL #
						<td><span class="pinned {wiki_items.STATUS_CLASS}">{wiki_items.L_STATUS}</span></td>
						<td>
							# IF wiki_items.C_AUTHOR_EXISTS #
								<a href="{wiki_items.U_AUTHOR_PROFILE}" class="{wiki_items.AUTHOR_LEVEL_CLASS} offload" # IF wiki_items.C_AUTHOR_GROUP_COLOR # style="color:{wiki_items.AUTHOR_GROUP_COLOR}"# ENDIF #>{wiki_items.AUTHOR_DISPLAY_NAME}</a>
							# ELSE #
								<span aria-label="{wiki_items.AUTHOR_IP}">{@user.guest}</span>
							# ENDIF #
						</td>
					# ENDIF #
				</tr>
			# END wiki_items #
		</tbody>
	</table>
</div>
