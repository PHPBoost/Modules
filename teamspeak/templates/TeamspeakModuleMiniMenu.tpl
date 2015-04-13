<script>
<!--
	function RefreshViewer() {
		new Ajax.Updater(
			'ts3',
			'${relative_url(TeamspeakUrlBuilder::refresh_viewer())}',
			{
				onLoading: function () {
					$('ts3_refresh_picture').style.display = 'inline';
				},
				onComplete: function(response) {
					$('ts3_refresh_picture').style.display = 'none';
				}
			}
		);
	}
	
	Event.observe(window, 'load', function() {
		RefreshViewer();
		# IF C_REFRESH_ENABLED #
		setInterval(RefreshViewer, {REFRESH_DELAY});
		# ENDIF #
	});
-->
</script>
<div class="module-mini-container"# IF C_HORIZONTAL # style="width:auto;"# ENDIF #>
	<div class="module-mini-top">
		<h5 class="sub-title">{@ts_title}</h5>
	</div>
	<div class="module-mini-contents">
		<div id="ts3"></div>
		<div class="spacer">&nbsp;</div>
		<i class="fa fa-spinner fa-spin" id="ts3_refresh_picture"></i>
	</div>
	<div class="module-mini-bottom"></div>
</div>