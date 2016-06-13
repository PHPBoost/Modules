		<nav id="admin-quick-menu">
                    <a href="" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;" title="{@module_title}">
                        <i class="fa fa-bars"></i> {@module_title}
                    </a>
                    <ul>
                        <li>
                            <a href="${relative_url(EasyCssUrlBuilder::theme_choice())}" class="quick-link">{@theme_choice}</a>
                        </li>
                    </ul>	
		</nav>
		
		<div id="admin-contents">
                    {@module_title}
                    <ul class="tabs">	
                    # START themes #
                        <li class="# IF themes.DEFAULT # current # END IF #" data-tab="tab-{themes.NAME}">{themes.NAME}</li>                    
                    # END themes #
                    </ul>
                    	
                    # START themes #
                    <div id="tab-{themes.NAME}" class="tab-content # IF themes.DEFAULT # current # END IF #">
                    # START themes.css #
                        <a href="{themes.css.URL}">{themes.css.NAME}</a></br>
                    # END themes.css #
                    </div>
                    # END themes #
                </div>
                    
                <script>
                    $(document).ready(function(){
                        $('ul.tabs li').click(function(){
                            var tab_id = $(this).attr('data-tab');
                                $('ul.tabs li').removeClass('current');
                                $('.tab-content').removeClass('current');
                                $(this).addClass('current');
                                $("#"+tab_id).addClass('current');
                        });
                    });
                </script>