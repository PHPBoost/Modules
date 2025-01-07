// @copyright   &copy; 2005-2025 PHPBoost
// @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
// @author      Sebastien LARTIGUE <babsolune@phpboost.com>
// @version     PHPBoost 6.0 - last update: 2023 03 06
// @since       PHPBoost 6.0 - 2023 03 05

(function(jQuery) {

    jQuery.fn.extend({
        discordviewer: function(options) {
            var defaults = {
                server_id: '',
                online_user: '',
                online_users: '',
                status_online: '',
                status_idle: '',
                status_dnd: '',
            };
            options = jQuery.extend(defaults, options);

            var nameHeader = document.getElementById('server-name');
            var instantInvite = document.getElementById('instant-invite');
            var channelBody = document.getElementById('channels-body');
            var userBody = document.getElementById('users-body');
            var usersNumber = document.getElementById('users-number');

            function discordAPI()
            {
                var init =
                {
                    method: 'GET',
                    mode: 'cors',
                    cache: 'reload'
                }
                fetch('https://discordapp.com/api/guilds/' + options.server_id + '/widget.json', init).then(function(response)
                {
                    if (response.status != 200)
                    {
                        console.log("it didn't work" + response.status);
                        return
                    }
                    response.json().then(function(data)
                    {
                        var users = data.members;

                        instantInvite.href = data.instant_invite;
                        nameHeader.innerHTML = data.name;
                        usersNumber.innerHTML = data.presence_count + ' ' + (data.presence_count > 1 ? options.online_users : options.online_user);

                        let ulChannel = document.createElement('ul');

                        function channelsFill()
                        {
                            for (let i = 0; i < data.channels.length; i++)
                            {
                                let liChannel = document.createElement('li');
                                liChannel.classList.add('discord-channel');
                                liChannel.setAttribute('id', 'channel-' + data.channels[i].id);
                                liChannel.innerText = data.channels[i].name;
                                for (let n = 0; n < data.members.length; n++)
                                {
                                    if (data.channels[i].id == users[n].channel_id)
                                    {
                                        let userWrap = document.createElement('div');
                                        let userId = document.createElement('div');
                                        let userName = document.createElement('span');
                                        let userImage = document.createElement('img');
                                        let userStatus = document.createElement('div');
                                        let userGame = document.createElement('span');
                                        let botTag = document.createElement('div');

                                        userWrap.classList.add('user-wrapper');
                                        userWrap.classList.add('flex-between');
                                        userId.classList.add('user-id');
                                        userName.classList.add('username');
                                        userImage.classList.add('image-wrapper');
                                        userStatus.classList.add('user-status');
                                        userStatus.classList.add('bgc-full');
                                        userGame.classList.add('user--game');
                                        botTag.classList.add('bot--tag');
                                        botTag.innerText = 'BOT';

                                        if (users[n].nick === undefined)
                                            userName.innerText = users[n].username;
                                        else
                                            userName.innerText = users[n].nick;

                                        if (users[n].status === 'online')
                                        {
                                            userStatus.classList.add('success');
                                            userStatus.setAttribute('aria-label', options.status_online)
                                        }

                                        if (users[n].status === 'idle')
                                        {
                                            userStatus.classList.add('warning');
                                            userStatus.setAttribute('aria-label', options.status_idle)
                                        }

                                        if (users[n].status ==='dnd')
                                        {
                                            userStatus.classList.add('error');
                                            userStatus.setAttribute('aria-label', options.status_dnd)
                                        }

                                        if (users[n].bot === true)
                                            userStatus.appendChild(botTag);

                                        if (users[n].game !== undefined)
                                        {
                                            userGame.setAttribute('aria-label', users[n].game.name)
                                            userGame.innerHTML = '<i class="fa fa-fw fa-play success" aria-hidden="true"></i>';
                                        }

                                        userImage.setAttribute('src', data.members[n].avatar_url);

                                        userWrap.appendChild(userId);
                                        userId.appendChild(userImage);
                                        userId.appendChild(userName);
                                        userWrap.appendChild(userGame);
                                        userWrap.appendChild(userStatus);

                                        liChannel.appendChild(userWrap);
                                    }
                                }
                                ulChannel.appendChild(liChannel);
                                channelBody.appendChild(ulChannel) ;
                            }
                        }

                        function noChannelsFill()
                        {
                            for (let n = 0; n < data.members.length; n++)
                            {
                                let userWrap = document.createElement('div');
                                let userId = document.createElement('div');
                                let userName = document.createElement('span');
                                let userImage = document.createElement('img');
                                let userStatus = document.createElement('div');
                                let userGame = document.createElement('span');
                                let botTag = document.createElement('div');

                                userWrap.classList.add('user-wrapper');
                                userWrap.classList.add('flex-between');
                                userId.classList.add('user-id');
                                userName.classList.add('username');
                                userImage.classList.add('image-wrapper');
                                userStatus.classList.add('user-status');
                                userStatus.classList.add('bgc-full');
                                userGame.classList.add('user--game');
                                botTag.classList.add('bot--tag');
                                botTag.innerText = 'BOT';

                                if (users[n].nick === undefined)
                                    userName.innerText = users[n].username;
                                else
                                    userName.innerText = users[n].nick;

                                if (users[n].status === 'online')
                                {
                                    userStatus.classList.add('success');
                                    userStatus.setAttribute('aria-label', options.status_online)
                                }

                                if (users[n].status === 'idle')
                                {
                                    userStatus.classList.add('warning');
                                    userStatus.setAttribute('aria-label', options.status_idle)
                                }

                                if (users[n].status ==='dnd')
                                {
                                    userStatus.classList.add('error');
                                    userStatus.setAttribute('aria-label', options.status_dnd)
                                }

                                if (users[n].bot === true)
                                    userStatus.appendChild(botTag);

                                if (users[n].game !== undefined)
                                {
                                    userGame.setAttribute('aria-label', users[n].game.name);
                                    userGame.innerHTML = '<i class="fa fa-fw fa-play" aria-hidden="true"></i>';
                                }

                                userImage.setAttribute('src', data.members[n].avatar_url);

                                userWrap.appendChild(userId);
                                userId.appendChild(userImage);
                                userId.appendChild(userName);
                                userWrap.appendChild(userGame);
                                userWrap.appendChild(userStatus);

                                var channelIds = [];
                                for (let i = 0; i < data.channels.length; i++)
                                {
                                    channelIds.push(data.channels[i].id)
                                }
                                if (!users[n].channel_id || !channelIds.includes(users[n].channel_id))
                                    userBody.appendChild(userWrap);
                            }
                        }
                        channelsFill();
                        noChannelsFill();
                    })
                })
                .catch(function(err)
                {
                    console.log('fetch error: ' + err)
                })
            }
            discordAPI()
        }
    });
})(jQuery);