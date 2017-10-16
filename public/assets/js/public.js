(function() {
	jQuery( '.image-scroll' ).each( function() {
		var container = jQuery('.swiper-container');
		var slide = jQuery('.swiper-slide');
		var multiplier = 1;

		function setContainerHeight (source, target, multiplier) {
			target.height( source.height() * multiplier );
		}

		var swiper = null,
			uniqId = jQuery( this ).data( 'uniq-id' ),
			slidesPerView = 1,
			slidesPerGroup = 1,
			slidesPerColumn = 1,
			spaceBetweenSlides = 0,
			durationSpeed = 200,
			swiperLoop = false,
			freeMode = false,
			grabCursor = false,
			mouseWheel = true;

		if ( 1 == slidesPerView ) {
			breakpointsSettings = {}
		}

		var swiper = new Swiper( '.image-scroll', {
			direction: 'vertical',
			scrollbarHide: false,
			scrollbarDraggable: true,
			slidesPerView: slidesPerView,
			slidesPerGroup: slidesPerGroup,
			slidesPerColumn: slidesPerColumn,
			spaceBetween: spaceBetweenSlides,
			speed: durationSpeed,
			loop: swiperLoop,
			freeMode: freeMode,
			grabCursor: grabCursor,
			mousewheelControl: mouseWheel,
			paginationClickable: true,

			scrollbar: '.swiper-scrollbar',
			scrollbarHide: false,
			slidesPerView: 'auto',
			centeredSlides: true,
			grabCursor: true,
			autoHeight: true,
			runCallbacksOnInit: true,
			effect: 'fade',
			breakpoints: breakpointsSettings,
			on: {
				init: function(){
					console.log('sdfsdfsdf'); // Swiper
				},
			},
		} );

		jQuery( this ).magnificPopup({
			delegate: 'a',
			type: 'image',
			tLoading: 'Loading image #%curr%...',
			mainClass: 'mfp-img-mobile',
			gallery: {
				enabled: true,
				navigateByImgClick: true,
				preload: [0,1] // Will preload 0 - before current, and 1 after the current image
			},
			image: {
				tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
				titleSrc: function(item) {
					return item.el.attr('title') + '<small>by Marsel Van Oosten</small>';
				}
			}
		});
	} );

}());
