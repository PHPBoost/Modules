<div class="cell-table">
	<table id="table-mini-wiki-status" class="table">
		<thead>
			<tr>
				<th><span aria-label="{@header.date}"><i class="far fa-fw fa-calendar"></i></span></th>
				<th><span aria-label="{@header.item}"><i class="far fa-fw fa-file-alt"></i></span></th>
				<th><span aria-label="{@header.status}"><i class="fa fa-fw fa-tasks"></i></span></th>
				<th><span aria-label="{@header.author}"><i class="fa fa-fw fa-user"></i></span></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="4" class="more">{@wiki.status.explain}</td>
			</tr>
		</tfoot>
		<tbody>
			# START wiki_items #
				<tr>
					<td>{wiki_items.DATE}</td>
					<td><a href="{PATH_TO_ROOT}/wiki/{wiki_items.U_ITEM}">{wiki_items.TITLE}</a></td>
					<td><span class="pinned {wiki_items.STATUS_CLASS}">{wiki_items.STATUS}</span></td>
					<td># IF wiki_items.C_AUTHOR_EXIST #<a href="{wiki_items.U_AUTHOR_PROFILE}" class="{wiki_items.USER_LEVEL_CLASS}" # IF wiki_items.C_USER_GROUP_COLOR # style="color:{wiki_items.USER_GROUP_COLOR}"# ENDIF #>{wiki_items.PSEUDO}</a># ELSE #{wiki_items.AUTHOR_IP}# ENDIF #</td>
				</tr>
			# END wiki_items #
		</tbody>
	</table>
</div>
