# IF C_COMS #
	# IF C_HORIZONTAL #
		<div class="block-container">
			<div class="block-content">
				<div class="sub-title">
					{@lastcoms.title}
				</div>
	# ENDIF #
				<ul class="lastcoms# IF C_HORIZONTAL # lastcoms-horizontal# ENDIF #">
					# START coms #
					<li>
						# IF coms.PROFIL #
							<a href="{coms.PROFIL}"{coms.LEVEL}>{coms.LOGIN}</a>
						# ELSE #
							{coms.LOGIN}
						# ENDIF #
						<span class="small">{coms.DATE}</span>
						<p><a href="{coms.PATH}">{coms.COM_CONTENT}{coms.ETC}</a></p>
					</li>
					# END coms #
				</ul>
	# IF C_HORIZONTAL #
			</div>
		</div>
	# ENDIF #
# ELSE #
	{@lastcoms.no.com}
# ENDIF #
