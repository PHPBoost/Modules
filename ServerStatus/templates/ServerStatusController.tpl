<section>
	<header class="section-header">
		<h1>{@module_title}</h1>
	</header>
	<div class="sub-section">
		<div class="content-container">
			<div class="cell-row">
				<article class="cell">
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
							<div class="cell-content">
								<span class="message-helper bgc notice">${LangLoader::get_message('no_item_now', 'common')}</span>
							</div>
						</div>
					# ENDIF #
				</article>
			</div>
		</div>
	</div>
	<footer></footer>
</section>
