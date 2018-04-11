
<article id="gallery" style="order: {GALLERY_POSITION}; -webkit-order: {GALLERY_POSITION}; -ms-flex-order: {GALLERY_POSITION}">
	<header>
		<h2>
			${Langloader::get_message('last.gallery', 'common', 'HomeLanding')}
			<span class="actions">
				<a href="{PATH_TO_ROOT}/gallery" title="${Langloader::get_message('link.to.gallery', 'common', 'HomeLanding')}">
					${Langloader::get_message('link.to.gallery', 'common', 'HomeLanding')}
				</a>
			</span>
		</h2>
	</header>
	<div class="elements-container columns-{COL_NBR} no-style">
	# START item #
		<div class="item-content block" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
			<a href="{item.U_CATEGORY}">
				<img src="{item.U_IMG}" alt="{item.TITLE}" />
				<p>{item.TITLE} <br /><i class="fa fa-eye"></i> {item.NB_VIEWS}</p>
			</a>
		</div>
	# END item #
	<div class="spacer"></div>
	</div>
	<footer></footer>
</article>
