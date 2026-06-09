<div id="{MODULE_ID}" class="cell-mini cell-tile">
	<div class="cell">
		<div class="cell-header">
			<h6 class="cell-name">{TITLE}</h6>
			<span><a href="{U_CONFIG}" aria-label="{@form.configuration}"><i class="fa fa-cog"></i></a></span>
		</div>
		# IF C_HORIZONTAL #<div class="broadcast-flex"># ENDIF #
			# IF C_HAS_LOGO #
				<div class="cell-thumbnail# IF C_HORIZONTAL # cell-horizontal-thumbnail# ENDIF #"><img src="{U_LOGO}" alt=""></div>
			# ENDIF #
			<div class="cell-body">
				<div class="cell-content align-center">
				<a href="{PATH_TO_ROOT}/broadcast/BroadcastPlayer.php" onclick="window.open(this.href, '', 'width={WIDTH}, height={HEIGHT}'); return false;">
					<i class="far fa-circle-play"></i> {@broadcast.live}
				</a>
				</div>
			</div>
			<div class="cell-body">
				<div class="cell-content align-center">
					# IF C_ITEMS #<a class="offload button" href="{PATH_TO_ROOT}/broadcast/">{@broadcast.programs}</a># ENDIF #
				</div>
			</div>
		# IF C_HORIZONTAL #</div># ENDIF #
	</div>
</div>
