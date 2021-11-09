<section id="module-flux" class="category-{CATEGORY_ID}">
	<header class="section-header">
		<div class="controls align-right">
			<a class="offload" href="{U_SYNDICATION}" aria-label="{@common.syndication}"><i class="fa fa-rss"></i></a>
			{MODULE_NAME}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF # # IF IS_ADMIN #<a class="offload" href="{U_EDIT_CATEGORY}" aria-label="{@common.edit}"><i class="fa fa-edit"></i></a># ENDIF #
		</div>
		<h1>
			<span id="name" itemprop="name">{TITLE}</span>
		</h1>
	</header>
	<div class="sub-section">
		<div class="content-container">
			# IF NOT C_IS_PUBLISHED #
				# INCLUDE NOT_VISIBLE_MESSAGE #
			# ENDIF #
			<article id="article-flux-{ID}" itemscope="itemscope" itemtype="https://schema.org/CreativeWork" class="flux-item single-item# IF C_IS_PARTNER # content-friends# ENDIF ## IF C_IS_PRIVILEGED_PARTNER # content-privileged-friends# ENDIF ## IF C_NEW_CONTENT # new-content# ENDIF#">
				# IF C_CONTROLS #
					<div class="controls align-right">
						# IF C_EDIT #
							<a class="offload" href="{U_EDIT}" aria-label="{@common.edit}"><i class="fa fa-edit"></i></a>
						# ENDIF #
						# IF C_DELETE #
							<a class="offload" href="{U_DELETE}" aria-label="{@common.delete}" data-confirmation="delete-element"><i class="far fa-trash-alt"></i></a>
						# ENDIF #
					</div>
				# ENDIF #
				<div class="content cell-tile">
					<div class="cell cell-options">
						<div class="cell-header">{@flux.website.infos}</div>
						# IF C_HAS_THUMBNAIL #
							<div class="cell-body">
								<div class="cell-thumbnail">
									<img src="{U_THUMBNAIL}" alt="{NAME}" itemprop="image" />
								</div>
							</div>
						# ENDIF #
						<div class="cell-list small">
							<ul>
								# IF C_IS_PUBLISHED #
									# IF C_VISIT #
										<li class="li-stretch">
											<a href="{U_VISIT}" # IF C_NEW_WINDOW #target="_blank" rel="noopener noreferrer"# ENDIF # class="button submit offload">
												<i class="fa fa-globe" aria-hidden="true"></i> {@common.visit}
											</a>
											# IF IS_USER_CONNECTED #
												<a href="{U_DEADLINK}" data-confirmation="{@contribution.dead.link.confirmation}" class="button offload bgc-full warning" aria-label="{@contribution.report.dead.link}">
													<i class="fa fa-unlink" aria-hidden="true"></i>
												</a>
											# ENDIF #
										</li>
									# ELSE #
										<li>{@flux.no.website}</li>
									# ENDIF #
								# ENDIF #
								<li class="li-stretch"><span class="text-strong">{@common.views.number} : </span><span>{VIEWS_NUMBER}</span></li>
								# IF C_VISIT #<li class="li-stretch"><span class="text-strong">{@common.visits.number} : </span><span>{VISITS_NUMBER}</span></li># ENDIF #
							</ul>
						</div>
					</div>

					# IF C_CONTENT #
						<div itemprop="text">
							{CONTENT}
						</div>
					# ENDIF #

				</div>
				# IF C_FEED_ITEMS #
					<div class="content">
						<ul>
							# START feed_items #
								<li>
									<span class="flex-between">
										<a class="big" href="{feed_items.U_ITEM}"# IF C_NEW_WINDOW # target="_blank" rel="noopener noreferrer"# ENDIF #>
											{feed_items.TITLE}
										</a>
										<span class="small align-right">{feed_items.DATE}</span>
									</span>
									<p>
										# IF feed_items.C_HAS_THUMBNAIL #
											<img src="{feed_items.U_THUMBNAIL}" class="align-left" alt="{feed_items.TITLE}" />
										# ENDIF #
										{feed_items.SUMMARY}# IF feed_items.C_READ_MORE #...# ENDIF #
									</p>
								</li>
							# END feed_items #
						</ul>
					</div>
				# ELSE #
					<div class="message-helper bgc warning">
						{@flux.rss.init}
					</div>
				# ENDIF #
				# IF C_CONTROLS #
					# INCLUDE FORM #
				# ENDIF #

				<aside class="sharing-container">
					${ContentSharingActionsMenuService::display()}
				</aside>
			</article>
		</div>
	</div>
	<footer>
		<meta itemprop="url" content="{U_ITEM}">
		<meta itemprop="description" content="${escape(DESCRIPTION)}" />
	</footer>
</section>

# IF C_GMAP_ENABLED #
	# IF C_DEFAULT_ADDRESS #
		<script src="{PATH_TO_ROOT}/flux/templates/js/sticky.js"></script>

		<script>
			jQuery(function(){
				jQuery('.fixed-top').sticky();
			});
		</script>

		<script>
			# IF C_LOCATION #
				var spot = {lat: {LATITUDE}, lng: {LONGITUDE}};
			# ELSE #
				var spot = {lat: {DEFAULT_LAT}, lng: {DEFAULT_LNG}};
			# ENDIF #
			var map;
			function initMap() {
				map = new google.maps.Map(document.getElementById('gmap'), {
				  	zoom: 10,
				  	center: spot,
					mapTypeId: 'roadmap',
				    mapTypeControlOptions: {
				      // position: google.maps.ControlPosition.LEFT_BOTTOM,
				    },
				});
			}
			initMap();
			# IF C_ROUTE #
				var panel = document.getElementById('panel'),
					# IF C_NEW_ADDRESS #
						origin = {lat: {NEW_LAT}, lng: {NEW_LNG}}
					# ELSE #
						origin = {lat: {DEFAULT_LAT}, lng: {DEFAULT_LNG}}
					# ENDIF #

				calculate = function(){
					origin      = origin
					destination = spot; // The point of arrival
					if(origin && destination){
						var request = {
							origin      : origin,
							destination : destination,
							provideRouteAlternatives: true,
							// avoidTolls: true,
							travelMode  : google.maps.DirectionsTravelMode.{TRAVEL_TYPE}, // Type of travel
						}
						direction = new google.maps.DirectionsRenderer({
							draggable: true,
							map: map,
							panel: panel
						});
						var directionsService = new google.maps.DirectionsService(); // Route planning service
						directionsService.route(request, function(response, status){ // Sends the request to calculate the route
							if(status == google.maps.DirectionsStatus.OK){
								direction.setDirections(response); // Trace the route on the map and the different stages of the route
							}
						});
					}
				};

				calculate();
			# ELSE #
				var marker = new google.maps.Marker({
					position: new google.maps.LatLng({LATITUDE}, {LONGITUDE}),
					map: map
				});
			# ENDIF #
		</script>
	# ENDIF #
# ENDIF #
