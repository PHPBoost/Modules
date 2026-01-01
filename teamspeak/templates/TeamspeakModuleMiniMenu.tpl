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
<div id="mini-teamspeak" class="cell-mini cell-tile# IF C_VERTICAL # cell-mini-vertical# ENDIF #">
	<div class="cell">
		<div class="cell-header">
			<h6 class="cell-name">{@ts.module.title}</h6>
		</div>
		<div class="cell-body">
			<div class="cell-content">
				<div id="ts3"></div>
				<div class="spacer">&nbsp;</div>
				<i class="fa fa-spinner fa-spin" id="ts3_refresh_picture"></i>
			</div>
		</div>
	</div>
</div>
