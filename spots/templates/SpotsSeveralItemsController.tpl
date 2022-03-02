<section id="module-spots" class="several-items">
	<header class="section-header">
		<div class="controls align-right">
			<a class="offload" href="${relative_url(SyndicationUrlBuilder::rss('spots', ID_CAT))}" aria-label="{@common.syndication}"><i class="fa fa-rss warning"></i></a>
			# IF NOT C_ROOT_CATEGORY #{MODULE_NAME}# ENDIF #
			# IF IS_ADMIN #<a class="offload" href="{U_EDIT_CATEGORY}" aria-label="{@common.edit}"><i class="fa fa-edit"></i></a># ENDIF #
		</div>
		<h1>
			# IF C_PENDING #
				{@spots.pending.items}
			# ELSE #
				# IF C_MEMBER_ITEMS #
					# IF C_MY_ITEMS #{@spots.my.items}# ELSE #{@spots.member.items} {MEMBER_NAME}# ENDIF #
				# ELSE #
					# IF C_ROOT_CATEGORY #{MODULE_NAME}# ELSE #{CATEGORY_NAME}# ENDIF #
				# ENDIF #
			# ENDIF #
		</h1>
	</header>

	# IF C_ROOT_CATEGORY #
		<div class="sub-section">
			<div class="content-container">
				<div class="cat-description">
					{ROOT_CATEGORY_DESC}
				</div>
			</div>
		</div>
	# ENDIF #

	# IF C_CATEGORY #
		# IF C_GMAP_ENABLED #
			<div id="map"></div>
		# ELSE #
			<div class="message-helper bgc warning">{@spots.no.gmap}</div>
		# ENDIF #
	# ENDIF #

	# IF C_SUB_CATEGORIES #
		<div class="sub-section">
			<div class="content-container">
				<div class="cell-flex cell-tile cell-columns-{CATEGORIES_PER_ROW}">
					# START sub_categories_list #
						<div class="cell category-{sub_categories_list.CATEGORY_ID}" itemscope>
							<div class="cell-header colored-category marker-container" data-color-surround="{sub_categories_list.CATEGORY_COLOR}">
								<h5 class="cell-name" itemprop="about">
									<i class="inner-marker ${sub_categories_list.CATEGORY_INNER_ICON}" aria-hidden="true"></i>
									<a class="offload" href="{sub_categories_list.U_CATEGORY}">
										{sub_categories_list.CATEGORY_NAME}
									</a>
								</h5>
								<span class="small pinned notice" role="contentinfo" aria-label="{@spots.items.number}">
									{sub_categories_list.ITEMS_NUMBER}
								</span>
							</div>
						</div>
					# END sub_categories_list #
				</div>
				# IF C_SUBCATEGORIES_PAGINATION #<div class="align-center"># INCLUDE SUBCATEGORIES_PAGINATION #</div># ENDIF #
			</div>
		</div>
	# ENDIF #

	# IF C_ITEMS #
		# IF C_SEVERAL_ITEMS #
			<div class="spacer"></div>
		# ENDIF #
		<div class="content-container">
			# IF C_TABLE_VIEW #
				<table class="table">
					<thead>
						<tr>
							<th class="col-small" aria-label="{@common.category}"><i class="far fa-folder" aria-hidden="true"></i><span class="hidden-large-screens">{@common.category}</span></th>
							<th>{@common.name}</th>
							<th class="coll-small" aria-label="{@common.website}"><i class="fa fa-link" aria-hidden="true"></i><span class="hidden-large-screens">{@common.website}</span></th>
							<th class="col-small" aria-label="{@common.views.number}"><i class="fa fa-eye" aria-hidden="true"></i><span class="hidden-large-screens">{@common.views.number}</span></th>
							<th class="col-small" aria-label="{@common.visits.number}"><i class="fa fa-external-link-alt" aria-hidden="true"></i><span class="hidden-large-screens">{@common.visits.number}</span></th>
							# IF C_CONTROLS #<th class="col-small" aria-label="{@common.moderation}"><i class="fa fa-cog" aria-hidden="true"></i><span class="hidden-large-screens">{@common.moderation}</span></th># ENDIF #
						</tr>
					</thead>
					<tbody>
						# START items #
							<tr>
								<td>
									<a class="offload" href="{items.U_CATEGORY}">
										<span class="marker-container marker-category hidden-small-screens" id="marker-{items.ID}" aria-label="{items.CATEGORY_NAME}">
											<svg width="24px" height="38px">
												<path
													fill="${items.CATEGORY_COLOR}"
												 	d="M-0.000,11.790 C-0.000,5.273 5.373,-0.008 12.000,-0.008 C18.627,-0.008 24.000,5.273 24.000,11.790 C24.000,18.305 12.000,38.008 12.000,38.008 C12.000,38.008 -0.000,18.305 -0.000,11.790 Z"/>
											</svg>
											<i class="inner-marker ${items.CATEGORY_INNER_ICON}" aria-hidden="true"></i>
										</span>
										<span class="hidden-large-screens">{items.CATEGORY_NAME}</span>
									</a>
								</td>
								<td>
									<a class="offload" href="{items.U_ITEM}"><span itemprop="name" aria-label="{@common.see.details}">{items.TITLE}</span></a>
								</td>
								<td>
									# IF items.C_VISIT #
										<a class="basic-button" aria-label="{@spots.visit.website}" # IF items.C_NEW_WINDOW #target="_blank" rel="noopener noreferrer"# ENDIF # href="{items.U_VISIT}">{@common.visit}</a>
									# ELSE #
										{@spots.no.website}
									# ENDIF #
								</td>
								<td>
									{items.VIEWS_NUMBER}
								</td>
								<td>
									{items.VISITS_NUMBER}
								</td>
								# IF C_CONTROLS #
									<td>
										# IF items.C_EDIT #
											<a class="offload" href="{items.U_EDIT}" aria-label="{@common.edit}"><i class="fa fa-edit"></i></a>
										# ENDIF #
										# IF items.C_DELETE #
											<a href="{items.U_DELETE}" aria-label="{@common.delete}" data-confirmation="delete-element"><i class="far fa-trash-alt"></i></a>
										# ENDIF #
									</td>
								# ENDIF #
							</tr>
						# END items #
					</tbody>
				</table>
			# ELSE #
				<div class="cell-flex cell-columns-{ITEMS_PER_ROW}">
					# START items #
						<article id="article-spots-{items.ID}" class="spots-item cell# IF items.C_IS_PARTNER # content-friends# ENDIF ## IF items.C_IS_PRIVILEGED_PARTNER # content-privileged-friends# ENDIF ## IF items.C_NEW_CONTENT # new-content# ENDIF#" itemscope="itemscope" itemtype="https://schema.org/CreativeWork">
							<header class="cell-header">
								<h2>
									<a class="offload" href="{items.U_ITEM}" itemprop="name">{items.TITLE}</a>
								</h2>
							</header>
							<div class="cell-infos">
								<div class="more">
									<span class="pinned item-views-number" aria-label="{@common.views.number}"> <i class="fa fa-eye" aria-hidden="true"></i> {items.VIEWS_NUMBER}</span>
									# IF items.C_VISIT #<span class="pinned item-visits-number" aria-label="{@common.visits.number}"> <i class="fa fa-external-link-alt" aria-hidden="true"></i> {items.VISITS_NUMBER}</span># ENDIF #
									<span class="pinned-category item-category" data-color-surround="{items.CATEGORY_COLOR}" aria-label="{@common.category}"><i class="far fa-folder" aria-hidden="true"></i> <a class="offload" itemprop="about" href="{items.U_CATEGORY}">{items.CATEGORY_NAME}</a></span>
								</div>
								# IF items.C_CONTROLS #
									<div class="controls align-right">
										# IF items.C_EDIT #<a class="offload item-edit" href="{items.U_EDIT}" aria-label="{@common.edit}"><i class="far fa-edit"></i></a># ENDIF #
										# IF items.C_DELETE #<a class="item-delete" href="{items.U_DELETE}" aria-label="{@common.delete}" data-confirmation="delete-element"><i class="far fa-trash-alt"></i></a># ENDIF #
									</div>
								# ENDIF #
							</div>
							<div class="cell-body">
								# IF items.C_HAS_THUMBNAIL #
									<div class="cell-thumbnail cell-landscape cell-center">
										<img src="{items.U_THUMBNAIL}" alt="{items.TITLE}" itemprop="image" />
										<a href="{items.U_ITEM}" class="cell-thumbnail-caption offload">
											{@common.see.details}
										</a>
									</div>
								# ENDIF #
								<div class="cell-content">
									<div itemprop="text">{items.CONTENT}</div>
								</div>
							</div>
						</article>
					# END items #
				</div>
			# ENDIF #
		</div>
	# ELSE #
		<div class="content">
			# IF NOT C_HIDE_NO_ITEM_MESSAGE #
				<div class="message-helper bgc notice">
					{@common.no.item.now}
				</div>
			# ENDIF #
		</div>
	# ENDIF #

	<footer># IF C_PAGINATION # # INCLUDE PAGINATION # # ENDIF #</footer>
</section>
# IF C_GMAP_ENABLED #
	<script src="{PATH_TO_ROOT}/spots/templates/js/leaflet.js"></script>
	<script src="https://maps.googleapis.com/maps/api/js?key={GMAP_API_KEY}"></script>
	<script src="{PATH_TO_ROOT}/spots/templates/js/leaflet.google# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>

	<script>
		var map = new L.Map('map', {
    		# IF C_ROOT_CATEGORY #
				center: new L.LatLng({DEFAULT_LAT}, {DEFAULT_LNG}),
			# ELSE #
				center: new L.LatLng({CATEGORY_LATITUDE}, {CATEGORY_LONGITUDE}),
			# ENDIF #
			zoom: 12,
			maxZoom: 18,
			minZoom: 1,
		});
		var osm = new L.TileLayer('https://\{s\}.tile.openstreetmap.org/\{z\}/\{x\}/\{y\}.png', {
            attribution: '© OpenStreetMap contributors'
        });
		var ocm = new L.TileLayer('https://\{s\}.tile-cyclosm.openstreetmap.fr/cyclosm/\{z\}/\{x\}/\{y\}.png', {
            attribution: '© OpenStreetMap contributors'
        });
		var sat = new L.TileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/\{z\}/\{y\}/\{x\}.png', {
            attribution: '© Esri contributors'
        });
		var topo = new L.TileLayer('https://\{s\}.tile.opentopomap.org/\{z\}/\{x\}/\{y\}.png', {
			attribution: '<a href="https://opentopomap.org/about#legende" target="_blank" rel="noopener noreferrer">OpenTopoMap - legend</a>'
		});

		var gmHyb  = new L.Google('HYBRID');
		var gmSat  = new L.Google('SATELLITE');
		var gmTer  = new L.Google('TERRAIN');
		var gmRoad = new L.Google('ROADMAP');

		var markers = new L.FeatureGroup();
		var marker = new Array();
		var markersData = [
			# START items #
				[
                    '<div class="align-center marker-logo"><a class="offload" href="${items.U_ITEM}" aria-label="{@common.see.details}"><img src="# IF items.C_HAS_THUMBNAIL #{items.U_THUMBNAIL}# ELSE #{PATH_TO_ROOT}/spots/spots.png# ENDIF #" alt="{items.TITLE}" /></a></div>'
	               	+ '<h4><a class="offload" href="${items.U_ITEM}" aria-label="{@common.see.details}">${items.TITLE}</a></h4>'
	                + '<div class="cell cell-list gm-location-location">'
	                    + '<ul>'
	                        + '<li class="li-stretch"><span class="text-strong">{@common.category}:</span> <span class="d-block">{items.CATEGORY_NAME}</span></li>'
	                        + '<li class="spacer"><span class="text-strong">{@spots.address}:</span> <span class="d-block align-right">${items.V_LOCATION}</span></li>'
	                    + '</ul>'
	                + '</div>',
					{items.LATITUDE},
					{items.LONGITUDE},
					L.divIcon({
						html: `
							<div class="marker-container" id="marker-{items.ID}">
								<svg width="24px" height="38px">
									<path
										stroke-width="1"
										fill="{items.CATEGORY_COLOR}"
									 	d="M-0.000,11.790 C-0.000,5.273 5.373,-0.008 12.000,-0.008 C18.627,-0.008 24.000,5.273 24.000,11.790 C24.000,18.305 12.000,38.008 12.000,38.008 C12.000,38.008 -0.000,18.305 -0.000,11.790 Z"/>
								</svg>
								<i class="inner-marker fa-fw {items.CATEGORY_INNER_ICON}"></i>
							</div>
						`,
						className: 'icon-cat-{items.CATEGORY_ID}',
						iconSize: [24, 38],
						iconAnchor: [12, 38],
				  		popupAnchor: [0, -38],
					})
				],
			# END items #
		];

		if(markersData.length > 0) {
			for (var i = 0; i < markersData.length; i++) {

				var popup = markersData[i][0];
				var lat = markersData[i][1];
				var lng = markersData[i][2];
				var icon = markersData[i][3];

				marker = L.marker([lat,lng], {icon: icon})
				.bindPopup(popup, {
				  	width: "auto"
				});
				markers.addLayer(marker);
			}

			var bounds = markers.getBounds();
			map.fitBounds(bounds);
			map.addLayer(markers);
		}

		//add on the map
		map.addLayer(osm);
		map.addControl(new L.Control.Layers( {
			'OpenStreetMap': osm,
			'OpenCycleMap': ocm,
			'{@spots.osm.satellite}': sat,
			'{@spots.osm.topo}': topo,
			'{@spots.google.hybrid}': gmHyb,
			'{@spots.google.sat}': gmSat,
			'{@spots.google.terrain}': gmTer,
			'{@spots.google.roadmap}': gmRoad,
		},{}));

	</script>
# ENDIF #
	<script>
		// inner icon color
		jQuery('.marker-container svg').each(function(){
			var bgColor = jQuery(this).children('path').attr('fill');

			var color = (bgColor.charAt(0) === '#') ? bgColor.substring(1, 7) : bgColor;
			var r = parseInt(color.substring(0, 2), 16); // hexToR
			var g = parseInt(color.substring(2, 4), 16); // hexToG
			var b = parseInt(color.substring(4, 6), 16); // hexToB
			color = ((r * 0.299) + (g * 0.587) + (b * 0.114)) > 186 ? '#313131' : '#FFFFFF';
			jQuery(this).children('path').attr('stroke', color);
			jQuery(this).siblings('.inner-marker').css({ 'color': color });
		});

		jQuery('.colored-category.marker-container').each(function(){
			var bgColor = jQuery(this).attr('data-color-surround');

			var color = (bgColor.charAt(0) === '#') ? bgColor.substring(1, 7) : bgColor;
			var r = parseInt(color.substring(0, 2), 16); // hexToR
			var g = parseInt(color.substring(2, 4), 16); // hexToG
			var b = parseInt(color.substring(4, 6), 16); // hexToB
			color = ((r * 0.299) + (g * 0.587) + (b * 0.114)) > 186 ? '#313131' : '#FFFFFF';
				jQuery(this).find('.inner-marker').css({ 'color': color })
		});

	</script>
