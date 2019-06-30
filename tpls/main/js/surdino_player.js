

        var player1;
        var player2;

        function onYouTubeIframeAPIReady() {
            player1 = new YT.Player('player1', {

                videoId: VIDEO.videoLink,
                playerVars: {
                    'autoplay': 0,
                    'controls': 0,
                    'showinfo': 0,
                    'rel': 0
                },
                events: {
                    'onReady': onPlayer1Ready,
                    'onStateChange': onPlayer1StateChange
                }
            });
            player2 = new YT.Player('player2', {

                videoId: VIDEO.link,
                playerVars: {
                    'autoplay': 0,
                    'controls': 0,
                    'showinfo': 0
                },
                events: {
                    'onReady': onPlayer2Ready,
                    'onStateChange': onPlayer2StateChange
                }
            });
        }

        var player1Ready = false;
        var player2Ready = false;

        var preloading1 = false;
        var preloading2 = false;

        function onPlayer1Ready(event) {
            player1Ready = true;
            preloading1 = true;
            player1.seekTo(1);

        }

        function onPlayer2Ready(event) {
            player2Ready = true;
            player2.mute();
        }


        function onPlayer1StateChange(event) {
            if (event.data == YT.PlayerState.PLAYING) {
                if (preloading1) {

                    player1.pauseVideo();
                    player1.seekTo(0);
                    player1.unMute();

                    preloading1 = false;

                    player2Ready = true;
                    preloading2 = true;
                    player2.mute();

                    player2.seekTo(1);
                    player2.playVideo();
                    player1.playVideo();


                } else player2.playVideo();
//        }
//
            } else if (event.data == YT.PlayerState.PAUSED) {
                //var elementPlayer = player1.getIframe('.ytp-pause-overlay');
                //$(elementPlayer).find('.ytp-pause-overlay').hide();
                var debug = debug;
                if (!preloading1) player2.pauseVideo();
            } else if (event.data == YT.PlayerState.BUFFERING) {
                if (!preloading1) {
                    player2.pauseVideo();
                }
            }

//        } else if (event.data == YT.PlayerState.CUED) {
//            if (!preloading1) player2.pauseVideo();
//
//        }
        }

        function onPlayer2StateChange(event) {
//        if (event.data == YT.PlayerState.PLAYING) {
//            if (preloading2) {
//                //prompt("2");
//                player2.pauseVideo();
//                player2.seekTo(0);
//                //player2.unMute();
//                preloading2 = false;
//                $("#player2").show(50, function() {
//                    //player1.playVideo();
//                });
//            } else player1.playVideo();
//        } else if (event.data == YT.PlayerState.PAUSED) {
//            if ( /*!preloading1 &&*/ !preloading2) player1.pauseVideo();
//        } else if (event.data == YT.PlayerState.BUFFERING) {
//            if (!preloading2) {
//                player1.pauseVideo();
//                //player1.seekTo(... // Здесь можно скорректировать отклонение
//            }
//
//        } else if (event.data == YT.PlayerState.CUED) {
//            if (!preloading2) player1.pauseVideo();
//
//        }

        }



        function stopVideo() {
            player1.stopVideo();
            player2.stopVideo();
        }

        $("#player-play").on('click', function(){
            player1.playVideo();
        });

        $("#player-pause").on('click', function(){
            player1.pauseVideo();
        });

