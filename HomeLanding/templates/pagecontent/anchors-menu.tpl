<nav id="cssmenu-anchors" class="cssmenu cssmenu-horizontal" style="order: {ANCHORS_POSITION};">
    <ul>
        <!-- # IF C_DISPLAYED_CAROUSEL #
            <li>
                <a href="#pbt-slider" class="cssmenu-title">${Langloader::get_message('anchors.carousel', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF # -->
        # IF C_DISPLAYED_EDITO #
            <li style="order: {AM_EDITO_POS};">
                <a href="#edito" class="cssmenu-title">${Langloader::get_message('anchors.edito', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_LASTCOMS #
            <li style="order: {AM_LASTCOMS_POS};">
                <a href="#lastcoms" class="cssmenu-title">${Langloader::get_message('anchors.lastcoms', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_ARTICLES #
            <li style="order: {AM_ARTICLES_POS};">
                <a href="#articles" class="cssmenu-title">${Langloader::get_message('anchors.articles', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_ARTICLES_CAT #
            <li style="order: {AM_ARTICLES_CAT_POS};">
                <a href="#articles-cat" class="cssmenu-title">{ARTICLES_CAT}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_CONTACT #
            <li style="order: {AM_CONTACT_POS};">
                <a href="#contact" class="cssmenu-title">${Langloader::get_message('anchors.contact', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_EVENTS #
            <li style="order: {AM_EVENTS_POS};">
                <a href="#events" class="cssmenu-title">${Langloader::get_message('anchors.calendar', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_DOWNLOAD #
            <li style="order: {AM_DOWNLOAD_POS};">
                <a href="#download" class="cssmenu-title">${Langloader::get_message('anchors.download', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_DOWNLOAD_CAT #
            <li style="order: {AM_DOWNLOAD_CAT_POS};">
                <a href="#download-cat" class="cssmenu-title">{DOWNLOAD_CAT}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_FORUM #
            <li style="order: {AM_FORUM_POS};">
                <a href="#forum" class="cssmenu-title">${Langloader::get_message('anchors.forum', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_GALLERY #
            <li style="order: {AM_GALLERY_POS};">
                <a href="#gallery" class="cssmenu-title">${Langloader::get_message('anchors.gallery', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_GUESTBOOK #
            <li style="order: {AM_GUESTBOOK_POS};">
                <a href="#guestbook" class="cssmenu-title">${Langloader::get_message('anchors.guestbook', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_MEDIA #
            <li style="order: {AM_MEDIA_POS};">
                <a href="#media" class="cssmenu-title">${Langloader::get_message('anchors.media', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_NEWS #
            <li style="order: {AM_NEWS_POS};">
                <a href="#news" class="cssmenu-title">${Langloader::get_message('anchors.news', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_NEWS_CAT #
            <li style="order: {AM_NEWS_CAT_POS};">
                <a href="#news-category" class="cssmenu-title">{NEWS_CAT}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_WEB #
            <li style="order: {AM_WEB_POS};">
                <a href="#web" class="cssmenu-title">${Langloader::get_message('anchors.web', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_WEB_CAT #
            <li style="order: {AM_WEB_CAT_POS};">
                <a href="#web-cat" class="cssmenu-title">{WEB_CAT}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_RSS #
            <li style="order: {AM_RSS_POS};">
                <a href="#rss" class="cssmenu-title">{RSS}</a>
            </li>
        # ENDIF #
    </ul>
</nav>
<script>
    jQuery("#cssmenu-anchors").menumaker({
        title: "${Langloader::get_message('anchors.title', 'common', 'HomeLanding')}",
        format: "multitoggle",
        breakpoint: 768
    });

    jQuery('.cssmenu-title').click(function(){
        var target = $(this).attr("href");

        $('html, body').animate({
            scrollTop:$(target).offset().top
        }, 800);
        return false;
    });
</script>
