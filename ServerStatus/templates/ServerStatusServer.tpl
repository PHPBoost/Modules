<li class="li-stretch">
	# IF C_ICON #<img src="{ICON}" alt="{NAME}" /># ELSE #<span></span># ENDIF #
	<div id="{ANCHOR}">
		<h2 class="biggest">{NAME}</h2>
		# IF C_ADDRESS_DISPLAYED #
			<span class="smaller">{ADDRESS}:{PORT}</span>
		# ENDIF #
	</div>
	# IF C_ONLINE #
		<span aria-label="{@server.online}"></span><i class="fa fa-signal fa-2x success" aria-hidden="true"></i>
	# ELSE #
		<span aria-label="{@server.offline}"></span><i class="fa fa-signal fa-flip-horizontal fa-2x error" aria-hidden="true"></i>
	# ENDIF #
</li>
# IF C_DESCRIPTION #
	<li>
		{DESCRIPTION}
	</li>
# ENDIF #
<hr />
