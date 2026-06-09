<nav id="admin-quick-menu">
    <a href="#" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
        <i class="fa fa-bars" aria-hidden="true"></i> {@module_title}
    </a>
    <ul>
        <li>
            <a href="${relative_url(EasyCssUrlBuilder::theme_choice())}" class="quick-link">{@theme_choice}</a>
        </li>
    </ul>
</nav>

<div id="admin-contents">
    <header>
        <h2>{@module_title}</h2>
    </header>
    <div id="theme-choice" class="tabs-container tabs-left tabsboost">
        <nav id="tabs-menu" class="tabs-nav">
            <ul class="tabs-items">
                # START themes #
                    <li><span class="tab-item --tab-{themes.NAME}# IF themes.DEFAULT # current-tab# END IF #">{themes.NAME}</span></li>
                # END themes #
            </ul>
        </nav>
        <div class="tabs-wrapper">
            # START themes #
                <div id="tab-{themes.NAME}" class="tab-content# IF themes.DEFAULT # current-tab# END IF #">
                    # START themes.css #
                        <a href="{themes.css.URL}">{themes.css.NAME}</a></br>
                    # END themes.css #
                </div>
            # END themes #
        </div>
    </div>
</div>
