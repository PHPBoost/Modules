
<article id="contact" style="order: {CONTACT_POSITION}; -webkit-order: {CONTACT_POSITION}; -ms-flex-order: {CONTACT_POSITION}">
	<header>
		<h2>

			<span class="actions">
				<a href="{PATH_TO_ROOT}/contact" title="${Langloader::get_message('link.to.contact', 'common', 'HomeLanding')}">
					${Langloader::get_message('link.to.contact', 'common', 'HomeLanding')}
				</a>
			</span>
		</h2>
	</header>
	<div class="content">
		# INCLUDE MSG #

		# IF C_MAP_ENABLED #
			# IF C_MAP_TOP #
				{MAP}
			# ENDIF #
		# ENDIF #
		# IF C_MAIL_SENT #
			<a href="{PATH_TO_ROOT}/contact" title="${Langloader::get_message('send.another.email', 'common', 'HomeLanding')}">
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
	<footer></footer>
</article>
