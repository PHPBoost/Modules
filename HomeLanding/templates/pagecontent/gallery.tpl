
<article id="last_gallery" style="order: {GALLERY_POSITION}; -webkit-order: {GALLERY_POSITION}; -ms-flex-order: {GALLERY_POSITION}">
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
	<div class="content"> 
	# START gallery_items #
		<div class="item-content" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
			<a href="{gallery_items.U_CATEGORY}">
				<img src="{gallery_items.U_IMG}" alt="{gallery_items.TITLE}" />
				<p>{gallery_items.TITLE} <br /><i class="fa fa-eye"></i> {gallery_items.NB_VIEWS}</p>
			</a>
		</div>
	# END gallery_items #
	<div class="spacer"></div>
	</div>            
	<footer></footer>
</article>