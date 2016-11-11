 

		<div>
			<ul class="lastcoms">
				# START coms #
				<li>
					# IF coms.PROFIL #
						<a href="{coms.PROFIL}"{coms.LEVEL}>{coms.LOGIN}</a>
					# ELSE #
						{coms.LOGIN}
					# ENDIF #
					<span class="small">{coms.DATE}</span>
					<p><a href="{coms.PATH}">{coms.CONTENTS}{coms.ETC}</a></p>
				</li>
				# END coms #
			</ul>
			<div class="spacer"></div>                         
		</div>
