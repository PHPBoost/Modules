<div id="module-mini-serverstatus" class="module-mini-container"# IF C_HORIZONTAL # style="width:auto;"# ENDIF #>
	<div class="module-mini-top">
		<div class="sub-title">{@module_title}</div>
	</div>
		<div class="module-mini-contents">
			# IF C_SERVERS #
				<table class="servers-mini">
					<tbody>
					# START servers #
						# IF C_HORIZONTAL ## IF servers.C_NEW_LINE #<tr># ENDIF ## ELSE #<tr># ENDIF #
							<td># IF servers.C_ICON #<img src="{servers.ICON}" alt="{servers.NAME}" title="{servers.NAME}" /># ENDIF #</td>
							<td>
								<span class="text-strong"><a href="{servers.U_DISPLAY_SERVER}">{servers.NAME}</a></span>
								# IF C_ADDRESS_DISPLAYED #
								<br />
								<span class="very-small">{servers.ADDRESS}:{servers.PORT}</span>
								# ENDIF #
							</td>
							<td class="status-picture">
								# IF servers.C_ONLINE #<img src="{PATH_TO_ROOT}/ServerStatus/templates/images/online.png" alt="{@server.online}" title="{@server.online}" /># ELSE #<img src="{PATH_TO_ROOT}/ServerStatus/templates/images/offline.png" alt="{@server.offline}" title="{@server.offline}" /># ENDIF #
							</td>
						# IF C_HORIZONTAL ## IF servers.C_END_LINE #</tr># ENDIF ## ELSE #</tr># ENDIF #
					# END servers #
					</tbody>
				</table>
			# ENDIF #
			<div class="center"# IF C_SERVERS #style="display:none;"# ENDIF #>{@admin.config.servers.no_server}</div>
		</div>
	<div class="module-mini-bottom"></div>
</div>
