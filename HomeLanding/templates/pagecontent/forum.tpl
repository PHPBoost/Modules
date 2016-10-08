
<article id="last_messages" style="order: {FORUM_POSITION}; -webkit-order: {FORUM_POSITION}; -ms-flex-order: {FORUM_POSITION}">
	<header>
		<h2>
			${Langloader::get_message('last.forum.messages', 'common', 'HomeLanding')}
			<span class="actions">
				<a href="{PATH_TO_ROOT}/forum" title="${Langloader::get_message('link.to.forum', 'common', 'HomeLanding')}">
					${Langloader::get_message('link.to.forum', 'common', 'HomeLanding')}
				</a>
			</span>
		</h2>
	</header>
	<div class="content">
	# START forum_items #	
		<div class="item-content" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
			
			<img class="avatar" src="{forum_items.U_AVATAR}" alt="{forum_items.PSEUDO}" />
			
			<div class="more">
				<p class="color-topic"><i class="fa fa-user"></i> {forum_items.PSEUDO}</p>
				<p><i class="fa fa-clock-o"></i>  {forum_items.DATE}</p>
				<p><i class="fa fa-file-o"></i>  <span class="color-topic"><a href="{forum_items.U_MESSAGE}">{forum_items.MESSAGE}</a></p>
			</div>
			
			<p class="item-desc">				
				{forum_items.CONTENTS} ... <a href="{forum_items.U_LINK}">[${LangLoader::get_message('read-more', 'common')}]</i></a>
			</p>
						
		</div>
	# END forum_items #
	</div>            
	<footer></footer>
</article>