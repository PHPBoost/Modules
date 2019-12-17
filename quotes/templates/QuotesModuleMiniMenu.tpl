<div class="cell-body">
    <div class="cell-content">
        # IF C_QUOTE #
            <blockquote class="formatter-container formatter-blockquote">
                <span class="formatter-title title-perso">{AUTHOR} :</span>
                <div class="formatter-content">{QUOTE}</div>
            </blockquote>
        # ELSE #
            <p class="align-center">${LangLoader::get_message('no_item_now', 'common')}</p>
        # ENDIF #
        <p class="align-center"><a class="button small" href="{U_MODULE_HOME_PAGE}">{@module_title}</a></p>

    </div>
</div>
