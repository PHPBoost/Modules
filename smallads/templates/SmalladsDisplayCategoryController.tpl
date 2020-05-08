<section id="smallads-module">
	<header>
		<div class="align-right controls">
			<a href="${relative_url(SyndicationUrlBuilder::rss('smallads', id_category))}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-rss warning" aria-hidden="true"></i> <span class="sr-only">${LangLoader::get_message('syndication', 'common')}</span></a>
			# IF C_CATEGORY ## IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true"></i></a># ENDIF ## ENDIF #
		</div>
		<h1>
			# IF C_PENDING #{@smallads.pending.items}# ELSE #{@smallads.module.title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF ## ENDIF #
		</h1>
	</header>

	# IF C_CATEGORY_DESCRIPTION #
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
	# ENDIF #
	<div class="spacer"></div>

	# IF C_ENABLED_FILTERS #
		# IF C_TYPES_FILTERS #
			<div class="jplist-panel">
				<div class="cell-flex cell-tile cell-columns-# IF C_PENDING #2# ELSE ## IF C_MEMBER #2# ELSE ## IF C_TAG #2# ELSE ## IF C_CATEGORY #3# ELSE #2# ENDIF ## ENDIF ## ENDIF ## ENDIF #">
					<!-- Categories -->
					# IF C_CATEGORY #
						<div class="category-select cell">
							<div class="cell-body">
								<div class="cell-content">
									<span>{@smallads.category.select} :</span>
									<div class="category-selected">{CATEGORY_NAME} <i class="fa fa-fw fa-caret-down" aria-hidden="true"></i></div>
									<nav id="category-nav" class="cssmenu cssmenu-static dropdown-container">
										<ul>
											<li data-sa-cat-id="0" data-sa-parent-id="0" data-sa-c-order="0">
												<a class="cssmenu-title" href="{PATH_TO_ROOT}/smallads">{@smallads.all.types.filters}</a>
											</li>
											# START categories #
												<li data-sa-cat-id="{categories.ID}" data-sa-parent-id="{categories.ID_PARENT}" data-sa-c-order="{categories.SUB_ORDER}">
													<a class="cssmenu-title" href="{categories.U_CATEGORY}">{categories.NAME}</a>
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
					<div class="jplist-type-filter cell">
						<div class="cell-body">
							<div class="cell-content">
								<span>{@smallads.form.smallads.types} :</span>
								<div class="type-filter-radio">
									<div class="selected-label">
										<span>{@smallads.all.types.filters}</span> <i class="fa fa-fw fa-caret-down" aria-hidden="true"></i>
									</div>
									<div class="label-list dropdown-container">
										<label class="jplist-label" for="default-radio">
											<input
												id="default-radio"
												type="radio"
												data-jplist-control="radio-buttons-text-filter"
												data-path="default"
		            							data-group="smallads-items"
											    name="jplist"
												checked
											/>	{@smallads.all.types.filters}
										</label>
										# START types #
											<label class="jplist-label" for="{types.TYPE_NAME_FILTER}">
												<input
													id="{types.TYPE_NAME_FILTER}"
													type="radio"
													data-jplist-control="radio-buttons-text-filter"
													data-path=".smallads-type"
													data-control-name="default"
			            							data-group="smallads-items"
												    name="jplist"
													value="{types.TYPE_NAME}"
												/>	{types.TYPE_NAME}
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
								<span>{@smallads.sort.by} :</span>
								<div
								    data-jplist-control="dropdown-sort"
								    class="jplist-drop-down"
								    data-group="smallads-items"
								    data-name="sorttitle">
									<div data-type="panel" class="jplist-dd-panel"></div>
									<ul data-type="content" class="dropdown-container">
										<li data-path=".jp-date" 		data-order="asc" 	data-type="date"> {@smallads.sort.date} <em class="sort-type">&#8593;</em></li>
										<li data-path=".jp-date" 		data-order="desc" 	data-type="date" data-selected="true"> {@smallads.sort.date} <em class="sort-type">&#8595;</em></li>
										<li data-path=".jp-title" 		data-order="asc" 	data-type="text"> {@smallads.sort.title} <em class="sort-type">&#8593;</em></li>
										<li data-path=".jp-title" 		data-order="desc" 	data-type="text"> {@smallads.sort.title} <em class="sort-type">&#8595;</em></li>
										<li data-path=".jp-price" 		data-order="asc" 	data-type="number"> {@smallads.sort.price} <em class="sort-type">&#8593;</em></li>
										<li data-path=".jp-price" 		data-order="desc" 	data-type="number"> {@smallads.sort.price} <em class="sort-type">&#8595;</em></li>
					   # IF C_LOCATION #<li data-path=".jp-location" 	data-order="asc" 	data-type="text"> {@location} <em class="sort-type">&#8593;</em></li>
							   		 	<li data-path=".jp-location" 	data-order="desc" 	data-type="text"> {@location} <em class="sort-type">&#8595;</em></li># ENDIF #
					 # IF NOT C_MEMBER #<li data-path=".jp-author" 		data-order="asc" 	data-type="text"> {@smallads.sort.author} <em class="sort-type">&#8593;</em></li>
										<li data-path=".jp-author" 		data-order="desc" 	data-type="text"> {@smallads.sort.author} <em class="sort-type">&#8595;</em></li># ENDIF #
					# IF NOT C_PENDING #<li data-path=".jp-comment" 	data-order="asc" 	data-type="number"> {@smallads.sort.coms} <em class="sort-type">&#8593;</em></li>
										<li data-path=".jp-comment" 	data-order="desc" 	data-type="number"> {@smallads.sort.coms} <em class="sort-type">&#8595;</em></li>
										<li data-path=".jp-view" 		data-order="asc" 	data-type="number"> {@smallads.sort.view} <em class="sort-type">&#8593;</em></li>
										<li data-path=".jp-view" 		data-order="desc" 	data-type="number"> {@smallads.sort.view} <em class="sort-type">&#8595;</em></li># ENDIF #
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		# ENDIF #
	# ENDIF #

	# IF C_NO_ITEM_AVAILABLE #
		# IF NOT C_HIDE_NO_ITEM_MESSAGE #
			<div class="content">
				<div class="message-helper bgc notice align-center">
					${LangLoader::get_message('no_item_now', 'common')}
				</div>
			</div>
		# ENDIF #
	# ELSE #
		# IF C_DISPLAY_TABLE_VIEW #
			<table class="table">
				<thead>
					<tr>
						<th class="smallads-title">${LangLoader::get_message('title', 'main')}</th>
						<th>{@smallads.form.price}</th>
						<th>{@smallads.ad.type}</th>
						<th>${LangLoader::get_message('author', 'common')}</th>
						# IF C_LOCATION #<th>{@location}</th># ENDIF #
						# IF C_CATEGORY #<th>${@smallads.category}</th># ENDIF #
						<th>${@smallads.publication.date}</th>
						# IF C_MODERATION #
							<th>${LangLoader::get_message('administrator_alerts_action', 'admin')}</th>
						# ENDIF #
					</tr>
				</thead>
				<tbody data-jplist-group="smallads-items">
					# START items #
						<tr data-jplist-item class="# IF items.C_COMPLETED # completed-smallad bgc error# ENDIF # category-{items.ID_CATEGORY}">
							<td>
								# IF NOT items.C_COMPLETED #<a itemprop="url" href="{items.U_ITEM}"># ENDIF #
									<span class="jp-title" itemprop="name">{items.TITLE}</span>
								# IF NOT items.C_COMPLETED #</a># ENDIF #
								<span class="jp-view hidden">{items.VIEWS_NUMBER}</span>
								<span class="jp-comment hidden">{items.COMMENTS_NUMBER}</span>
								<span class="jp-date hidden">{items.DATE_TIMESTAMP}</span>
							</td>
							<td class="jp-price"># IF items.C_COMPLETED #{@smallads.completed.item}# ELSE ## IF items.C_PRICE #{items.PRICE} {items.CURRENCY}# ENDIF ## ENDIF #</td>
							<td class="{items.SMALLAD_TYPE_FILTER} smallads-type">{items.SMALLAD_TYPE}</td>
							# IF items.C_DISPLAYED_AUTHOR #
								<td class="jp-author">
									# IF items.C_CUSTOM_AUTHOR_NAME #
										{items.CUSTOM_AUTHOR_NAME}
									# ELSE #
										# IF items.C_AUTHOR_EXIST #<a itemprop="author" href="{items.U_AUTHOR}" class="{items.USER_LEVEL_CLASS}" # IF C_USER_GROUP_COLOR # style="color:{items.USER_GROUP_COLOR}"# ENDIF #>{items.PSEUDO}</a># ELSE #{items.PSEUDO}# ENDIF #
									# ENDIF #
								</td>
							# ENDIF #
							# IF C_LOCATION #
								<td class="jp-location">
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
								<a itemprop="about" href="{items.U_CATEGORY}">{items.CATEGORY_NAME}</a>
							</td>
							# ENDIF #
							<td>
								<time datetime="# IF NOT items.C_DIFFERED #{items.DATE_ISO8601}# ELSE #{items.PUBLICATION_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF NOT items.C_DIFFERED #{items.DATE_RELATIVE}# ELSE #{items.PUBLICATION_START_DATE_RELATIVE}# ENDIF #</time>
							</td>
							# IF C_MODERATION #
								<td class="controls">
									# IF NOT items.C_COMPLETED #
										# IF items.C_EDIT #
											<a href="{items.U_EDIT_ITEM}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true"></i> <span class="sr-only">${LangLoader::get_message('edit', 'common')}</span></a>
										# ENDIF #
									# ENDIF #
										# IF items.C_DELETE #
											<a href="{items.U_DELETE_ITEM}" aria-label="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-trash-alt" aria-hidden="true"></i> <span class="sr-only">${LangLoader::get_message('delete', 'common')}</span></a>
										# ENDIF #
								</td>
							# ENDIF #
						</tr>
					# END items #
				</tbody>
			</table>

		# ELSE #

			<div data-jplist-group="smallads-items" class="# IF C_DISPLAY_GRID_VIEW #cell-flex cell-columns-{COLUMNS_NUMBER}# ENDIF ## IF C_DISPLAY_LIST_VIEW # cell-row# ENDIF #">
				# START items #
					<article data-jplist-item id="smallads-items-{items.ID}" class="smallads-items several-items category-{items.ID_CATEGORY} cell# IF items.C_COMPLETED# completed-smallad bgc error# ENDIF ## IF items.C_NEW_CONTENT # new-content# ENDIF #" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
						# IF items.C_COMPLETED # <div class="completed-item bgc error"><span>{@smallads.completed.item}</span></div># ENDIF #
						<header class="cell-header">
							<h2 class="cell-name"><a class="jp-title" itemprop="url" href="{items.U_ITEM}"><span itemprop="name">{items.TITLE}</span></a></h2>
						</header>
						<div class="cell-infos">
							<div class="more">
								<span class="{items.SMALLAD_TYPE_FILTER} pinned success"><i class="fa fa-ticket-alt"></i> <span>{items.SMALLAD_TYPE}</span></span>
								# IF items.C_DISPLAYED_AUTHOR #
									<span class="jp-author pinned {items.USER_LEVEL_CLASS}">
										<i class="far fa-user"></i>
										# IF items.C_CUSTOM_AUTHOR_NAME #
											{items.CUSTOM_AUTHOR_NAME}
										# ELSE #
											# IF items.C_AUTHOR_EXIST #<a itemprop="author" href="{items.U_AUTHOR}" class="{items.USER_LEVEL_CLASS}" # IF C_USER_GROUP_COLOR # style="color:{items.USER_GROUP_COLOR}"# ENDIF #>{items.PSEUDO}</a># ELSE #{items.PSEUDO}# ENDIF #,
										# ENDIF #
									</span>
								# ENDIF #
								<span class="pinned">
									<i class="far fa-calendar"></i> <time datetime="# IF NOT items.C_DIFFERED #{items.DATE_ISO8601}# ELSE #{items.PUBLICATION_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF NOT items.C_DIFFERED #{items.DATE}# ELSE #{items.PUBLICATION_START_DATE}# ENDIF #</time>
								</span>
								<span class="pinned">
									<i class="far fa-folder"></i> <a itemprop="about" href="{items.U_CATEGORY}">{items.CATEGORY_NAME}</a>
								</span>
								# IF C_LOCATION #
									<span class="jp-location pinned">
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
								<span class="jp-view hidden">{items.VIEWS_NUMBER}</span>
								<span class="jp-comment hidden">{items.COMMENTS_NUMBER}</span>
								<span class="jp-date hidden">{items.DATE_TIMESTAMP}</span>
							</div>

							<div class="controls">

								# IF items.C_EDIT #
									<a href="{items.U_EDIT_ITEM}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true"></i> <span class="sr-only">${LangLoader::get_message('edit', 'common')}</span></a>
								# ENDIF #
								# IF items.C_DELETE #
									<a href="{items.U_DELETE_ITEM}" aria-label="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-trash-alt" aria-hidden="true"></i> <span class="sr-only">${LangLoader::get_message('delete', 'common')}</span></a>
								# ENDIF #
							</div>

						</div>
						<a href="#" aria-label="{items.TITLE}" class="item-thumbnail">
						</a>
						<div class="cell-body">
							# IF items.C_HAS_THUMBNAIL #
								<div class="cell-thumbnail cell-landscape">
									<img src="{items.U_THUMBNAIL}" alt="{items.TITLE}" itemprop="thumbnailUrl" />
									<a href="{items.U_ITEM}" class="cell-thumbnail-caption">${LangLoader::get_message('read-more', 'common')}</a>
								</div>
							# ENDIF #
							<div class="cell-content">
								<div itemprop="text">{items.DESCRIPTION}# IF items.C_READ_MORE #... <a href="{items.U_ITEM}" class="read-more">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF #</div>
								<div class="smallad-price jp-price"># IF items.C_PRICE #{items.PRICE} {items.CURRENCY}# ENDIF #</div>
							</div>
						</div>

						# IF items.C_SOURCES #
							<aside>
								<div id="smallads-sources-container">
									<span>${LangLoader::get_message('form.sources', 'common')}</span> :
									# START items.sources #
									<a itemprop="isBasedOnUrl" href="{items.sources.URL}" class="small">{items.sources.NAME}</a># IF items.sources.C_SEPARATOR #, # ENDIF #
									# END items.sources #
								</div>
							</aside>
						# ENDIF #

						<footer>
							<meta itemprop="url" content="{items.U_ITEM}">
							<meta itemprop="description" content="${escape(items.DESCRIPTION)}"/>
							<meta itemprop="discussionUrl" content="{items.U_COMMENTS}">
							<meta itemprop="interactionCount" content="{items.COMMENTS_NUMBER} UserComments">
						</footer>
					</article>
				# END items #
			</div>
		# ENDIF #
	# ENDIF #

			<!-- Pagination -->
			# IF C_PAGINATION #
				<div class="pagination options no-style">
					<div
					   	class="jplist-pagination"
					   	data-jplist-control="pagination"
				        data-group="smallads-items"
				        data-items-per-page="{ITEMS_PER_PAGE}"
				        data-current-page="0"
				        data-name="pagination1"
					   	data-name="paging"
					>
						<button type="button" data-type="first" aria-label="${LangLoader::get_message('pagination.first', 'common')}"><i class="fa fa-chevron-circle-left" aria-hidden="true"></i> </button>
					    <button type="button" data-type="prev" aria-label="${LangLoader::get_message('pagination.previous', 'common')}"><i class="fa fa-chevron-left" aria-hidden="true"></i> </button>

					    <div class="jplist-holder" data-type="pages">
					        <button type="button" class="button submit" data-type="page">{@smallads.active.page}</button>
					    </div>

					    <button type="button" data-type="next" aria-label="${LangLoader::get_message('pagination.next', 'common')}"><i class="fa fa-chevron-right" aria-hidden="true"></i> </button>
					    <button type="button" data-type="last" aria-label="${LangLoader::get_message('pagination.last', 'common')}"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i> </button>
					</div>
				</div>
			# ENDIF #

	<div class="spacer"></div>
	<footer># IF C_USAGE_TERMS # <i class="fa fa-book" aria-hidden="true"></i> <a href="{U_USAGE_TERMS}">{@smallads.usage.terms}</a># ENDIF #</footer>
</section>

<script src="{PATH_TO_ROOT}/smallads/templates/js/jplist.min.js"></script>

<script>
	jQuery('document').ready(function(){
		// jpList
		jplist.init();

		// Type filters
			// toggle sub-menu on click (close on click outside)
		jQuery('.selected-label').click(function(e){
			jQuery('.label-list').toggleClass('reveal-list');
    		e.stopPropagation();
		});
		jQuery(document).click(function(e) {
		    if (jQuery(e.target).is('.selected-label') === false) {
		      jQuery('.label-list').removeClass('reveal-list');
		    }
		});
			// send label text of selected input to title on click
		$('.label-list input').click(function(e) {
		    var radioText = e.currentTarget.nextSibling.data;
		    $('.selected-label span').html(radioText);
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
		jQuery('.category-selected:contains("${LangLoader::get_message('root', 'main')}")').html('{@smallads.category.all} <i class="fa fa-fw fa-caret-down" aria-hidden="true"></i>');

			// toggle sub-menu (close on click outside)
		jQuery('.category-selected').click(function(e){
			jQuery('.category-select').toggleClass('reveal-subcat');
    		e.stopPropagation();
		});
		jQuery(document).click(function(e) {
		    if (jQuery(e.target).is('.category-selected') === false) {
		      jQuery('.category-select').removeClass('reveal-subcat');
		    }
		});
	});

</script>
