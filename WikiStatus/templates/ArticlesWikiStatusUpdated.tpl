<div class="cell-table">
	<table id="table-mini-wiki-status" class="table">
		<thead>
			<tr>
				<th><span aria-label="{@header.date}"><i class="far fa-fw fa-calendar" aria-hidden></i></span><span class="hidden-large-screens">{@header.date}</span></th>
				<th><span aria-label="{@header.item}"><i class="far fa-fw fa-file-alt" aria-hidden></i></span><span class="hidden-large-screens">{@header.item}</span></th>
				# IF C_HORIZONTAL #
					<th><span aria-label="{@header.status}"><i class="fa fa-fw fa-tasks" aria-hidden></i></span><span class="hidden-large-screens">{@header.status}</span></th>
					<th><span aria-label="{@header.author}"><i class="fa fa-fw fa-user" aria-hidden></i></span><span class="hidden-large-screens">{@header.author}</span></th>
				# ENDIF #
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="# IF C_HORIZONTAL #4# ELSE #2# ENDIF #" class="more">{@wiki.status.explain}</td>
			</tr>
		</tfoot>
		<tbody>
			# START wiki_items #
				<tr>
					<td># IF C_HORIZONTAL #{wiki_items.DATE}# ELSE #{wiki_items.SHORT_DATE}# ENDIF #</td>
					<td><a href="{PATH_TO_ROOT}/wiki/{wiki_items.U_ITEM}">{wiki_items.TITLE}</a></td>
					# IF C_HORIZONTAL #
						<td><span class="pinned {wiki_items.STATUS_CLASS}">{wiki_items.STATUS}</span></td>
						<td># IF wiki_items.C_AUTHOR_EXIST #<a href="{wiki_items.U_AUTHOR_PROFILE}" class="{wiki_items.USER_LEVEL_CLASS}" # IF wiki_items.C_USER_GROUP_COLOR # style="color:{wiki_items.USER_GROUP_COLOR}"# ENDIF #>{wiki_items.PSEUDO}</a># ELSE #{wiki_items.AUTHOR_IP}# ENDIF #</td>
					# ENDIF #
			</tr>
			# END wiki_items #
		</tbody>
	</table>
</div>
