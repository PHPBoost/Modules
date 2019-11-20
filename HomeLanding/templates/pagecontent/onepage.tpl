<nav id="cssmenu-onepage" class="cssmenu cssmenu-horizontal" style="order: {ONEPAGE_POSITION};">
    <ul>
        <!-- # IF C_DISPLAYED_CAROUSEL #
            <li>
                <a href="#pbt-slider" class="cssmenu-title">${Langloader::get_message('onepage.carousel', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF # -->
        # IF C_DISPLAYED_EDITO #
            <li style="order: {OPM_EDITO_POS};">
                <a href="#edito" class="cssmenu-title">${Langloader::get_message('onepage.edito', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_LASTCOMS #
            <li style="order: {OPM_LASTCOMS_POS};">
                <a href="#lastcoms" class="cssmenu-title">${Langloader::get_message('onepage.lastcoms', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_ARTICLES #
            <li style="order: {OPM_ARTICLES_POS};">
                <a href="#articles" class="cssmenu-title">${Langloader::get_message('onepage.articles', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_ARTICLES_CAT #
            <li style="order: {OPM_ARTICLES_CAT_POS};">
                <a href="#articles-cat" class="cssmenu-title">{ARTICLES_CAT}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_CONTACT #
            <li style="order: {OPM_CONTACT_POS};">
                <a href="#contact" class="cssmenu-title">${Langloader::get_message('onepage.contact', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_EVENTS #
            <li style="order: {OPM_EVENTS_POS};">
                <a href="#events" class="cssmenu-title">${Langloader::get_message('onepage.calendar', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_DOWNLOAD #
            <li style="order: {OPM_DOWNLOAD_POS};">
                <a href="#download" class="cssmenu-title">${Langloader::get_message('onepage.download', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_DOWNLOAD_CAT #
            <li style="order: {OPM_DOWNLOAD_CAT_POS};">
                <a href="#download-cat" class="cssmenu-title">{DOWNLOAD_CAT}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_FORUM #
            <li style="order: {OPM_FORUM_POS};">
                <a href="#forum" class="cssmenu-title">${Langloader::get_message('onepage.forum', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_GALLERY #
            <li style="order: {OPM_GALLERY_POS};">
                <a href="#gallery" class="cssmenu-title">${Langloader::get_message('onepage.gallery', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_GUESTBOOK #
            <li style="order: {OPM_GUESTBOOK_POS};">
                <a href="#guestbook" class="cssmenu-title">${Langloader::get_message('onepage.guestbook', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_MEDIA #
            <li style="order: {OPM_MEDIA_POS};">
                <a href="#media" class="cssmenu-title">${Langloader::get_message('onepage.media', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_NEWS #
            <li style="order: {OPM_NEWS_POS};">
                <a href="#news" class="cssmenu-title">${Langloader::get_message('onepage.news', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_NEWS_CAT #
            <li style="order: {OPM_NEWS_CAT_POS};">
                <a href="#news-cat" class="cssmenu-title">{NEWS_CAT}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_WEB #
            <li style="order: {OPM_WEB_POS};">
                <a href="#web" class="cssmenu-title">${Langloader::get_message('onepage.web', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_WEB_CAT #
            <li style="order: {OPM_WEB_CAT_POS};">
                <a href="#web-cat" class="cssmenu-title">{WEB_CAT}</a>
            </li>
        # ENDIF #
    </ul>
</nav>
<script>
    jQuery("#cssmenu-onepage").menumaker({
        title: "${Langloader::get_message('onepage.title', 'common', 'HomeLanding')}",
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
