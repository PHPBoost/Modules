
<article id="forum" style="order: {FORUM_POSITION}; -webkit-order: {FORUM_POSITION}; -ms-flex-order: {FORUM_POSITION}">
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
	<div class="elements-container columns-3 no-style">
	# START item #
		<div class="item-content block" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">

			<img class="avatar" src="{item.U_AVATAR}" alt="{item.PSEUDO}" />

			<div class="more">
				<p><i class="fa fa-fw fa-user"></i> {item.PSEUDO}</p>
				<p><i class="fa fa-fw fa-clock-o"></i> {item.DATE}</p>
				<p><i class="fa fa-fw fa-file-o"></i>  <span class="color-topic"><a href="{item.U_MESSAGE}">{item.MESSAGE}</a></span></p>
			</div>

			<p class="item-desc">
				{item.CONTENTS} ... <a href="{item.U_LINK}">[${LangLoader::get_message('read-more', 'common')}]</i></a>
			</p>

		</div>
	# END item #
	</div>
	<footer></footer>
</article>
