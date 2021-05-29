# IF C_ERROR #
	<p><span class="error"><span class="text-strong">ERROR 0x{ERROR_CODE}</span> : {ERROR_MESSAGE}</span></p>
# ELSE #
	{VIEWER}
	# IF C_NUMBER_CLIENTS_DISPLAYED #
		<div class="spacer">&nbsp;</div>
		<div class="align-center">{NUMBER_CLIENTS} # IF C_SEVERAL_CLIENTS #{@ts.connected.clients}# ELSE #{@ts.connected.client}# ENDIF #</div>
	# ENDIF #
# ENDIF #
