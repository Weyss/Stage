'use strict';
/***** MES VARIABLE *****/
let $player = $(document).find('.player-audio');

/***** MON CODE *****/
(function ($) {
    $(document).ready(function () {
        $($player).each(function (index, element) {
            let $audio = element.querySelector('audio');
            let $playPause = element.querySelector('.play-pause');
            let $stopPlay = element.querySelector('.stop');
            let $timeline = element.querySelector('.timeline');
            let $volumeSlider = element.querySelector('.volumeSlider');
            let $progress = element.querySelector('.progress');
            let $current = element.querySelector('.currentTime');
            let $duration = element.querySelector('.duration');

            if ($duration != null) {
                let $sD = parseInt($audio.duration % 60);
                let $mD = parseInt(($audio.duration / 60) % 60);
                $duration.innerHTML = $mD + ':' + $sD;
            }

            $audio.volume = $volumeSlider.value / 100;

            /** Fonction pour mettre en play / pause **/
            $($playPause).click(function () {
                if ($audio.paused) {
                    $($playPause).addClass("pause");
                    $($playPause).removeClass("play");
                    $audio.play();
                } else {
                    $($playPause).removeClass("pause");
                    $($playPause).addClass("play");
                    $audio.pause();
                }
            });

            /** Fonction pour stop la lecture et la remettre à 0 **/
            $($stopPlay).click(function () {
                $audio.currentTime = 0;
                $audio.pause();
                $($playPause).removeClass("pause");
                $($playPause).addClass("play");
            })

            /** Fonction pour changer le volume **/
            $($volumeSlider).change(function () {
                $audio.volume = $volumeSlider.value / 100;
            });

            /** Fonction pour la barre de progression et le timer **/
            $($audio).on('timeupdate', function () {
                /** Code pour la barre de progression */
                let $timelinePos = $audio.currentTime / $audio.duration;
                $timeline.style.width = $timelinePos * 100 + '%';

                if ($audio.ended) {
                    $($playPause).removeClass("pause");
                    $($playPause).addClass("play");
                }
                /** Code pour le timer */
                let $s = parseInt($audio.currentTime % 60);
                let $m = parseInt(($audio.currentTime / 60) % 60);

                $s = ($s >= 10) ? $s : "0" + $s;
                $m = ($m >= 10) ? $m : "0" + $m;

                $current.innerHTML = $m + ':' + $s;
            });

            /** Fonction pour rendre la barre de progression cliquable **/
            $($progress).click(function (e) {
                let $rect = $progress.getBoundingClientRect();
                let $width = $rect.width;
                let $x = e.clientX - $rect.left;
                let $widthPercent = (($x * 100) / $width);
                let $currentTimeTrue = ($widthPercent * $audio.duration) / 100;

                $audio.currentTime = $currentTimeTrue
            });

        });
    });
})(jQuery);

