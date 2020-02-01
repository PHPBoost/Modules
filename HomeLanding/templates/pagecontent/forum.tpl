
<article id="forum" style="order: {FORUM_POSITION};">
	<header>
		<h2>
			${Langloader::get_message('last.forum.messages', 'common', 'HomeLanding')}
		</h2>
		<span class="controls">
			<a href="{PATH_TO_ROOT}/forum">
				${Langloader::get_message('link.to.forum', 'common', 'HomeLanding')}
			</a>
		</span>
	</header>
	<div class="elements-container columns-3 no-style">
	# START item #
		<div class="item-content block" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">

			<img class="avatar" src="{item.U_AVATAR}" alt="{item.PSEUDO}" />

			<div class="more">
				<p><i class="fa fa-fw fa-user"></i> {item.PSEUDO}</p>
				<p><i class="far fa-fw fa-clock"></i> {item.DATE}</p>
				<p><i class="fa fa-fw fa-file"></i>  <a href="{item.U_MESSAGE}">{item.MESSAGE}</a></p>
			</div>

			<p class="item-desc">
				{item.CONTENTS} ... <a href="{item.U_LINK}">[${LangLoader::get_message('read-more', 'common')}]</a>
			</p>

		</div>
	# END item #
	</div>
	<footer></footer>
</article>
