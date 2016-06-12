
<article id="last_web" style="order: {WEB_POSITION}; -webkit-order: {WEB_POSITION}; -ms-flex-order: {WEB_POSITION}">
	<header>
		<h2>
			${Langloader::get_message('last.web', 'common', 'HomeLanding')}
			<span class="actions">
				<a href="{PATH_TO_ROOT}/web" title="${Langloader::get_message('link.to.web', 'common', 'HomeLanding')}">
					${Langloader::get_message('link.to.web', 'common', 'HomeLanding')}
				</a>
			</span>
		</h2>
	</header>
	<div class="content"> 
		<ul>
		# START web_items #
			
			<li>
				<a href="{web_items.U_LINK}" title="{web_items.NAME}">
					# IF web_items.C_HAS_PARTNER_PICTURE #
						<img class="item-picture" src="{web_items.U_PARTNER_PICTURE}" alt="{web_items.NAME}" />
					# ELSE #
						{web_items.NAME}
					# ENDIF #
				</a>
			</li>	
			
		# END web_items #
		</ul>
	</div>        
	<footer></footer>
</article>