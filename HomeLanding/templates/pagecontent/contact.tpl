<div class="sub-section" style="order: {CONTACT_POSITION};">
	<div class="content-container">
		<article id="contact-panel">
			<header class="module-header flex-between">
				<h2>{L_MODULE_TITLE}</h2>
				<div class="controls align-right">
					<a href="{PATH_TO_ROOT}/contact" aria-label="{@homelanding.see.module}"><i class="fa fa-share-square"></i></a>
				</div>
			</header>
			<div class="content">
				# INCLUDE MESSAGE_HELPER #

				# IF C_MAP_ENABLED #
					# IF C_MAP_TOP #
						{MAP}
					# ENDIF #
				# ENDIF #
				# IF C_MAIL_SENT #
					<a href="{PATH_TO_ROOT}/contact">
						{@homelanding.send.another.email}
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
