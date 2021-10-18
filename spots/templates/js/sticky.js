var sticky = (function(){

	var $window,
		$stickyNav,
		$stickyParent,
		stickyPos,
		stickyHeight,
		stickyParentPaddingTop;

	var init = function(elem, options){
		$window 	       	   = $(window);
		$stickyNav             = $(elem);
		$stickyParent          = $stickyNav.parent();
		stickyPos              = $stickyNav.offset().top;
		stickyHeight           = $stickyNav.outerHeight();
		stickyParentPaddingTop = $stickyParent.css('padding-top');

		_eventHandlers();
	}

	var _stickyValidation = function(){

		var scrollPos = $window.scrollTop();

		if(scrollPos >= stickyPos){

			$stickyNav.addClass('sticky');

			//Add space previously occupied by nav (improve this)
			$stickyParent.css('padding-top', stickyHeight);
		}else{
			$stickyNav.removeClass('sticky');
			//Go back to normal (improve this)
			$stickyParent.css('padding-top', stickyParentPaddingTop);
		}
	}

	var _eventHandlers = function(){
		//$(window).on('scroll', function () { _stickyValidation(); });

		window.addEventListener('scroll', _stickyValidation);
	}

	return {
		init: init
	}

}());

//Create jquery plugin
if (window.jQuery) {
    (function($) {
        $.fn.sticky = function(options) {
            this.each(function() {
                sticky.init(this, options);
            });

            return this;
        };
    })(window.jQuery);
}else{
	console.warn("jQuery library is not defined, please make sure it's added before this script");
}
