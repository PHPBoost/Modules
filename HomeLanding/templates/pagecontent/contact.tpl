<div class="sub-section" style="order: {CONTACT_POSITION};">
	<div class="content-container">
		<article id="contact-panel">
			<header class="module-header">
				<span class="controls">
					<a href="{PATH_TO_ROOT}/contact">
						${Langloader::get_message('link.to.contact', 'common', 'HomeLanding')}
					</a>
				</span>
			</header>
			<div class="content">
				# INCLUDE MSG #

				# IF C_MAP_ENABLED #
					# IF C_MAP_TOP #
						{MAP}
					# ENDIF #
				# ENDIF #
				# IF C_MAIL_SENT #
					<a href="{PATH_TO_ROOT}/contact">
						${Langloader::get_message('send.another.email', 'common', 'HomeLanding')}
					</a>
				# ELSE #
					# INCLUDE CONTACT_FORM #
				# ENDIF #

				# IF C_MAP_ENABLED #
					# IF C_MAP_BOTTOM #
						{MAP}
					# ENDIF #
				# ENDIF #
			</div>
		</article>
	</div>
</div>
