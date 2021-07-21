<section id="module-smallads">
	<header class="section-header">
		<div class="controls align-right">
			<a class="offload" href="${relative_url(SyndicationUrlBuilder::rss('smallads', id_category))}" aria-label="{@common.syndication}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
			# IF NOT C_ROOT_CATEGORY #{@smallads.module.title}# ENDIF #
			# IF C_CATEGORY ## IF IS_ADMIN #<a class="offload" href="{U_EDIT_CATEGORY}" aria-label="{@common.edit}"><i class="fa fa-edit" aria-hidden="true"></i></a># ENDIF ## ENDIF #
		</div>
		<h1>
			# IF C_ARCHIVED #
				{@smallads.archived.items}
			# ELSE #
				# IF C_PENDING #
					{@smallads.pending.items}
				# ELSE #
					# IF C_MEMBER_ITEMS #
				 		# IF C_MY_ITEMS #{@smallads.my.items}# ELSE #{@smallads.member.items} {MEMBER_NAME}# ENDIF #
					# ELSE #
						# IF C_ROOT_CATEGORY #{@smallads.module.title}# ELSE #{CATEGORY_NAME}# ENDIF #
					# ENDIF #
				# ENDIF #
			# ENDIF #
		</h1>
	</header>

	# IF C_CATEGORY_DESCRIPTION #
		<div class="sub-section">
			<div class="content-container">
				<div class="cat-description">
					# IF NOT C_ROOT_CATEGORY #
						# IF C_DISPLAY_CAT_ICONS #
							# IF C_CATEGORY_THUMBNAIL #
								<img class="item-thumbnail" itemprop="thumbnailUrl" src="{U_CATEGORY_THUMBNAIL}" alt="{CATEGORY_NAME}" aria-label="{CATEGORY_NAME}" />
							# ENDIF #
						# ENDIF #
					# ENDIF #
					{CATEGORY_DESCRIPTION}
				</div>
			</div>
		</div>
	# ENDIF #

	# IF C_ENABLED_FILTERS #
		<div class="sub-section">
			<div class="content-container">
				# IF C_TYPES_FILTERS #
					<div class="listorder-panel">
						<div class="cell-flex cell-tile cell-columns-# IF C_PENDING #2# ELSE ## IF C_MEMBER #2# ELSE ## IF C_TAG #2# ELSE ## IF C_CATEGORY #3# ELSE #2# ENDIF ## ENDIF ## ENDIF ## ENDIF #">
							# IF C_CATEGORY #
								<div class="category-select cell">
									<div class="cell-body">
										<div class="cell-content">
											<span>{@smallads.category.select} :</span>
											<div class="category-selected">{CATEGORY_NAME} <i class="fa fa-fw fa-caret-down" aria-hidden="true"></i></div>
											<nav id="category-nav" class="cssmenu cssmenu-static dropdown-container">
												<ul>
													<li data-sa-cat-id="0" data-sa-parent-id="0" data-sa-c-order="0">
														<a class="cssmenu-title offload" href="{PATH_TO_ROOT}/smallads">{@smallads.all.types.filters}</a>
													</li>
													# START categories #
														<li data-sa-cat-id="{categories.ID}" data-sa-parent-id="{categories.ID_PARENT}" data-sa-c-order="{categories.SUB_ORDER}">
															<a class="cssmenu-title offload" href="{categories.U_CATEGORY}">{categories.NAME}</a>
														</li>
													# END categories #
												</ul>
											</nav>
										</div>
									</div>
									<script>jQuery("#category-nav").menumaker({ title: "{@smallads.category.list}", format: "multitoggle", breakpoint: 768 }); </script>
								</div>
							# ENDIF #

							<!-- Types filter -->
							<div class="listorder-type-filter cell">
								<div class="cell-body">
									<div class="cell-content">
										<span>{@smallads.form.smallads.types} :</span>
										<div class="type-filter-radio">
											<div class="selected-label">
												<span>{@smallads.all.types.filters}</span> <i class="fa fa-fw fa-caret-down" aria-hidden="true"></i>
											</div>
											<div class="label-list dropdown-container">
												<label class="listorder-label" for="default-radio">
													<input
														id="default-radio"
														type="radio"
														data-listorder-control="radio-buttons-path-filter"
														data-path="default"
				            							data-group="smallads-items"
													    name="smallads-type"
														checked />	{@smallads.all.types.filters}
												</label>
												# START types #
													<label class="listorder-label" for="{types.TYPE_NAME_FILTER}">
														<input
															id="{types.TYPE_NAME_FILTER}"
															type="radio"
															data-listorder-control="radio-buttons-path-filter"
															data-path=".{types.TYPE_NAME_FILTER}"
					            							data-group="smallads-items"
														    name="smallads-type"
															value="{types.TYPE_NAME}"/>	{types.TYPE_NAME}
													</label>
												# END types #
											</div>
										</div>
									</div>
								</div>
							</div>

							<!-- sort dropdown -->
							<div class="sort-list cell">
								<div class="cell-body">
									<div class="cell-content">
										<span>{@common.sort.by} :</span>
										<div
										    data-listorder-control="dropdown-sort"
										    class="listorder-drop-down"
										    data-group="smallads-items"
										    data-name="sorttitle">
											<div data-type="panel" class="listorder-dd-panel"></div>
											<ul data-type="content" class="dropdown-container">
												<li> {@common.sort.by.date}
													<em class="sort-type bgc-full link-color" data-path=".lo-date" data-order="asc" data-type="number"><span class="sr-only">{@common.sort.by.date} &#8593;</span> <i class="fa fa-sort-numeric-up-alt"></i></em>
													<em class="sort-type bgc-full logo-color" data-path=".lo-date" data-order="desc" data-type="number" data-selected="true"><span class="sr-only">{@common.sort.by.date} &#8595;</span> <i class="fa fa-sort-numeric-down-alt"></i></em>
												</li>
												<li> {@common.sort.by.alphabetic}
													<em class="sort-type bgc-full link-color" data-path=".lo-title" data-order="asc" data-type="text"><span class="sr-only">{@common.sort.by.alphabetic} &#8593;</span> <i class="fa fa-sort-alpha-up-alt"></i></em>
													<em class="sort-type bgc-full logo-color" data-path=".lo-title" data-order="desc" data-type="text"><span class="sr-only">{@common.sort.by.alphabetic} &#8595;</span> <i class="fa fa-sort-alpha-down-alt"></i></em>
												</li>
												<li> {@common.sort.by.price}
													<em class="sort-type bgc-full link-color" data-path=".lo-price" data-order="asc" data-type="number"><span class="sr-only">{@common.sort.by.price} &#8593;</span> <i class="fa fa-sort-numeric-up-alt"></i></em>
													<em class="sort-type bgc-full logo-color" data-path=".lo-price" data-order="desc" data-type="number"><span class="sr-only">{@common.sort.by.price} &#8595;</span> <i class="fa fa-sort-numeric-down-alt"></i></em>
												</li>
												# IF C_LOCATION #
												  	<li> {@common.sort.by.location}
													    <em class="sort-type bgc-full link-color" data-path=".lo-location" data-order="asc" data-type="text"><span class="sr-only">{@common.sort.by.location} &#8593;</span> <i class="fa fa-sort-alpha-up-alt"></i></em>
													   	<em class="sort-type bgc-full logo-color" data-path=".lo-location" data-order="desc" data-type="text"><span class="sr-only">{@common.sort.by.location} &#8595;</span> <i class="fa fa-sort-alpha-down-alt"></i></em>
											   		</li>
												# ENDIF #
							 					# IF NOT C_MEMBER #
													<li> {@common.sort.by.author}
														<em class="sort-type bgc-full link-color" data-path=".lo-author" data-order="asc" data-type="text"><span class="sr-only">{@common.sort.by.author} &#8593;</span> <i class="fa fa-sort-alpha-up-alt"></i></em>
														<em class="sort-type bgc-full logo-color" data-path=".lo-author" data-order="desc" data-type="text"><span class="sr-only">{@common.sort.by.author} &#8595;</span> <i class="fa fa-sort-alpha-down-alt"></i></em>
													</li>
												# ENDIF #
												# IF NOT C_PENDING #
													<li> {@common.sort.by.comments.number}
														<em class="sort-type bgc-full link-color" data-path=".lo-comment" data-order="asc" data-type="number"><span class="sr-only">{@common.sort.by.comments.number} &#8593;</span> <i class="fa fa-sort-numeric-up-alt"></i></em>
														<em class="sort-type bgc-full logo-color" data-path=".lo-comment" data-order="desc" data-type="number"><span class="sr-only">{@common.sort.by.comments.number} &#8595;</span> <i class="fa fa-sort-numeric-down-alt"></i></em>
													</li>
													<li> {@common.sort.by.views.number}
														<em class="sort-type bgc-full link-color" data-path=".lo-view" data-order="asc" data-type="number"><span class="sr-only">{@common.sort.by.views.number} &#8593;</span> <i class="fa fa-sort-numeric-up-alt"></i></em>
														<em class="sort-type bgc-full logo-color" data-path=".lo-view" data-order="desc" data-type="number"><span class="sr-only">{@common.sort.by.views.number} &#8595;</span> <i class="fa fa-sort-numeric-down-alt"></i></em>
													</li>
												# ENDIF #
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				# ENDIF #
			</div>
		</div>
	# ENDIF #

	<div class="sub-section">
		<div class="content-container">
			# IF C_NO_ITEM #
				# IF NOT C_HIDE_NO_ITEM_MESSAGE #
						<div class="content">
							<div class="message-helper bgc notice align-center">
								{@common.no.item.now}
							</div>
						</div>
				# ENDIF #
			# ELSE #
				# IF C_TABLE_VIEW #
					<div class="responsive-table">
						<table class="table">
							<thead>
								<tr>
									<th class="smallads-title">{@common.title}</th>
									<th>{@smallads.form.price}</th>
									<th>{@smallads.ad.type}</th>
									<th>{@common.author}</th>
									# IF C_LOCATION #<th>{@location}</th># ENDIF #
									# IF C_CATEGORY #<th>${@smallads.category}</th># ENDIF #
									<th>${@smallads.publication.date}</th>
									# IF C_MODERATION #
										<th>{@common.moderation}</th>
									# ENDIF #
								</tr>
							</thead>
							<tbody data-listorder-group="smallads-items">
								# START items #
									<tr data-listorder-item class="# IF items.C_COMPLETED # completed-smallad bgc error# ENDIF # category-{items.ID_CATEGORY}">
										<td>
											# IF NOT items.C_COMPLETED #<a class="offload" itemprop="url" href="{items.U_ITEM}"># ENDIF #
												<span class="lo-title# IF items.C_ARCHIVED # text-strike# ENDIF #" itemprop="name">{items.TITLE}</span>
											# IF NOT items.C_COMPLETED #</a># ENDIF #
											<span class="lo-view hidden">{items.VIEWS_NUMBER}</span>
											<span class="lo-comment hidden">{items.COMMENTS_NUMBER}</span>
											<span class="lo-date hidden">{items.DATE_TIMESTAMP}</span>
										</td>
										<td class="lo-price"># IF items.C_COMPLETED #{@common.status.finished}# ELSE ## IF items.C_PRICE #{items.PRICE} {items.CURRENCY}# ENDIF ## ENDIF #</td>
										<td class="{items.SMALLAD_TYPE_FILTER} smallads-type">{items.SMALLAD_TYPE}</td>
										# IF items.C_DISPLAYED_AUTHOR #
											<td class="lo-author">
												# IF items.C_CUSTOM_AUTHOR_NAME #
													{items.CUSTOM_AUTHOR_NAME}
												# ELSE #
													# IF items.C_AUTHOR_EXISTS #<a itemprop="author" href="{items.U_AUTHOR_PROFILE}" class="{items.AUTHOR_LEVEL_CLASS} offload" # IF C_AUTHOR_GROUP_COLOR # style="color:{items.AUTHOR_GROUP_COLOR}"# ENDIF #>{items.AUTHOR_DISPLAY_NAME}</a># ELSE #{items.AUTHOR_DISPLAY_NAME}# ENDIF #
												# ENDIF #
											</td>
										# ENDIF #
										# IF C_LOCATION #
											<td class="lo-location">
												# IF items.IS_LOCATED #
													# IF items.C_GMAP #
														{items.LOCATION}
													# ELSE #
														# IF items.C_OTHER_LOCATION #
															{@other.country} : {items.OTHER_LOCATION}
														# ELSE #
															{items.LOCATION}
														# ENDIF #
													# ENDIF #
												# ENDIF #
											</td>
										# ENDIF #
										# IF C_CATEGORY #
										<td>
											<a class="offload" aria-label="{@common.category}" itemprop="about" href="{items.U_CATEGORY}">{items.CATEGORY_NAME}</a>
										</td>
										# ENDIF #
										<td>
											# IF items.C_ARCHIVED #
												{@common.status.archived.alt}
											# ELSE #
												<time datetime="# IF NOT items.C_DIFFERED #{items.DATE_ISO8601}# ELSE #{items.PUBLISHING_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF NOT items.C_DIFFERED #{items.DATE_RELATIVE}# ELSE #{items.PUBLISHING_START_DATE_RELATIVE}# ENDIF #</time>
											# ENDIF #
										</td>
										# IF C_MODERATION #
											<td class="controls">
												# IF NOT items.C_COMPLETED #
													# IF items.C_EDIT #
														<a class="offload" href="{items.U_EDIT}" aria-label="{@common.edit}"><i class="fa fa-edit" aria-hidden="true"></i></a>
													# ENDIF #
												# ENDIF #
													# IF items.C_DELETE #
														<a href="{items.U_DELETE}" aria-label="{@common.delete}" data-confirmation="delete-element"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
													# ENDIF #
											</td>
										# ENDIF #
									</tr>
								# END items #
								<tr class="no-result hidden">
									<td colspan="# IF IS_ADMIN #8# ELSE #7# ENDIF #"><div class="message-helper bgc notice">{@common.no.item.now}</div></td>
								</tr>
							</tbody>
						</table>
					</div>

				# ELSE #
					<div data-listorder-group="smallads-items" class="# IF C_GRID_VIEW #cell-flex cell-columns-{ITEMS_PER_ROW}# ENDIF ## IF C_LIST_VIEW # cell-row# ENDIF #">
						# START items #
							<article data-listorder-item id="smallads-items-{items.ID}" class="smallads-item several-items category-{items.ID_CATEGORY} cell# IF items.C_COMPLETED# completed-smallad bgc error# ENDIF ## IF items.C_NEW_CONTENT # new-content# ENDIF #" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
								# IF items.C_COMPLETED #<span class="bigger">{@common.status.finished}</span># ENDIF #
								<header class="cell-header">
									<h2 class="cell-name# IF items.C_ARCHIVED # text-strike# ENDIF #">
										# IF NOT items.C_COMPLETED #<a class="lo-title offload" itemprop="url" href="{items.U_ITEM}"># ENDIF #
											<span itemprop="name">{items.TITLE}</span>
										# IF NOT items.C_COMPLETED #</a># ENDIF #
									</h2>
								</header>
								<div class="cell-infos">
									<div class="more">
										<span class="{items.SMALLAD_TYPE_FILTER} pinned success"><i class="fa fa-ticket-alt"></i> <span>{items.SMALLAD_TYPE}</span></span>
										# IF items.C_DISPLAYED_AUTHOR #
											<span class="lo-author pinned {items.AUTHOR_LEVEL_CLASS}">
												<i class="far fa-user"></i>
												# IF items.C_CUSTOM_AUTHOR_NAME #
													{items.CUSTOM_AUTHOR_NAME}
												# ELSE #
													# IF items.C_AUTHOR_EXISTS #<a itemprop="author" href="{items.U_AUTHOR_PROFILE}" class="{items.AUTHOR_LEVEL_CLASS} offload" # IF C_AUTHOR_GROUP_COLOR # style="color:{items.AUTHOR_GROUP_COLOR}"# ENDIF #>{items.AUTHOR_DISPLAY_NAME}</a># ELSE #{items.AUTHOR_DISPLAY_NAME}# ENDIF #,
												# ENDIF #
											</span>
										# ENDIF #
										<span class="pinned">
											<i class="far fa-calendar"></i>
											# IF items.C_ARCHIVED #
												{@common.status.archived.alt}
											# ELSE #
												<time datetime="# IF NOT items.C_DIFFERED #{items.DATE_ISO8601}# ELSE #{items.PUBLISHING_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF NOT items.C_DIFFERED #{items.DATE}# ELSE #{items.PUBLISHING_START_DATE}# ENDIF #</time>
											# ENDIF #
										</span>
										<span class="pinned">
											<i class="far fa-folder"></i> <a class="offload" aria-label="{@common.category}" itemprop="about" href="{items.U_CATEGORY}">{items.CATEGORY_NAME}</a>
										</span>
										# IF C_LOCATION #
											<span class="lo-location pinned">
												# IF items.C_GMAP #
													# IF items.IS_LOCATED #
														<i class="fa fa-map-marker-alt"></i> {items.LOCATION}
													# ENDIF #
												# ELSE #
													# IF items.IS_LOCATED #
														<i class="fa fa-map-marker-alt"></i>
														# IF items.C_OTHER_LOCATION #
															{@other.country} : {items.OTHER_LOCATION}
														# ELSE #
															{@county} : {items.LOCATION}
														# ENDIF #
													# ENDIF #
												# ENDIF #
											</span>
										# ENDIF #
										# IF C_MEMBER # | <i class="fa fa-fw fa-eye" aria-hidden="true"></i> {items.VIEWS_NUMBER} # ENDIF #
										<span class="lo-view hidden">{items.VIEWS_NUMBER}</span>
										<span class="lo-comment hidden">{items.COMMENTS_NUMBER}</span>
										<span class="lo-date hidden">{items.DATE_TIMESTAMP}</span>
									</div>
									# IF items.C_CONTROLS #
										<div class="controls">
											# IF NOT items.C_COMPLETED #
												# IF items.C_EDIT #
													<a class="offload" href="{items.U_EDIT}" aria-label="{@common.edit}"><i class="fa fa-edit" aria-hidden="true"></i></a>
												# ENDIF #
											# ENDIF #
											# IF items.C_DELETE #
												<a href="{items.U_DELETE}" aria-label="{@common.delete}" data-confirmation="delete-element"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
											# ENDIF #
										</div>
									# ENDIF #
								</div>
								<div class="cell-body">
									# IF items.C_HAS_THUMBNAIL #
										<div class="cell-thumbnail cell-landscape cell-center">
											<img src="{items.U_THUMBNAIL}" alt="{items.TITLE}" itemprop="thumbnailUrl" />
											# IF NOT items.C_COMPLETED #<a href="{items.U_ITEM}" class="cell-thumbnail-caption offload">{@common.read.more}</a># ENDIF #
										</div>
									# ENDIF #
									<div class="cell-content">
										<div itemprop="text">{items.SUMMARY}# IF items.C_READ_MORE #... # IF NOT items.C_COMPLETED #<a class="read-more offload" href="{items.U_ITEM}">[{@common.read.more}]</a># ENDIF ## ENDIF #</div>
										<div class="smallad-price lo-price"># IF items.C_PRICE #{items.PRICE} {items.CURRENCY}# ENDIF #</div>
									</div>
								</div>

								# IF items.C_SOURCES #
									<aside>
										<div id="smallads-sources-container">
											<span>{@common.sources}</span> :
											# START items.sources #
												<a itemprop="isBasedOnUrl" href="{items.sources.URL}" class="small offload">{items.sources.NAME}</a># IF items.sources.C_SEPARATOR #, # ENDIF #
											# END items.sources #
										</div>
									</aside>
								# ENDIF #

								<footer>
									<meta itemprop="url" content="{items.U_ITEM}">
									<meta itemprop="description" content="${escape(items.SUMMARY)}"/>
									<meta itemprop="discussionUrl" content="{items.U_COMMENTS}">
									<meta itemprop="interactionCount" content="{items.COMMENTS_NUMBER} UserComments">
								</footer>
							</article>
						# END items #
					</div>
					<div class="no-result hidden message-helper bgc notice"> {@common.no.item.now} </div>
				# ENDIF #
			# ENDIF #
		</div>
	</div>

	# IF C_PAGINATION #
		<div class="sub-section items-pagination">
			<div class="content-container">
				<nav
				   	class="listorder-pagination pagination"
				   	data-listorder-control="pagination"
			        data-group="smallads-items"
			        data-items-per-page="{ITEMS_PER_PAGE}"
			        data-current-page="0"
			        data-name="pagination1"
					data-id="paging">
					<p data-type="info" class="align-center">
						{@common.listorder.item.start} - {@common.listorder.item.end} / {@common.listorder.items.number} ${TextHelper::lcfirst(@items)}
					</p>
					<ul>
						<li class="pagination-item" data-type="first" aria-label="{@common.pagination.first}"><a href="#"><i class="fa fa-chevron-circle-left" aria-hidden="true"></i></a> </li>
					    <li class="pagination-item" data-type="prev" aria-label="{@common.pagination.previous}"><a href="#"><i class="fa fa-chevron-left" aria-hidden="true"></i></a> </li>

					    <ul class="listorder-holder" data-type="pages">
					        <li class="pagination-item" data-type="page"><a href="#">{@common.listorder.page.number}</a></li>
					    </ul>

					    <li class="pagination-item" data-type="next" aria-label="{@common.pagination.next}"><a href="#"><i class="fa fa-chevron-right" aria-hidden="true"></i></a> </li>
					    <li class="pagination-item" data-type="last" aria-label="{@common.pagination.last}"><a href="#"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a> </li>
					</ul>
					<div class="align-center">
			            <select data-type="items-per-page">
			                <option value="{ITEMS_PER_PAGE}"> {ITEMS_PER_PAGE} {@common.pagination.per}</option>
			                <option value="25"> 25 {@common.pagination.per}</option>
			                <option value="50"> 50 {@common.pagination.per}</option>
			                <option value="0"> {@common.all.alt} </option>
			            </select>
					</div>
				</nav>
			</div>
		</div>
	# ENDIF #

	<footer>
		# IF C_USAGE_TERMS #
			<div class="sub-section">
				<div class="content-container">
					<div class="content"><i class="fa fa-book" aria-hidden="true"></i> <a class="offload" href="{U_USAGE_TERMS}">{@smallads.usage.terms}</a></div>
				</div>
			</div>
		# ENDIF #
	</footer>
</section>

<script>
	jQuery('document').ready(function(){
		// listorder
		listorder.init();

		jQuery('input[type=radio][name=smallads-type]').change(function(){
			var itemsNumber = jQuery('[data-listorder-item]').length,
				maxItems = {ITEMS_PER_PAGE};
			if (itemsNumber < 1) jQuery('.no-result').show();
			else jQuery('.no-result').hide();
			if (itemsNumber < maxItems) jQuery('.items-pagination').hide();
			else jQuery('.items-pagination').show();
		});

		// Type filters
			// toggle sub-menu on click (close on click outside)
		jQuery('.selected-label').on('click', function(e){
			jQuery('.label-list').toggleClass('reveal-list');
    		e.stopPropagation();
		});
		jQuery(document).click(function(e) {
		    if (jQuery(e.target).is('.selected-label') === false) {
		      jQuery('.label-list').removeClass('reveal-list');
		    }
		});
			// send label text of selected input to title on click
		jQuery('.label-list input').on('click', function(e) {
		    var radioText = e.currentTarget.nextSibling.data;
		    jQuery('.selected-label span').html(radioText);
		});

		// Categories
			// build order
		jQuery('#category-nav').append(CreatChild(0)).find('ul:first').remove();
		function CreatChild(id){
		    var $li = jQuery('li[data-sa-parent-id=' + id + ']').sort(function(a, b){
				return jQuery(a).attr('data-sa-c-order') - jQuery(b).attr('data-sa-c-order');
			});
		    if($li.length > 0){
		        for(var i = 0; i < $li.length; i++){
		            var $this = $li.eq(i);
					$this[0].remove();
		            $this.append(CreatChild($this.attr('data-sa-cat-id')));
		        }
		        return jQuery('<ul>').append($li);
		    }
		}

			// build cssmenu
		jQuery('li:not([cat_id=0])').has('ul').addClass('has-sub');

			// change root name
		jQuery('.category-selected:contains("{@common.root}")').html('{@smallads.category.all} <i class="fa fa-fw fa-caret-down" aria-hidden="true"></i>');

			// toggle sub-menu (close on click outside)
		jQuery('.category-selected').on('click', function(e){
			jQuery('.category-select').toggleClass('reveal-subcat');
    		e.stopPropagation();
		});
		jQuery(document).on('click', function(e) {
		    if (jQuery(e.target).is('.category-selected') === false) {
		      jQuery('.category-select').removeClass('reveal-subcat');
		    }
		});
	});

</script>
