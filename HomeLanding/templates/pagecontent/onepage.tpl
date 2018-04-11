<nav id="cssmenu-onepage" class="cssmenu cssmenu-horizontal" style="order: {ONEPAGE_POSITION}; -webkit-order: {ONEPAGE_POSITION}; -ms-flex-order: {ONEPAGE_POSITION}">
    <ul>
        <!-- # IF C_DISPLAYED_CAROUSEL #
            <li>
                <a href="#pbt-slider" class="cssmenu-title">${Langloader::get_message('onepage.carousel', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF # -->
        # IF C_DISPLAYED_EDITO #
            <li>
                <a href="#edito" class="cssmenu-title">${Langloader::get_message('onepage.edito', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_LASTCOMS #
            <li>
                <a href="#lastcoms" class="cssmenu-title">${Langloader::get_message('onepage.lastcoms', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_ARTICLES #
            <li>
                <a href="#articles" class="cssmenu-title">${Langloader::get_message('onepage.articles', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_ARTICLES_CAT #
            <li>
                <a href="#articles-cat" class="cssmenu-title">{ARTICLES_CAT}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_CONTACT #
            <li>
                <a href="#contact" class="cssmenu-title">${Langloader::get_message('onepage.contact', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_EVENTS #
            <li>
                <a href="#events" class="cssmenu-title">${Langloader::get_message('onepage.events', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_DOWNLOAD #
            <li>
                <a href="#download" class="cssmenu-title">${Langloader::get_message('onepage.download', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_DOWNLOAD_CAT #
            <li>
                <a href="#download-cat" class="cssmenu-title">{DOWNLOAD_CAT}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_FORUM #
            <li>
                <a href="#forum" class="cssmenu-title">${Langloader::get_message('onepage.forum', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_GALLERY #
            <li>
                <a href="#gallery" class="cssmenu-title">${Langloader::get_message('onepage.gallery', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_GUESTBOOK #
            <li>
                <a href="#guestbook" class="cssmenu-title">${Langloader::get_message('onepage.guestbook', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_MEDIA #
            <li>
                <a href="#media" class="cssmenu-title">${Langloader::get_message('onepage.media', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_NEWS #
            <li>
                <a href="#news" class="cssmenu-title">${Langloader::get_message('onepage.news', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_NEWS_CAT #
            <li>
                <a href="#news-cat" class="cssmenu-title">{NEWS_CAT}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_WEB #
            <li>
                <a href="#web" class="cssmenu-title">${Langloader::get_message('onepage.web', 'common', 'HomeLanding')}</a>
            </li>
        # ENDIF #
        # IF C_DISPLAYED_WEB_CAT #
            <li>
                <a href="#web-cat" class="cssmenu-title">{WEB_CAT}</a>
            </li>
        # ENDIF #
    </ul>
</nav>
<script>jQuery("#cssmenu-onepage").menumaker({ title: "${Langloader::get_message('onepage.title', 'common', 'HomeLanding')}", format: "multitoggle", breakpoint: 768 }); </script>
<script>
    $('.cssmenu-title').click(function(){
        var target = $(this).attr("href");

        $('html, body').animate({
            scrollTop:$(target).offset().top
        }, 800);
        return false;
    });
</script>
