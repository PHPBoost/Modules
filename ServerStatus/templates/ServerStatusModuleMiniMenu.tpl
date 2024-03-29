<div id="module-mini-serverstatus" class="cell-tile cell-mini# IF C_VERTICAL # cell-mini-vertical# ENDIF ## IF C_HIDDEN_WITH_SMALL_SCREENS # hidden-small-screens# ENDIF #">
	<div class="cell">
		<div class="cell-header">
			<h6 class="cell-name">{@server.module.title}</h6>
		</div>
		# IF C_SERVERS #
			<div class="cell-list">
				<ul>
					# START servers #
						<li class="li-stretch">
							# IF servers.C_ICON #<img src="{servers.ICON}" alt="{servers.NAME}" /># ENDIF #
							<span class="text-strong">
								<a class="offload" href="{servers.U_DISPLAY_SERVER}">{servers.NAME}</a>
								# IF C_ADDRESS_DISPLAYED #<br /><span class="smaller">{servers.ADDRESS}:{servers.PORT}</span># ENDIF #
							</span>
							# IF servers.C_ONLINE #
								<span aria-label="{@server.online}"><i class="fa fa-signal success" aria-hidden="true"></i></span>
							# ELSE #
								<span aria-label="{@server.offline}"><i class="fa fa-signal fa-flip-horizontal error" aria-hidden="true"></i></span>
							# ENDIF #
						</li>
					# END servers #
				</ul>
			</div>
		# ELSE #
			<div class="cell-alert">
				<div class="message-helper bgc notice">{@common.no.item.now}</div>
			</div>
		# ENDIF #
	</div>
</div>
