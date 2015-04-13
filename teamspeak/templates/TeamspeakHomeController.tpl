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
<section>
	<header>
		<h1>{@ts_title}</h1>
	</header>
	<div class="content">
		<div id="ts3"></div>
		<div class="spacer">&nbsp;</div>
		<div class="center">
			<i class="fa fa-spinner fa-spin fa-2x" id="ts3_refresh_picture"></i>
		</div>
	</div>
	<footer></footer>
</section>
