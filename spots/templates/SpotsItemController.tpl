<section id="module-spots" class="category-{CATEGORY_ID} single-item">
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
			# IF NOT C_VISIBLE #
				# INCLUDE NOT_VISIBLE_MESSAGE #
			# ENDIF #
			<article id="article-spots-{ID}" itemscope="itemscope" itemtype="https://schema.org/CreativeWork" class="spots-item# IF C_IS_PARTNER # content-friends# ENDIF ## IF C_IS_PRIVILEGED_PARTNER # content-privileged-friends# ENDIF ## IF C_NEW_CONTENT # new-content# ENDIF#">
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
						<div class="cell-header">{@spots.link.infos}</div>
						# IF C_HAS_THUMBNAIL #
							<div class="cell-body">
								<div class="cell-thumbnail">
									<img src="{U_THUMBNAIL}" alt="{NAME}" itemprop="image" />
								</div>
							</div>
						# ENDIF #
						<div class="cell-list small">
							<ul>
								# IF C_VISIBLE #
									# IF C_VISIT #
										<li class="li-stretch">
											<a href="{U_VISIT}" # IF C_NEW_WINDOW #target="_blank" rel="noopener noreferrer"# ENDIF # class="button submit offload" aria-label="{@spots.visit.website}">
												<i class="fa fa-globe" aria-hidden="true"></i> {@common.visit}
											</a>
											# IF IS_USER_CONNECTED #
												<a href="{U_DEADLINK}" data-confirmation="{@contribution.dead.link.confirmation}" class="button offload bgc-full warning" aria-label="{@contribution.report.dead.link}">
													<i class="fa fa-unlink" aria-hidden="true"></i>
												</a>
											# ENDIF #
										</li>
									# ELSE #
										<li>{@spots.no.website}</li>
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

					# IF C_DIRECT_CONTACT #
						<aside>
							<h6>{@spots.contact} :</h6>
							<div class="flex-between">
								# IF C_CONTACT #
									<div>
										# IF C_PHONE #
											<div>
												<i class="fa fa-fw fa-phone"></i> {PHONE}
											</div>
										# ENDIF #
										# IF C_EMAIL #
											<a href="mailto:{EMAIL}" aria-label="{@common.email}"><i class="fa fa-fw fa-envelope fa-lg"></i></a>
										# ENDIF #
									</div>
								# ELSE #
									<div></div>
								# ENDIF #
								# IF C_NETWORK #
									<div class="controls align-right">
										# IF C_FACEBOOK #
											<a class="offload" href="{U_FACEBOOK}" aria-label="Facebook" # IF C_NEW_WINDOW #target="_blank" rel="noopener noreferrer"# ENDIF #><i class="fab fa-fw fa-facebook-square fa-lg"></i></a>
										# ENDIF #
										# IF C_TWITTER #
											<a class="offload" href="{U_TWITTER}" aria-label="Twitter" # IF C_NEW_WINDOW #target="_blank" rel="noopener noreferrer"# ENDIF #><i class="fab fa-fw fa-twitter-square fa-lg"></i></a>
										# ENDIF #
										# IF C_INSTAGRAM #
											<a class="offload" href="{U_INSTAGRAM}" aria-label="Instagram" # IF C_NEW_WINDOW #target="_blank" rel="noopener noreferrer"# ENDIF #><i class="fab fa-fw fa-instagram-square fa-lg"></i></a>
										# ENDIF #
										# IF C_YOUTUBE #
											<a class="offload" href="{U_YOUTUBE}" aria-label="Youtube" # IF C_NEW_WINDOW #target="_blank" rel="noopener noreferrer"# ENDIF #><i class="fab fa-fw fa-youtube-square fa-lg"></i></a>
										# ENDIF #
									</div>
								# ENDIF #
							</div>
						</aside>
					# ENDIF #

				</div>
				# IF C_GMAP_ENABLED #
					# IF C_DEFAULT_ADDRESS #
						# IF C_LOCATION #
							<div class="cell">
								<div class="cell-body">
									<div class="cell-content">
										<i class="fab fa-waze fa-2x" aria-hidden="true"></i>
										{@H|spots.waze.description}
									</div>
								</div>
								<div class="cell-body">
									<div class="cell-content align-center">
										<a class="button bgc-full moderator" href="https://www.waze.com/ul?ll={LATITUDE}%2C{LONGITUDE}&navigate=yes&navigate=yes">
											<i class="fab fa-waze" aria-hidden="true"></i> {@spots.send.to.waze}
										</a>
									</div>
								</div>

							</div>
							<div class="fixed-top">
								<div id="gmap"></div>
							</div>
							<div id="panel"></div>
							# INCLUDE FORM #
							<h5>{@spots.location} :</h5>
							<p>{@spots.location.lat} : {LOCA_LAT} / {LATITUDE}</p>
							<p>{@spots.location.lng} : {LOCA_LNG} / {LONGITUDE}</p>
						# ELSE #
							<div class="message-helper bgc warning">{@spots.no.gps}</div>
						# ENDIF #
					# ELSE #
						<div class="message-helper bgc warning">{@H|spots.no.default.address}</div>
					# ENDIF #
				# ELSE #
					<div class="message-helper bgc warning">{@spots.no.gmap}</div>
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
		<script src="{PATH_TO_ROOT}/spots/templates/js/sticky# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>

		<script>
			jQuery(function(){
				jQuery('.fixed-top').sticky();
			});
		</script>

		<script>
			# IF C_LOCATION #
				var spot = {lat: {LATITUDE}, lng: {LONGITUDE}};
			# ELSE #
				var spot = {lat: {CATEGORY_LATITUDE}, lng: {CATEGORY_LONGITUDE}};
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
						origin = {lat: {CATEGORY_LATITUDE}, lng: {CATEGORY_LONGITUDE}}
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
