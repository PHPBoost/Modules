<section>
	<header>
		<h1>{@module_title}</h1>
	</header>
	<div class="content">
		<table>
			<tbody>
			# START servers #
			{servers.VIEW}
			# END servers #
			</tbody>
		</table>
		<div class="center"# IF C_SERVERS # style="display:none;"# ENDIF #>{@admin.config.servers.no_server}</div>
	</div>
	<footer></footer>
</section>
