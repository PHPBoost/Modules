    <div id="pbt-slider" style="order: {CAROUSEL_POSITION}; -webkit-order: {CAROUSEL_POSITION}; -ms-flex-order: {CAROUSEL_POSITION}">
		<ul class="slides">
            # START carousel #
			<li class="slide" style="padding-bottom: calc(100% / {NB_DOTS} / 2); background-image: url(# IF carousel.C_PTR #{PATH_TO_ROOT}# ENDIF #{carousel.PICTURE_URL})" title="{carousel.PICTURE_TITLE}">
                # IF carousel.DESCRIPTION #
					<p class="slideCaption">
						# IF carousel.LINK #<a class="slideLink" href="# IF carousel.C_INT_LINK #{PATH_TO_ROOT}# ENDIF #{carousel.LINK}" # IF NOT carousel.C_INT_LINK #target="_blank" rel="noopener noreferrer"# ENDIF #># ENDIF #
							{carousel.DESCRIPTION}
						# IF carousel.LINK #</a># ENDIF #
					</p>
				# ENDIF #
                <img class="slideImage" src="# IF carousel.C_PTR #{PATH_TO_ROOT}# ENDIF #{carousel.PICTURE_URL}" alt="{carousel.PICTURE_TITLE}" />
            </li>
            # END carousel #
        </ul>
    </div>

    <script>
        <!--
        (function ($){
            $.fn.simpleSlider = function(options){
                'use strict';
                //Options du carrousel
                var settings = $.extend({

                    animationSpeed: ${escape(CAROUSEL_SPEED)}, //Vitesse d'animation
                    animationPause: ${escape(CAROUSEL_TIME)}, //Temps d'affichage des images
                    arrowNav: ${escape(CAROUSEL_NAV)}, //Navigation suivant/précédent
                    hoverHandler: ${escape(CAROUSEL_HOVER)}, //Met le défilement en pause au survol des images
                    nav: ${escapejs(CAROUSEL_MINI)} //Options de navigations, 'dot' (affiche des points), 'img' (affiche les images en miniature) ou null (aucun)
                    // pour supprimmer la navigation par miniature remplacer ${escapejs(CAROUSEL_MINI)} par null
                }, options);

                //Classes et id des conteneurs
                var $slider = $('#pbt-slider'),
                    $slideContainer = $slider.find('.slides'),
                    $slide = $slideContainer.find('.slide');

                //Variables générales
                var  currentSlide = 0,
                     interval,
                     resizeId,
                     slideLength = $slide.length,
                     containerWidth = $slider.width(),
                     sliderLengthWithAppend = slideLength + 1,
                     movementLeft;

                //-------------------------------//
                //     Options  de navigation    //
                //-------------------------------//

                //Navigation par points
                if (settings.nav === 'dot') {

                    $slider.append('<ul id="dotsNavigation"></ul>');

                    $slide.each(function(index) {
                      $('#dotsNavigation').append('<li class="dotNavigation" id="' + index + '" data-id="' + index + '"></li>');
                    });
                }

                //Navigation par miniatures
                if (settings.nav === 'img') {

                    $slider.append('<ul id="imagesNavigation"></ul>');

                    $slide.each(function(index) {
                      $('#imagesNavigation').append('<li id="nav' + index + '" class="imageNavigation" data-id="' + index + '"></li>');
                    });

                    $slide.each(function(i) {
                      var image = $(this).children('.slideImage').attr('src');
                      $('#nav' + i).css('background-image', 'url(' + image + ')');
                    });
                }

                //Flèches de navigation
                if (settings.arrowNav === true) {
                    $slider.append('<div class="previous hideArrow"><p>&#x27E8;</p></div>');
                    $slider.append('<div class="next hideArrow"><p>&#x27E9;</p></div>');

                    $('.previous').on('click', function() {

                        if (currentSlide === 0) {
                            $slideContainer.css('margin-left', -(slideLength - 1) * containerWidth);
                            currentSlide = slideLength - 1;
                        } else {
                            currentSlide = currentSlide - 1;
                            $slideContainer.css('margin-left', -containerWidth * currentSlide);
                        }

                        $('.dotNavigation').removeClass('slide-active');
                        $('#' + currentSlide).addClass('slide-active');
                    });

                    $('.next').on('click', function() {

                        var nextSlide = (currentSlide * containerWidth) + containerWidth;
                        currentSlide++;

                        if (currentSlide === slideLength) {
                          currentSlide = 0;
                          $slideContainer.css('margin-left', 0);
                        }

                        $('.dotNavigation').removeClass('slide-active');
                        $('#' + currentSlide).addClass('slide-active');
                        $slideContainer.css('margin-left', -nextSlide);
                    });

                    $slider.on('mouseenter', function() {
                        $('.previous').removeClass('hideArrow');
                        $('.next').removeClass('hideArrow');
                     });

                     $slider.on('mouseleave', function() {
                        $('.previous').addClass('hideArrow');
                        $('.next').addClass('hideArrow');
                     });
                }

             //-------------------------------//
             //     Action de navigation      //
             //-------------------------------//

             $('.dotNavigation').on('click', function() {
               menuHandler($(this));
               $('.dotNavigation').removeClass('slide-active');
               $(this).addClass('slide-active');
             });

             $('.imageNavigation').on('click', function() {
               menuHandler($(this));
             });


             //-------------------------------//
             //        Pause au survol        //
             //-------------------------------//

             if (settings.hoverHandler === true) {
                $slider.on('mouseenter', function() {
                    pauseSlider();
                 });

                 $slider.on('mouseleave', function() {
                    slider();
                 });
             }

             //------------------------------//
             //       Redimensionnement      //
             //------------------------------//

             $(window).resize(function() {
                clearTimeout(resizeId);
                resizeId = setTimeout(slider, 1);
             });

             document.addEventListener("visibilitychange", function() {
               if (document.visibilityState === 'hidden') {

                 pauseSlider();
               } else if (document.visibilityState === 'visible') {
                 slider();
               }
             });

             //Défilement

             function slider() {
               pauseSlider();

               containerWidth = $slider.width();
               $slideContainer.css('width', containerWidth * (slideLength + 1));
               $slide.css('width', containerWidth);

               movementLeft = sliderLengthWithAppend - (containerWidth * currentSlide + sliderLengthWithAppend);

               $slideContainer.css('margin-left', movementLeft);
               $('.clonedAppend').remove();
               $slideContainer.append($slide.first().clone().addClass('clonedAppend'));
               $('#' + currentSlide).addClass('slide-active');

               startSlider($slideContainer, containerWidth);
             }

             function menuHandler(_this) {
               pauseSlider();

               var handler = $(_this).attr('data-id'),
                 handlerMovement = handler * containerWidth;

               $slideContainer.css('margin-left', -handlerMovement);

               currentSlide = handler;
               startSlider($slideContainer, containerWidth);
             }

             function startSlider(sContainer, cWidth) {
               interval = setInterval(function() {
                 sContainer.animate({
                   'margin-left': '-=' + cWidth
                 }, settings.animationSpeed, function() {
                   currentSlide++;

                   movementLeft = sliderLengthWithAppend - (containerWidth * currentSlide + sliderLengthWithAppend);
                   $slideContainer.css('margin-left', movementLeft);

                   if (currentSlide === slideLength) {
                     currentSlide = 0;
                     sContainer.css('margin-left', 0);
                   }

                   $('.dotNavigation').removeClass('slide-active');
                   $('#' + currentSlide).addClass('slide-active');
                 });
               }, settings.animationPause);
             }

             //Initialisation du carrousel

             function pauseSlider() {
               clearInterval(interval);
             }

             //Démarrage du carrousel
             slider();
           };
        }(jQuery));
         -->
    </script>
    <script>
        <!---
        $( "#pbt-slider" ).simpleSlider();
        -->
    </script>
