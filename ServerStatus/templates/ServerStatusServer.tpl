	<tr>
		<td class="no-separator" style="padding:10px"># IF C_ICON #<img src="{ICON}" alt="" /># ENDIF #</td>
		<td id="{ANCHOR}" class="no-separator">
			<span class="biggest">{NAME}</span>
			# IF C_ADDRESS_DISPLAYED #
			<br />
			<span class="smaller">{ADDRESS}:{PORT}</span>
			# ENDIF #
			# IF C_DESCRIPTION #
			<div class="spacer">&nbsp;</div>
			{DESCRIPTION}
			# ENDIF #
		</td>
		<td class="no-separator">
			# IF C_ONLINE #<img src="{PATH_TO_ROOT}/ServerStatus/templates/images/online.png" alt="{@server.online}" title="{@server.online}" /># ELSE #<img src="{PATH_TO_ROOT}/ServerStatus/templates/images/offline.png" alt="{@server.offline}" title="{@server.offline}" /># ENDIF #
		</td>
	</tr>
