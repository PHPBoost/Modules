# IF C_ERROR #
	<p><span class="error"><span class="text-strong">ERROR 0x{ERROR_CODE}</span> : {ERROR_MESSAGE}</span></p>
# ELSE #
	{VIEWER}
	# IF C_NUMBER_CLIENTS_DISPLAYED #
		<div class="spacer">&nbsp;</div>
		<div class="center">{NUMBER_CLIENTS} # IF C_MORE_THAN_ONE_CLIENT #{@connected_clients}# ELSE #{@connected_client}# ENDIF #</div>
	# ENDIF #
# ENDIF #