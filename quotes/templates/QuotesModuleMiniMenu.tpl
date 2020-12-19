<div class="cell-body">
    <div class="cell-content">
        # IF C_ITEMS #
            <blockquote class="formatter-container formatter-blockquote">
                <span class="formatter-title title-perso"><a href="{U_WRITER}">{WRITER_NAME}</a> :</span>
                <div class="formatter-content">{CONTENT}</div>
            </blockquote>
        # ELSE #
            <p class="align-center">${LangLoader::get_message('no_item_now', 'common')}</p>
        # ENDIF #
    </div>
    <div class="cell-content align-center">
        <a class="button small" href="{U_MODULE_HOME_PAGE}">{@module.title}</a>
    </div>
</div>
