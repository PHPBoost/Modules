<div class="broadcast-flex">
    # IF C_IS_WIDGET #
        <div class="broadcast-player">{WIDGET}</div>
    # ELSE #
        # IF C_HAS_WIDGET #
            <div class="broadcast-player">{WIDGET}</div>
        # ELSE #
            # IF C_HAS_LOGO #<img class="broadcast-logo" src="{U_LOGO}" alt="" /># ENDIF #
        # ENDIF #
        <div class="player align-center">
            <div class="details">
                <!-- <div class="now-playing"></div> -->
                <div class="track-art"></div>
                <h1 class="track-name">{TITLE}</h1>
                <!-- <div class="track-artist">Track Artist</div> -->
            </div>
            <div class="buttons">
                <!-- <div class="prev-track" onclick="prevTrack()"><i class="fa fa-step-backward fa-2x"></i></div> -->
                <div class="playpause-track" onclick="playpauseTrack()"><i class="fa fa-play-circle fa-5x"></i></div>
                <!-- <div class="next-track" onclick="nextTrack()"><i class="fa fa-step-forward fa-2x"></i></div> -->
            </div>
            <!-- <div class="slider_container">
                <div class="current-time">00:00</div>
                <input type="range" min="1" max="100" value="0" class="seek_slider" onchange="seekTo()">
                <div class="total-duration">00:00</div>
            </div> -->
            <div class="slider_container">
                <i class="fa fa-volume-down"></i>
                <input class="volume_slider" type="range" min="1" max="100" value="100" onchange="setVolume()">
                <i class="fa fa-volume-up"></i>
            </div>
        </div>
        <script>
            let TRACKLIST = [
                    {
                        name: "{TITLE}",
                        artist: "",
                        image: "",
                        path: "{U_STREAM}"
                    }
                ];
        </script>
        <script>
            // let now_playing = document.querySelector(".now-playing");
            let track_art = document.querySelector(".track-art");
            let track_name = document.querySelector(".track-name");
            let track_artist = document.querySelector(".track-artist");

            let playpause_btn = document.querySelector(".playpause-track");
            let next_btn = document.querySelector(".next-track");
            let prev_btn = document.querySelector(".prev-track");

            // let seek_slider = document.querySelector(".seek_slider");
            let volume_slider = document.querySelector(".volume_slider");
            // let curr_time = document.querySelector(".current-time");
            // let total_duration = document.querySelector(".total-duration");

            let track_index = 0;
            let isPlaying = false;
            let updateTimer;

            // Create new audio element
            let curr_track = document.createElement('audio');

            // Define the tracks that have to be played
            let track_list = TRACKLIST;

            function loadTrack(track_index) {
                clearInterval(updateTimer);
                // resetValues();
                curr_track.src = track_list[track_index].path;
                curr_track.load();

                track_art.style.backgroundImage = "url(" + track_list[track_index].image + ")";
                track_name.textContent = track_list[track_index].name;
                // track_artist.textContent = track_list[track_index].artist;
                // now_playing.textContent = "PLAYING " + (track_index + 1) + " OF " + track_list.length;

                updateTimer = setInterval(seekUpdate, 1000);
                curr_track.addEventListener("ended", nextTrack);
            }

            // function resetValues() {
            //     curr_time.textContent = "00:00";
            //     total_duration.textContent = "00:00";
            //     seek_slider.value = 0;
            // }

            // Load the first track in the tracklist
            loadTrack(track_index);

            function playpauseTrack() {
                if
                    (!isPlaying) playTrack();
                else
                    pauseTrack();
            }

            function playTrack() {
                curr_track.play();
                isPlaying = true;
                playpause_btn.innerHTML = '<i class="fa fa-pause-circle fa-5x"></i>';
            }

            function pauseTrack() {
                curr_track.pause();
                isPlaying = false;
                playpause_btn.innerHTML = '<i class="fa fa-play-circle fa-5x"></i>';;
            }

            function nextTrack() {
                if (track_index < track_list.length - 1)
                    track_index +=1;
                else
                    track_index=0; loadTrack(track_index);

                playTrack();
            }

            function prevTrack() {
                if (track_index> 0)
                    track_index -= 1;
                else
                    track_index = track_list.length;
                loadTrack(track_index);
                playTrack();
            }

            function seekTo() {
                // let seekto = curr_track.duration * (seek_slider.value / 100);
                // curr_track.currentTime = seekto;
            }

            function setVolume() {
                curr_track.volume = volume_slider.value / 100;
            }

            function seekUpdate() {
                let seekPosition = 0;

                if (!isNaN(curr_track.duration))
                {
                    seekPosition = curr_track.currentTime * (100 / curr_track.duration);

                    // seek_slider.value = seekPosition;

                    let currentMinutes = Math.floor(curr_track.currentTime / 60);
                    let currentSeconds = Math.floor(curr_track.currentTime - currentMinutes * 60);
                    let durationMinutes = Math.floor(curr_track.duration / 60);
                    let durationSeconds = Math.floor(curr_track.duration - durationMinutes * 60);

                    if (currentSeconds < 10)
                        currentSeconds="0" + currentSeconds;
                    if (durationSeconds < 10)
                        durationSeconds="0" + durationSeconds;
                    if (currentMinutes < 10)
                        currentMinutes="0" + currentMinutes;
                    if (durationMinutes < 10)
                        durationMinutes="0" + durationMinutes;
                    // curr_time.textContent=currentMinutes + ":" + currentSeconds;
                    // total_duration.textContent=durationMinutes + ":" + durationSeconds;
                }
            }
        </script>
    # ENDIF #
</div>
