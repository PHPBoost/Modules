# IF C_ITEMS #
    <div class="cell-body">
        <div class="cell-content">
                <blockquote class="formatter-container formatter-blockquote">
                    <span class="formatter-title title-perso"><a class="offload" href="{U_WRITER}">{WRITER_NAME}</a> :</span>
                    <div class="formatter-content">{CONTENT}</div>
                </blockquote>
        </div>
        <div class="cell-content align-center">
            <a class="button small offload" href="{U_MODULE_HOME_PAGE}">{@quotes.module.title}</a>
        </div>
    </div>
# ELSE #
	<div class="cell-alert">
		<div class="message-helper bgc notice">
			{@common.no.item}
		</div>
	</div>
# ENDIF #
