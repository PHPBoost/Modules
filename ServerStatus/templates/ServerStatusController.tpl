<section>
	<header>
		<h1>{@module_title}</h1>
	</header>
	<div class="cell-row">
		<div class="cell">
			# IF C_SERVERS #
				<div class="cell-list">
					<ul>
						# START servers #
							{servers.VIEW}
						# END servers #
					</ul>
				</div>
			# ELSE #
				<div class="cell-body">
					<div class="cell-content align-center">{@admin.config.servers.no_server}</div>
				</div>
			# ENDIF #
		</div>
	</div>
	<footer></footer>
</section>
