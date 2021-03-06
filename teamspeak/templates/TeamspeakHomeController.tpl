<script>
	function RefreshViewer() {
		jQuery('#ts3_refresh_picture').show();
		jQuery.ajax({
			url: '${relative_url(TeamspeakUrlBuilder::refresh_viewer())}',
			data: {'token' : '{TOKEN}'},
			success: function(returnData){
				jQuery('#ts3_refresh_picture').hide();
				jQuery('#ts3').html(returnData);
			}
		});
	}

	jQuery(document).ready(function() {
		RefreshViewer();
		# IF C_REFRESH_ENABLED #
		setInterval(RefreshViewer, {REFRESH_DELAY});
		# ENDIF #
	});
</script>
<section>
	<header class="section-header">
		<h1>{@ts.module.title}</h1>
	</header>
	<div class="sub-section">
		<div class="content-container">
			<article class="teamspeak-item several-items">
				<div class="content">
					<div id="ts3"></div>
					<div class="spacer">&nbsp;</div>
					<div class="align-center">
						<i class="fa fa-spinner fa-spin fa-2x" id="ts3_refresh_picture"></i>
					</div>
				</div>
			</article>
		</div>
	</div>
	<footer></footer>
</section>
