<div id="module-mini-discord" class="cell-mini cell-tile# IF C_VERTICAL_BLOCK # cell-mini-vertical# ENDIF ## IF C_HIDDEN_WITH_SMALL_SCREENS # hidden-small-screens# ENDIF #">
    <div class="cell">
        <div class="cell-header">
            <h6 class="cell-name">
                {@countdown.module.title}
            </h6>
            # IF IS_ADMIN #
                <span>
                    <a class="offload" href="${relative_url(ModulesUrlBuilder::configuration('countdown'))}" aria-label="{@form.configuration}">
                        <i class="fa fa-cog"></i>
                    </a>
                </span>
            # ENDIF #
        </div>
        # IF C_DISABLED #
            <div class="cell-alert">
                <div class="message-helper bgc notice">{NO_EVENT}</div>
            </div>
        # ELSE #
            <div class="cell-body">
                <div class="cell-content">
                    <div id="countdown"><noscript>{NO_JS}</noscript></div>
                </div>
            </div>
        # ENDIF #
    </div>
</div>

<script>
    jQuery('#countdown').countdown('{TIMER_YEAR}/{TIMER_MONTH}/{TIMER_DAY} {TIMER_HOUR}:{TIMER_MINUTE}'# IF NOT C_STOP_COUNTER #, {elapse: true}# ENDIF #)
		# IF C_STOP_COUNTER #
			.on('finish.countdown', function(event) {
				var $this = $(this);
				$this.html(event.strftime('<span class="event-title">' + ${escapejs(STOPPED_EVENT)} + '</span> # IF C_RELEASE_COUNTER #<div class="pbt-timer"><div class="timer-unit"><div class="timer-date">%D</div> <div class="timer-name">{@date.day}%!D</div></div> <div class="timer-unit"><div class="timer-date">%H</div><div class="timer-name"># IF C_VERTICAL #{@date.unit.hour}# ELSE #{@date.hour}%!H# ENDIF #</div></div><div class="timer-unit"><div class="timer-date">%M</div><div class="timer-name"># IF C_VERTICAL #{@date.unit.minute}# ELSE #{@date.minute}%!M# ENDIF #</div></div><div class="timer-unit"><div class="timer-date">%S</div><div class="timer-name"># IF C_VERTICAL #{@date.unit.seconds}# ELSE #{@date.second}%!S# ENDIF #</div></div></div></div># ENDIF #'));
			})
			.on('update.countdown', function(event) {
				var $this = $(this);
				$this.html(event.strftime('<span class="event-title">' + ${escapejs(NEXT_EVENT)} + '</span> <div class="pbt-timer"><div class="timer-unit"><div class="timer-date">%D</div> <div class="timer-name">{@date.day}%!D</div></div> <div class="timer-unit"><div class="timer-date">%H</div><div class="timer-name"># IF C_VERTICAL #{@date.unit.hour}# ELSE #{@date.hour}%!H# ENDIF #</div></div><div class="timer-unit"><div class="timer-date">%M</div><div class="timer-name"># IF C_VERTICAL #{@date.unit.minute}# ELSE #{@date.minute}%!M# ENDIF #</div></div><div class="timer-unit"><div class="timer-date">%S</div><div class="timer-name"># IF C_VERTICAL #{@date.unit.seconds}# ELSE #{@date.second}%!S# ENDIF #</div></div></div></div>'));
			})
		# ELSE #
			.on('update.countdown', function(event) {
				var $this = $(this);
				if (event.elapsed) {
                    $this.html(event.strftime('<span class="event-title">' + ${escapejs(LAST_EVENT)} + '</span> <div class="pbt-timer"><div class="timer-unit"><div class="timer-date">%D</div> <div class="timer-name">{@date.day}%!D</div></div> <div class="timer-unit"><div class="timer-date">%H</div><div class="timer-name"># IF C_VERTICAL #{@date.unit.hour}# ELSE #{@date.hour}%!H# ENDIF #</div></div><div class="timer-unit"><div class="timer-date">%M</div><div class="timer-name"># IF C_VERTICAL #{@date.unit.minute}# ELSE #{@date.minute}%!M# ENDIF #</div></div><div class="timer-unit"><div class="timer-date">%S</div><div class="timer-name"># IF C_VERTICAL #{@date.unit.seconds}# ELSE #{@date.second}%!S# ENDIF #</div></div></div></div>'));
				} else {
                    $this.html(event.strftime('<span class="event-title">' + ${escapejs(NEXT_EVENT)} + '</span> <div class="pbt-timer"><div class="timer-unit"><div class="timer-date">%D</div> <div class="timer-name">{@date.day}%!D</div></div> <div class="timer-unit"><div class="timer-date">%H</div><div class="timer-name"># IF C_VERTICAL #{@date.unit.hour}# ELSE #{@date.hour}%!H# ENDIF #</div></div><div class="timer-unit"><div class="timer-date">%M</div><div class="timer-name"># IF C_VERTICAL #{@date.unit.minute}# ELSE #{@date.minute}%!M# ENDIF #</div></div><div class="timer-unit"><div class="timer-date">%S</div><div class="timer-name"># IF C_VERTICAL #{@date.unit.seconds}# ELSE #{@date.second}%!S# ENDIF #</div></div></div></div>'));
				}
			})
		# ENDIF #
	;
</script>
