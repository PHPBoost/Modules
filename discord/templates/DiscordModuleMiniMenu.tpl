<div id="module-mini-discord" class="cell-mini cell-tile# IF C_HIDDEN_WITH_SMALL_SCREENS # hidden-small-screens# ENDIF #">
    <div class="cell">
        <div class="cell-header">
            <h6 class="cell-name">
                {@discord.module.title}
            </h6>
            <span>
                <a id="instant-invite" class="stacked" aria-label="{@discord.join.tooltip}">
                    <i class="fa fa-headset fa-fw fa-lg moderator" arria-hidden="true"></i>
                    <span class="stack-event stack-top-right"><i class="fa fa-plug"></i></span>
                </a>
            </span>
        </div>

        # IF C_DISCORD_ID #
            <div class="cell-body">
                <div id="channels-body" class="cell-list"></div>
            </div>
            <div class="cell-body">
                <div class="cell-content bgc moderator">
                    <div id="server-name" class="bigger"></div>
                    <div id="users-number" class="align-right small"></div>
                </div>
                <div class="cell-content" id="users-body"></div>
            </div>
        # ELSE #
            <div class="cell-alert bgc notice">
                <div class="cell-content">
                    {@discord.no.item}
                    # IF IS_ADMIN #<a href="${relative_url(ModulesUrlBuilder::configuration('discord'))}" aria-label="{@form.configuration}"><i class="fa fa-fw fa-cogs" aria-hidden="true"></i></a># ENDIF #
                </div>
            </div>
        # ENDIF #

    </div>
</div>


# IF C_DISCORD_ID #
    <script src="{PATH_TO_ROOT}/discord/templates/js/discord.viewer# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>
    <script>
        jQuery('#module-mini-discord').discordviewer({
            server_id: '{DISCORD_ID}',
            online_user: ${escapejs(@discord.online.user)},
            online_users: ${escapejs(@discord.online.users)},
            status_online: ${escapejs(@discord.status.online)},
            status_idle: ${escapejs(@discord.status.idle)},
            status_dnd: ${escapejs(@discord.status.dnd)},
        });
    </script>
# ENDIF #
