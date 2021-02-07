<div class="sticky-menu" style="order: {MENU_POSITION};">
    <div class="sub-section">
        <nav id="cssmenu-anchors" class="cssmenu cssmenu-horizontal">
            <ul>
                # START tabs #
                    # IF tabs.C_DISPLAYED_TAB #
                        <li style="order: {tabs.TAB_POSITION};">
                            <a href="{tabs.U_TAB}-panel" class="cssmenu-title">{tabs.TAB_TITLE}</a>
                        </li>
                    # ENDIF #
                # END tabs #
            </ul>
        </nav>        
    </div>
</div>

<script>
    jQuery("#cssmenu-anchors").menumaker({
        title: "${Langloader::get_message('anchors.title', 'common', 'HomeLanding')}",
        format: "multitoggle",
        breakpoint: 768
    });
</script>
